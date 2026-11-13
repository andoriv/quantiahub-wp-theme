<?php
/**
 * Entry point for the Quantia Hub theme.
 *
 * This file exists because WordPress requires it. Its single responsibility is
 * to declare the base constants of the theme and delegate all startup work to
 * the central bootstrap file. Any additional logic belongs outside this file to
 * keep it predictable and easy to audit.
 *
 * Punto de entrada del tema Quantia Hub.
 *
 * Este archivo existe por exigencia de WordPress. Su única responsabilidad es
 * declarar las constantes base del tema y delegar todo el arranque al archivo
 * de bootstrap central. Cualquier otra lógica vive fuera de este archivo para
 * mantenerlo predecible y auditable.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme version, used for asset cache busting.
 *
 * Versión del tema, utilizada para invalidar la caché de los assets.
 */
define( 'QUANTIA_VERSION', '1.0.0' );

/**
 * Absolute path to the theme directory on the file system.
 *
 * Ruta absoluta al directorio del tema en el sistema de archivos.
 */
define( 'QUANTIA_DIR', get_template_directory() );

/**
 * Public URL of the theme directory.
 *
 * URL pública del directorio del tema.
 */
define( 'QUANTIA_URI', get_template_directory_uri() );

/**
 * Loads the central bootstrap that orchestrates the shared helpers and the
 * features pending migration.
 *
 * Carga el bootstrap central que orquesta los helpers compartidos y las
 * features pendientes de migración.
 */
require_once QUANTIA_DIR . '/app/bootstrap.php';