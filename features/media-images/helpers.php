<?php
/**
 * Helpers públicos de imágenes administrables.
 *
 * Prioridad de resolución:
 *   1) Override en quantia_managed_images[key] (attachment ID o URL externa).
 *   2) Legacy quantia_option( legacy_key ) si existe.
 *   3) Default del registry.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

const QUANTIA_MEDIA_OPTION = 'quantia_managed_images';

function quantia_managed_images_option(): array {
    $data = get_option( QUANTIA_MEDIA_OPTION, array() );
    return is_array( $data ) ? $data : array();
}

/**
 * Devuelve la URL final de una imagen administrable.
 */
function quantia_managed_image_src( string $key, string $size = 'full' ): string {
    $item = quantia_media_registry_item( $key );
    if ( ! $item ) {
        return '';
    }

    $entry = quantia_managed_images_option()[ $key ] ?? null;

    if ( is_array( $entry ) ) {
        if ( ! empty( $entry['id'] ) ) {
            $url = wp_get_attachment_image_url( (int) $entry['id'], $size );
            if ( $url ) {
                return esc_url_raw( $url );
            }
        }
        if ( ! empty( $entry['url'] ) ) {
            return esc_url_raw( (string) $entry['url'] );
        }
    }

    if ( ! empty( $item['legacy_key'] ) && function_exists( 'quantia_option' ) ) {
        $legacy = quantia_option( $item['legacy_key'], '' );
        if ( is_array( $legacy ) && ! empty( $legacy['url'] ) ) {
            return esc_url_raw( (string) $legacy['url'] );
        }
        if ( is_numeric( $legacy ) ) {
            $url = wp_get_attachment_image_url( (int) $legacy, $size );
            if ( $url ) {
                return esc_url_raw( $url );
            }
        }
        if ( is_string( $legacy ) && $legacy !== '' ) {
            return esc_url_raw( $legacy );
        }
    }

    return esc_url_raw( (string) ( $item['default'] ?? '' ) );
}

/**
 * Devuelve el attachment ID si el override es una imagen de la biblioteca.
 */
function quantia_managed_image_id( string $key ): int {
    $entry = quantia_managed_images_option()[ $key ] ?? null;
    if ( is_array( $entry ) && ! empty( $entry['id'] ) ) {
        return (int) $entry['id'];
    }
    return 0;
}

/**
 * Renderiza <img> usando preferentemente la biblioteca (con srcset).
 */
function quantia_managed_image_render( string $key, string $size = 'large', array $attr = array() ): string {
    $id = quantia_managed_image_id( $key );
    if ( $id > 0 ) {
        return wp_get_attachment_image( $id, $size, false, $attr );
    }

    $src = quantia_managed_image_src( $key, $size );
    if ( ! $src ) {
        return '';
    }

    $alt = $attr['alt'] ?? '';
    unset( $attr['alt'] );

    $attrs = '';
    foreach ( $attr as $k => $v ) {
        $attrs .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
    }

    return sprintf( '<img src="%s" alt="%s"%s>', esc_url( $src ), esc_attr( $alt ), $attrs );
}

/**
 * Devuelve true si la imagen tiene un override explícito (biblioteca o URL externa).
 * No cuenta ni el legacy ni el default.
 *
 * @param string $key Key del registry.
 * @return bool
 */
function quantia_managed_image_has_override( string $key ): bool {
    $entry = quantia_managed_images_option()[ $key ] ?? null;
    if ( ! is_array( $entry ) ) {
        return false;
    }
    return ! empty( $entry['id'] ) || ! empty( $entry['url'] );
}

/**
 * Devuelve el key del registry para un screen de phone-frame.
 *
 * @param string $screen Valor del parámetro 'screen' del componente.
 * @return string Key del registry, o cadena vacía si no hay frame registrado.
 */
function quantia_media_frame_key_for_screen( string $screen ): string {
    $map = array(
        'inicio'      => 'frame_inicio',
        'inversiones' => 'frame_inversiones',
        'alcancias'   => 'frame_alcancias',
        'jubilación'  => 'frame_jubilacion',
        'jubilacion'  => 'frame_jubilacion',
        'tarjeta'     => 'frame_tarjeta',
        'kuara'       => 'frame_kuara',
    );
    return $map[ $screen ] ?? '';
}