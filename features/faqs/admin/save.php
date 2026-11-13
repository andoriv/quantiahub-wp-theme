<?php
/**
 * FAQ save, toggle and delete handlers.
 *
 * This module handles the creation and update of a single FAQ, the toggle of
 * its visibility flag and the permanent deletion of a question. Deletion uses
 * the standard WordPress flow with a JavaScript confirmation. Access is gated
 * through manage_options.
 *
 * Manejadores de guardado, visibilidad y eliminación de FAQ.
 *
 * Este módulo maneja la creación y actualización de una pregunta, el cambio de
 * su marca de visibilidad y la eliminación permanente de una pregunta. La
 * eliminación usa el flujo estándar de WordPress con una confirmación de
 * JavaScript. El acceso está limitado por manage_options.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_post_quantia_faq_save', 'quantia_handle_faq_save' );
function quantia_handle_faq_save(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    if ( ! isset( $_POST['quantia_faq_nonce'] ) || ! wp_verify_nonce( $_POST['quantia_faq_nonce'], 'quantia_faq_save' ) ) {
        wp_die( __( 'Invalid security token.', 'quantia-hub' ) );
    }

    $id       = isset( $_POST['faq_id'] )       ? (int) $_POST['faq_id'] : 0;
    $question = isset( $_POST['faq_question'] ) ? sanitize_text_field( wp_unslash( $_POST['faq_question'] ) ) : '';
    $answer   = isset( $_POST['faq_answer'] )   ? sanitize_textarea_field( wp_unslash( $_POST['faq_answer'] ) ) : '';
    $category = isset( $_POST['faq_category'] ) ? sanitize_text_field( wp_unslash( $_POST['faq_category'] ) ) : '';
    $product  = isset( $_POST['faq_product'] )  ? sanitize_key( $_POST['faq_product'] ) : '';
    $visible  = isset( $_POST['faq_visible'] )  ? '1' : '0';
    $order    = isset( $_POST['faq_order'] )    ? (int) $_POST['faq_order'] : 0;

    if ( '' === $question || '' === $answer || '' === $category ) {
        $redirect = add_query_arg(
            array(
                'page'   => 'quantia-faqs',
                'action' => $id ? 'edit' : 'new',
                'id'     => $id,
                'error'  => 'required',
            ),
            admin_url( 'admin.php' )
        );
        wp_safe_redirect( $redirect );
        exit;
    }

    $term = get_term_by( 'name', $category, 'categoria_faq' );
    if ( ! $term || is_wp_error( $term ) ) {
        $redirect = add_query_arg(
            array(
                'page'   => 'quantia-faqs',
                'action' => $id ? 'edit' : 'new',
                'id'     => $id,
                'error'  => 'required',
            ),
            admin_url( 'admin.php' )
        );
        wp_safe_redirect( $redirect );
        exit;
    }

    $post_data = array(
        'post_type'    => 'faq',
        'post_title'   => $question,
        'post_content' => $answer,
        'post_status'  => 'publish',
        'menu_order'   => $order,
    );

    if ( $id ) {
        $post_data['ID'] = $id;
        $result = wp_update_post( $post_data, true );
    } else {
        $result = wp_insert_post( $post_data, true );
    }

    if ( is_wp_error( $result ) || ! $result ) {
        wp_die( __( 'The question could not be saved.', 'quantia-hub' ) );
    }

    $post_id = (int) $result;
    update_post_meta( $post_id, 'faq_product', $product );
    update_post_meta( $post_id, 'faq_visible', $visible );
    wp_set_object_terms( $post_id, array( (int) $term->term_id ), 'categoria_faq' );

    wp_safe_redirect( admin_url( 'admin.php?page=quantia-faqs&saved=1' ) );
    exit;
}

add_action( 'admin_post_quantia_faq_toggle', 'quantia_handle_faq_toggle' );
function quantia_handle_faq_toggle(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    $id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
    if ( ! $id ) {
        wp_die( __( 'Invalid ID.', 'quantia-hub' ) );
    }
    check_admin_referer( 'quantia_faq_toggle_' . $id );

    $current = get_post_meta( $id, 'faq_visible', true );
    update_post_meta( $id, 'faq_visible', ( '0' === $current ) ? '1' : '0' );

    wp_safe_redirect( admin_url( 'admin.php?page=quantia-faqs&toggled=1' ) );
    exit;
}

/**
 * Handles the permanent deletion of a single FAQ.
 *
 * Maneja la eliminación permanente de una pregunta individual.
 */
function quantia_handle_faq_delete(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }

    $id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
    if ( ! $id ) {
        wp_die( __( 'Invalid ID.', 'quantia-hub' ) );
    }

    check_admin_referer( 'quantia_faq_delete_' . $id );

    wp_delete_post( $id, true );

    wp_safe_redirect( admin_url( 'admin.php?page=quantia-faqs&deleted=1' ) );
    exit;
}