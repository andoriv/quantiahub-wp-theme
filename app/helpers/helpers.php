<?php
/**
 * Global theme utilities.
 *
 * This file groups the helper functions consumed by templates and data modules.
 * It includes ACF option resolution, image rendering, currency formatting, SVG
 * icon rendering, product icon mapping, home fallback data and article
 * manipulation utilities.
 *
 * Utilidades globales del tema.
 *
 * Este archivo agrupa las funciones auxiliares que consumen las plantillas y
 * los módulos de datos. Incluye resolución de opciones de ACF, renderizado de
 * imágenes, formateo de moneda, renderizado de iconos SVG, mapeo de iconos por
 * producto, datos de respaldo de la home y utilidades de manipulación de
 * artículos.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the value of a global ACF option with a safe fallback.
 *
 * Devuelve el valor de una opción global de ACF con respaldo seguro.
 *
 * @param string $key     Nombre del campo.
 * @param mixed  $default Valor de respaldo.
 * @return mixed
 */
function quantia_option( string $key, $default = null ) {
    static $cache = array();

    if ( array_key_exists( $key, $cache ) ) {
        return $cache[ $key ];
    }

    $value = get_option( 'quantia_option_' . $key, null );

    if ( null !== $value && '' !== $value ) {
        $cache[ $key ] = $value;
        return $value;
    }

    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $key, 'option' );
        if ( null !== $value && '' !== $value && false !== $value ) {
            $cache[ $key ] = $value;
            return $value;
        }
    }

    $cache[ $key ] = $default;
    return $default;
}

/**
 * Returns the rows of an ACF repeater as an array.
 *
 * Devuelve las filas de un repeater de ACF como array.
 *
 * @param string $key Nombre del campo repeater.
 * @return array
 */
function quantia_option_rows( string $key ): array {
    static $cache = array();

    if ( array_key_exists( $key, $cache ) ) {
        return $cache[ $key ];
    }

    $value = get_option( 'quantia_option_' . $key, null );

    if ( is_array( $value ) && ! empty( $value ) ) {
        $cache[ $key ] = $value;
        return $value;
    }

    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $key, 'option' );
        if ( is_array( $value ) && ! empty( $value ) ) {
            $cache[ $key ] = $value;
            return $value;
        }
    }

    $cache[ $key ] = array();
    return array();
}

/**
 * Renders an image handling the three ACF formats.
 *
 * Acepta un array con clave ID, un entero con el ID del attachment o una URL
 * como string.
 *
 * @param mixed  $image Referencia de la imagen.
 * @param string $size  Tamaño de imagen registrado.
 * @param array  $attr  Atributos adicionales del tag img.
 * @return string HTML del tag img o cadena vacía.
 */
function quantia_image( $image, string $size = 'large', array $attr = array() ): string {
    if ( empty( $image ) ) {
        return '';
    }

    if ( is_array( $image ) && isset( $image['ID'] ) ) {
        return wp_get_attachment_image( $image['ID'], $size, false, $attr );
    }

    if ( is_numeric( $image ) ) {
        return wp_get_attachment_image( (int) $image, $size, false, $attr );
    }

    if ( is_string( $image ) ) {
        $alt = $attr['alt'] ?? '';
        unset( $attr['alt'] );
        $attrs = '';
        foreach ( $attr as $k => $v ) {
            $attrs .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
        }
        return sprintf(
            '<img src="%s" alt="%s"%s>',
            esc_url( $image ),
            esc_attr( $alt ),
            $attrs
        );
    }

    return '';
}

/**
 * Returns the URL of an image without rendering the tag.
 *
 * Devuelve la URL de una imagen sin renderizar el tag.
 *
 * @param mixed  $image Referencia de la imagen.
 * @param string $size  Tamaño de imagen registrado.
 * @return string URL o cadena vacía.
 */
