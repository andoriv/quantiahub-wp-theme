<?php
/**
 * Excluye las secciones ocultas de los sitemaps XML de WordPress.
 *
 * Evita que un buscador descubra URLs que van a devolver 404 (integridad SEO).
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'wp_sitemaps_posts_query_args', 'quantia_visibility_sitemap_exclude', 10, 2 );
function quantia_visibility_sitemap_exclude( $args, $post_type ) {
    if ( ! is_array( $args ) ) {
        return $args;
    }

    $hidden = quantia_hidden_sections();
    if ( empty( $hidden ) ) {
        return $args;
    }

    $exclude_ids = array();

    if ( 'page' === $post_type ) {
        foreach ( quantia_hidden_route_slugs( 'page' ) as $slug ) {
            $page = get_page_by_path( $slug, OBJECT, 'page' );
            if ( $page ) {
                $exclude_ids[] = (int) $page->ID;
            }
        }
    }

    if ( 'producto' === $post_type ) {
        foreach ( quantia_hidden_route_slugs( 'producto' ) as $slug ) {
            $product = get_page_by_path( $slug, OBJECT, 'producto' );
            if ( $product ) {
                $exclude_ids[] = (int) $product->ID;
            }
        }
    }

    if ( ! empty( $exclude_ids ) ) {
        $existing = isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array();
        $args['post__not_in'] = array_values( array_unique( array_merge( $existing, $exclude_ids ) ) );
    }

    return $args;
}