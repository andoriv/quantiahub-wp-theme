<?php
/**
 * Componente de logo.
 *
 * Prioridad de resolución:
 *   1) Override explícito en WP Admin → Quantia Hub → Imágenes (key: brand_logo).
 *   2) Opciones legacy (site_logo_dark / site_logo) si fueron configuradas.
 *   3) Default del registry de imágenes.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$class   = $args['class']   ?? '';
$variant = $args['variant'] ?? 'light';
$height  = $args['height']  ?? 'h-7';

$full_brand = quantia_option( 'site_full_brand', 'Quantia Hub' );

$logo_url = '';

$managed_entry = quantia_managed_images_option()['brand_logo'] ?? array();
$has_managed   = ! empty( $managed_entry['id'] ) || ! empty( $managed_entry['url'] );

if ( $has_managed ) {
    $logo_url = quantia_managed_image_src( 'brand_logo' );
}

if ( ! $logo_url ) {
    $legacy_logo = '';

    if ( 'dark' === $variant ) {
        $legacy_logo = quantia_option( 'site_logo_dark', '' );
    }
    if ( ! $legacy_logo ) {
        $legacy_logo = quantia_option( 'site_logo', '' );
    }

    if ( is_array( $legacy_logo ) && ! empty( $legacy_logo['url'] ) ) {
        $logo_url = (string) $legacy_logo['url'];
    } elseif ( is_numeric( $legacy_logo ) ) {
        $resolved = wp_get_attachment_image_url( (int) $legacy_logo, 'full' );
        $logo_url = $resolved ?: '';
    } elseif ( is_string( $legacy_logo ) && $legacy_logo !== '' ) {
        $logo_url = $legacy_logo;
    }
}

if ( ! $logo_url ) {
    $logo_url = quantia_managed_image_src( 'brand_logo' );
}
?>
<span class="inline-flex items-center <?php echo esc_attr( $class ); ?>" aria-label="<?php echo esc_attr( $full_brand ); ?>">
    <img src="<?php echo esc_url( $logo_url ); ?>"
        alt="<?php echo esc_attr( $full_brand ); ?>"
        class="<?php echo esc_attr( $height ); ?> w-auto"
        loading="eager"
        decoding="async"
        fetchpriority="high" />
</span>