function quantia_image_url( $image, string $size = 'large' ): string {
    if ( is_array( $image ) && isset( $image['ID'] ) ) {
        $url = wp_get_attachment_image_src( $image['ID'], $size );
        return $url ? $url[0] : '';
    }
    if ( is_numeric( $image ) ) {
        $url = wp_get_attachment_image_src( (int) $image, $size );
        return $url ? $url[0] : '';
    }
    return is_string( $image ) ? $image : '';
}

/**
 * Formats a numeric amount as currency with the US$ prefix.
 *
 * Formatea un monto numérico como moneda con prefijo US$.
 *
 * @param float $value Monto.
 * @return string Monto formateado.
 */
function quantia_money( float $value ): string {
    $abs = number_format( abs( $value ), 2, '.', ',' );
    return ( $value < 0 ? '-' : '' ) . 'US$' . $abs;
}

/**
 * Joins an array of CSS classes filtering the empty ones.
 *
 * Une un array de clases CSS filtrando las vacías.
 *
 * @param array $classes Lista de clases.
 * @return string Cadena escapada lista para el atributo class.
 */
function quantia_classes( array $classes ): string {
    return esc_attr( implode( ' ', array_filter( $classes ) ) );
}

/**
 * Renders an SVG icon from the internal registry.
 *
 * Renderiza un icono SVG del registro interno.
 *
 * @param string $name  Nombre del icono.
 * @param string $class Clases CSS del icono.
 * @return string HTML del SVG o cadena vacía.
 */
function quantia_icon( string $name, string $class = 'h-4 w-4' ): string {
    $icons = quantia_icons_registry();
    if ( ! isset( $icons[ $name ] ) ) {
        return '';
    }
    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="%s" aria-hidden="true">%s</svg>',
        esc_attr( $class ),
        $icons[ $name ]
    );
}

/**
 * Central registry of theme SVG icons.
 *
 * Registro central de iconos SVG del tema.
 *
 * @return array Mapa de nombre a contenido SVG interno.
 */
function quantia_icons_registry(): array {
    return array(
        'menu'              => '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
        'x'                 => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        'chevron-down'      => '<path d="m6 9 6 6 6-6"/>',
        'arrow-up'          => '<path d="m5 12 7-7 7 7"/><path d="M12 19V5"/>',
        'arrow-right'       => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
        'arrow-up-right'    => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
        'arrow-down'        => '<path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>',
        'arrow-left'        => '<path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>',
        'external-link'     => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
        'mail'              => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
        'phone'             => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'message-circle'    => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
        'trending-up'       => '<polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>',
        'piggy-bank'        => '<path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2V5z"/><path d="M2 9v1c0 1.1.9 2 2 2h1"/>',
        'sunset'            => '<path d="M12 10V2"/><path d="m4.93 10.93 1.41 1.41"/><path d="M2 18h2"/><path d="M20 18h2"/><path d="m19.07 10.93-1.41 1.41"/><path d="M22 22H2"/><path d="m16 6-4 4-4-4"/><path d="M16 18a4 4 0 0 0-8 0"/>',
        'credit-card'       => '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
        'shield-check'      => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
        'play'              => '<polygon points="6 3 20 12 6 21 6 3"/>',
        'check'             => '<polyline points="20 6 9 17 4 12"/>',
        'landmark'          => '<line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/>',
        'hand'              => '<path d="M18 11V6a2 2 0 0 0-2-2 2 2 0 0 0-2 2"/><path d="M14 10V4a2 2 0 0 0-2-2 2 2 0 0 0-2 2v2"/><path d="M10 10.5V6a2 2 0 0 0-2-2 2 2 0 0 0-2 2v8"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/>',
        'scan-face'         => '<path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" x2="9.01" y1="9" y2="9"/><line x1="15" x2="15.01" y1="9" y2="9"/>',
        'home'              => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'circle-alert'      => '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>',
        'download'          => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>',
        'shield'            => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>',
        'scale'             => '<path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/>',
        'rocket'            => '<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>',
        'layers'            => '<path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>',
        'target'            => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
        'infinity'          => '<path d="M12 12c-2-2.67-4-4-6-4a4 4 0 1 0 0 8c2 0 4-1.33 6-4Zm0 0c2 2.67 4 4 6 4a4 4 0 0 0 0-8c-2 0-4 1.33-6 4Z"/>',
        'smartphone'        => '<rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/>',
        'file-signature'    => '<path d="M20 19.5v.5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-9"/><path d="M20 13V6a2 2 0 0 0-2-2H9"/><path d="M14 2v6h6"/><path d="M2 14.5 5.5 11l3.75 3.75L12 12l5 5"/><path d="M16 12v5h5"/>',
        'calendar-clock'    => '<path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h5"/><path d="M17.5 17.5 16 16.3V14"/><circle cx="16" cy="16" r="6"/>',
        'line-chart'        => '<path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m19 9-5 5-4-4-3 3"/>',
        'globe'             => '<circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>',
        'banknote'          => '<rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
        'chevron-down-full' => '<path d="m6 9 6 6 6-6"/>',
        'search'            => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'send'              => '<path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>',
        'check-circle'      => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'lock'              => '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'file-text'              => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
        'message-square-warning' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><path d="M12 7v2"/><path d="M12 13h.01"/>',
        'receipt'                => '<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 17.5v-11"/>',
        'arrow-down-right'       => '<path d="m7 7 10 10"/><path d="M17 7v10H7"/>',
    );
}

