<?php
/**
 * FAQ feature bootstrap.
 *
 * This file loads the runtime modules of the FAQ feature. The custom post type
 * and taxonomy must load first because the administration screens, the data
 * layer and the import handler all depend on them being registered on init.
 * The data layer loads after that because the administration modules consume
 * its query helpers. The history module loads before the importer because the
 * importer logs every operation through it.
 *
 * Arranque de la feature de FAQ.
 *
 * Este archivo carga los módulos de ejecución de la feature de FAQ. El tipo de
 * contenido y la taxonomía deben cargarse primero porque las pantallas de
 * administración, la capa de datos y el manejador de importación dependen de
 * que estén registrados en init. La capa de datos se carga a continuación
 * porque los módulos de administración consumen sus funciones de consulta. El
 * módulo de historial se carga antes que el importador porque el importador
 * registra cada operación a través de él.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/cpt.php';
require_once __DIR__ . '/data/queries.php';

if ( is_admin() ) {
    require_once __DIR__ . '/admin/historial.php';
    require_once __DIR__ . '/admin/menu.php';
    require_once __DIR__ . '/admin/list-table.php';
    require_once __DIR__ . '/admin/form.php';
    require_once __DIR__ . '/admin/save.php';
    require_once __DIR__ . '/admin/categorias.php';
    require_once __DIR__ . '/admin/importar.php';
}