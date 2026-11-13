<?php
/**
 * Tipo de contenido, taxonomía y activación de FAQ.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'quantia_register_faq_cpt' );
function quantia_register_faq_cpt(): void {
    register_post_type(
        'faq',
        array(
            'labels'          => array(
                'name'          => __( 'Preguntas frecuentes', 'quantia-hub' ),
                'singular_name' => __( 'Pregunta', 'quantia-hub' ),
                'menu_name'     => __( 'FAQ', 'quantia-hub' ),
            ),
            'public'          => false,
            'show_ui'         => false,
            'show_in_menu'    => false,
            'show_in_rest'    => false,
            'has_archive'     => false,
            'supports'        => array( 'title' ),
            'capability_type' => 'post',
            'map_meta_cap'    => true,
        )
    );

    register_taxonomy(
        'categoria_faq',
        array( 'faq' ),
        array(
            'labels'            => array(
                'name'          => __( 'Categorías de FAQ', 'quantia-hub' ),
                'singular_name' => __( 'Categoría', 'quantia-hub' ),
                'menu_name'     => __( 'Categorías', 'quantia-hub' ),
            ),
            'public'            => false,
            'show_ui'           => true,
            'hierarchical'      => false,
            'show_admin_column' => true,
            'show_in_rest'      => true,
        )
    );
}

add_action( 'after_switch_theme', 'quantia_activate_faq_cpt' );
function quantia_activate_faq_cpt(): void {
    quantia_register_faq_cpt();
    flush_rewrite_rules();
}