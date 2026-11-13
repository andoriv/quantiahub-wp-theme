<?php
/**
 * Orquestador de arranque del tema.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$quantia_helpers = array(
    '/app/helpers/setup.php',
    '/app/helpers/enqueue.php',
    '/app/helpers/helpers.php',
    '/app/helpers/navigation.php',
    '/app/helpers/seo.php',
    '/app/helpers/acf-options.php',
    '/app/helpers/language-detection.php',
);

foreach ( $quantia_helpers as $quantia_file ) {
    $quantia_path = QUANTIA_DIR . $quantia_file;
    if ( file_exists( $quantia_path ) ) {
        require_once $quantia_path;
    }
}

require_once QUANTIA_DIR . '/features/settings/bootstrap.php';

$quantia_legacy_core = array(
    '/inc/cpts.php',
    '/inc/data-fallbacks.php',
);

foreach ( $quantia_legacy_core as $quantia_file ) {
    $quantia_path = QUANTIA_DIR . $quantia_file;
    if ( file_exists( $quantia_path ) ) {
        require_once $quantia_path;
    }
}

$quantia_features = array(
    '/features/faqs/bootstrap.php',
    '/features/articles/bootstrap.php',
    '/features/contact/bootstrap.php',
    '/features/legal/bootstrap.php',
);

foreach ( $quantia_features as $quantia_file ) {
    $quantia_path = QUANTIA_DIR . $quantia_file;
    if ( file_exists( $quantia_path ) ) {
        require_once $quantia_path;
    }
}