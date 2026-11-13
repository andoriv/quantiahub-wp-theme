<?php
/**
 * Settings feature bootstrap.
 *
 * Este archivo carga el módulo de opciones personalizado que reemplaza por
 * completo al sistema de opciones de ACF. Los accessors se cargan en todas las
 * peticiones porque las plantillas del frontend los consultan. Las páginas de
 * administración y los handlers solo se cargan en el contexto del panel.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/data/defaults.php';
require_once __DIR__ . '/data/accessors.php';

if ( is_admin() ) {
    require_once __DIR__ . '/admin/menu.php';
    require_once __DIR__ . '/admin/handlers.php';
    require_once __DIR__ . '/admin/page-calculator.php';
    require_once __DIR__ . '/admin/page-benefits.php';
    require_once __DIR__ . '/admin/page-funds.php';
    require_once __DIR__ . '/admin/page-profiles.php';
    require_once __DIR__ . '/admin/page-product-data.php';
    require_once __DIR__ . '/admin/page-learn.php';
}