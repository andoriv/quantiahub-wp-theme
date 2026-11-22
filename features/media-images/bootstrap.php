<?php
/**
 * Arranque de la feature de imágenes administrables.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/registry.php';
require_once __DIR__ . '/helpers.php';

if ( is_admin() ) {
    require_once __DIR__ . '/admin.php';
    require_once __DIR__ . '/save.php';
}