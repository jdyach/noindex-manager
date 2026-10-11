<?php
/**
 * Plugin Name:       Noindex Manager
 * Plugin URI:        https://github.com/jdyach/noindex-manager
 * Description:       Adds a simple checkbox to the post/page editor to set individual content as noindex, keeping it out of search engine results.
 * Version:           1.0.0
 * Author:            Jacqui Dyachenko
 * Author URI:        https://jacquid.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       noindex-manager
 * Requires at least: 6.0
 * Requires PHP:      8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NIM_VERSION',    '1.0.0' );
define( 'NIM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'NIM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'NIM_META_KEY',   '_nim_noindex' );

require_once NIM_PLUGIN_DIR . 'includes/class-nim-meta-box.php';
require_once NIM_PLUGIN_DIR . 'includes/class-nim-block-sidebar.php';
require_once NIM_PLUGIN_DIR . 'includes/class-nim-output.php';

function nim_init(): void {
    NIM_Meta_Box::init();
    NIM_Block_Sidebar::init();
    NIM_Output::init();
}
add_action( 'plugins_loaded', 'nim_init' );
