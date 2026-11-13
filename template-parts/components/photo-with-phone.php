<?php
/**
 * Foto con teléfono flotante.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$src         = $args['src']         ?? '';
$alt         = $args['alt']         ?? '';
$screen      = $args['screen']      ?? 'inicio';
$phone_label = $args['phone_label'] ?? '';
?>
<div class="relative">
    <div class="relative overflow-hidden aspect-[4/5] w-full rounded-3xl sm:w-[76%]">
        <?php if ( $src ) : ?>
            <img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" class="absolute inset-x-0 -top-[15%] h-[130%] w-full object-cover" loading="lazy" decoding="async">
        <?php endif; ?>
    </div>
    <div data-parallax data-parallax-speed="-0.22" class="pointer-events-none absolute -bottom-6 right-0 hidden sm:block">
        <div class="origin-bottom-right scale-[0.9] md:scale-[0.72] lg:scale-[0.88]">
            <?php get_template_part( 'template-parts/components/phone-frame', null, array(
                'label'  => $phone_label,
                'screen' => $screen,
            ) ); ?>
        </div>
    </div>
</div>