<?php
/**
 * Gutenberg block editor sidebar panel via the REST API + post meta registration.
 *
 * @package Noindex_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class NIM_Block_Sidebar {

    public static function init(): void {
        add_action( 'init',                   [ __CLASS__, 'register_meta' ] );
        add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_sidebar' ] );
    }

    /**
     * Register the post meta so the REST API and block editor can read/write it.
     */
    public static function register_meta(): void {
        $post_types = get_post_types( [ 'public' => true ] );

        foreach ( $post_types as $post_type ) {
            register_post_meta(
                $post_type,
                NIM_META_KEY,
                [
                    'show_in_rest'  => true,
                    'single'        => true,
                    'type'          => 'string',
                    'auth_callback' => function () {
                        return current_user_can( 'edit_posts' );
                    },
                ]
            );
        }
    }

    /**
     * Enqueue the sidebar JS plugin for the block editor.
     */
    public static function enqueue_sidebar(): void {
        wp_enqueue_script(
            'nim-block-sidebar',
            NIM_PLUGIN_URL . 'assets/js/block-sidebar.js',
            [ 'wp-plugins', 'wp-edit-post', 'wp-components', 'wp-data', 'wp-element', 'wp-i18n' ],
            NIM_VERSION,
            true
        );

        wp_enqueue_style(
            'nim-admin',
            NIM_PLUGIN_URL . 'assets/css/admin.css',
            [],
            NIM_VERSION
        );
    }
}
