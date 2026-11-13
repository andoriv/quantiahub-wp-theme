<?php
defined( 'ABSPATH' ) || exit;

$p          = $args['product'];
$disclaimer = __( 'De acuerdo con la Ley 49 de 17 de septiembre de 2009, a todas las comisiones generadas por servicios bancarios, financieros y/o de inversión, les será aplicado el Impuesto sobre la Transferencia de Bienes Corporales Muebles y la Prestación de Servicios (ITBMS).', 'quantia-hub' );
?>

<section class="w-full bg-white py-14 md:py-20">
    <div class="mx-auto max-w-6xl px-5 sm:px-6">
        <div class="rounded-2xl border border-ink/10 p-6 sm:p-8">

            <div class="flex flex-wrap items-baseline justify-between gap-4">
                <h2 class="font-display text-2xl font-extrabold tracking-[-0.02em] text-ink sm:text-3xl">
                    <?php
                    printf(
                        /* translators: %s: nombre del producto */
                        esc_html__( 'Tarifas de %s', 'quantia-hub' ),
                        esc_html( $p['name'] )
                    );
                    ?>
                </h2>
                <a href="<?php echo esc_url( home_url( '/tarifas/#' . $p['slug'] ) ); ?>"
                   data-quantia-fee-sheet
                   data-product="<?php echo esc_attr( $p['key'] ); ?>"
                   data-location="fees"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-qh transition-colors hover:text-qh-700">
                    <?php esc_html_e( 'Ver tarifas completas', 'quantia-hub' ); ?>
                    <?php echo quantia_icon( 'arrow-right', 'h-4 w-4' ); ?>
                </a>
            </div>

            <ul class="mt-6 divide-y divide-ink/10 border-y border-ink/10">
                <?php foreach ( $p['fees'] as $fee ) : ?>
                    <li class="flex flex-col gap-1 py-4 sm:flex-row sm:items-baseline sm:justify-between sm:gap-8">
                        <span class="text-sm font-semibold text-ink">
                            <?php echo esc_html( $fee['concept'] ); ?>
                            <?php if ( ! empty( $fee['note'] ) ) : ?>
                                <span class="mt-0.5 block text-xs font-normal text-ink/50"><?php echo esc_html( $fee['note'] ); ?></span>
                            <?php endif; ?>
                        </span>
                        <span class="font-display text-base font-extrabold text-qh sm:text-right">
                            <?php echo esc_html( $fee['cost'] ); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php if ( ! empty( $p['external_link'] ) ) : ?>
                <a href="<?php echo esc_url( $p['external_link']['href'] ); ?>"
                   target="_blank" rel="noopener noreferrer"
                   class="mt-6 inline-flex items-center gap-2 rounded-full border border-ink/15 px-5 py-2.5 text-sm font-semibold text-ink transition-colors hover:border-qh hover:text-qh">
                    <?php echo esc_html( $p['external_link']['label'] ); ?>
                    <?php echo quantia_icon( 'external-link', 'h-4 w-4' ); ?>
                </a>
            <?php endif; ?>

            <p class="mt-6 text-xs leading-relaxed text-ink/50"><?php echo esc_html( $disclaimer ); ?></p>
        </div>
    </div>
</section>