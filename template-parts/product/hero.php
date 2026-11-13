<?php
defined( 'ABSPATH' ) || exit;

$p = $args['product'];

$screen_map = array(
    'inversiones' => 'inversiones',
    'alcancias'   => 'alcancias',
    'retiro'      => 'retiro',
    'tarjeta'     => 'tarjeta',
);
$screen = $screen_map[ $p['slug'] ] ?? 'inicio';
?>

<section class="qh-aurora w-full overflow-hidden">
    <div class="mx-auto grid max-w-6xl grid-cols-1 items-center gap-10 px-5 pb-14 pt-24 sm:px-6 md:grid-cols-2 md:pb-20 md:pt-32">

        <div data-parallax data-parallax-speed="-0.06">
            <nav aria-label="<?php esc_attr_e( 'Ruta', 'quantia-hub' ); ?>" class="text-xs text-ink/50">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="transition-colors hover:text-qh"><?php esc_html_e( 'Inicio', 'quantia-hub' ); ?></a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-ink/70"><?php echo esc_html( $p['name'] ); ?></span>
            </nav>

            <p class="mt-6 text-xs font-semibold uppercase tracking-widest text-qh-700">
                <?php echo esc_html( $p['eyebrow'] ); ?>
            </p>

            <h1 class="mt-3 font-display text-4xl font-extrabold leading-[1.03] tracking-[-0.03em] text-ink sm:text-5xl">
                <?php echo esc_html( $p['title'] ); ?>
                <span class="text-qh"><?php echo esc_html( $p['title_highlight'] ); ?></span>
            </h1>

            <p class="mt-6 max-w-md text-sm leading-relaxed text-ink/70">
                <?php echo esc_html( $p['intro'] ); ?>
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <?php if ( 'whatsapp' === $p['cta'] ) : ?>
                    <?php get_template_part( 'template-parts/components/whatsapp-button', null, array(
                        'label'    => $p['cta_label'],
                        'variant'  => 'green',
                        'product'  => $p['key'],
                        'location' => 'product_page',
                        'message'  => __( 'Hola, quiero solicitar mi Tarjeta Visa Débito de Quantia.', 'quantia-hub' ),
                    ) ); ?>
                <?php else : ?>
                    <?php get_template_part( 'template-parts/components/download-app-button', null, array(
                        'label'    => $p['cta_label'],
                        'variant'  => 'green',
                        'product'  => $p['key'],
                        'location' => 'product_page',
                    ) ); ?>
                <?php endif; ?>

                <a href="<?php echo esc_url( home_url( '/tarifas/' ) ); ?>"
                   class="rounded-full border border-ink/15 bg-white px-6 py-3 text-sm font-semibold text-ink transition-colors hover:border-ink/40">
                    <?php esc_html_e( 'Ver tarifas', 'quantia-hub' ); ?>
                </a>
            </div>
        </div>

        <?php get_template_part( 'template-parts/components/photo-with-phone', null, array(
            'src'         => $p['hero_image'],
            'alt'         => sprintf( __( 'Cliente de Quantia Hub usando %s', 'quantia-hub' ), $p['name'] ),
            'screen'      => $screen,
            'phone_label' => sprintf( __( 'Pantalla de %s en la aplicación Quantia Hub', 'quantia-hub' ), $p['name'] ),
        ) ); ?>
    </div>
</section>