# Minimal Polylang Extensions
Polylang language switcher shortcode, manual language switcher, and per-block language visibility via CSS.

## Features

- Dark and Light themed `[tcp-lang-switcher]` shortcode.
- Show language code or full name.
- Dropdown of languages.
- Per‐block visibility via `show-for-xx` CSS classes.
- Manual language switcher (in case you want to build your own using Gutenberg blocks)


## Installation

1. Copy to `wp-content/plugins/minimal-polylang-extensions`.
2. Activate plugin.


## Shortcode

1. Add `[tcp-lang-switcher]` to shortcode block.
2. Add `[tcp-lang-switcher theme="light"]` to use black over light backgrounds. The default theme is for dark backgrounds.

## Language Switcher Menu

1. In the WordPress Block Editor (Site Editor or Navigation block), create a **Navigation** block with a submenu.
2. The parent menu item should display a placeholder: **"LAN"**.
3. Add child menu items for each language (e.g., "EN", "ES").
4. Set the URL of each child to `#`.
5. Add the following CSS classes to each menu item:

   - Parent: `.lang-switcher`
   - Child for English: `.lang-btn lang-en`
   - Child for Spanish: `.lang-btn lang-es`
   - Repeat for other languages.

The JavaScript will automatically update the `#` links to point to the correct translated URLs.


## Block Visibility

This is intended to be used in headers, footers, or any template part that needs to be translated/replaced. Will

1. Wrap blocks in a Group (or any container)
2. Add CSS class `show-for-xx`. Replace xx with the language code. (ie: en for English)
3. Duplicate and edit the xx part per language

Only matching content renders based on `<html lang="">`.


## License

GPLv2 or later.


## Requirements

- **Polylang** plugin must be active.
- Works with FSE themes and block-based templates.


## What's ahead?

Will improve this plugin to add features as we need them

## Credits
Shortcode based and extended from BW_Lang_Switcher_Plugin by blickwert
Manual switcher based on our other plugin "Manual Polylang Switcher"
