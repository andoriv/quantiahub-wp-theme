<?php
defined( 'ABSPATH' ) || exit;

$steps        = quantia_fallback_onboarding_steps();
$marquee_text = __( 'Download · Verify · Activate · ', 'quantia-hub' );
$marquee_x3   = trim( str_repeat( $marquee_text, 3 ) );
?>

<div class="bg-ink" data-reveal-frame>
    <section id="empieza" class="relative w-full overflow-hidden bg-ink py-14 text-white sm:py-20 lg:py-32">
        <div data-marquee data-marquee-distance="30" data-marquee-reverse="true" aria-hidden="true"
            class="pointer-events-none select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(4rem,11vw,10rem)] text-white/[0.05]">
                <?php echo esc_html( $marquee_x3 ); ?>
            </p>
        </div>

        <div class="mx-auto -mt-6 max-w-5xl px-5 sm:px-6">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <h2 class="font-display text-4xl font-extrabold tracking-[-0.035em] sm:text-5xl">
                    <?php esc_html_e( 'Your account, ready', 'quantia-hub' ); ?><br>
                    <span class="text-qh-300"><?php esc_html_e( 'in minutes.', 'quantia-hub' ); ?></span>
                </h2>
                <p class="max-w-xs text-sm leading-relaxed text-white/60">
                    <?php esc_html_e( 'No branches, no queues. Everything happens inside Quantia Hub.', 'quantia-hub' ); ?>
                </p>
            </div>

            <ol data-steps-list class="relative mt-8 md:mt-12">
                <span aria-hidden="true" class="absolute bottom-8 left-[19.5px] top-12 w-px bg-white/10 md:bottom-12 md:left-[27.5px] md:top-12"></span>
                <span data-steps-fill aria-hidden="true"
                    class="absolute bottom-8 left-[19.5px] top-12 w-px origin-top bg-qh-300 md:bottom-12 md:left-[27.5px] md:top-12"
                    style="transform:scaleY(0)"></span>

                <?php foreach ( $steps as $i => $step ) :
                    get_template_part( 'template-parts/home/step-row', null, array(
                        'id'          => $step['id'],
                        'title'       => $step['title'],
                        'description' => $step['description'],
                        'index'       => $i,
                    ) );
                endforeach; ?>
            </ol>

            <div class="mt-6 flex flex-wrap items-center gap-4 pl-14 md:mt-8 md:pl-[96px]">
                <?php get_template_part( 'template-parts/components/download-app-button', null, array(
                    'variant'  => 'green',
                    'location' => 'hero',
                ) ); ?>
                <span class="text-xs text-white/50">
                    <?php esc_html_e( 'iOS and Android, operated in Panama', 'quantia-hub' ); ?>
                </span>
            </div>
        </div>
    </section>
</div>