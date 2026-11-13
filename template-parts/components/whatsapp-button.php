<?php
/**
 * Botón de WhatsApp.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$class    = $args['class']    ?? '';
$variant  = $args['variant']  ?? 'green';
$label    = $args['label']    ?? __( 'Escribir por WhatsApp', 'quantia-hub' );
$product  = $args['product']  ?? 'general';
$location = $args['location'] ?? 'support';
$message  = $args['message']  ?? __( 'Hola, quiero información sobre Quantia Hub.', 'quantia-hub' );

$variants = array(
    'green'   => 'bg-qh text-white hover:bg-qh-600',
    'dark'    => 'bg-ink text-white hover:bg-qh-700',
    'outline' => 'border border-ink/15 bg-white text-ink hover:border-ink/40',
);
$variant_class = $variants[ $variant ] ?? $variants['green'];

$wa_base = quantia_option( 'contact_whatsapp_url', 'https://wa.me/50764777460' );
$href    = $wa_base . '?text=' . rawurlencode( $message );
?>
<a href="<?php echo esc_url( $href ); ?>"
   target="_blank"
   rel="noopener noreferrer"
   data-quantia-whatsapp
   data-product="<?php echo esc_attr( $product ); ?>"
   data-location="<?php echo esc_attr( $location ); ?>"
   class="inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 text-sm font-semibold transition-colors <?php echo esc_attr( $variant_class . ' ' . $class ); ?>">
    <?php echo quantia_icon( 'message-circle', 'h-4 w-4' ); // phpcs:ignore ?>
    <?php echo esc_html( $label ); ?>
</a>