/**
 * Returns the canonical icon name for a product.
 *
 * Devuelve el nombre de icono canónico para un producto.
 *
 * @param string $slug Slug del producto.
 * @return string Nombre del icono.
 */
function quantia_product_icon_name( string $slug ): string {
    $map = array(
        'inversiones' => 'trending-up',
        'alcancias'   => 'piggy-bank',
        'jubilación'      => 'sunset',
        'tarjeta'     => 'credit-card',
    );
    return $map[ $slug ] ?? 'trending-up';
}

/**
 * Attempts to resolve the product icon from its URL.
 *
 * Intenta adivinar el icono de un producto a partir de su URL.
 *
 * @param string $url URL del producto.
 * @return string Nombre del icono.
 */
function quantia_product_icon_from_url( string $url ): string {
    foreach ( array( 'inversiones', 'alcancias', 'jubilación', 'tarjeta' ) as $slug ) {
        if ( false !== strpos( $url, $slug ) ) {
            return quantia_product_icon_name( $slug );
        }
    }
    return 'trending-up';
}

/**
 * Returns the set of suggested icons for a product options section.
 *
 * Devuelve el set de iconos sugeridos para las opciones de un producto.
 *
 * @param string $slug Slug del producto.
 * @return array
 */
function quantia_product_option_icons( string $slug ): array {
    $map = array(
        'inversiones' => array( 'shield', 'scale', 'rocket', 'layers' ),
        'alcancias'   => array( 'target', 'infinity' ),
        'jubilación'      => array( 'smartphone', 'file-signature', 'calendar-clock', 'line-chart' ),
        'tarjeta'     => array( 'globe', 'banknote', 'smartphone', 'shield-check' ),
    );
    return $map[ $slug ] ?? array();
}

/**
 * Returns the predefined brand images.
 *
 * Devuelve URLs de imágenes de marca predefinidas.
 *
 * @param string $key Clave de la imagen.
 * @return string URL o cadena vacía.
 */
function quantia_brand_image( string $key ): string {
    $map = array(
        'team'    => home_url( '/wp-content/uploads/2026/10/7e9ca160-e5f6-4a1a-af25-902c93113a67.jpg' ),
        'learn'   => home_url( '/wp-content/uploads/2026/10/7732df65-c2c8-43c1-9d99-d9456e4a2947.jpg' ),
        'support' => home_url( '/wp-content/uploads/2026/10/5962edf7-e9c9-467e-ab1c-87fade080c22.jpg' ),
    );
    return $map[ $key ] ?? '';
}

