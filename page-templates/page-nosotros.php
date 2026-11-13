<?php
/**
 * Plantilla de la página Nosotros.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

get_header();

$team_image       = quantia_option( 'nosotros_team_image', quantia_option( 'home_team_image', quantia_brand_image( 'team' ) ) );
$hero_credentials = quantia_option_rows( 'home_hero_credentials' ) ?: quantia_fallback_hero_credentials();
$highlights       = quantia_option_rows( 'about_highlights' ) ?: quantia_fallback_about_highlights();
$regulators       = quantia_option_rows( 'regulators' ) ?: quantia_fallback_regulators();
$bank             = quantia_option( 'site_bank', 'MMG Bank Corporation' );
$bank_url         = quantia_option( 'site_bank_url', 'https://www.mmgbank.com' );
$full_brand       = quantia_option( 'site_full_brand', 'Quantia Hub' );
$claims_email     = quantia_option( 'contact_claims_email', 'reclamos@mmgbank.com' );
$claims_url       = quantia_option( 'contact_claims_url', 'mailto:reclamos@mmgbank.com' );

$credential_speeds = array( 0.1, 0.3, 0.18, 0.4 );

$marquee_hero = trim( str_repeat( __( 'Quantia Hub · MMG Bank · Panamá · ', 'quantia-hub' ), 3 ) );
$marquee_cta  = trim( str_repeat( __( 'Abre tu cuenta · Respaldo MMG Bank · ', 'quantia-hub' ), 3 ) );
?>

<main class="w-full bg-white">

    <section aria-labelledby="nosotros-hero-title"
        class="qh-page-hero relative flex w-full flex-col justify-end overflow-hidden bg-ink pt-32 text-white">

        <div class="relative mx-auto grid w-full max-w-6xl flex-1 grid-cols-1 items-center gap-12 px-5 pb-10 sm:px-6 lg:grid-cols-[1.2fr_1fr]">
            <div data-parallax data-parallax-speed="-0.1">
                <p class="text-sm font-semibold text-qh-300"><?php esc_html_e( 'Nosotros', 'quantia-hub' ); ?></p>
                <h1 id="nosotros-hero-title"
                    class="mt-5 font-display text-[clamp(2.25rem,1.2rem+4.4vw,6rem)] font-extrabold leading-[0.94] tracking-[-0.045em]">
                    <?php esc_html_e( 'Un banco de inversión', 'quantia-hub' ); ?><br>
                    <span class="text-qh-300"><?php esc_html_e( 'que habla tu idioma.', 'quantia-hub' ); ?></span>
                </h1>
                <p class="mt-7 max-w-lg text-lg leading-relaxed text-white/70">
                    <?php
                    printf(
                        /* translators: 1: marca completa, 2: banco */
                        esc_html__( '%1$s es la aplicación financiera de %2$s, con trayectoria en Panamá.', 'quantia-hub' ),
                        esc_html( $full_brand ),
                        esc_html( $bank )
                    );
                    ?>
                </p>
                <div class="mt-9 flex flex-wrap items-center gap-3">
                    <?php get_template_part( 'template-parts/components/download-app-button', null, array(
                        'label'    => __( 'Comienza ya', 'quantia-hub' ),
                        'variant'  => 'green',
                        'location' => 'about',
                        'class'    => '!px-7 !py-3.5 !text-base',
                    ) ); ?>
                    <a href="<?php echo esc_url( $bank_url ); ?>"
                        target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-full border border-white/30 px-7 py-3.5 text-base font-semibold text-white transition-colors hover:border-white/70 hover:bg-white/10">
                        <?php esc_html_e( 'Conoce MMG Bank', 'quantia-hub' ); ?>
                        <?php echo quantia_icon( 'external-link', 'h-4 w-4' ); ?>
                    </a>
                </div>
            </div>

            <div data-parallax data-parallax-speed="0.14" class="relative mx-auto hidden w-full max-w-md lg:block lg:ml-auto lg:mr-0">
                <div class="relative aspect-[4/5] w-full overflow-hidden rounded-3xl">
                    <?php if ( $team_image ) : ?>
                        <img src="<?php echo esc_url( $team_image ); ?>"
                            alt="<?php esc_attr_e( 'Equipo de Quantia Hub en sus oficinas en Ciudad de Panamá', 'quantia-hub' ); ?>"
                            class="absolute inset-x-0 -top-[15%] h-[130%] w-full object-cover"
                            loading="lazy" decoding="async">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div data-marquee data-marquee-distance="35" aria-hidden="true"
            class="pointer-events-none select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-white/[0.06] -mb-[0.18em]"
                style="font-size:clamp(4rem,11vw,10rem);">
                <?php echo esc_html( $marquee_hero ); ?>
            </p>
        </div>
    </section>

    <section aria-labelledby="nosotros-reveal-title"
        class="mx-auto max-w-6xl px-5 py-14 sm:px-6 sm:py-20 lg:py-28">

        <h2 id="nosotros-reveal-title"
            data-scroll-reveal-text
            data-text="<?php esc_attr_e( 'Reunimos inversión, ahorro, jubilación y medios de pago en una sola experiencia digital, con el respaldo y la supervisión de una entidad regulada.', 'quantia-hub' ); ?>"
            data-highlight="sola|experiencia|digital,"
            class="max-w-5xl font-display text-[clamp(1.75rem,1rem+3.1vw,4.25rem)] font-extrabold leading-[1.05] tracking-[-0.04em] text-ink">
            <?php esc_html_e( 'Reunimos inversión, ahorro, jubilación y medios de pago en una sola experiencia digital, con el respaldo y la supervisión de una entidad regulada.', 'quantia-hub' ); ?>
        </h2>

        <dl class="mt-20 grid grid-cols-2 border-t-2 border-ink lg:grid-cols-4">
            <?php foreach ( $hero_credentials as $i => $c ) :
                $speed      = $credential_speeds[ $i % 4 ];
                $border_lg  = $i > 0 ? 'lg:border-l lg:border-ink/10 lg:pl-8' : '';
                $border_odd = $i % 2 === 1 ? 'border-l border-ink/10 pl-4' : '';
            ?>
                <div data-parallax data-parallax-speed="<?php echo esc_attr( (string) $speed ); ?>"
                    class="pt-8 pr-4 <?php echo esc_attr( $border_lg . ' ' . $border_odd ); ?>">
                    <dt class="font-display text-[clamp(1.875rem,1.2rem+2.6vw,3.75rem)] font-extrabold leading-none tracking-[-0.04em] text-qh">
                        <?php echo esc_html( $c['value'] ); ?>
                    </dt>
                    <dd class="mt-3 text-sm leading-snug text-ink/60">
                        <?php echo esc_html( $c['label'] ); ?>
                    </dd>
                </div>
            <?php endforeach; ?>
        </dl>
    </section>

    <div class="bg-white" data-reveal-frame>
        <section aria-labelledby="nosotros-respaldo-title"
            class="w-full overflow-hidden bg-mist py-14 sm:py-20 lg:py-28">
            <div class="mx-auto grid max-w-6xl grid-cols-1 gap-12 px-5 sm:px-6 lg:grid-cols-[1fr_1.4fr] lg:gap-20">
                <div data-parallax data-parallax-speed="0.25">
                    <h2 id="nosotros-respaldo-title"
                        class="font-display text-5xl font-extrabold leading-[0.98] tracking-[-0.04em] text-ink sm:text-6xl">
                        <?php esc_html_e( 'Nuestro', 'quantia-hub' ); ?><br>
                        <span class="text-qh"><?php esc_html_e( 'respaldo.', 'quantia-hub' ); ?></span>
                    </h2>
                </div>
                <dl class="border-t-2 border-ink">
                    <?php foreach ( $highlights as $i => $h ) : ?>
                        <div data-slide data-slide-from="<?php echo $i % 2 === 0 ? '80' : '-80'; ?>"
                            class="border-b border-ink/10 py-8">
                            <dt class="font-display text-3xl font-bold tracking-[-0.025em] text-ink">
                                <?php echo esc_html( $h['title'] ); ?>
                            </dt>
                            <dd class="mt-3 max-w-lg text-base leading-relaxed text-ink/60">
                                <?php echo esc_html( $h['description'] ); ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>
        </section>
    </div>

    <div class="bg-mist" data-reveal-frame>
        <section aria-labelledby="nosotros-reguladores-title"
            class="w-full overflow-hidden bg-ink py-14 text-white sm:py-20 lg:py-28">
            <div class="mx-auto max-w-6xl px-5 sm:px-6">
                <h2 id="nosotros-reguladores-title" class="text-sm font-semibold text-qh-300">
                    <?php esc_html_e( 'Regulados y supervisados por', 'quantia-hub' ); ?>
                </h2>
                <ul class="mt-8 border-t border-white/15">
                    <?php foreach ( $regulators as $i => $r ) : ?>
                        <li class="border-b border-white/15">
                            <div data-slide data-slide-from="<?php echo $i % 2 === 0 ? '120' : '-120'; ?>"
                                class="grid grid-cols-1 gap-3 py-10 md:grid-cols-[1fr_auto] md:items-end md:gap-10">
                                <span>
                                    <span class="block font-display text-[clamp(1.5rem,0.95rem+2.8vw,3.75rem)] font-extrabold leading-[1.02] tracking-[-0.04em]">
                                        <?php echo esc_html( $r['name'] ); ?>
                                    </span>
                                    <?php if ( ! empty( $r['description'] ) ) : ?>
                                        <span class="mt-3 block max-w-lg text-base text-white/60">
                                            <?php echo esc_html( $r['description'] ); ?>
                                        </span>
                                    <?php endif; ?>
                                </span>
                                <span class="font-display text-2xl font-extrabold text-qh-300 md:text-right">
                                    <?php echo esc_html( $r['short'] ?? '' ); ?>
                                </span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    </div>

    <section aria-labelledby="nosotros-privacidad-title"
        class="w-full overflow-hidden py-14 sm:py-20 lg:py-28">
        <div class="mx-auto grid max-w-6xl grid-cols-1 gap-14 px-5 sm:px-6 lg:grid-cols-2 lg:gap-20">

            <div data-parallax data-parallax-speed="0.1">
                <div class="border-t-2 border-ink pt-8">
                    <h2 id="nosotros-privacidad-title"
                        class="font-display text-3xl font-extrabold tracking-[-0.03em] text-ink sm:text-4xl">
                        <?php esc_html_e( 'Protección de datos', 'quantia-hub' ); ?>
                    </h2>
                    <p class="mt-4 max-w-md text-base leading-relaxed text-ink/65">
                        <?php esc_html_e( 'Tratamos tus datos personales conforme a la normativa panameña aplicable. Consulta finalidades, transferencias y tus derechos en el aviso de privacidad.', 'quantia-hub' ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/legal/aviso-de-privacidad/' ) ); ?>"
                        class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-qh-700 transition-colors hover:text-ink">
                        <?php esc_html_e( 'Ver aviso de privacidad', 'quantia-hub' ); ?>
                        <?php echo quantia_icon( 'arrow-right', 'h-4 w-4' ); ?>
                    </a>
                </div>
            </div>

            <div data-parallax data-parallax-speed="0.3">
                <div class="border-t-2 border-ink pt-8">
                    <h2 class="font-display text-3xl font-extrabold tracking-[-0.03em] text-ink sm:text-4xl">
                        <?php esc_html_e( 'Atención de reclamos', 'quantia-hub' ); ?>
                    </h2>
                    <p class="mt-4 max-w-md text-base leading-relaxed text-ink/65">
                        <?php esc_html_e( 'Escríbenos a', 'quantia-hub' ); ?>
                        <a href="<?php echo esc_url( $claims_url ); ?>"
                            class="font-semibold text-qh-700 underline underline-offset-4 hover:text-ink">
                            <?php echo esc_html( $claims_email ); ?>
                        </a>.
                        <?php esc_html_e( 'Recibirás un número de caso y seguimiento dentro de los plazos regulatorios.', 'quantia-hub' ); ?>
                    </p>
                    <a href="<?php echo esc_url( home_url( '/soporte/' ) ); ?>"
                        class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-qh-700 transition-colors hover:text-ink">
                        <?php esc_html_e( 'Ir a Soporte', 'quantia-hub' ); ?>
                        <?php echo quantia_icon( 'arrow-right', 'h-4 w-4' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="bg-white" data-reveal-frame>
        <section aria-labelledby="nosotros-cta-title"
            class="relative overflow-hidden bg-qh-700 py-14 text-white sm:py-20 lg:py-28">
            <div data-marquee data-marquee-distance="30" aria-hidden="true"
                class="pointer-events-none select-none overflow-hidden">
                <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(3.5rem,9vw,8rem)] text-white/10">
                    <?php echo esc_html( $marquee_cta ); ?>
                </p>
            </div>

            <div class="mx-auto -mt-4 max-w-6xl px-5 sm:px-6">
                <div data-parallax data-parallax-speed="0.2">
                    <h2 id="nosotros-cta-title"
                        class="font-display text-5xl font-extrabold leading-[0.96] tracking-[-0.045em] sm:text-6xl">
                        <?php esc_html_e( 'Tu banco,', 'quantia-hub' ); ?><br><?php esc_html_e( 'en tu bolsillo.', 'quantia-hub' ); ?>
                    </h2>
                    <p class="mt-6 max-w-md text-base leading-relaxed text-white/80">
                        <?php esc_html_e( 'Abre tu cuenta Quantia desde la app, sin saldo mínimo ni costo de apertura.', 'quantia-hub' ); ?>
                    </p>
                    <div class="mt-9 flex flex-wrap items-center gap-3">
                        <?php get_template_part( 'template-parts/components/download-app-button', null, array(
                            'variant'  => 'light',
                            'location' => 'about',
                            'class'    => '!px-7 !py-3.5 !text-base',
                        ) ); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php get_footer(); ?>