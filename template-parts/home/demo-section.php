<?php
defined( 'ABSPATH' ) || exit;

$demo_url = 'https://quantia-hub-simulator.vercel.app/';

$try_list = array(
    array(
        'title' => __( 'Revisa tu saldo', 'quantia-hub' ),
        'text'  => __( 'Mira cómo se ve tu cuenta y tus movimientos.', 'quantia-hub' ),
    ),
    array(
        'title' => __( 'Crea una alcancía', 'quantia-hub' ),
        'text'  => __( 'Separa dinero para una meta en segundos.', 'quantia-hub' ),
    ),
    array(
        'title' => __( 'Explora un portafolio', 'quantia-hub' ),
        'text'  => __( 'Conoce los perfiles de inversión y de retiro.', 'quantia-hub' ),
    ),
);

$marquee_text = __( 'Pruébala sin descargar nada · Pruébala sin descargar nada · Pruébala sin descargar nada · ', 'quantia-hub' );
?>

<div class="bg-mist" data-reveal-frame>
    <section id="demo" aria-labelledby="demo-title"
        class="relative w-full scroll-mt-20 overflow-hidden bg-mist py-14 sm:py-20 lg:py-32">

        <div data-marquee data-marquee-distance="30" aria-hidden="true"
            class="pointer-events-none select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(4rem,10vw,9rem)] text-ink/[0.05]">
                <?php echo esc_html( $marquee_text ); ?>
            </p>
        </div>

        <div class="mx-auto -mt-8 grid max-w-6xl grid-cols-1 items-center gap-14 px-5 sm:px-6 lg:grid-cols-[1fr_auto] lg:gap-24">
            <div data-parallax data-parallax-speed="0.15">
                <p class="text-sm font-semibold text-qh-700"><?php esc_html_e( 'Demo interactiva', 'quantia-hub' ); ?></p>
                <h2 id="demo-title"
                    class="mt-4 font-display text-[clamp(2rem,1.15rem+3.4vw,4.5rem)] font-extrabold leading-[0.98] tracking-[-0.045em] text-ink">
                    <?php esc_html_e( 'Pruébala antes', 'quantia-hub' ); ?><br>
                    <span class="text-qh"><?php esc_html_e( 'de descargarla.', 'quantia-hub' ); ?></span>
                </h2>
                <p class="mt-5 max-w-md text-base leading-relaxed text-ink/65 sm:mt-6 sm:text-lg">
                    <?php esc_html_e( 'Esta es la app de Quantia Hub funcionando aquí mismo. Tócala, navega y descubre cómo se siente manejar tu dinero.', 'quantia-hub' ); ?>
                </p>

                <ol class="mt-10 border-t border-ink/10">
                    <?php foreach ( $try_list as $i => $item ) : ?>
                        <li class="flex gap-5 border-b border-ink/10 py-5">
                            <span class="font-display text-2xl font-extrabold leading-none text-qh"><?php echo (int) ( $i + 1 ); ?></span>
                            <div>
                                <p class="font-display text-lg font-bold tracking-[-0.02em] text-ink"><?php echo esc_html( $item['title'] ); ?></p>
                                <p class="mt-1 text-sm text-ink/60"><?php echo esc_html( $item['text'] ); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>

                <a href="<?php echo esc_url( $demo_url ); ?>"
                    target="_blank" rel="noopener noreferrer"
                    data-quantia-demo-open
                    class="mt-8 inline-flex items-center gap-2 whitespace-nowrap text-sm font-semibold text-qh-700 transition-colors hover:text-ink">
                    <?php esc_html_e( 'Abrir la demo en pantalla completa', 'quantia-hub' ); ?>
                    <?php echo quantia_icon( 'external-link', 'h-4 w-4' ); ?>
                </a>
                <p class="mt-3 text-xs text-ink/45"><?php esc_html_e( 'Demo con datos de ejemplo. No se mueve dinero real.', 'quantia-hub' ); ?></p>
            </div>

            <div data-parallax data-parallax-speed="0.3" data-parallax-rotate="3" class="flex justify-center">
                <div class="relative w-[min(300px,calc(100vw-3rem))] rounded-[2.4rem] bg-ink p-2 sm:w-[330px] sm:rounded-[2.75rem] sm:p-2.5 lg:w-[360px] shadow-[0_50px_100px_-40px_rgba(11,31,23,0.55)]">
                    <span class="absolute left-1/2 top-5 z-10 h-6 w-28 -translate-x-1/2 rounded-full bg-ink" aria-hidden="true"></span>
                    <div class="relative h-[min(700px,72svh)] min-h-[480px] overflow-hidden rounded-[2rem] bg-white sm:min-h-[540px] sm:rounded-[2.2rem]">
                        <iframe
                            src="<?php echo esc_url( $demo_url ); ?>"
                            title="<?php esc_attr_e( 'Demo interactiva de la app Quantia Hub', 'quantia-hub' ); ?>"
                            loading="lazy"
                            class="h-full w-full border-0"
                            allow="clipboard-write"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>