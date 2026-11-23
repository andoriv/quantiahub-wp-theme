<?php
/**
 * Configuración de contacto desde constantes (wp-config.php o env).
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_contact_config_get( string $const_name ): string {
    if ( defined( $const_name ) && constant( $const_name ) ) {
        return (string) constant( $const_name );
    }
    $env = getenv( $const_name );
    return is_string( $env ) ? $env : '';
}

add_filter( 'option_quantia_option_contact_email', 'quantia_contact_email_from_config' );
add_filter( 'default_option_quantia_option_contact_email', 'quantia_contact_email_from_config' );
function quantia_contact_email_from_config( $value ) {
    $configured = quantia_contact_config_get( 'QUANTIA_CONTACT_EMAIL' );
    return $configured !== '' ? $configured : $value;
}