<?php
/**
 * FAQ administration menu and router.
 *
 * This file registers the FAQ top level menu together with its four subpages
 * and implements the router that dispatches requests to the list, the form or
 * the delete action. Access is gated through the manage_options capability
 * that WordPress assigns to the administrator role by default.
 *
 * Menú y enrutador de la administración de FAQ.
 *
 * Este archivo registra el menú principal de FAQ junto con sus cuatro
 * subpáginas e implementa el enrutador que distribuye las peticiones al
 * listado, al formulario o a la acción de eliminación. El acceso está limitado
 * por la capability manage_options que WordPress asigna por defecto al rol de
 * administrador.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'quantia_faqs_admin_menu', 5 );
function quantia_faqs_admin_menu(): void {
    add_menu_page(
        __( 'FAQ', 'quantia-hub' ),
        __( 'FAQ', 'quantia-hub' ),
        'manage_options',
        'quantia-faqs',
        'quantia_render_faqs_admin',
        'dashicons-editor-help',
        4
    );

    add_submenu_page(
        'quantia-faqs',
        __( 'Questions', 'quantia-hub' ),
        __( 'Questions', 'quantia-hub' ),
        'manage_options',
        'quantia-faqs',
        'quantia_render_faqs_admin'
    );

    add_submenu_page(
        'quantia-faqs',
        __( 'Categories', 'quantia-hub' ),
        __( 'Categories', 'quantia-hub' ),
        'manage_options',
        'quantia-faqs-categorias',
        'quantia_render_faqs_categorias'
    );

    add_submenu_page(
        'quantia-faqs',
        __( 'Import', 'quantia-hub' ),
        __( 'Import', 'quantia-hub' ),
        'manage_options',
        'quantia-faqs-importar',
        'quantia_render_faqs_importar'
    );

    add_submenu_page(
        'quantia-faqs',
        __( 'History', 'quantia-hub' ),
        __( 'History', 'quantia-hub' ),
        'manage_options',
        'quantia-faqs-historial',
        'quantia_render_faqs_historial'
    );
}

/**
 * Router for FAQ administration requests.
 *
 * Enrutador para las peticiones de administración de FAQ.
 */
function quantia_render_faqs_admin(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $action = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : 'list';

    if ( 'new' === $action || 'edit' === $action ) {
        quantia_render_faq_form( $action );
        return;
    }

    if ( 'delete' === $action ) {
        quantia_handle_faq_delete();
        return;
    }

    quantia_render_faq_list();
}