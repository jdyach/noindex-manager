<?php
/**
 * Outputs the <meta name="robots" content="noindex"> tag on the front end.
 *
 * @package Noindex_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class NIM_Output {

    public static function init(): void {
        add_action( 'wp_head', [ __CLASS__, 'maybe_output_noindex' ], 1 );
    }

    /**
     * Output the noindex meta tag when the current singular post has the flag set.
     * Runs at priority 1 so it appears near the top of <head>.
     */
    public static function maybe_output_noindex(): void {
        if ( ! is_singular() ) {
            return;
        }

        $post_id = get_queried_object_id();

        if ( ! $post_id ) {
            return;
        }

        $is_noindex = (bool) get_post_meta( $post_id, NIM_META_KEY, true );

        if ( ! $is_noindex ) {
            return;
        }

        echo '<meta name="robots" content="noindex, follow" />' . "\n";
    }
}
