<?php
/**
 * Plugin Name: Minimal Polylang Extensions
 * Description: Polylang language switcher shortcode, manual language switcher, and per-block language visibility via CSS.
 * Version: 1.1
 * Author: Cynthia Lara @ The Creative Pixels
 * Author URI: https://thecreativepixels.com/
 * Text Domain: tcp-polylang-extensions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class TCP_Polylang_Extensions {

    public function __construct() {
        add_action( 'init',                        [ $this, 'register_assets' ] );
        add_action( 'plugins_loaded',              [ $this, 'initialize_plugin' ], 20 );
        add_action( 'wp_enqueue_scripts',          [ $this, 'enqueue_frontend' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_frontend' ] );
        add_action( 'init',                        [ $this, 'register_shortcodes' ] );
        add_action( 'wp_enqueue_scripts',          [ $this, 'inline_language_css' ], 20 );
    }

    public function initialize_plugin() {
        if ( ! function_exists( 'pll_current_language' ) ) {
            add_action( 'admin_notices', [ $this, 'dependency_error_notice' ] );
        }
    }

    public function dependency_error_notice() {
        echo '<div class="notice notice-error"><p>'
            . esc_html__( 'TCP Polylang Extensions requires Polylang to be active.', 'tcp-polylang-extensions' )
            . '</p></div>';
    }

    public function register_assets() {
        wp_register_style(
            'tcp-polylang-extensions-style',
            plugin_dir_url( __FILE__ ) . 'assets/css/style.css',
            [],
            '1.0'
        );
        wp_register_script(
            'tcp-polylang-extensions-script',
            plugin_dir_url( __FILE__ ) . 'assets/js/script.js',
            [ 'jquery' ],
            '1.0',
            true
        );
    }

    public function enqueue_frontend() {
        wp_enqueue_style(  'tcp-polylang-extensions-style' );
        wp_enqueue_script( 'tcp-polylang-extensions-script' );
        // Manual switcher JS (merged version of switcher.js)
    wp_enqueue_script(
        'tcp-manual-switcher',
        plugin_dir_url( __FILE__ ) . 'assets/js/manual-switcher.js',
        [ 'jquery' ],
        '1.0',
        true
    );
    // Prepare language data for manual switcher
    if ( function_exists( 'pll_the_languages' ) ) {
        $languages = pll_the_languages( [ 'raw' => 1 ] );

        if ( ! $languages ) {
            return;
        }

        // Category/taxonomy fix: add translated term slug and link
        if ( is_category() || is_tag() || is_tax() ) {
            $term = get_queried_object();
            if ( $term && isset( $term->term_id ) ) {
                foreach ( $languages as $lang => &$data ) {
                    $translated_id = pll_get_term( $term->term_id, $lang );
                    if ( $translated_id ) {
                        $translated_term = get_term( $translated_id );
                        if ( $translated_term && ! is_wp_error( $translated_term ) ) {
                            $data['current_term_slug'] = $translated_term->slug;
                            $data['current_term_link'] = get_term_link( $translated_term );
                        }
                    }
                }
            }
        }

        // Localize language data for JS
        wp_localize_script(
            'tcp-manual-switcher',
            'PolylangLanguages',
            $languages
        );
    }
 }

    public function register_shortcodes() {
        add_shortcode( 'tcp-lang-switcher', [ $this, 'shortcode_language_switcher' ] );
    }

    public function shortcode_language_switcher( $atts ) {
        $atts = shortcode_atts( [
            'available-lang' => true,
            'layout'         => 'text',
            'theme'          => 'dark',
        ], $atts );

        $current = pll_current_language();
        $langs   = pll_the_languages( [ 'raw' => 1 ] );
        if ( empty( $langs ) ) {
            return '<!-- No languages available -->';
        }

        $theme = in_array( $atts['theme'], [ 'light','dark' ], true ) ? $atts['theme'] : 'dark';

        ob_start(); ?>
        <div id="tcp-language-switcher"
             class="theme-<?php echo esc_attr( $theme ); ?>"
             aria-label="<?php esc_attr_e( 'Language Switcher', 'tcp-polylang-extensions' ); ?>">

            <?php if ( isset( $langs[ $current ] ) ) : ?>
                <div class="current-language"
                     aria-label="<?php echo esc_attr( $langs[ $current ]['name'] ); ?>"
                     aria-current="true">
                    <img src="<?php echo esc_url( plugin_dir_url(__FILE__) . 'assets/lang-icon.svg' ); ?>" alt="" class="lang-icon" />
                    <span class="lang-name">
                        <?php echo esc_html( $langs[ $current ]['name'] ); ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ( $atts['available-lang'] ) : ?>
                <div class="language-list hidden layout-<?php echo esc_attr( $atts['layout'] ); ?>">
                    <?php foreach ( $langs as $code => $info ) :
                        if ( $code === $current ) {
                            continue;
                        } ?>
                        <a href="<?php echo esc_url( $info['url'] ); ?>"
                           class="lang-link lang-<?php echo esc_attr( $code ); ?>"
                           aria-label="<?php echo esc_attr( $info['name'] ); ?>">
                            <span class="lang-name">
                                <?php echo esc_html( $info['name'] ); ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
        <?php
        return ob_get_clean();
    }

public function inline_language_css() {
    if ( ! function_exists( 'pll_languages_list' ) ) {
        return;
    }
    $slugs = pll_languages_list( [ 'fields' => 'slug' ] );
    $css   = '[class*="show-for-"]{display:none!important;}';
    foreach ( (array) $slugs as $slug ) {
        $code = substr( $slug, 0, 2 );
        $css .= sprintf(
            'html[lang^="%1$s"] .show-for-%1$s{display:block!important;}',
            esc_attr( $code )
        );
    }
    wp_add_inline_style( 'tcp-polylang-extensions-style', $css );
}


}

new TCP_Polylang_Extensions();