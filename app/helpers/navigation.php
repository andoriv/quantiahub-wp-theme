<?php
/**
 * Utilidades de navegación del tema.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_primary_menu_fallback(): void {
    $items = array(
        array( 'label' => __( 'Inicio', 'quantia-hub' ),   'url' => home_url( '/' ) ),
        array( 'label' => __( 'Tarifas', 'quantia-hub' ),  'url' => home_url( '/tarifas/' ) ),
        array( 'label' => __( 'Aprende', 'quantia-hub' ),  'url' => home_url( '/aprende/' ) ),
        array( 'label' => __( 'Soporte', 'quantia-hub' ),  'url' => home_url( '/soporte/' ) ),
        array( 'label' => __( 'Nosotros', 'quantia-hub' ), 'url' => home_url( '/nosotros/' ) ),
    );
    echo '<ul class="flex items-center gap-0.5">';
    foreach ( $items as $item ) {
        printf(
            '<li><a href="%s" class="rounded-full px-3 py-2 text-[13px] font-semibold text-ink/65 transition-colors hover:bg-mist hover:text-ink">%s</a></li>',
            esc_url( $item['url'] ),
            esc_html( $item['label'] )
        );
    }
    echo '</ul>';
}

function quantia_get_product_nav(): array {
    $locations = get_nav_menu_locations();
    $items     = array();

    if ( ! empty( $locations['primary'] ) ) {
        $menu_items = wp_get_nav_menu_items( $locations['primary'] );
        if ( $menu_items ) {
            foreach ( $menu_items as $item ) {
                if ( 'productos' === $item->object || ( isset( $item->object_id ) && 'producto' === get_post_type( $item->object_id ) ) ) {
                    $items[] = array(
                        'label'       => $item->title,
                        'url'         => $item->url,
                        'description' => $item->description,
                        'icon'        => get_post_meta( $item->object_id, '_quantia_icon', true ) ?: 'trending-up',
                    );
                }
            }
        }
    }

    if ( empty( $items ) ) {
        $items = array(
            array(
                'label'       => __( 'Inversiones', 'quantia-hub' ),
                'url'         => home_url( '/productos/inversiones/' ),
                'description' => __( 'Portafolios administrados según tu perfil', 'quantia-hub' ),
                'icon'        => 'trending-up',
            ),
            array(
                'label'       => __( 'Alcancías', 'quantia-hub' ),
                'url'         => home_url( '/productos/alcancias/' ),
                'description' => __( 'Ahorro META y LIBRE con interés', 'quantia-hub' ),
                'icon'        => 'piggy-bank',
            ),
            array(
                'label'       => __( 'Retiro', 'quantia-hub' ),
                'url'         => home_url( '/productos/retiro/' ),
                'description' => __( 'Fondo de jubilación Quantia AFP', 'quantia-hub' ),
                'icon'        => 'sunset',
            ),
            array(
                'label'       => __( 'Tarjetas', 'quantia-hub' ),
                'url'         => home_url( '/productos/tarjeta/' ),
                'description' => __( 'Visa Débito para cajeros y comercios', 'quantia-hub' ),
                'icon'        => 'credit-card',
            ),
        );
    }

    return $items;
}