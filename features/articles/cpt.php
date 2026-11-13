<?php
/**
 * Tipo de contenido, taxonomía y activación de artículos.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'quantia_register_articles_cpt' );
function quantia_register_articles_cpt(): void {
    register_post_type(
        'articulo',
        array(
            'labels'          => array(
                'name'          => __( 'Artículos', 'quantia-hub' ),
                'singular_name' => __( 'Artículo', 'quantia-hub' ),
                'add_new_item'  => __( 'Añadir artículo', 'quantia-hub' ),
                'edit_item'     => __( 'Editar artículo', 'quantia-hub' ),
                'all_items'     => __( 'Todos los artículos', 'quantia-hub' ),
                'menu_name'     => __( 'Artículos', 'quantia-hub' ),
            ),
            'public'          => true,
            'has_archive'     => false,
            'rewrite'         => array( 'slug' => 'aprende', 'with_front' => false ),
            'menu_icon'       => 'dashicons-welcome-learn-more',
            'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
            'show_in_rest'    => true,
            'capability_type' => 'post',
        )
    );

    register_taxonomy(
        'categoria_articulo',
        array( 'articulo' ),
        array(
            'labels'            => array(
                'name'          => __( 'Categorías de artículos', 'quantia-hub' ),
                'singular_name' => __( 'Categoría', 'quantia-hub' ),
                'menu_name'     => __( 'Categorías', 'quantia-hub' ),
            ),
            'public'            => true,
            'hierarchical'      => false,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'rewrite'           => array( 'slug' => 'aprende/categoria', 'with_front' => false ),
        )
    );
}

add_action( 'after_switch_theme', 'quantia_activate_articles_cpt' );
function quantia_activate_articles_cpt(): void {
    quantia_register_articles_cpt();
    $terms = array(
        __( 'Inversión', 'quantia-hub' ),
        __( 'Ahorros', 'quantia-hub' ),
        __( 'Jubilación', 'quantia-hub' ),
    );
    foreach ( $terms as $term ) {
        if ( ! term_exists( $term, 'categoria_articulo' ) ) {
            wp_insert_term( $term, 'categoria_articulo' );
        }
    }
    flush_rewrite_rules();
}