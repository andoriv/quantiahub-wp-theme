<?php
/**
 * Contact feature bootstrap.
 *
 * This file loads the runtime modules of the contact feature. The custom post
 * type and the AJAX handler load on every request because the contact form is
 * public. The administration module loads only inside the admin context. The
 * views are consumed by the page templates through get_template_part and are
 * not required here.
 *
 * Arranque de la feature de contacto.
 *
 * Este archivo carga los módulos de ejecución de la feature de contacto. El
 * tipo de contenido y el manejador AJAX se cargan en cada petición porque el
 * formulario es público. El módulo de administración solo se carga dentro del
 * contexto del panel. Las vistas se consumen desde las plantillas a través de
 * get_template_part y no se requieren aquí.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$quantia_contact_modules = array(
    __DIR__ . '/cpt.php',
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