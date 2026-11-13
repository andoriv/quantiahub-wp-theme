<?php
/**
 * Tipo de contenido y activación de contactos.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'quantia_register_contact_cpt' );
function quantia_register_contact_cpt(): void {
    register_post_type(
        'contacto',
        array(
            'labels'          => array(
                'name'          => __( 'Contactos', 'quantia-hub' ),
                'singular_name' => __( 'Contacto', 'quantia-hub' ),
                'menu_name'     => __( 'Contactos', 'quantia-hub' ),
                'all_items'     => __( 'Todos los contactos', 'quantia-hub' ),
                'edit_item'     => __( 'Contacto', 'quantia-hub' ),
                'view_item'     => __( 'Ver contacto', 'quantia-hub' ),
            ),
            'public'          => false,
            'show_ui'         => true,
            'show_in_menu'    => false,
            'show_in_rest'    => false,
            'has_archive'     => false,
            'rewrite'         => false,
            'capability_type' => 'post',
            'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
            'map_meta_cap'    => true,
            'supports'        => array( 'title' ),
            'menu_icon'       => 'dashicons-email-alt',
        )
    );
}

add_action( 'after_switch_theme', 'quantia_activate_contact_cpt' );
function quantia_activate_contact_cpt(): void {
    quantia_register_contact_cpt();
    flush_rewrite_rules();
}