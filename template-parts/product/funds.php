<?php
defined( 'ABSPATH' ) || exit;

$p    = $args['product'];
$plan = $args['plan'];

if ( 'inversion' === $plan ) {
    $funds            = quantia_get_funds( 'inversion' );
    $label            = __( 'Fondos de inversión, MMG Bank', 'quantia-hub' );
    $title_a          = __( 'Oferta de fondos', 'quantia-hub' );
    $title_b          = __( 'de tu portafolio.', 'quantia-hub' );
    $profiles_title_a = __( '¿Qué tipo de', 'quantia-hub' );
    $profiles_title_b = __( 'inversionista eres?', 'quantia-hub' );
    $intro            = __( 'Elige un perfil de riesgo y mira cómo se distribuye tu portafolio entre los fondos.', 'quantia-hub' );
} else {
    $funds            = quantia_get_funds( 'jubilación' );
    $label            = __( 'Fondos de los planes de jubilación, Quantia AFP', 'quantia-hub' );
    $title_a          = __( 'Oferta de fondos', 'quantia-hub' );
    $title_b          = __( 'de tu jubilación.', 'quantia-hub' );
    $profiles_title_a = __( 'Tu perfil', 'quantia-hub' );
    $profiles_title_b = __( 'para el jubilación.', 'quantia-hub' );
    $intro            = __( 'Cada perfil de jubilación invierte de forma individual en un fondo de Quantia AFP.', 'quantia-hub' );
}

$fund_names  = wp_list_pluck( $funds, 'name' );
$marquee_raw = implode( ' · ', $fund_names ) . ' · ';
$marquee_x3  = trim( str_repeat( $marquee_raw, 3 ) );
?>

<section id="fondos" class="w-full scroll-mt-20 overflow-hidden bg-white py-14 sm:py-20 lg:py-28">

    <div data-marquee data-marquee-distance="30" aria-hidden="true"
         class="pointer-events-none select-none overflow-hidden">
        <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(3.5rem,8vw,7rem)] text-ink/[0.05]">
            <?php echo esc_html( $marquee_x3 ); ?>
        </p>
    </div>

    <div class="mx-auto -mt-6 max-w-6xl px-5 sm:px-6">
        <h2 class="font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-5xl">
            <?php echo esc_html( $title_a ); ?><br>
            <span class="text-qh"><?php echo esc_html( $title_b ); ?></span>
        </h2>

        <p class="mt-10 text-sm font-semibold text-qh-700"><?php echo esc_html( $label ); ?></p>

        <ul class="mt-4 border-t-2 border-ink">
            <?php foreach ( $funds as $i => $fund ) : ?>
                <li class="border-b border-ink/10">
                    <div data-slide data-slide-from="<?php echo $i % 2 === 0 ? '100' : '-100'; ?>"
                         class="flex flex-col gap-1 py-6 sm:flex-row sm:items-center sm:justify-between sm:gap-8">
                        <span class="flex items-center gap-4">
                            <span class="h-3.5 w-3.5 shrink-0 rounded-full <?php echo esc_attr( $fund['swatch'] ); ?>"></span>
                            <span class="font-display text-[clamp(1.375rem,1rem+1.8vw,2.75rem)] font-extrabold leading-none tracking-[-0.04em] text-ink">
                                <?php echo esc_html( $fund['name'] ); ?>
                            </span>
                        </span>
                        <span class="pl-8 text-sm font-semibold text-ink/50 sm:pl-0">
                            <?php echo esc_html( 'Individual' === $fund['asset'] ? __( 'Plan de jubilación', 'quantia-hub' ) : $fund['asset'] ); ?>
                        </span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="mt-24">
        <?php get_template_part( 'template-parts/product/profile-explorer', null, array(
            'plan'    => $plan,
            'title_a' => $profiles_title_a,
            'title_b' => $profiles_title_b,
            'intro'   => $intro,
        ) ); ?>
    </div>

    <p class="mx-auto mt-16 max-w-6xl px-5 text-xs leading-relaxed text-ink/45 sm:px-6">
        <?php esc_html_e( 'Distribuciones referenciales por perfil. Rendimientos pasados no garantizan resultados futuros; consulta el prospecto de cada fondo antes de invertir.', 'quantia-hub' ); ?>
    </p>
</section>