<?php
defined( 'ABSPATH' ) || exit;

$p = $args['product'];
?>

<section class="w-full bg-ink py-14 text-white md:py-24">
    <div class="mx-auto max-w-6xl px-5 sm:px-6">

        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="font-display text-3xl font-extrabold tracking-[-0.03em] sm:text-4xl">
                    <?php esc_html_e( 'Cómo empezar', 'quantia-hub' ); ?>
                </h2>
                <p class="mt-3 max-w-sm text-sm leading-relaxed text-white/65">
                    <?php esc_html_e( 'Tres pasos y listo. Todo desde tu teléfono.', 'quantia-hub' ); ?>
                </p>
            </div>

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
        </div>

        <ol class="relative mt-14 grid grid-cols-1 gap-10 md:grid-cols-3 md:gap-8">
            <span aria-hidden="true" class="absolute left-6 top-6 hidden h-px w-[calc(100%-3rem)] bg-white/15 md:block"></span>

            <?php foreach ( $p['steps'] as $i => $step ) : ?>
                <li data-slide data-slide-from="<?php echo $i % 2 === 0 ? '40' : '-40'; ?>"
                    class="relative flex gap-5 md:block">
                    <span class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-qh font-display text-lg font-extrabold text-white ring-8 ring-ink">
                        <?php echo (int) ( $i + 1 ); ?>
                    </span>
                    <span class="block md:mt-6">
                        <span class="block font-display text-lg font-semibold">
                            <?php echo esc_html( $step['title'] ); ?>
                        </span>
                        <span class="mt-2 block max-w-xs text-sm leading-relaxed text-white/65">
                            <?php echo esc_html( $step['description'] ); ?>
                        </span>
                    </span>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>