<?php
/**
 * Plantilla de la página Tarifas.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

get_header();

$schedules  = quantia_fallback_fee_schedules();
$legal_text = quantia_fallback_fee_legal_text();
$sheet      = quantia_fallback_fee_sheet();

$default_schedule = $schedules[0];
$default_counts   = quantia_fee_schedule_counts( $default_schedule );

$hero_figures = array(
    array( 'value' => '4%',                  'label' => __( 'Interés anual sobre tu saldo promedio, hasta B/. 50,000', 'quantia-hub' ), 'speed' => '0.1',  'offset' => 'ml-0' ),
    array( 'value' => __( 'Sin costo', 'quantia-hub' ), 'label' => __( 'Comprar activos en tu portafolio', 'quantia-hub' ),                  'speed' => '0.35', 'offset' => 'ml-10 sm:ml-20' ),
    array( 'value' => 'Kuara',               'label' => __( 'Transferencias interbancarias al instante, sin comisión', 'quantia-hub' ),     'speed' => '0.6',  'offset' => 'ml-4 sm:ml-8' ),
);

$marquee_raw = __( '4% anual · Compra de activos sin costo · Kuara sin comisión · ', 'quantia-hub' );
$marquee_x3  = trim( str_repeat( $marquee_raw, 3 ) );
?>

<main class="w-full bg-white">

    <section aria-labelledby="tarifas-hero-title"
        class="qh-page-hero relative flex w-full flex-col justify-end overflow-hidden bg-ink pt-32 text-white">

        <div class="relative mx-auto grid w-full max-w-6xl flex-1 grid-cols-1 items-center gap-12 px-5 pb-10 sm:px-6 lg:grid-cols-[1.2fr_1fr]">

            <div data-parallax data-parallax-speed="-0.1">
                <p class="text-sm font-semibold text-qh-300"><?php esc_html_e( 'Tarifas', 'quantia-hub' ); ?></p>

                <h1 id="tarifas-hero-title"
                    class="mt-5 font-display font-extrabold leading-[0.94] tracking-[-0.045em]"
                    style="font-size:clamp(3rem,7vw,6rem);">
                    <?php esc_html_e( 'Tarifas claras.', 'quantia-hub' ); ?><br>
                    <span class="text-qh-300"><?php esc_html_e( 'Sin sorpresas.', 'quantia-hub' ); ?></span>
                </h1>

                <p class="mt-7 max-w-lg text-lg leading-relaxed text-white/70">
                    <?php esc_html_e( 'Todos los cargos y comisiones de Quantia Hub, para personas y para cuentas de planilla.', 'quantia-hub' ); ?>
                </p>

                <div class="mt-9 flex flex-wrap items-center gap-3">
                    <a href="<?php echo esc_url( $sheet['href'] ); ?>"
                        target="_blank" rel="noopener noreferrer"
                        data-quantia-fee-sheet
                        class="inline-flex items-center gap-2 rounded-full bg-qh px-7 py-3.5 text-base font-semibold text-white transition-colors hover:bg-qh-600">
                        <?php echo quantia_icon( 'download', 'h-4 w-4' ); ?>
                        <?php echo esc_html( $sheet['label'] ); ?>
                    </a>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-md lg:ml-auto lg:mr-0">
                <div class="space-y-8 py-4" aria-label="<?php esc_attr_e( 'Lo más importante', 'quantia-hub' ); ?>">
                    <?php foreach ( $hero_figures as $fig ) : ?>
                        <div data-parallax data-parallax-speed="<?php echo esc_attr( $fig['speed'] ); ?>"
                            class="<?php echo esc_attr( $fig['offset'] ); ?>">
                            <p class="font-display font-extrabold leading-none tracking-[-0.05em] text-qh-300"
                                style="font-size:clamp(3rem,6vw,5.25rem);">
                                <?php echo esc_html( $fig['value'] ); ?>
                            </p>
                            <p class="mt-2 max-w-[260px] text-sm font-semibold text-white/60">
                                <?php echo esc_html( $fig['label'] ); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div data-marquee data-marquee-distance="35" aria-hidden="true"
            class="pointer-events-none select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-white/[0.06] -mb-[0.18em]"
                style="font-size:clamp(4rem,11vw,10rem);">
                <?php echo esc_html( $marquee_x3 ); ?>
            </p>
        </div>
    </section>

    <section id="tarifario" class="w-full scroll-mt-20 overflow-x-clip py-20 md:py-28">
        <div class="mx-auto max-w-6xl px-5 sm:px-6">

            <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-5xl">
                        <?php esc_html_e( 'Elige tu tipo', 'quantia-hub' ); ?><br>
                        <span class="text-qh"><?php esc_html_e( 'de cuenta.', 'quantia-hub' ); ?></span>
                    </h2>
                    <p class="mt-5 max-w-md text-base leading-relaxed text-ink/65" data-fee-description>
                        <?php echo esc_html( $default_schedule['description'] ); ?>
                    </p>
                </div>

                <div role="tablist" aria-label="<?php esc_attr_e( 'Tipo de cliente', 'quantia-hub' ); ?>"
                    class="relative flex w-full rounded-full bg-mist p-1.5 sm:w-auto">
                    <?php foreach ( $schedules as $s_index => $schedule ) : ?>
                        <button type="button"
                            role="tab"
                            aria-selected="<?php echo 0 === $s_index ? 'true' : 'false'; ?>"
                            data-fee-tab="<?php echo esc_attr( $schedule['id'] ); ?>"
                            class="relative flex-1 whitespace-nowrap rounded-full px-5 py-3 text-sm font-semibold transition-colors sm:flex-none sm:px-7 <?php echo 0 === $s_index ? 'bg-ink text-white' : 'text-ink/60 hover:text-ink'; ?>">
                            <?php echo esc_html( $schedule['short_label'] ); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <p class="mt-12 font-display text-2xl font-bold tracking-[-0.02em] text-ink/40 sm:text-3xl"
                data-fee-count aria-live="polite">
                <span class="text-ink"><?php echo (int) $default_counts['rows']; ?> <?php esc_html_e( 'conceptos', 'quantia-hub' ); ?></span>
                <?php esc_html_e( 'en el tarifario de', 'quantia-hub' ); ?> <?php echo esc_html( $default_schedule['short_label'] ); ?>.
                <span class="text-qh"><?php echo (int) $default_counts['free']; ?> <?php esc_html_e( 'sin costo', 'quantia-hub' ); ?>.</span>
            </p>

            <div class="mt-16">
                <?php foreach ( $schedules as $s_index => $schedule ) :
                    $counts = quantia_fee_schedule_counts( $schedule );
                ?>
                    <div data-fee-schedule-panel="<?php echo esc_attr( $schedule['id'] ); ?>"
                        data-fee-schedule-short-label="<?php echo esc_attr( $schedule['short_label'] ); ?>"
                        data-fee-schedule-rows="<?php echo (int) $counts['rows']; ?>"
                        data-fee-schedule-free="<?php echo (int) $counts['free']; ?>"
                        data-fee-schedule-description="<?php echo esc_attr( $schedule['description'] ); ?>"
                        class="<?php echo 0 === $s_index ? '' : 'hidden'; ?>">

                        <div class="grid grid-cols-1 gap-12 lg:grid-cols-[220px_1fr] lg:gap-20">

                            <nav aria-label="<?php esc_attr_e( 'Secciones de tarifas', 'quantia-hub' ); ?>" class="lg:sticky lg:top-28 lg:self-start">
                                <ul class="-mx-5 flex gap-2 overflow-x-auto px-5 pb-2 lg:mx-0 lg:block lg:space-y-1 lg:overflow-visible lg:border-l lg:border-ink/10 lg:px-0 lg:pb-0">
                                    <?php foreach ( $schedule['groups'] as $g_index => $group ) :
                                        $gid = $schedule['id'] . '-' . $group['id'];
                                    ?>
                                        <li>
                                            <a href="#<?php echo esc_attr( $gid ); ?>"
                                                data-fee-nav="<?php echo esc_attr( $gid ); ?>"
                                                class="block whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition-colors lg:-ml-px lg:rounded-none lg:border-l-2 lg:bg-transparent lg:py-2.5 lg:pl-5 lg:font-display lg:text-lg <?php echo 0 === $g_index ? 'bg-ink text-white lg:border-qh lg:text-ink' : 'bg-mist text-ink/55 hover:text-ink lg:border-transparent lg:text-ink/35'; ?>">
                                                <?php echo esc_html( $group['title'] ); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </nav>

                            <div class="space-y-24">
                                <?php foreach ( $schedule['groups'] as $group ) :
                                    $gid = $schedule['id'] . '-' . $group['id'];
                                ?>
                                    <section id="<?php echo esc_attr( $gid ); ?>" class="scroll-mt-28">

                                        <div class="flex items-end justify-between gap-6 border-b-2 border-ink pb-5">
                                            <h2 class="font-display text-4xl font-extrabold tracking-[-0.035em] text-ink sm:text-5xl">
                                                <?php echo esc_html( $group['title'] ); ?>
                                            </h2>
                                            <span class="shrink-0 text-sm text-ink/50">
                                                <?php echo (int) count( $group['rows'] ); ?> <?php esc_html_e( 'conceptos', 'quantia-hub' ); ?>
                                            </span>
                                        </div>

                                        <ul class="divide-y divide-ink/10">
                                            <?php foreach ( $group['rows'] as $r_index => $row ) :
                                                $free = quantia_is_free( $row['cost'] );
                                            ?>
                                                <li>
                                                    <div data-slide data-slide-from="<?php echo $r_index % 2 === 0 ? '60' : '-60'; ?>"
                                                        class="flex flex-col gap-2 py-6 sm:flex-row sm:items-center sm:justify-between sm:gap-10">
                                                        <span>
                                                            <span class="block font-display text-lg font-semibold text-ink sm:text-xl">
                                                                <?php echo esc_html( $row['concept'] ); ?>
                                                            </span>
                                                            <?php if ( ! empty( $row['note'] ) ) : ?>
                                                                <span class="mt-1 block max-w-md text-sm text-ink/50">
                                                                    <?php echo esc_html( $row['note'] ); ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </span>
                                                        <span class="shrink-0 font-display text-2xl font-extrabold tracking-[-0.03em] sm:text-right sm:text-3xl <?php echo $free ? 'text-qh' : 'text-ink'; ?>">
                                                            <?php echo esc_html( $row['cost'] ); ?>
                                                        </span>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </section>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section aria-labelledby="legal-tarifas" class="w-full bg-mist py-16 md:py-20">
        <div class="mx-auto grid max-w-6xl grid-cols-1 gap-8 px-5 sm:px-6 lg:grid-cols-[220px_1fr] lg:gap-20">
            <h2 id="legal-tarifas" class="font-display text-lg font-bold text-ink">
                <?php esc_html_e( 'Información legal', 'quantia-hub' ); ?>
            </h2>
            <div class="max-w-3xl space-y-4 text-sm leading-relaxed text-ink/60">
                <?php foreach ( $legal_text as $paragraph ) : ?>
                    <p><?php echo esc_html( $paragraph ); ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <div class="bg-mist" data-reveal-frame>
        <section class="relative overflow-hidden bg-qh-700 py-20 text-white md:py-28">
            <div data-marquee data-marquee-distance="30" aria-hidden="true"
                class="pointer-events-none select-none overflow-hidden">
                <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(3.5rem,9vw,8rem)] text-white/10">
                    <?php esc_html_e( 'Tarifario completo · Sin letra pequeña · Tarifario completo · Sin letra pequeña · Tarifario completo · Sin letra pequeña ·', 'quantia-hub' ); ?>
                </p>
            </div>

            <div class="mx-auto -mt-4 max-w-6xl px-5 sm:px-6">
                <div data-parallax data-parallax-speed="0.2">
                    <h2 class="font-display text-5xl font-extrabold leading-[0.96] tracking-[-0.045em] sm:text-6xl">
                        <?php esc_html_e( '¿Quieres el', 'quantia-hub' ); ?><br>
                        <?php esc_html_e( 'tarifario en PDF?', 'quantia-hub' ); ?>
                    </h2>
                    <p class="mt-6 max-w-md text-base leading-relaxed text-white/80">
                        <?php esc_html_e( 'Descarga el folleto oficial de tarifas con todos los conceptos, montos e impuestos aplicables.', 'quantia-hub' ); ?>
                    </p>
                    <div class="mt-9 flex flex-wrap items-center gap-3">
                        <a href="<?php echo esc_url( $sheet['href'] ); ?>"
                            target="_blank" rel="noopener noreferrer"
                            data-quantia-fee-sheet
                            class="inline-flex items-center gap-2 rounded-full bg-white px-7 py-3.5 text-base font-semibold text-ink transition-colors hover:bg-qh-100">
                            <?php echo quantia_icon( 'download', 'h-4 w-4' ); ?>
                            <?php echo esc_html( $sheet['label'] ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php get_footer(); ?>