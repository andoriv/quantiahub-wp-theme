<?php
defined( 'ABSPATH' ) || exit;

$bg          = quantia_fallback_hero_background();
$credentials = quantia_option_rows( 'home_hero_credentials' ) ?: quantia_fallback_hero_credentials();
$products    = quantia_fallback_hero_products();

$card_positions = array(
    '-left-1 top-0 sm:-left-4 sm:top-[8%]',
    '-right-1 top-[14%] sm:-right-2 sm:top-[2%]',
    '-left-1 bottom-[14%] sm:-left-8 sm:bottom-[16%]',
    '-right-1 bottom-0 sm:-right-4 sm:bottom-[6%]',
);
?>

<section id="top-hero"
    data-parallax-root
    aria-labelledby="hero-title"
    class="qh-hero relative flex w-full flex-col overflow-hidden bg-ink text-white">

    <?php if ( $bg ) : ?>
        <img src="<?php echo esc_url( $bg ); ?>" alt="" aria-hidden="true"
            data-parallax data-parallax-speed="0.18"
            class="absolute inset-0 h-full w-full scale-[1.08] object-cover object-right" />
    <?php else : ?>
        <div class="absolute inset-0 bg-gradient-to-br from-ink to-qh-900" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="absolute inset-0 bg-ink/60" aria-hidden="true"></div>

    <div class="relative mx-auto grid w-full max-w-7xl flex-1 grid-cols-1 items-center gap-6 px-5 pb-8 pt-28 sm:px-6 lg:grid-cols-[1.25fr_1fr] lg:gap-4 lg:pt-32">

        <div data-parallax data-parallax-speed="-0.08">
            <p class="inline-flex items-center gap-2 text-sm font-medium text-white/80">
                <span class="text-qh-300"><?php echo quantia_icon( 'shield-check', 'h-4 w-4' ); ?></span>
                <?php esc_html_e( 'Respaldado por MMG Bank · Regulado en Panamá', 'quantia-hub' ); ?>
            </p>

            <h1 id="hero-title"
                class="mt-5 font-display text-[clamp(2.125rem,1.1rem+4.4vw,4.375rem)] font-extrabold leading-[0.95] tracking-[-0.045em] sm:mt-6">
                <?php esc_html_e( 'La experiencia de', 'quantia-hub' ); ?><br>
                <?php esc_html_e( 'un banco de inversión.', 'quantia-hub' ); ?><br>
                <span class="text-qh-300"><?php esc_html_e( 'En tu bolsillo.', 'quantia-hub' ); ?></span>
            </h1>

            <p class="mt-5 max-w-md text-base leading-relaxed text-white/75 sm:mt-7 sm:text-lg">
                <?php esc_html_e( 'Ahorra, invierte y planifica tu retiro con el conocimiento de quienes llevan años haciéndolo.', 'quantia-hub' ); ?>
            </p>

            <div class="mt-9 flex flex-wrap items-center gap-3">
                <?php get_template_part( 'template-parts/components/download-app-button', null, array(
                    'label'    => __( 'Abre tu cuenta', 'quantia-hub' ),
                    'variant'  => 'green',
                    'location' => 'hero',
                    'class'    => '!px-7 !py-3.5 !text-base',
                ) ); ?>
                <a href="#demo"
                    class="inline-flex items-center gap-2 rounded-full border border-white/25 px-7 py-3.5 text-base font-semibold text-white transition-colors hover:border-white/60 hover:bg-white/10">
                    <span class="text-qh-300"><?php echo quantia_icon( 'play', 'h-4 w-4' ); ?></span>
                    <?php esc_html_e( 'Prueba la demo', 'quantia-hub' ); ?>
                </a>
            </div>
        </div>

        <div class="relative mx-auto h-[400px] w-full max-w-[500px] min-[400px]:h-[440px] sm:h-[540px] lg:h-[620px]">
            <div class="absolute inset-0 flex items-center justify-center" data-parallax data-parallax-speed="0.16">
                <div>
                    <?php get_template_part( 'template-parts/components/phone-frame', null, array(
                        'label'  => __( 'Pantalla de inicio de Quantia Hub con saldo y resumen de productos', 'quantia-hub' ),
                        'screen' => 'inicio',
                        'class'  => 'ring-1 ring-white/15',
                    ) ); ?>
                </div>
            </div>

            <?php foreach ( $products as $i => $p ) : ?>
                <?php
                $slot      = $i % 4;
                $pos_class = $card_positions[ $slot ];
                $is_dark   = ! empty( $p['dark'] );
                $icon_name = quantia_product_icon_name( $p['slug'] );
                ?>
                <div data-parallax data-parallax-speed="<?php echo esc_attr( (string) ( -$p['speed'] / 1000 ) ); ?>"
                    class="absolute z-10 <?php echo esc_attr( $pos_class ); ?>">
                    <a href="<?php echo esc_url( home_url( '/productos/' . $p['slug'] . '/' ) ); ?>"
                        class="block w-[104px] rounded-xl p-2.5 shadow-[0_12px_30px_-14px_rgba(11,31,23,0.45)] transition-transform duration-200 ease-out hover:-translate-y-1 sm:w-[176px] sm:rounded-2xl sm:p-3.5 <?php echo $is_dark ? 'bg-qh-700 text-white' : 'bg-white text-ink'; ?>">
                        <span class="flex items-center gap-1.5 sm:gap-2">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full sm:h-7 sm:w-7 <?php echo $is_dark ? 'bg-white/10 text-qh-300' : 'bg-qh-100 text-qh-700'; ?>">
                                <?php echo quantia_icon( $icon_name, 'h-3 w-3 sm:h-3.5 sm:w-3.5' ); ?>
                            </span>
                            <span class="truncate text-[11px] font-semibold sm:text-xs <?php echo $is_dark ? 'text-white/70' : 'text-ink/55'; ?>">
                                <?php echo esc_html( $p['label'] ); ?>
                            </span>
                        </span>
                        <span class="mt-1.5 block truncate font-display text-sm font-extrabold tracking-[-0.02em] sm:mt-2.5 sm:text-xl">
                            <?php echo esc_html( $p['value'] ); ?>
                        </span>
                        <span class="hidden text-[11px] sm:block <?php echo $is_dark ? 'text-white/60' : 'text-ink/50'; ?>">
                            <?php echo esc_html( $p['detail'] ); ?>
                        </span>
                        <?php if ( isset( $p['progress'] ) && null !== $p['progress'] ) : ?>
                            <span class="mt-2 hidden h-1.5 w-full rounded-full bg-mist sm:block">
                                <span class="block h-1.5 rounded-full bg-qh" style="width:<?php echo (int) $p['progress']; ?>%"></span>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="relative border-t border-white/10 bg-ink/70 backdrop-blur">
        <dl class="mx-auto grid max-w-7xl grid-cols-2 px-5 sm:px-6 lg:grid-cols-4">
            <?php foreach ( $credentials as $i => $c ) : ?>
                <div class="py-6 pr-4 <?php echo $i > 0 ? 'lg:border-l lg:border-white/10 lg:pl-8' : ''; ?> <?php echo $i % 2 === 1 ? 'border-l border-white/10 pl-4 lg:pl-8' : ''; ?>">
                    <dt class="font-display text-2xl font-extrabold tracking-[-0.02em] text-white sm:text-3xl">
                        <?php echo esc_html( $c['value'] ); ?>
                    </dt>
                    <dd class="mt-1 text-xs leading-snug text-white/60 sm:text-sm">
                        <?php echo esc_html( $c['label'] ); ?>
                    </dd>
                </div>
            <?php endforeach; ?>
        </dl>
    </div>
</section>