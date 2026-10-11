<?php
/**
 * Classic editor meta box for the noindex checkbox.
 *
 * @package Noindex_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class NIM_Meta_Box {

    public static function init(): void {
        add_action( 'add_meta_boxes',  [ __CLASS__, 'register' ] );
        add_action( 'save_post',       [ __CLASS__, 'save' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue' ] );
    }

    /**
     * Register the meta box on all public post types.
     */
    public static function register(): void {
        $post_types = get_post_types( [ 'public' => true ] );

        foreach ( $post_types as $post_type ) {
            add_meta_box(
                'nim_noindex',
                __( 'Search Engine Visibility', 'noindex-manager' ),
                [ __CLASS__, 'render' ],
                $post_type,
                'side',
                'default'
            );
        }
    }

    /**
     * Render the meta box HTML.
     */
    public static function render( WP_Post $post ): void {
        $is_noindex = (bool) get_post_meta( $post->ID, NIM_META_KEY, true );
        wp_nonce_field( 'nim_save_noindex', 'nim_nonce' );
        ?>
        <div class="nim-meta-box">
            <label class="nim-label">
                <input
                    type="checkbox"
                    name="nim_noindex"
                    value="1"
                    <?php checked( $is_noindex ); ?>
                />
                <?php esc_html_e( 'Hide from search engines (noindex)', 'noindex-manager' ); ?>
            </label>
            <p class="nim-help">
                <?php esc_html_e( 'Adds a noindex meta tag to this page. Search engines that respect this tag will exclude it from their results.', 'noindex-manager' ); ?>
            </p>
            <?php if ( $is_noindex ) : ?>
                <p class="nim-active-notice">
                    <span class="dashicons dashicons-hidden"></span>
                    <?php esc_html_e( 'This page is set to noindex.', 'noindex-manager' ); ?>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Save the meta value on post save.
     */
    public static function save( int $post_id ): void {
        // Verify nonce.
        if ( ! isset( $_POST['nim_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nim_nonce'] ), 'nim_save_noindex' ) ) {
            return;
        }

        // Skip autosaves and revisions.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        // Check capability.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $noindex = isset( $_POST['nim_noindex'] ) ? '1' : '';
        update_post_meta( $post_id, NIM_META_KEY, $noindex );
    }

    /**
     * Enqueue meta box styles in the admin.
     */
    public static function enqueue( string $hook ): void {
        if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
            return;
        }
        wp_enqueue_style(
            'nim-admin',
            NIM_PLUGIN_URL . 'assets/css/admin.css',
            [],
            NIM_VERSION
        );
    }
}
