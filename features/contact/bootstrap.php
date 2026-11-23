<?php
/**
 * Arranque de la feature de contacto.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$quantia_contact_modules = array(
    __DIR__ . '/cpt.php',
    __DIR__ . '/config.php',
    __DIR__ . '/turnstile.php',
    __DIR__ . '/ajax.php',
);

foreach ( $quantia_contact_modules as $quantia_module ) {
    if ( file_exists( $quantia_module ) ) {
        require_once $quantia_module;
    }
}

if ( is_admin() && file_exists( __DIR__ . '/admin.php' ) ) {
    require_once __DIR__ . '/admin.php';
}