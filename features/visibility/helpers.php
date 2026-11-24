<?php
/**
 * Helpers públicos del módulo de visibilidad.
 *
 * Expone la API que consumen las plantillas, el router y los filtros de
 * navegación. Todas las lecturas se hacen contra la option directa para que
 * la caché de objetos de WordPress se invalide correctamente cuando el
 * administrador guarda cambios.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Nombre de la option que almacena las claves ocultas.
 */
const QUANTIA_VISIBILITY_OPTION = 'quantia_hidden_sections';

/**
 * Devuelve las claves ocultas filtradas contra el registro actual.
 *
 * @return string[]
 */
function quantia_hidden_sections(): array {
    $value = get_option( QUANTIA_VISIBILITY_OPTION, array() );
    if ( ! is_array( $value ) ) {
        return array();
    }
    $valid = array_keys( quantia_visibility_registry() );
    return array_values( array_intersect( $value, $valid ) );
}

/**
 * Indica si un elemento del registro está visible al público.
 *
 * Las claves desconocidas se consideran visibles para no romper la web si
 * el registro cambia de una versión a otra.
 *
 * @param string $key Clave del registro.
 * @return bool
 */
function quantia_section_visible( string $key ): bool {
    if ( ! array_key_exists( $key, quantia_visibility_registry() ) ) {
        return true;
    }
    return ! in_array( $key, quantia_hidden_sections(), true );
}

/**
 * Normaliza un slug para comparaciones seguras.
 *
 * @param string $slug Slug original.
 * @return string
 */
function quantia_visibility_normalize_slug( string $slug ): string {
    return sanitize_title( remove_accents( $slug ) );
}

/**
 * Devuelve los slugs ocultos para un tipo dado.
 *
 * @param string $type 'page' | 'producto'.
 * @return string[]
 */
function quantia_hidden_route_slugs( string $type ): array {
    $hidden = quantia_hidden_sections();
    if ( empty( $hidden ) ) {
        return array();
    }

    $slugs = array();
    foreach ( quantia_visibility_registry() as $key => $item ) {
        if ( ! isset( $item['type'] ) || $item['type'] !== $type ) {
            continue;
        }
        if ( ! in_array( $key, $hidden, true ) ) {
            continue;
        }
        if ( empty( $item['slug'] ) ) {
            continue;
        }
        $slugs[] = quantia_visibility_normalize_slug( (string) $item['slug'] );
    }
    return $slugs;
}

/**
 * Devuelve los anchors de todas las secciones ocultas.
 *
 * @return string[]
 */
function quantia_hidden_anchors(): array {
    $hidden = quantia_hidden_sections();
    if ( empty( $hidden ) ) {
        return array();
    }

    $anchors = array();
    foreach ( quantia_visibility_registry() as $key => $item ) {
        if ( ! in_array( $key, $hidden, true ) ) {
            continue;
        }
        if ( empty( $item['anchors'] ) || ! is_array( $item['anchors'] ) ) {
            continue;
        }
        foreach ( $item['anchors'] as $anchor ) {
            $anchor = ltrim( strtolower( (string) $anchor ), '#' );
            if ( '' !== $anchor ) {
                $anchors[] = $anchor;
            }
        }
    }
    return array_values( array_unique( $anchors ) );
}

/**
 * Devuelve la clave del registro que coincide con un slug oculto.
 *
 * @param string $slug Slug normalizado o no.
 * @param string $type Tipo del registro.
 * @return string|null
 */
function quantia_visibility_hidden_key( string $slug, string $type ): ?string {
    if ( '' === $slug ) {
        return null;
    }
    $hidden     = quantia_hidden_sections();
    if ( empty( $hidden ) ) {
        return null;
    }
    $normalized = quantia_visibility_normalize_slug( $slug );

    foreach ( quantia_visibility_registry() as $key => $item ) {
        if ( ! isset( $item['type'] ) || $item['type'] !== $type ) {
            continue;
        }
        if ( empty( $item['slug'] ) ) {
            continue;
        }
        if ( quantia_visibility_normalize_slug( (string) $item['slug'] ) !== $normalized ) {
            continue;
        }
        if ( in_array( $key, $hidden, true ) ) {
            return $key;
        }
    }
    return null;
}

/**
 * Devuelve la ruta del template part registrado para una sección.
 *
 * @param string $key Clave del registro.
 * @return string
 */
function quantia_visibility_template_for( string $key ): string {
    $registry = quantia_visibility_registry();
    if ( isset( $registry[ $key ]['template'] ) ) {
        return (string) $registry[ $key ]['template'];
    }
    return '';
}

/**
 * Renderiza un template part solo si la sección está visible.
 *
 * Es el reemplazo directo de get_template_part() en las plantillas que
 * deben respetar la configuración de visibilidad.
 *
 * @param string $key           Clave del registro.
 * @param string $template_path Ruta opcional del template part.
 * @param array  $args          Argumentos opcionales para el template.
 * @return void
 */
function quantia_render_section( string $key, string $template_path = '', array $args = array() ): void {
    if ( ! quantia_section_visible( $key ) ) {
        return;
    }

    if ( '' === $template_path ) {
        $template_path = quantia_visibility_template_for( $key );
    }

    if ( '' === $template_path ) {
        return;
    }

    get_template_part( $template_path, null, $args );
}

/**
 * Indica si una URL de menú apunta a contenido oculto.
 *
 * Comprueba el fragmento y el último segmento de la ruta contra los slugs
 * y anchors ocultos. Se usa desde el filtro de menús.
 *
 * @param string   $url             URL a analizar.
 * @param string[] $hidden_pages    Slugs de páginas ocultas.
 * @param string[] $hidden_products Slugs de productos ocultos.
 * @param string[] $hidden_anchors  Anchors de secciones ocultas.
 * @return bool
 */
function quantia_visibility_url_targets_hidden(
    string $url,
    array $hidden_pages,
    array $hidden_products,
    array $hidden_anchors
): bool {
    if ( '' === $url ) {
        return false;
    }

    $fragment = wp_parse_url( $url, PHP_URL_FRAGMENT );
    if ( $fragment && ! empty( $hidden_anchors ) ) {
        if ( in_array( strtolower( $fragment ), $hidden_anchors, true ) ) {
            return true;
        }
    }

    $path = wp_parse_url( $url, PHP_URL_PATH );
    if ( ! $path ) {
        return false;
    }

    $segments = array_values( array_filter( explode( '/', trim( $path, '/' ) ) ) );
    if ( empty( $segments ) ) {
        return false;
    }

    $last = quantia_visibility_normalize_slug( (string) end( $segments ) );
    if ( in_array( $last, $hidden_pages, true ) ) {
        return true;
    }
    if ( in_array( $last, $hidden_products, true ) ) {
        return true;
    }

    return false;
}