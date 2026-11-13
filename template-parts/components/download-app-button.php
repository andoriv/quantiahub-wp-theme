<?php
/**
 * Botón de descarga de la app.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$class    = $args['class']    ?? '';
$variant  = $args['variant']  ?? 'green';
$label    = $args['label']    ?? __( 'Comienza ya', 'quantia-hub' );
$product  = $args['product']  ?? 'general';
$location = $args['location'] ?? 'hero';

$variants = array(
    'dark'    => 'bg-ink text-white hover:bg-qh-700',
    'green'   => 'bg-qh text-white hover:bg-qh-600',
    'light'   => 'bg-white text-ink hover:bg-qh-100',
    'outline' => 'border border-ink/15 bg-white text-ink hover:border-ink/40',
);
$variant_class = $variants[ $variant ] ?? $variants['green'];
?>
<a href="<?php echo esc_url( 'https://apps.apple.com/us/app/quantia-hub/id1499843860' ); ?>"
   target="_blank"
   rel="noopener noreferrer"
   data-quantia-app-download
   data-product="<?php echo esc_attr( $product ); ?>"
   data-location="<?php echo esc_attr( $location ); ?>"
   class="inline-flex items-center justify-center rounded-full px-6 py-3 text-sm font-semibold transition-colors <?php echo esc_attr( $variant_class . ' ' . $class ); ?>">
    <?php echo esc_html( $label ); ?>
</a>