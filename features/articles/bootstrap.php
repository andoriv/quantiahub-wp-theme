<?php
/**
 * Arranque de la feature de artículos.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$quantia_article_modules = array(
    __DIR__ . '/cpt.php',
    __DIR__ . '/data.php',
    __DIR__ . '/fields.php',
);

foreach ( $quantia_article_modules as $quantia_module ) {
    if ( file_exists( $quantia_module ) ) {
        require_once $quantia_module;
    }
}