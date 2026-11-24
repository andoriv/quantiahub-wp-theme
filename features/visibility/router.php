<?php
/**
 * Intercepta peticiones a secciones ocultas y las convierte en 404.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'template_redirect', 'quantia_visibility_enforce', 5 );
function quantia_visibility_enforce(): void {
    if ( is_admin() || wp_doing_ajax() || wp_doing_cron()
        || is_feed() || is_robots() || is_trackback()
        || ( defined( 'REST_REQUEST' ) && REST_REQUEST )
    ) {
        return;
    }

    if ( is_front_page() || is_home() ) {
        return;
    }

    $key = quantia_visibility_current_request_key();
    if ( ! $key ) {
        return;
    }
    
    $action = apply_filters( 'quantia_visibility_hidden_action', '404', $key );

    if ( '404' === $action ) {
        quantia_visibility_force_404();
        return;
    }

    if ( is_string( $action ) && '' !== $action ) {
        wp_safe_redirect( esc_url_raw( $action ), 302 );
        exit;
    }
}

/**
 * Devuelve la clave de la sección oculta que corresponde a la petición actual.
 *
 * @return string|null
 */
function quantia_visibility_current_request_key(): ?string {
    $obj = get_queried_object();

    if ( is_page() && $obj instanceof WP_Post ) {
        return quantia_visibility_hidden_key( $obj->post_name, 'page' );
    }

    if ( is_singular( 'producto' ) && $obj instanceof WP_Post ) {
        return quantia_visibility_hidden_key( $obj->post_name, 'producto' );
    }

    return null;
}

/**
 * Fuerza un 404 limpio sin permitir redirecciones de otros hooks.
 *
 * @return void
 */
function quantia_visibility_force_404(): void {
    global $wp_query;

    if ( $wp_query instanceof WP_Query ) {
        $wp_query->set_404();
        $wp_query->queried_object    = null;
        $wp_query->queried_object_id = 0;
        $wp_query->post              = null;
        $wp_query->posts             = array();
        $wp_query->post_count        = 0;
        $wp_query->found_posts       = 0;
    }

    $GLOBALS['post'] = null;

    status_header( 404 );
    nocache_headers();

    remove_action( 'template_redirect', 'redirect_canonical' );
}