<?php
/**
 * Tipos de contenido personalizados pendientes.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'quantia_register_legacy_cpts' );
function quantia_register_legacy_cpts(): void {

    register_post_type(
        'producto',
        array(
            'labels'          => array(
                'name'          => __( 'Productos', 'quantia-hub' ),
                'singular_name' => __( 'Producto', 'quantia-hub' ),
                'add_new_item'  => __( 'Añadir producto', 'quantia-hub' ),
                'edit_item'     => __( 'Editar producto', 'quantia-hub' ),
                'all_items'     => __( 'Todos los productos', 'quantia-hub' ),
                'menu_name'     => __( 'Productos', 'quantia-hub' ),
            ),
            'public'          => true,
            'has_archive'     => false,
            'rewrite'         => array( 'slug' => 'productos', 'with_front' => false ),
            'menu_icon'       => 'dashicons-chart-line',
            'supports'        => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
            'show_in_rest'    => true,
            'capability_type' => 'post',
        )
    );

    register_post_type(
        'documento_legal',
        array(
            'labels'          => array(
                'name'          => __( 'Documentos legales', 'quantia-hub' ),
                'singular_name' => __( 'Documento legal', 'quantia-hub' ),
                'add_new_item'  => __( 'Añadir documento', 'quantia-hub' ),
                'edit_item'     => __( 'Editar documento', 'quantia-hub' ),
                'all_items'     => __( 'Todos los documentos', 'quantia-hub' ),
                'menu_name'     => __( 'Legal', 'quantia-hub' ),
            ),
            'public'          => true,
            'has_archive'     => false,
            'rewrite'         => array( 'slug' => 'legal', 'with_front' => false ),
            'menu_icon'       => 'dashicons-shield',
            'supports'        => array( 'title', 'editor', 'revisions', 'page-attributes' ),
            'show_in_rest'    => true,
            'capability_type' => 'post',
        )
    );

    register_post_type(
        'testimonio',
        array(
            'labels'          => array(
                'name'          => __( 'Testimonios', 'quantia-hub' ),
                'singular_name' => __( 'Testimonio', 'quantia-hub' ),
                'add_new_item'  => __( 'Añadir testimonio', 'quantia-hub' ),
                'edit_item'     => __( 'Editar testimonio', 'quantia-hub' ),
                'all_items'     => __( 'Todos los testimonios', 'quantia-hub' ),
                'menu_name'     => __( 'Testimonios', 'quantia-hub' ),
            ),
            'public'          => true,
            'has_archive'     => false,
            'rewrite'         => array( 'slug' => 'testimonios', 'with_front' => false ),
            'menu_icon'       => 'dashicons-format-quote',
            'supports'        => array( 'title', 'thumbnail', 'page-attributes' ),
            'show_in_rest'    => true,
            'capability_type' => 'post',
        )
    );
}

add_action( 'after_switch_theme', function () {
    quantia_register_legacy_cpts();
    flush_rewrite_rules();
} );