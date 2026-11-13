<?php
/**
 * Carga de assets del tema.
 *
 * Los textos de internacionalización para JavaScript están en español. El
 * archivo .po/.mo de inglés actúa como diccionario de traducción.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_asset( string $relative_path ): string {
    $path = QUANTIA_DIR . '/' . ltrim( $relative_path, '/' );
    $url  = QUANTIA_URI . '/' . ltrim( $relative_path, '/' );
    if ( file_exists( $path ) ) {
        $url .= '?v=' . filemtime( $path );
    }
    return $url;
}

add_action( 'wp_enqueue_scripts', 'quantia_enqueue_assets' );
function quantia_enqueue_assets(): void {

    wp_enqueue_style(
        'quantia-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Ubuntu:wght@400;500;700&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'quantia-app',
        quantia_asset( 'assets/dist/app.css' ),
        array( 'quantia-fonts' ),
        QUANTIA_VERSION
    );

    wp_enqueue_style(
        'quantia-style',
        get_stylesheet_uri(),
        array( 'quantia-app' ),
        QUANTIA_VERSION
    );

    wp_enqueue_script(
        'quantia-app',
        quantia_asset( 'assets/js/app.js' ),
        array(),
        QUANTIA_VERSION,
        array(
            'in_footer' => true,
            'strategy'  => 'defer',
        )
    );

    wp_enqueue_script(
        'quantia-motion',
        quantia_asset( 'assets/js/motion.js' ),
        array( 'quantia-app' ),
        QUANTIA_VERSION,
        array(
            'in_footer' => true,
            'strategy'  => 'defer',
        )
    );

    wp_localize_script(
        'quantia-app',
        'QuantiaConfig',
        array(
            'homeUrl'       => esc_url_raw( home_url( '/' ) ),
            'restUrl'       => esc_url_raw( rest_url( 'quantia/v1/' ) ),
            'nonce'         => wp_create_nonce( 'wp_rest' ),
            'storeIos'      => 'https://apps.apple.com/us/app/quantia-hub/id1499843860',
            'storeAndroid'  => 'https://play.google.com/store/search?q=QUANTIA%20HUB&c=apps&hl=es_419',
            'reducedMotion' => false,
            'locale'        => determine_locale(),
            'i18n'          => array(
                'loading'       => __( 'Cargando', 'quantia-hub' ),
                'error'         => __( 'Ocurrió un error. Intenta de nuevo.', 'quantia-hub' ),
                'year'          => __( 'año', 'quantia-hub' ),
                'years'         => __( 'años', 'quantia-hub' ),
                'resultsFor'    => __( 'Resultados para', 'quantia-hub' ),
                'faqTitle'      => __( 'Preguntas frecuentes', 'quantia-hub' ),
                'answer'        => __( 'respuesta', 'quantia-hub' ),
                'answers'       => __( 'respuestas', 'quantia-hub' ),
                'noResults'     => __( 'No encontramos una respuesta para eso.', 'quantia-hub' ),
                'noResultsHelp' => __( 'Escríbenos y una persona de nuestro equipo te ayuda directamente.', 'quantia-hub' ),
                'writeToTeam'   => __( 'Escribir al equipo', 'quantia-hub' ),
                'copyLink'      => __( 'Copiar enlace', 'quantia-hub' ),
                'linkCopied'    => __( 'Enlace copiado', 'quantia-hub' ),
            ),
        )
    );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

add_filter( 'script_loader_tag', 'quantia_script_module', 10, 3 );
function quantia_script_module( $tag, $handle, $src ) {
    $handles = array( 'quantia-app', 'quantia-motion' );

    if ( in_array( $handle, $handles, true ) ) {
        $tag = sprintf(
            '<script type="module" src="%s" id="%s-js" defer></script>' . "\n",
            esc_url( $src ),
            esc_attr( $handle )
        );
    }

    return $tag;
}

add_filter( 'wp_resource_hints', 'quantia_resource_hints', 10, 2 );
function quantia_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}