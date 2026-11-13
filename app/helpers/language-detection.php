<?php
/**
 * Language detection placeholder.
 *
 * Durante el desarrollo del proyecto el sitio se sirve siempre en español.
 * Esta versión mantiene el archivo y el nombre de la función original para
 * que la reactivación de la detección por dispositivo sea un cambio de una
 * sola función en la fase de traducción. El sitio se sirve con el locale
 * es_PA sin importar el idioma del dispositivo del visitante.
 *
 * Placeholder de detección de idioma.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_apply_visitor_locale( string $locale ): string {
    if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        return $locale;
    }

    return 'es_PA';
}
add_filter( 'locale', 'quantia_apply_visitor_locale' );