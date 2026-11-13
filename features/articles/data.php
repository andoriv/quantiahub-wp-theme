<?php
/**
 * Helpers de datos de artículos.
 *
 * Lee los conceptos del Aprende desde el módulo de configuración del tema y
 * los normaliza al formato que consumen las plantillas. Si el módulo de
 * configuración no está disponible o la opción está vacía, recurre a los
 * valores por defecto declarados en features/settings/data/defaults.php.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cuenta las palabras en un cuerpo de artículo normalizado.
 *
 * @param array $blocks Bloques normalizados del cuerpo.
 * @return int
 */
function quantia_count_words_in_blocks( array $blocks ): int {
    $text = '';

    foreach ( $blocks as $block ) {
        if ( isset( $block['text'] ) && is_string( $block['text'] ) ) {
            $text .= ' ' . $block['text'];
        }
        if ( isset( $block['items'] ) && is_array( $block['items'] ) ) {
            foreach ( $block['items'] as $item ) {
                if ( is_string( $item ) ) {
                    $text .= ' ' . $item;
                } elseif ( is_array( $item ) ) {
                    foreach ( array( 'text', 'term', 'q', 'a' ) as $key ) {
                        if ( isset( $item[ $key ] ) && is_string( $item[ $key ] ) ) {
                            $text .= ' ' . $item[ $key ];
                        }
                    }
                }
            }
        }
        if ( isset( $block['head'] ) && is_array( $block['head'] ) ) {
            $text .= ' ' . implode( ' ', array_filter( $block['head'], 'is_string' ) );
        }
        if ( isset( $block['rows'] ) && is_array( $block['rows'] ) ) {
            foreach ( $block['rows'] as $row ) {
                if ( is_array( $row ) ) {
                    $text .= ' ' . implode( ' ', array_filter( $row, 'is_string' ) );
                }
            }
        }
    }

    if ( preg_match_all( '/[\p{L}\p{N}]+/u', $text, $matches ) ) {
        return count( $matches[0] );
    }

    return 0;
}

/**
 * Devuelve los conceptos del Aprende configurados en el panel.
 *
 * @return array
 */
function quantia_fallback_learn_articles(): array {
    if ( ! function_exists( 'quantia_settings_rows' ) ) {
        return array();
    }

    $rows = quantia_settings_rows( 'learn_concepts' );
    if ( empty( $rows ) ) {
        return array();
    }

    $concepts = array();
    foreach ( $rows as $row ) {
        $points = array();
        if ( is_array( $row['points'] ?? null ) ) {
            foreach ( $row['points'] as $point ) {
                if ( is_array( $point ) && ! empty( $point['text'] ) ) {
                    $points[] = $point['text'];
                } elseif ( is_string( $point ) && '' !== $point ) {
                    $points[] = $point;
                }
            }
        }

        $concepts[] = array(
            'id'          => sanitize_key( $row['concept_id'] ?? '' ),
            'label'       => $row['label'] ?? '',
            'eyebrow'     => $row['eyebrow'] ?? '',
            'title'       => $row['title'] ?? '',
            'description' => $row['description'] ?? '',
            'visual'      => sanitize_key( $row['visual'] ?? 'riesgo' ),
            'points'      => $points,
        );
    }

    return $concepts;
}

/**
 * Devuelve las etiquetas de navegación de los conceptos indexadas por id.
 *
 * Se mantiene por compatibilidad con código antiguo que aún consulte el array
 * de etiquetas. Internamente se deriva de quantia_fallback_learn_articles().
 *
 * @return array
 */
function quantia_fallback_learn_labels(): array {
    $labels = array();
    foreach ( quantia_fallback_learn_articles() as $concept ) {
        $labels[ $concept['id'] ] = $concept['label'] ?: $concept['id'];
    }
    return $labels;
}