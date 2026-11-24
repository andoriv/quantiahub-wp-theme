<?php
/**
 * Section visibility feature.
 *
 * Permite al administrador ocultar páginas y productos del sitio público
 * sin borrar su contenido. Las secciones ocultas devuelven 404, desaparecen
 * de los menús y se excluyen de los sitemaps.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/registry.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/admin.php';
require_once __DIR__ . '/router.php';
require_once __DIR__ . '/nav-filter.php';
require_once __DIR__ . '/sitemap-filter.php';