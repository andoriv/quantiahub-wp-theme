<?php
defined( 'ABSPATH' ) || exit;

$p = $args['product'];

$screen_map = array(
    'inversiones' => 'inversiones',
    'alcancias'   => 'alcancias',
    'jubilación'      => 'jubilación',
    'tarjeta'     => 'tarjeta',
);
$screen = $screen_map[ $p['slug'] ] ?? 'inicio';
?>

<section class="w-full overflow-hidden bg-white py-14 md:py-24">
    <div class="mx-auto grid max-w-6xl grid-cols-1 items-center gap-12 px-5 sm:px-6 lg:grid-cols-[auto_1fr] lg:gap-20">

        <div data-slide data-slide-from="-60"
             class="order-last flex justify-center rounded-3xl bg-mist px-6 pt-8 sm:px-8 sm:pt-10 lg:order-first lg:px-12">
            <div class="h-[280px] overflow-hidden min-[400px]:h-[310px] sm:h-[370px] lg:h-[440px]">
                <div data-parallax data-parallax-speed="0.3">
                    <?php get_template_part( 'template-parts/components/phone-frame', null, array(
                        'label'  => sprintf( __( 'Pantalla de %s en la app Quantia Hub', 'quantia-hub' ), $p['name'] ),
                        'screen' => $screen,
                    ) ); ?>
                </div>
            </div>
        </div>

        <div>
            <h2 class="font-display text-3xl font-extrabold tracking-[-0.03em] text-ink sm:text-4xl">
                <?php esc_html_e( 'Cómo funciona', 'quantia-hub' ); ?>
            </h2>
            <p class="mt-4 max-w-md text-sm leading-relaxed text-ink/65">
                <?php
                printf(
                    /* translators: %s: nombre del producto */
                    esc_html__( 'Así se ve %s dentro de Quantia Hub.', 'quantia-hub' ),
                    esc_html( $p['name'] )
                );
                ?>
            </p>

            <dl class="mt-10 grid grid-cols-1 gap-x-10 gap-y-8 sm:grid-cols-2">
                <?php foreach ( $p['features'] as $feature ) : ?>
                    <div class="border-l-2 border-qh pl-5">
                        <dt class="font-display text-base font-semibold text-ink">
                            <?php echo esc_html( $feature['title'] ); ?>
                        </dt>
                        <dd class="mt-2 text-sm leading-relaxed text-ink/65">
                            <?php echo esc_html( $feature['description'] ); ?>
                        </dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>
    </div>
</section>