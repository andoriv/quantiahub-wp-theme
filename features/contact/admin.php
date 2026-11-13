<?php
/**
 * Contacts administration.
 *
 * This file registers the contact list screen inside the Quantia Hub menu,
 * defines the custom columns, renders the detail metabox and adjusts the row
 * actions to reflect that contacts are read only entries created by the
 * public form.
 *
 * Administración de contactos.
 *
 * Este archivo registra la pantalla del listado de contactos dentro del menú
 * de Quantia Hub, define las columnas personalizadas, renderiza el metabox de
 * detalle y ajusta las acciones por fila para reflejar que los contactos son
 * entradas de solo lectura creadas por el formulario público.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'quantia_add_contacts_menu', 20 );
function quantia_add_contacts_menu(): void {
    if ( ! function_exists( 'acf_add_options_page' ) ) {
        add_menu_page(
            __( 'Quantia Hub', 'quantia-hub' ),
            __( 'Quantia Hub', 'quantia-hub' ),
            'manage_options',
            'quantia-hub',
            '__return_null',
            'dashicons-admin-generic',
            3
        );
    }

    add_submenu_page(
        'quantia-hub',
        __( 'Contacts', 'quantia-hub' ),
        __( 'Contacts', 'quantia-hub' ),
        'manage_options',
        'edit.php?post_type=contacto'
    );
}

add_filter( 'manage_contacto_posts_columns', 'quantia_contact_columns' );
function quantia_contact_columns( $columns ): array {
    $new = array();
    if ( isset( $columns['cb'] ) ) {
        $new['cb'] = $columns['cb'];
    }
    $new['title']        = __( 'Contact', 'quantia-hub' );
    $new['email']        = __( 'Email', 'quantia-hub' );
    $new['phone']        = __( 'Phone', 'quantia-hub' );
    $new['inquiry_type'] = __( 'Inquiry type', 'quantia-hub' );
    $new['new_client']   = __( 'New client', 'quantia-hub' );
    $new['date']         = __( 'Date', 'quantia-hub' );
    return $new;
}

add_action( 'manage_contacto_posts_custom_column', 'quantia_contact_column_content', 10, 2 );
function quantia_contact_column_content( $column, $post_id ): void {
    switch ( $column ) {
        case 'email':
            $email = get_post_meta( $post_id, '_quantia_contact_email', true );
            if ( $email ) {
                echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
            }
            break;
        case 'phone':
            echo esc_html( get_post_meta( $post_id, '_quantia_contact_phone', true ) );
            break;
        case 'inquiry_type':
            echo esc_html( get_post_meta( $post_id, '_quantia_contact_inquiry_type', true ) );
            break;
        case 'new_client':
            echo esc_html( get_post_meta( $post_id, '_quantia_contact_new_client', true ) );
            break;
    }
}

add_action( 'add_meta_boxes', 'quantia_contact_metabox' );
function quantia_contact_metabox(): void {
    add_meta_box(
        'quantia_contact_details',
        __( 'Contact details', 'quantia-hub' ),
        'quantia_render_contact_metabox',
        'contacto',
        'normal',
        'high'
    );
}

function quantia_render_contact_metabox( $post ): void {
    $fields = array(
        __( 'Name', 'quantia-hub' )         => get_post_meta( $post->ID, '_quantia_contact_name', true ),
        __( 'Email', 'quantia-hub' )        => get_post_meta( $post->ID, '_quantia_contact_email', true ),
        __( 'Phone', 'quantia-hub' )        => get_post_meta( $post->ID, '_quantia_contact_phone', true ),
        __( 'Company', 'quantia-hub' )      => get_post_meta( $post->ID, '_quantia_contact_company', true ),
        __( 'Role', 'quantia-hub' )         => get_post_meta( $post->ID, '_quantia_contact_role', true ),
        __( 'New client', 'quantia-hub' )   => get_post_meta( $post->ID, '_quantia_contact_new_client', true ),
        __( 'Inquiry type', 'quantia-hub' ) => get_post_meta( $post->ID, '_quantia_contact_inquiry_type', true ),
        __( 'IP', 'quantia-hub' )           => get_post_meta( $post->ID, '_quantia_contact_ip', true ),
    );

    echo '<table class="form-table" role="presentation"><tbody>';
    foreach ( $fields as $label => $value ) {
        echo '<tr>';
        echo '<th scope="row" style="width:180px;"><strong>' . esc_html( $label ) . '</strong></th>';
        echo '<td>' . ( $value ? esc_html( $value ) : '—' ) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';

    echo '<h3 style="margin-top:20px;">' . esc_html__( 'Message', 'quantia-hub' ) . '</h3>';
    echo '<div style="background:#f6f7f7;padding:16px;border-radius:6px;white-space:pre-wrap;">';
    echo esc_html( $post->post_content );
    echo '</div>';

    $email = get_post_meta( $post->ID, '_quantia_contact_email', true );
    if ( $email ) {
        echo '<p style="margin-top:20px;"><a class="button button-primary" href="mailto:' . esc_attr( $email ) . '">' . esc_html__( 'Reply by email', 'quantia-hub' ) . '</a></p>';
    }
}

add_action( 'admin_head', function () {
    global $post_type;
    if ( 'contacto' === $post_type ) {
        echo '<style>
            #titlediv { display: none !important; }
            #postdivrich { display: none !important; }
        </style>';
    }
} );

add_filter( 'post_row_actions', 'quantia_contact_row_actions', 10, 2 );
function quantia_contact_row_actions( $actions, $post ): array {
    if ( 'contacto' === $post->post_type && isset( $actions['edit'] ) ) {
        $actions['edit'] = str_replace( __( 'Edit', 'quantia-hub' ), __( 'View', 'quantia-hub' ), $actions['edit'] );
        $actions['edit'] = str_replace( 'Editar', 'Ver', $actions['edit'] );
        $actions['edit'] = str_replace( 'Edit', 'View', $actions['edit'] );
    }
    return $actions;
}