# Noindex Manager

A WordPress plugin that adds a simple checkbox to the post/page editor — in both the Classic editor and Gutenberg block editor — to set individual content as `noindex`, keeping it out of search engine results.

## Features

- **Classic editor** meta box under "Search Engine Visibility" in the post sidebar
- **Gutenberg block editor** plugin sidebar panel with the same checkbox
- Outputs `<meta name="robots" content="noindex, follow">` at the top of `<head>` on the front end
- Works on all public post types (posts, pages, and any custom post type)
- Visual warning notice in the editor when noindex is active, so editors don't forget
- No settings page needed — it's a per-post toggle

## Installation

1. Upload the `noindex-manager` folder to `/wp-content/plugins/`
2. Activate through **Plugins → Installed Plugins**
3. Edit any post or page — the "Search Engine Visibility" panel appears in the editor sidebar

## How It Works

The noindex state is stored as post meta (`_nim_noindex`) via `update_post_meta()` in the classic editor and via the REST API (`register_post_meta` with `show_in_rest: true`) in the block editor. On the front end, `wp_head` at priority 1 checks the meta value for singular posts and outputs the robots tag if set.

## WordPress Concepts Demonstrated

| Concept | Where Used |
|---|---|
| `add_meta_box()` + nonce verification | `class-nim-meta-box.php` |
| `save_post` hook with autosave/revision guards | `class-nim-meta-box.php` |
| `register_post_meta()` with `show_in_rest` | `class-nim-block-sidebar.php` |
| `enqueue_block_editor_assets` | `class-nim-block-sidebar.php` |
| Gutenberg `registerPlugin` / `PluginSidebar` | `assets/js/block-sidebar.js` |
| `wp_head` hook for front-end output | `class-nim-output.php` |
| `is_singular()` / `get_queried_object_id()` | `class-nim-output.php` |
| `get_post_types()` loop for all public types | Both class files |

## File Structure

```
noindex-manager/
├── noindex-manager.php               # Bootstrap, constants, includes
├── README.md
├── assets/
│   ├── css/
│   │   └── admin.css                 # Classic + block editor styles
│   └── js/
│       └── block-sidebar.js          # Gutenberg sidebar panel
└── includes/
    ├── class-nim-meta-box.php        # Classic editor meta box + save
    ├── class-nim-block-sidebar.php   # Meta registration + block editor enqueue
    └── class-nim-output.php          # Front-end <meta> tag output
```

## Requirements

- WordPress 6.0+
- PHP 8.0+

## License

GPL-2.0-or-later