/**
 * Returns the fallback FAQ category structure.
 *
 * The frontend no longer consumes this structure because it reads from the faq
 * custom post type. It remains available as a reference for the seeder and for
 * backup processes.
 *
 * Estructura de respaldo de las categorías de FAQ.
 *
 * @return array
 */
function quantia_fallback_faq_categories(): array {
    return array(
        array(
            'id'        => 'cuenta',
            'label'     => __( 'Checking account', 'quantia-hub' ),
            'questions' => array(
                array(
                    'question' => __( 'How can I receive funds into my Quantia Hub account', 'quantia-hub' ),
                    'answer'   => __( 'You can receive funds through ACH transfers, transfers between Quantia Hub users and Kuara interbank transfers.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'How do I open a Quantia Hub account', 'quantia-hub' ),
                    'answer'   => __( 'Download the Quantia Hub app, follow the onboarding steps and complete the registration with your personal data.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'What is the maximum amount allowed in my account', 'quantia-hub' ),
                    'answer'   => __( 'The initial limit is 2,500 dollars and can be increased after reviewing the financial support behind your income.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'How can I increase my account limit', 'quantia-hub' ),
                    'answer'   => __( 'You can request it through the WhatsApp link inside the app.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'Is there a minimum amount to keep my account active', 'quantia-hub' ),
                    'answer'   => __( 'No minimum amount is required. An inactive account with zero balance for more than six months may be closed by the bank.', 'quantia-hub' ),
                ),
            ),
        ),
        array(
            'id'        => 'kuara',
            'label'     => __( 'Kuara and transfers', 'quantia-hub' ),
            'questions' => array(
                array(
                    'question' => __( 'What is Kuara', 'quantia-hub' ),
                    'answer'   => __( 'Kuara enables instant interbank transfers every day at no additional cost.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'How do I transfer money to other Quantia Hub users', 'quantia-hub' ),
                    'answer'   => __( 'From the app, choose P2P, enter the amount and the destination user. Funds are credited immediately.', 'quantia-hub' ),
                ),
            ),
        ),
        array(
            'id'        => 'seguridad',
            'label'     => __( 'Devices and security', 'quantia-hub' ),
            'questions' => array(
                array(
                    'question' => __( 'What should I do if I change my device or phone number', 'quantia-hub' ),
                    'answer'   => __( 'Notify the bank so the previous device or number is unlinked from your account.', 'quantia-hub' ),
                ),
            ),
        ),
        array(
            'id'        => 'intereses',
            'label'     => __( 'Interest and data', 'quantia-hub' ),
            'questions' => array(
                array(
                    'question' => __( 'How does the interest rate work at Quantia Hub', 'quantia-hub' ),
                    'answer'   => __( 'Your account earns annualized interest on the monthly average balance up to 50,000 dollars. Interest is paid on the first of each month.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'Where can I update my personal data', 'quantia-hub' ),
                    'answer'   => __( 'From the app in the Personal data menu, or by contacting the customer service team.', 'quantia-hub' ),
                ),
            ),
        ),
        array(
            'id'        => 'cartas',
            'label'     => __( 'Letters and transactions', 'quantia-hub' ),
            'questions' => array(
                array(
                    'question' => __( 'How do I request a bank reference letter', 'quantia-hub' ),
                    'answer'   => __( 'Request it by email or WhatsApp indicating the data you need and the language.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'How can I see my transactions', 'quantia-hub' ),
                    'answer'   => __( 'From the app you can consult the full history in real time.', 'quantia-hub' ),
                ),
            ),
        ),
        array(
            'id'        => 'tarjeta',
            'label'     => __( 'Visa debit card', 'quantia-hub' ),
            'questions' => array(
                array(
                    'question' => __( 'How do I request the Visa debit card', 'quantia-hub' ),
                    'answer'   => __( 'You can request it when opening your account by email or WhatsApp.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'How do I block my card', 'quantia-hub' ),
                    'answer'   => __( 'You can block it from the app or by contacting customer service.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'In which countries can I use the card', 'quantia-hub' ),
                    'answer'   => __( 'In any country except those blocked for being considered high risk.', 'quantia-hub' ),
                ),
            ),
        ),
        array(
            'id'        => 'inversiones',
            'label'     => __( 'Investments', 'quantia-hub' ),
            'questions' => array(
                array(
                    'question' => __( 'What is the minimum amount to open a portfolio', 'quantia-hub' ),
                    'answer'   => __( 'The minimum amount is 100 dollars.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'When and how can I withdraw the invested money', 'quantia-hub' ),
                    'answer'   => __( 'You must keep the investment for three months. After that you can withdraw once the sell order has been processed.', 'quantia-hub' ),
                ),
                array(
                    'question' => __( 'What happens with the dividends of my investments', 'quantia-hub' ),
                    'answer'   => __( 'Dividends are automatically reinvested into your portfolio.', 'quantia-hub' ),
                ),
            ),
        ),
    );
}

/**
 * Returns the steps of the claims and clarifications process.
 *
 * Devuelve los pasos del proceso de aclaraciones y reclamaciones.
 *
 * @return array
 */
function quantia_fallback_claim_steps(): array {
    return array(
        array(
            'id'          => 'escribe',
            'title'       => __( 'Send us your claim', 'quantia-hub' ),
            'description' => __( 'Send it by email to reclamos@mmgbank.com or through a written note.', 'quantia-hub' ),
        ),
        array(
            'id'          => 'constancia',
            'title'       => __( 'Receive your acknowledgment', 'quantia-hub' ),
            'description' => __( 'We confirm in writing that your claim has been received, using the same channel you used.', 'quantia-hub' ),
        ),
        array(
            'id'          => 'respuesta',
            'title'       => __( 'We respond within thirty days', 'quantia-hub' ),
            'description' => __( 'The Claims Officer investigates your case and provides a personalized written response.', 'quantia-hub' ),
        ),
    );
}

/**
 * Retrieves an article by its slug.
 *
 * Obtiene un artículo por su slug.
 *
 * @param string $slug Slug del artículo.
 * @return array|null Datos normalizados del artículo o null.
 */
function quantia_get_article_by_slug( string $slug ): ?array {
    $post = get_page_by_path( $slug, OBJECT, 'articulo' );
    if ( ! $post ) {
        return null;
    }
    return quantia_get_article_from_post( $post );
}

/**
 * Normalizes a WP_Post of the articulo type to the format consumed by templates.
 *
 * Normaliza un objeto WP_Post de tipo articulo al formato que consumen las
 * plantillas.
 *
 * @param WP_Post $post Entrada del CPT articulo.
 * @return array
 */
function quantia_get_article_from_post( WP_Post $post ): array {
    $categories = get_the_terms( $post->ID, 'categoria_articulo' );
    $cat_names  = ( $categories && ! is_wp_error( $categories ) ) ? wp_list_pluck( $categories, 'name' ) : array();

    $visual = get_field( 'article_visual', $post->ID );
    if ( ! $visual ) {
        $visual = get_post_meta( $post->ID, 'article_visual', true ) ?: 'riesgo';
    }

    return array(
        'ID'         => $post->ID,
        'slug'       => $post->post_name,
        'title'      => get_the_title( $post ),
        'date'       => get_the_date( 'j \d\e F \d\e Y', $post ),
        'iso'        => get_the_date( 'c', $post ),
        'categories' => $cat_names,
        'excerpt'    => get_the_excerpt( $post ),
        'visual'     => $visual,
        'body'       => quantia_normalize_article_body( $post->ID ),
    );
}

/**
 * Converts the ACF flexible content into a flat block array consumed by the
 * article template.
 *
 * Convierte el flexible content de ACF en un array de bloques planos que pueda
 * consumir la plantilla del artículo.
 *
 * @param int $post_id ID del artículo.
 * @return array
 */
function quantia_normalize_article_body( int $post_id ): array {
    $rows = get_field( 'article_body', $post_id );
    if ( ! is_array( $rows ) ) {
        return array();
    }

    $blocks = array();

    foreach ( $rows as $row ) {
        $layout = $row['acf_fc_layout'] ?? '';
        switch ( $layout ) {
            case 'p':
                $blocks[] = array( 'type' => 'p', 'text' => $row['text'] ?? '' );
                break;
            case 'h2':
                $blocks[] = array( 'type' => 'h2', 'text' => $row['text'] ?? '' );
                break;
            case 'h3':
                $blocks[] = array( 'type' => 'h3', 'text' => $row['text'] ?? '' );
                break;
            case 'list':
                $items = array();
                foreach ( $row['items'] ?? array() as $item ) {
                    $items[] = $item['text'] ?? '';
                }
                $blocks[] = array( 'type' => 'list', 'items' => $items );
                break;
            case 'defs':
                $items = array();
                foreach ( $row['items'] ?? array() as $item ) {
                    $items[] = array(
                        'term' => $item['term'] ?? '',
                        'text' => $item['text'] ?? '',
                    );
                }
                $blocks[] = array( 'type' => 'defs', 'items' => $items );
                break;
            case 'table':
                $head = array();
                foreach ( $row['head'] ?? array() as $h ) {
                    $head[] = $h['text'] ?? '';
                }
                $rows_data = array();
                foreach ( $row['rows'] ?? array() as $r ) {
                    $cells = array();
                    foreach ( $r['cells'] ?? array() as $c ) {
                        $cells[] = $c['text'] ?? '';
                    }
                    $rows_data[] = $cells;
                }
                $blocks[] = array( 'type' => 'table', 'head' => $head, 'rows' => $rows_data );
                break;
            case 'callout':
                $blocks[] = array( 'type' => 'callout', 'text' => $row['text'] ?? '' );
                break;
            case 'faq':
                $items = array();
                foreach ( $row['items'] ?? array() as $item ) {
                    $items[] = array(
                        'q' => $item['q'] ?? '',
                        'a' => $item['a'] ?? '',
                    );
                }
                $blocks[] = array( 'type' => 'faq', 'items' => $items );
                break;
        }
    }

    return $blocks;
}

/**
 * Returns the published articles ordered by date descending.
 *
 * Devuelve los artículos publicados ordenados por fecha descendente.
 *
 * @param int $limit Número máximo de artículos.
 * @return array
 */
function quantia_get_all_articles( int $limit = 20 ): array {
    $query = new WP_Query( array(
        'post_type'      => 'articulo',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    $articles = array();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $articles[] = quantia_get_article_from_post( get_post() );
        }
        wp_reset_postdata();
    }
    return $articles;
}

/**
 * Checks whether at least one published article exists.
 *
 * Comprueba si existe al menos un artículo publicado.
 *
 * @return bool
 */
function quantia_has_articles(): bool {
    $query = new WP_Query( array(
        'post_type'      => 'articulo',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ) );
    return $query->have_posts();
}

/**
 * Returns the permalink of an article by slug or a safe fallback.
 *
 * Devuelve el permalink de un artículo por su slug o un fallback seguro.
 *
 * @param string $slug Slug del artículo.
 * @return string URL
 */
function get_permalink_by_slug_safe( string $slug ): string {
    $post = get_page_by_path( $slug, OBJECT, 'articulo' );
    return $post ? get_permalink( $post ) : home_url( '/aprende/' );
}