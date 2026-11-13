<?php
defined( 'ABSPATH' ) || exit;

$rate_options    = quantia_get_calculator_options();
$defaults        = quantia_get_calculator_defaults();
$default_rate    = $defaults['rate'];
$default_initial = $defaults['initial'];
$default_monthly = $defaults['monthly'];
$default_years   = $defaults['years'];

$marquee_text = __( 'Interés sobre interés · Tiempo · Constancia · ', 'quantia-hub' );
$marquee_x3   = trim( str_repeat( $marquee_text, 3 ) );
?>

<div class="bg-white" data-reveal-frame>
    <section id="interes-compuesto"
        aria-labelledby="compound-title"
        data-compound-calculator
        class="relative w-full scroll-mt-20 overflow-hidden bg-ink py-14 text-white sm:py-20 lg:py-28">

        <div data-marquee data-marquee-distance="30" aria-hidden="true"
            class="pointer-events-none select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(2.75rem,1rem+7vw,8rem)] text-white/[0.05]">
                <?php echo esc_html( $marquee_x3 ); ?>
            </p>
        </div>

        <div class="mx-auto -mt-4 max-w-6xl px-5 sm:-mt-6 sm:px-6">
            <div data-parallax data-parallax-speed="0.2">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <h2 id="compound-title"
                        class="font-display text-[clamp(2rem,1.15rem+3.4vw,4.25rem)] font-extrabold leading-[1.02] tracking-[-0.035em]">
                        <?php esc_html_e( 'Deja que el tiempo', 'quantia-hub' ); ?><br>
                        <span class="text-qh-300"><?php esc_html_e( 'haga el trabajo.', 'quantia-hub' ); ?></span>
                    </h2>
                    <p class="max-w-sm text-base leading-relaxed text-white/60">
                        <?php esc_html_e( 'Con el interés compuesto, lo que ganas también empieza a ganar. Mueve los números y mira la diferencia.', 'quantia-hub' ); ?>
                    </p>
                </div>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-12 sm:mt-14 lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)] lg:gap-16">

                <form data-compound-form class="flex flex-col gap-8">

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="cc-initial" class="text-sm font-medium text-white/60"><?php esc_html_e( 'Empiezas con', 'quantia-hub' ); ?></label>
                            <div class="mt-2 flex items-baseline gap-2 border-b border-white/20 pb-2 transition-colors duration-200 ease-out focus-within:border-qh-300">
                                <span class="font-display text-lg font-bold text-white/40 sm:text-xl">US$</span>
                                <input id="cc-initial" data-compound-initial type="number" inputmode="numeric"
                                    min="0" max="1000000" step="10" value="<?php echo (int) $default_initial; ?>"
                                    class="w-full min-w-0 bg-transparent font-display text-2xl font-extrabold tracking-[-0.02em] text-white outline-none [appearance:textfield] sm:text-3xl [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                            </div>
                        </div>
                        <div>
                            <label for="cc-monthly" class="text-sm font-medium text-white/60"><?php esc_html_e( 'Aportas al mes', 'quantia-hub' ); ?></label>
                            <div class="mt-2 flex items-baseline gap-2 border-b border-white/20 pb-2 transition-colors duration-200 ease-out focus-within:border-qh-300">
                                <span class="font-display text-lg font-bold text-white/40 sm:text-xl">US$</span>
                                <input id="cc-monthly" data-compound-monthly type="number" inputmode="numeric"
                                    min="0" max="50000" step="10" value="<?php echo (int) $default_monthly; ?>"
                                    class="w-full min-w-0 bg-transparent font-display text-2xl font-extrabold tracking-[-0.02em] text-white outline-none [appearance:textfield] sm:text-3xl [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-baseline justify-between">
                            <label for="cc-years" class="text-sm font-medium text-white/60"><?php esc_html_e( 'Durante', 'quantia-hub' ); ?></label>
                            <output htmlfor="cc-years" data-compound-years-out class="font-display text-2xl font-extrabold tracking-[-0.02em]">
                                <?php
                                printf(
                                    /* translators: %d: número de años */
                                    esc_html__( '%d años', 'quantia-hub' ),
                                    (int) $default_years
                                );
                                ?>
                            </output>
                        </div>
                        <input id="cc-years" data-compound-years type="range" min="1" max="40"
                            value="<?php echo (int) $default_years; ?>"
                            class="mt-3 h-2 w-full cursor-pointer accent-qh-300">
                        <div class="mt-1 flex justify-between text-xs text-white/40">
                            <span><?php esc_html_e( '1 año', 'quantia-hub' ); ?></span>
                            <span><?php esc_html_e( '40 años', 'quantia-hub' ); ?></span>
                        </div>
                    </div>

                    <fieldset>
                        <legend class="text-sm font-medium text-white/60"><?php esc_html_e( '¿Dónde lo pones?', 'quantia-hub' ); ?></legend>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <?php foreach ( $rate_options as $opt ) :
                                $is_active = $opt['key'] === $default_rate;
                                $label_cls = $is_active
                                    ? 'bg-qh-300 text-ink ring-qh-300'
                                    : 'text-white ring-white/15 hover:bg-white/[0.06]';
                            ?>
                                <label data-compound-rate-label="<?php echo esc_attr( $opt['key'] ); ?>"
                                    class="flex cursor-pointer items-center justify-between gap-2 rounded-xl px-3.5 py-3 ring-1 transition-colors duration-150 ease-out focus-within:ring-2 focus-within:ring-qh-300 <?php echo esc_attr( $label_cls ); ?>">

                                    <input type="radio" name="cc-rate"
                                        value="<?php echo esc_attr( $opt['key'] ); ?>"
                                        data-rate="<?php echo esc_attr( (string) $opt['rate'] ); ?>"
                                        data-compound-rate
                                        <?php checked( $is_active ); ?>
                                        class="sr-only">

                                    <span class="min-w-0">
                                        <span class="block truncate text-sm font-bold"><?php echo esc_html( $opt['name'] ); ?></span>
                                        <span data-compound-rate-kind class="block text-xs <?php echo $is_active ? 'text-ink/60' : 'text-white/45'; ?>">
                                            <?php echo esc_html( $opt['kind'] ); ?>
                                        </span>
                                    </span>
                                    <span class="font-display text-lg font-extrabold">
                                        <?php echo (int) round( $opt['rate'] * 100 ); ?>%
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                </form>

                <div data-parallax data-parallax-speed="0.12">
                    <div aria-live="polite">
                        <p class="text-sm font-medium text-white/60">
                            <?php
                            printf(
                                /* translators: %s: placeholder con el número de años */
                                esc_html__( 'En %s tendrías', 'quantia-hub' ),
                                sprintf(
                                    '<span data-compound-years-out>%s</span>',
                                    sprintf(
                                        /* translators: %d: número de años */
                                        esc_html__( '%d años', 'quantia-hub' ),
                                        (int) $default_years
                                    )
                                )
                            );
                            ?>
                        </p>
                        <p data-compound-total class="mt-2 font-display text-[clamp(2.75rem,1.4rem+6vw,6rem)] font-extrabold leading-none tracking-[-0.05em] text-qh-300">
                            US$0
                        </p>

                        <dl class="mt-6 grid grid-cols-2 gap-6 border-t border-white/10 pt-6">
                            <div>
                                <dt class="flex items-center gap-2 text-sm text-white/60">
                                    <span aria-hidden="true" class="h-2.5 w-2.5 rounded-sm bg-white/25"></span>
                                    <?php esc_html_e( 'Lo que aportaste', 'quantia-hub' ); ?>
                                </dt>
                                <dd data-compound-contributed class="mt-1 font-display text-xl font-extrabold sm:text-2xl">US$0</dd>
                            </div>
                            <div>
                                <dt class="flex items-center gap-2 text-sm text-white/60">
                                    <span aria-hidden="true" class="h-2.5 w-2.5 rounded-sm bg-qh-300"></span>
                                    <?php esc_html_e( 'Lo que ganó tu dinero', 'quantia-hub' ); ?>
                                </dt>
                                <dd data-compound-interest class="mt-1 font-display text-xl font-extrabold text-qh-300 sm:text-2xl">+US$0</dd>
                            </div>
                        </dl>

                        <div data-compound-bars class="mt-8"></div>

                        <p class="mt-6 rounded-2xl bg-white/[0.06] px-4 py-4 text-sm leading-relaxed text-white/75 sm:px-5">
                            <?php
                            printf(
                                /* translators: 1: total con interés simple, 2: ventaja del interés compuesto */
                                esc_html__( 'Con interés simple tendrías %1$s. El interés compuesto te suma %2$s más.', 'quantia-hub' ),
                                '<strong data-compound-simple class="text-white">US$0</strong>',
                                '<strong data-compound-advantage class="text-qh-300">US$0</strong>'
                            );
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-col gap-4 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <p class="max-w-xl text-xs leading-relaxed text-white/45">
                    <?php esc_html_e( 'Simulación ilustrativa. La Alcancía usa la tasa de 4% anual; los perfiles de inversión usan rendimientos estimados que no están garantizados. Los rendimientos pasados no garantizan resultados futuros.', 'quantia-hub' ); ?>
                </p>
                <a href="<?php echo esc_url( home_url( '/aprende/interes-simple-y-compuesto/' ) ); ?>"
                    class="group inline-flex shrink-0 items-center gap-2 text-sm font-bold text-qh-300 transition-colors hover:text-white">
                    <?php esc_html_e( 'Entiende la diferencia', 'quantia-hub' ); ?>
                    <?php echo quantia_icon( 'arrow-up-right', 'h-4 w-4 transition-transform duration-200 ease-out group-hover:-translate-y-0.5 group-hover:translate-x-0.5' ); ?>
                </a>
            </div>
        </div>
    </section>
</div>