<?php
/**
 * Componente de logo.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$class   = $args['class']   ?? '';
$variant = $args['variant'] ?? 'light';
$height  = $args['height']  ?? 'h-7';

$full_brand = quantia_option( 'site_full_brand', 'Quantia Hub' );

$logo_url = '';

if ( 'dark' === $variant ) {
    $logo_url = quantia_option( 'site_logo_dark', '' );
}

if ( ! $logo_url ) {
    $logo_url = quantia_option( 'site_logo', '' );
}

if ( is_array( $logo_url ) && ! empty( $logo_url['url'] ) ) {
    $logo_url = $logo_url['url'];
} elseif ( is_numeric( $logo_url ) ) {
    $resolved = wp_get_attachment_image_url( (int) $logo_url, 'full' );
    $logo_url = $resolved ? $resolved : '';
}

if ( empty( $logo_url ) ) {
    $logo_url = home_url( '/wp-content/uploads/2026/10/logo-de-quantia.png' );
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