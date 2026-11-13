<?php
defined( 'ABSPATH' ) || exit;

$tabs         = quantia_fallback_app_tabs();
$marquee_text = __( 'Donde estés, vamos contigo · ', 'quantia-hub' );
$marquee_x3   = trim( str_repeat( $marquee_text, 3 ) );
?>

<div class="bg-mist" data-reveal-frame>
    <section id="demo" aria-labelledby="demo-title"
        class="relative w-full scroll-mt-20 overflow-hidden bg-mist py-20 md:py-32">

        <div data-marquee data-marquee-distance="30" aria-hidden="true"
            class="pointer-events-none select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(4rem,10vw,9rem)] text-ink/[0.05]">
                <?php echo esc_html( $marquee_x3 ); ?>
            </p>
        </div>

        <div class="mx-auto -mt-8 grid max-w-6xl grid-cols-1 items-center gap-14 px-5 sm:px-6 lg:grid-cols-[1fr_auto] lg:gap-24">
            <div>
                <h2 id="demo-title"
                    class="font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-5xl">
                    <?php esc_html_e( 'Donde estés,', 'quantia-hub' ); ?><br><?php esc_html_e( 'vamos contigo.', 'quantia-hub' ); ?>
                </h2>

                <p class="mt-5 flex items-center gap-2 text-sm text-ink/60">
                    <span class="text-qh"><?php echo quantia_icon( 'hand', 'h-4 w-4' ); // phpcs:ignore ?></span>
                    <?php esc_html_e( 'Pruébala aquí mismo, toca el teléfono o elige una sección.', 'quantia-hub' ); ?>
                </p>

                <ul class="mt-10" data-demo-tabs>
                    <?php foreach ( $tabs as $i => $tab ) : ?>
                        <li>
                            <button type="button"
                                data-demo-tab="<?php echo esc_attr( $tab['key'] ); ?>"
                                aria-pressed="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                                class="group flex w-full items-start gap-5 py-3 text-left">
                                <span data-tab-bar
                                    class="mt-4 h-0.5 shrink-0 bg-ink/20 transition-all duration-200 <?php echo $i === 0 ? 'w-10 bg-qh' : 'w-4'; ?>"
                                    aria-hidden="true"></span>
                                <span>
                                    <span data-tab-title
                                        class="block font-display text-2xl font-bold tracking-[-0.02em] transition-colors sm:text-3xl <?php echo $i === 0 ? 'text-ink' : 'text-ink/30 group-hover:text-ink/60'; ?>">
                                        <?php echo esc_html( $tab['title'] ); ?>
                                    </span>
                                    <span data-tab-desc
                                        class="block overflow-hidden pt-1.5 text-sm text-ink/60 transition-all <?php echo $i === 0 ? '' : 'hidden'; ?>">
                                        <?php echo esc_html( $tab['description'] ); ?>
                                    </span>
                                </span>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <p class="mt-8 text-xs text-ink/45"><?php esc_html_e( 'Demo con datos de ejemplo.', 'quantia-hub' ); ?></p>
            </div>

            <div data-parallax data-parallax-speed="0.2" class="flex justify-center">
                <div data-demo-phone>
                    <?php get_template_part( 'template-parts/components/phone-frame', null, array(
                        'label'  => __( 'Demo de la aplicación Quantia Hub', 'quantia-hub' ),
                        'screen' => 'inicio',
                    ) ); ?>
                </div>
            </div>
        </div>
    </section>
</div>