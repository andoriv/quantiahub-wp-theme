<?php
/**
 * Elimina los elementos ocultos de los menús de navegación.
 *
 * Cubre tres fuentes distintas:
 *
 *   1. Menús reales de WordPress mediante wp_nav_menu_objects.
 *   2. Menú fallback del tema mediante quantia_primary_menu_fallback_items.
 *   3. Navegación de productos mediante quantia_product_nav_items.
 *
 * En los tres casos se filtran tanto páginas y productos ocultos como
 * enlaces que apunten a un ancla de una sección oculta de la portada.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filtra los items de cualquier menú de WordPress.
 *
 * @param WP_Post[] $items Items del menú.
 * @param stdClass  $args  Argumentos del menú.
 * @return WP_Post[]
 */
add_filter( 'wp_nav_menu_objects', 'quantia_visibility_filter_nav_menu', 10, 2 );
function quantia_visibility_filter_nav_menu( $items, $args ) {
    if ( ! is_array( $items ) || empty( $items ) ) {
        return $items;
    }

    $hidden = quantia_hidden_sections();
    if ( empty( $hidden ) ) {
        return $items;
    }

    $hidden_pages    = quantia_hidden_route_slugs( 'page' );
    $hidden_products = quantia_hidden_route_slugs( 'producto' );
    $hidden_anchors  = quantia_hidden_anchors();

    $filtered = array();
    foreach ( $items as $item ) {
        if ( ! $item instanceof WP_Post ) {
            $filtered[] = $item;
            continue;
        }

        $url = isset( $item->url ) ? (string) $item->url : '';
        if ( quantia_visibility_url_targets_hidden( $url, $hidden_pages, $hidden_products, $hidden_anchors ) ) {
            continue;
        }

        $object_id = isset( $item->object_id ) ? (int) $item->object_id : 0;
        if ( $object_id > 0 ) {
            $post_type = get_post_type( $object_id );
            if ( 'page' === $post_type || 'producto' === $post_type ) {
                $slug = quantia_visibility_normalize_slug(
                    (string) get_post_field( 'post_name', $object_id )
                );
                if ( 'page' === $post_type && in_array( $slug, $hidden_pages, true ) ) {
                    continue;
                }
                if ( 'producto' === $post_type && in_array( $slug, $hidden_products, true ) ) {
                    continue;
                }
            }
        }

        $filtered[] = $item;
    }

    return $filtered;
}

/**
 * Filtra el menú fallback del tema.
 *
 * @param array<int,array<string,string>> $items
 * @return array<int,array<string,string>>
 */
add_filter( 'quantia_primary_menu_fallback_items', 'quantia_visibility_filter_fallback_items' );
function quantia_visibility_filter_fallback_items( $items ) {
    if ( ! is_array( $items ) || empty( $items ) ) {
        return $items;
    }

    $hidden_pages    = quantia_hidden_route_slugs( 'page' );
    $hidden_products = quantia_hidden_route_slugs( 'producto' );
    $hidden_anchors  = quantia_hidden_anchors();

    if ( empty( $hidden_pages ) && empty( $hidden_products ) && empty( $hidden_anchors ) ) {
        return $items;
    }

    $filtered = array();
    foreach ( $items as $item ) {
        $url = isset( $item['url'] ) ? (string) $item['url'] : '';
        if ( quantia_visibility_url_targets_hidden( $url, $hidden_pages, $hidden_products, $hidden_anchors ) ) {
            continue;
        }
        $filtered[] = $item;
    }
    return $filtered;
}

/**
 * Filtra la navegación de productos del dropdown.
 *
 * @param array<int,array<string,string>> $items
 * @return array<int,array<string,string>>
 */
add_filter( 'quantia_product_nav_items', 'quantia_visibility_filter_product_nav' );
function quantia_visibility_filter_product_nav( $items ) {
    if ( ! is_array( $items ) || empty( $items ) ) {
        return $items;
    }

    $hidden_products = quantia_hidden_route_slugs( 'producto' );
    if ( empty( $hidden_products ) ) {
        return $items;
    }

    $filtered = array();
    foreach ( $items as $item ) {
        $url  = isset( $item['url'] ) ? (string) $item['url'] : '';
        $path = $url ? wp_parse_url( $url, PHP_URL_PATH ) : '';
        $path = $path ? trim( $path, '/' ) : '';

        $segments = array_values( array_filter( explode( '/', $path ) ) );
        if ( ! empty( $segments ) ) {
            $slug = quantia_visibility_normalize_slug( (string) end( $segments ) );
            if ( in_array( $slug, $hidden_products, true ) ) {
                continue;
            }
        }

        $filtered[] = $item;
    }
    return $filtered;
}