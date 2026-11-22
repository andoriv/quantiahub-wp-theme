<?php
/**
 * Marco de teléfono reutilizable.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$label  = $args['label']  ?? '';
$screen = $args['screen'] ?? 'inicio';
$class  = $args['class']  ?? '';

$frame_key = quantia_media_frame_key_for_screen( $screen );
$frame_url = '';
$frame_has = false;

if ( $frame_key && quantia_managed_image_has_override( $frame_key ) ) {
    $frame_url = quantia_managed_image_src( $frame_key, 'large' );
    $frame_has = $frame_url !== '';
}
?>
<div role="<?php echo $label ? 'img' : ''; ?>"
    aria-label="<?php echo esc_attr( $label ); ?>"
    class="w-[280px] shrink-0 rounded-[2.6rem] bg-ink p-2.5 shadow-[0_30px_60px_-24px_rgba(11,31,23,0.5)] [zoom:0.6] min-[400px]:[zoom:0.66] sm:[zoom:0.74] md:[zoom:0.8] lg:[zoom:0.88] <?php echo esc_attr( $class ); ?>">
    <div class="overflow-hidden rounded-[2.1rem] bg-mist">
        <div class="h-[560px]">
            <?php if ( $frame_has ) : ?>
                <img src="<?php echo esc_url( $frame_url ); ?>"
                     alt=""
                     class="block h-full w-full object-cover"
                     loading="lazy"
                     decoding="async">
            <?php else : ?>
                <?php get_template_part( 'template-parts/components/app-screen', null, array( 'screen' => $screen ) ); ?>
            <?php endif; ?>
        </div>
    </div>
</div>