<?php
/**
 * Sección de confianza institucional para el Home.
 * 
 * Contexto de Arquitectura:
 * - Ubicación: Se renderiza en front-page.php justo antes de 'demo-section'.
 * - Origen de datos: quantia_option('trust_bank') con fallback a quantia_fallback_trust_bank().
 * - Dependencias visuales: Requiere quantia_icon() para SVGs.
 * - Dependencias JS: Utiliza initParallax, initMarquee, initScrollRevealText e initRevealFrames de motion.js.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$data = wp_parse_args(
    (array) quantia_option( 'trust_bank', array() ),
    quantia_fallback_trust_bank()
);

$image_url      = ! empty( $data['image'] ) ? $data['image'] : '';
$marquee_text   = trim( $data['regulators'] ) . ' · ';
$marquee_repeat = str_repeat( $marquee_text, 3 );
?>

<!-- El contenedor bg-ink junto con data-reveal-frame activa el efecto de escala y border-radius en scroll -->
<div class="bg-ink" data-reveal-frame>
    <!-- El hijo directo es el que recibe la animación -->
    <div class="origin-top overflow-hidden">
        
        <section class="w-full overflow-hidden bg-mist py-14 sm:py-20 lg:py-32" aria-labelledby="trust-bank-title">
            <div class="mx-auto max-w-6xl px-5 sm:px-6">

                <?php if ( ! empty( $data['heading'] ) ) : ?>
                    <!-- data-scroll-reveal-text divide el texto, data-highlight pinta las palabras clave -->
                    <h2 id="trust-bank-title" 
                        class="max-w-5xl font-display text-[clamp(1.75rem,1rem+3.3vw,4.5rem)] font-extrabold leading-[1.04] tracking-[-0.04em] text-ink" 
                        data-scroll-reveal-text
                        data-highlight="banco,real:">
                        <?php echo esc_html( $data['heading'] ); ?>
                    </h2>
                <?php endif; ?>

                <div class="mt-20 grid grid-cols-1 items-center gap-14 lg:grid-cols-[1.15fr_1fr] lg:gap-16">
                    
                    <div class="relative">
                        <?php if ( $image_url ) : ?>
                            <div class="relative overflow-hidden aspect-[4/3] w-full rounded-3xl">
                                <img src="<?php echo esc_url( $image_url ); ?>" 
                                     alt="<?php echo esc_attr( $data['image_alt'] ); ?>" 
                                     loading="lazy" 
                                     class="absolute inset-x-0 -top-[15%] h-[130%] w-full object-cover" 
                                     data-parallax 
                                     data-parallax-speed="-0.15">
                            </div>
                        <?php endif; ?>

                        <div class="absolute -bottom-8 left-6">
                            <div data-parallax data-parallax-speed="0.08">
                                <div class="flex items-center gap-3 rounded-2xl bg-ink px-5 py-4 text-white shadow-[0_20px_50px_-20px_rgba(11,31,23,0.6)]">
                                    <?php echo quantia_icon( 'landmark', 'h-5 w-5 text-qh-300' ); ?>
                                    <span>
                                        <span class="block text-[11px] text-white/60"><?php echo esc_html( $data['badge_kicker'] ); ?></span>
                                        <span class="block text-sm font-semibold"><?php echo esc_html( $data['badge_name'] ); ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <dl class="space-y-8">
                            <?php foreach ( (array) $data['items'] as $item ) : 
                                if ( empty( $item['title'] ) ) continue; 
                            ?>
                                <!-- data-slide hace que los items entren deslizándose lateralmente -->
                                <div data-slide data-slide-from="<?php echo ( $loop_index++ % 2 === 0 ) ? '70' : '-70'; ?>">
                                    <div class="border-l-2 border-qh pl-5">
                                        <dt class="font-display text-2xl font-bold tracking-[-0.02em] text-ink"><?php echo esc_html( $item['title'] ); ?></dt>
                                        <dd class="mt-2 text-sm leading-relaxed text-ink/60"><?php echo esc_html( $item['text'] ?? '' ); ?></dd>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </dl>

                        <?php if ( ! empty( $data['cta_label'] ) && ! empty( $data['cta_url'] ) ) : ?>
                            <a href="<?php echo esc_url( $data['cta_url'] ); ?>" class="mt-10 inline-flex items-center gap-2 text-sm font-semibold text-qh-700 transition-colors hover:text-ink">
                                <?php echo esc_html( $data['cta_label'] ); ?>
                                <?php echo quantia_icon( 'arrow-right', 'h-4 w-4' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ( ! empty( $data['regulators'] ) ) : ?>
                    <div class="mt-24 border-y border-ink/10 py-6">
                        <p class="sr-only"><?php echo esc_html( $data['regulators'] ); ?></p>
                        
                        <div class="pointer-events-none select-none overflow-hidden" aria-hidden="true" data-marquee data-marquee-distance="35">
                            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(1.375rem,0.9rem+2.2vw,3rem)] text-ink/70">
                                <?php echo esc_html( $marquee_repeat ); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </div>
</div>