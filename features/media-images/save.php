<?php
/**
 * Guardado de imágenes administrables.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_post_quantia_save_managed_images', 'quantia_handle_save_managed_images' );
function quantia_handle_save_managed_images(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    if ( ! isset( $_POST['quantia_managed_images_nonce'] ) || ! wp_verify_nonce( $_POST['quantia_managed_images_nonce'], 'quantia_save_managed_images' ) ) {
        wp_die( __( 'Invalid security token.', 'quantia-hub' ) );
    }

    $saved = array();

    foreach ( quantia_media_registry() as $group ) {
        foreach ( $group['items'] as $key => $item ) {
            $id  = isset( $_POST[ 'img_' . $key . '_id' ] )  ? (int) $_POST[ 'img_' . $key . '_id' ] : 0;
            $url = isset( $_POST[ 'img_' . $key . '_url' ] ) ? esc_url_raw( wp_unslash( $_POST[ 'img_' . $key . '_url' ] ) ) : '';

            if ( $id > 0 || $url !== '' ) {
                $saved[ $key ] = array( 'id' => $id, 'url' => $url );
            }
        }
    }

    update_option( QUANTIA_MEDIA_OPTION, $saved, false );

    wp_safe_redirect( admin_url( 'admin.php?page=quantia-imagenes&updated=1' ) );
    exit;
}