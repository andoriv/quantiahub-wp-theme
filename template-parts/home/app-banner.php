<?php
defined( 'ABSPATH' ) || exit;

$marquee_text = __( 'Open it today · No minimum balance · 4% annual · ', 'quantia-hub' );
$marquee_x3   = trim( str_repeat( $marquee_text, 3 ) );
?>

<div class="bg-white" data-reveal-frame>
    <section class="relative w-full overflow-hidden bg-qh-700 pt-12 text-white sm:pt-16 lg:pt-24">
        <div data-marquee data-marquee-distance="30" aria-hidden="true"
            class="pointer-events-none select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(2.5rem,1rem+7vw,8rem)] text-white/10">
                <?php echo esc_html( $marquee_x3 ); ?>
            </p>
        </div>

        <div class="mx-auto grid max-w-6xl grid-cols-1 items-end gap-6 px-5 sm:gap-10 sm:px-6 lg:grid-cols-[1.2fr_1fr]">
            <div class="pb-4 sm:pb-10 lg:pb-24">
                <h2 class="font-display text-5xl font-extrabold leading-[0.95] tracking-[-0.045em] sm:text-6xl lg:text-7xl">
                    <?php esc_html_e( 'Open your account', 'quantia-hub' ); ?><br>
                    <?php esc_html_e( 'from the app?', 'quantia-hub' ); ?><br>
                    <span class="text-qh-200"><?php esc_html_e( 'Now!', 'quantia-hub' ); ?></span>
                </h2>
                <p class="mt-6 max-w-sm text-base leading-relaxed text-white/80">
                    <?php esc_html_e( 'Take your bank with you and create your Quantia account the moment you need it.', 'quantia-hub' ); ?>
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <?php get_template_part( 'template-parts/components/download-app-button', null, array(
                        'label'    => __( 'Download now', 'quantia-hub' ),
                        'variant'  => 'light',
                        'location' => 'hero',
                    ) ); ?>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="text-xs font-semibold uppercase tracking-widest text-white opacity-60">
                            <?php esc_html_e( 'Available on', 'quantia-hub' ); ?>
                        </span>
                        <a href="https://apps.apple.com/us/app/quantia-hub/id1499843860" target="_blank" rel="noopener noreferrer"
                           class="rounded-full border border-white/25 px-4 py-1.5 text-xs font-semibold text-white transition-colors hover:border-qh hover:text-qh-300">
                            App Store
                        </a>
                        <a href="https://play.google.com/store/search?q=QUANTIA%20HUB&c=apps&hl=es_419" target="_blank" rel="noopener noreferrer"
                           class="rounded-full border border-white/25 px-4 py-1.5 text-xs font-semibold text-white transition-colors hover:border-qh hover:text-qh-300">
                            Google Play
                        </a>
                    </div>
                </div>
            </div>

            <div data-parallax data-parallax-speed="0.35" data-parallax-rotate="-5" class="flex h-[260px] justify-center overflow-hidden min-[400px]:h-[290px] sm:h-[360px] lg:h-[440px]">
                <?php get_template_part( 'template-parts/components/phone-frame', null, array(
                    'label'  => __( 'Visa Debit card screen in the Quantia Hub app', 'quantia-hub' ),
                    'screen' => 'tarjeta',
                    'class'  => 'ring-1 ring-white/20',
                ) ); ?>
            </div>
        </div>
    </section>
</div>