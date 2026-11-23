<?php
/**
 * Custom Post Type para contactos.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action(
    'init',
    'quantia_register_contact_post_type'
);

function quantia_register_contact_post_type(): void {
    $labels = array(
        'name'          => __(
            'Contactos',
            'quantia-hub'
        ),
        'singular_name' => __(
            'Contacto',
            'quantia-hub'
        ),
        'menu_name'     => __(
            'Contactos',
            'quantia-hub'
        ),
        'all_items'     => __(
            'Todos los contactos',
            'quantia-hub'
        ),
        'view_item'     => __(
            'Ver contacto',
            'quantia-hub'
        ),
        'search_items'  => __(
            'Buscar contactos',
            'quantia-hub'
        ),
        'not_found'     => __(
            'No se encontraron contactos.',
            'quantia-hub'
        ),
    );

    register_post_type(
        'contacto',
        array(
            'labels'              => $labels,
            'public'              => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_rest'        => false,
            'has_archive'         => false,
            'rewrite'             => false,
            'query_var'           => false,
            'supports'            => array(
                'title',
                'editor',
            ),
            'capability_type'      => 'post',
            'map_meta_cap'         => true,
            'delete_with_user'     => false,
        )
    );
}