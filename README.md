# Minimal Polylang Extensions

Based and extended from BW_Lang_Switcher_Plugin by blickwert

## Features

- Dark and Light themed `[tcp-lang-switcher]` shortcode.
- Show language code or full name.
- Dropdown of languages.
- Per‐block visibility via `show-for-xx` CSS classes.

## Installation

1. Copy to `wp-content/plugins/minimal-polylang-extensions`.
2. Activate plugin.

## Shortcode

1. Add `[tcp-lang-switcher]` to shortcode block.
2. Add `[tcp-lang-switcher theme="light"]` to use black over light backgrounds. The default theme is for dark backgrounds.


## Block Visibility

This is intended to be used in headers, footers, or any template part that needs to be translated/replaced. Will

1. Wrap blocks in a Group (or any container)
2. Add CSS class `show-for-xx`. Replace xx with the language code. (ie: en for English)
3. Duplicate and edit the xx part per language

Only matching content renders based on `<html lang="">`.

## License

GPLv2 or later.


## What's ahead?

Will improve this plugin to add features as we need them