<?php
/**
 * Contact form AJAX handler.
 *
 * This file handles the submission of the contact form. It validates every
 * field, rate limits submissions per email and IP, stores the submission as a
 * contacto custom post and sends a notification email to the configured
 * address.
 *
 * Manejador AJAX del formulario de contacto.
 *
 * Este archivo maneja el envío del formulario de contacto. Valida cada campo,
 * limita la tasa de envíos por correo e IP, guarda el envío como un tipo de
 * contenido contacto y envía un correo de notificación a la dirección
 * configurada.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_ajax_nopriv_quantia_contact', 'quantia_handle_contact_form' );
add_action( 'wp_ajax_quantia_contact',        'quantia_handle_contact_form' );

function quantia_handle_contact_form(): void {
    if ( ! check_ajax_referer( 'quantia_contact', 'nonce', false ) ) {
        wp_send_json_error( array( 'message' => __( 'Session expired. Reload the page and try again.', 'quantia-hub' ) ), 403 );
    }

    $name         = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
    $email        = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $company      = sanitize_text_field( wp_unslash( $_POST['company'] ?? '' ) );
    $role         = sanitize_text_field( wp_unslash( $_POST['role'] ?? '' ) );
    $phone        = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
    $new_client   = sanitize_text_field( wp_unslash( $_POST['newClient'] ?? '' ) );
    $inquiry_type = sanitize_text_field( wp_unslash( $_POST['inquiryType'] ?? '' ) );
    $message      = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

    if ( ! $name || ! is_email( $email ) || ! $phone || ! $new_client || ! $inquiry_type || strlen( $message ) < 10 ) {
        wp_send_json_error( array( 'message' => __( 'Please review the required fields.', 'quantia-hub' ) ), 400 );
    }

    $allowed_types = array(
        __( 'Checking account', 'quantia-hub' ),
        __( 'Kuara and transfers', 'quantia-hub' ),
        __( 'Visa debit card', 'quantia-hub' ),
        __( 'Investments', 'quantia-hub' ),
        __( 'Retirement', 'quantia-hub' ),
        __( 'Payroll account', 'quantia-hub' ),
        __( 'Other', 'quantia-hub' ),
    );
    if ( ! in_array( $inquiry_type, $allowed_types, true ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid inquiry type.', 'quantia-hub' ) ), 400 );
    }

    $rate_key = 'quantia_contact_' . md5( $email . '|' . ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
    if ( get_transient( $rate_key ) ) {
        wp_send_json_error( array( 'message' => __( 'We already received your inquiry. Wait a few minutes before sending another one.', 'quantia-hub' ) ), 429 );
    }
    set_transient( $rate_key, 1, 5 * MINUTE_IN_SECONDS );

    $title = sprintf( '%s · %s', $name, $inquiry_type );

    $post_id = wp_insert_post( array(
        'post_type'    => 'contacto',
        'post_status'  => 'publish',
        'post_title'   => $title,
        'post_content' => $message,
        'meta_input'   => array(
            '_quantia_contact_name'         => $name,
            '_quantia_contact_email'        => $email,
            '_quantia_contact_phone'        => $phone,
            '_quantia_contact_company'      => $company,
            '_quantia_contact_role'         => $role,
            '_quantia_contact_new_client'   => $new_client,
            '_quantia_contact_inquiry_type' => $inquiry_type,
            '_quantia_contact_ip'           => $_SERVER['REMOTE_ADDR'] ?? '',
            '_quantia_contact_ua'           => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ),
    ), true );

    if ( is_wp_error( $post_id ) || ! $post_id ) {
        delete_transient( $rate_key );
        wp_send_json_error( array( 'message' => __( 'We could not save your inquiry. Please try again.', 'quantia-hub' ) ), 500 );
    }

    $to      = quantia_option( 'contact_email', get_option( 'admin_email' ) );
    $subject = sprintf(
        /* translators: %s: inquiry type */
        __( '[Quantia Hub] New inquiry, %s', 'quantia-hub' ),
        $inquiry_type
    );

    $lines = array(
        __( 'New inquiry from quantiahub.com', 'quantia-hub' ),
        '',
        __( 'Name', 'quantia-hub' ) . ': ' . $name,
        __( 'Email', 'quantia-hub' ) . ': ' . $email,
        __( 'Phone', 'quantia-hub' ) . ': ' . $phone,
        __( 'Company', 'quantia-hub' ) . ': ' . ( $company ?: '—' ),
        __( 'Role', 'quantia-hub' ) . ': ' . ( $role ?: '—' ),
        __( 'New client', 'quantia-hub' ) . ': ' . $new_client,
        __( 'Inquiry type', 'quantia-hub' ) . ': ' . $inquiry_type,
        '',
        __( 'Message', 'quantia-hub' ) . ':',
        $message,
        '',
        '---',
        __( 'View in admin', 'quantia-hub' ) . ': ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
    );

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    wp_mail( $to, $subject, implode( "\n", $lines ), $headers );

    wp_send_json_success( array(
        'message' => __( 'Inquiry sent. An executive will contact you soon.', 'quantia-hub' ),
        'id'      => $post_id,
    ) );
}