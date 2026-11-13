<?php
/**
 * Plantilla de la página Aprende.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

get_header();

$learn       = quantia_fallback_learn_articles();
$learn_image = quantia_option( 'home_learn_image', quantia_brand_image( 'learn' ) );

$marquee_hero = trim( str_repeat( __( 'Conceptos · Artículos · Riesgo · Interés compuesto · Ahorro · ', 'quantia-hub' ), 3 ) );
$marquee_art  = trim( str_repeat( __( 'Inversión · Ahorros · Jubilación · ', 'quantia-hub' ), 3 ) );
?>

<main class="w-full bg-white">

    <section aria-labelledby="aprende-hero-title"
        class="qh-page-hero relative flex w-full flex-col justify-end overflow-hidden bg-ink pt-32 text-white">

        <div class="relative mx-auto grid w-full max-w-6xl flex-1 grid-cols-1 items-center gap-12 px-5 pb-10 sm:px-6 lg:grid-cols-[1.2fr_1fr]">
            <div data-parallax data-parallax-speed="-0.1">
                <p class="text-sm font-semibold text-qh-300"><?php esc_html_e( 'Aprende', 'quantia-hub' ); ?></p>
                <h1 id="aprende-hero-title"
                    class="mt-5 font-display text-[clamp(2.25rem,1.2rem+4.4vw,6rem)] font-extrabold leading-[0.94] tracking-[-0.045em]">
                    <?php esc_html_e( 'Aprende a mover', 'quantia-hub' ); ?><br>
                    <span class="text-qh-300"><?php esc_html_e( 'tu dinero.', 'quantia-hub' ); ?></span>
                </h1>
                <p class="mt-7 max-w-lg text-lg leading-relaxed text-white/70">
                    <?php esc_html_e( 'Conceptos clave para entender tu dinero y artículos para decidir con calma.', 'quantia-hub' ); ?>
                </p>
                <div class="mt-9 flex flex-wrap items-center gap-3">
                    <a href="#conceptos" class="inline-flex items-center gap-2 rounded-full bg-qh px-7 py-3.5 text-base font-semibold text-white transition-colors hover:bg-qh-600">
                        <?php esc_html_e( 'Conceptos', 'quantia-hub' ); ?>
                        <?php echo quantia_icon( 'arrow-down', 'h-4 w-4' ); ?>
                    </a>
                    <a href="#articulos" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-7 py-3.5 text-base font-semibold text-white transition-colors hover:border-white/70 hover:bg-white/10">
                        <?php esc_html_e( 'Artículos', 'quantia-hub' ); ?>
                        <?php echo quantia_icon( 'arrow-down', 'h-4 w-4' ); ?>
                    </a>
                </div>
            </div>

            <div data-parallax data-parallax-speed="0.14" class="relative mx-auto hidden w-full max-w-md lg:block lg:ml-auto lg:mr-0">
                <div class="relative aspect-[4/5] w-full overflow-hidden rounded-3xl">
                    <?php if ( $learn_image ) : ?>
                        <img src="<?php echo esc_url( $learn_image ); ?>" alt="<?php esc_attr_e( 'Joven tomando notas mientras revisa sus finanzas en el teléfono', 'quantia-hub' ); ?>" class="absolute inset-x-0 -top-[15%] h-[130%] w-full object-cover" loading="lazy" decoding="async">
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div data-marquee data-marquee-distance="35" aria-hidden="true" class="pointer-events-none select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-white/[0.06] -mb-[0.18em]" style="font-size:clamp(4rem,11vw,10rem);">
                <?php echo esc_html( $marquee_hero ); ?>
            </p>
        </div>
    </section>

    <?php if ( ! empty( $learn ) ) : ?>
        <section id="conceptos" class="mx-auto max-w-6xl scroll-mt-20 px-5 pt-20 sm:px-6 md:pt-28">
            <p class="mb-6 text-sm font-semibold text-qh-700"><?php esc_html_e( 'Conceptos', 'quantia-hub' ); ?></p>

            <?php
            $concepts_intro = __( 'No necesitas ser experto. Necesitas entender cuatro conceptos y tomar decisiones con calma.', 'quantia-hub' );
            ?>
            <h2 data-scroll-reveal-text
                data-text="<?php echo esc_attr( $concepts_intro ); ?>"
                data-highlight="cuatro,conceptos"
                class="max-w-5xl font-display text-[clamp(1.75rem,1rem+3.1vw,4.25rem)] font-extrabold leading-[1.05] tracking-[-0.04em] text-ink">
                <?php echo esc_html( $concepts_intro ); ?>
            </h2>

            <nav aria-label="<?php esc_attr_e( 'Conceptos', 'quantia-hub' ); ?>" class="mt-12 flex flex-wrap gap-x-8 gap-y-3">
                <?php foreach ( $learn as $concept ) : ?>
                    <a href="#<?php echo esc_attr( $concept['id'] ); ?>"
                       class="font-display text-lg font-semibold text-ink/40 transition-colors hover:text-qh-700">
                        <?php echo esc_html( $concept['label'] ?: $concept['id'] ); ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="mt-16">
                <?php foreach ( $learn as $i => $concept ) :
                    get_template_part( 'features/articles/views/learn-article-block', null, array(
                        'article' => $concept,
                        'index'   => $i,
                    ) );
                endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ( quantia_has_articles() ) : ?>
        <a href="#articulos" class="inline-flex items-center gap-2 rounded-full border border-white/30 px-7 py-3.5 text-base font-semibold text-white transition-colors hover:border-white/70 hover:bg-white/10">
            <?php esc_html_e( 'Artículos', 'quantia-hub' ); ?>
            <?php echo quantia_icon( 'arrow-down', 'h-4 w-4' ); ?>
        </a>
    <?php endif; ?>

    <div class="bg-white" data-reveal-frame>
        <section id="articulos" class="w-full scroll-mt-20 overflow-hidden bg-white pb-16 pt-14 sm:pb-20 sm:pt-16 md:pb-24 md:pt-20">
            <div data-marquee data-marquee-distance="30" aria-hidden="true" class="pointer-events-none select-none overflow-hidden">
                <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(4rem,10vw,9rem)] text-ink/[0.04]">
                    <?php echo esc_html( $marquee_art ); ?>
                </p>
            </div>
            <div class="mx-auto -mt-8 max-w-6xl px-5 sm:px-6">
                <div data-parallax data-parallax-speed="0.2">
                    <p class="text-sm font-semibold text-qh-700"><?php esc_html_e( 'Artículos', 'quantia-hub' ); ?></p>
                    <h2 class="mt-4 font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-6xl">
                        <?php esc_html_e( 'Lecturas para', 'quantia-hub' ); ?><br><span class="text-qh"><?php esc_html_e( 'decidir mejor.', 'quantia-hub' ); ?></span>
                    </h2>
                </div>
                <div class="mt-12">
                    <?php get_template_part( 'features/articles/views/list' ); ?>
                </div>
            </div>
        </section>
    </div>

    <div class="bg-white" data-reveal-frame>
        <section class="relative overflow-hidden bg-qh-700 py-14 text-white sm:py-20 lg:py-28">
            <div data-marquee data-marquee-distance="30" aria-hidden="true" class="pointer-events-none select-none overflow-hidden">
                <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(3.5rem,9vw,8rem)] text-white/10">
                    <?php esc_html_e( 'Pon en práctica lo que leíste · Pon en práctica lo que leíste · Pon en práctica lo que leíste ·', 'quantia-hub' ); ?>
                </p>
            </div>
            <div class="mx-auto -mt-4 max-w-6xl px-5 sm:px-6">
                <div data-parallax data-parallax-speed="0.2">
                    <h2 class="font-display text-5xl font-extrabold leading-[0.96] tracking-[-0.045em] sm:text-6xl">
                        <?php esc_html_e( 'Ahora,', 'quantia-hub' ); ?><br><?php esc_html_e( 'ponlo en práctica.', 'quantia-hub' ); ?>
                    </h2>
                    <p class="mt-6 max-w-md text-base leading-relaxed text-white/80">
                        <?php esc_html_e( 'Empieza con una alcancía o conoce los portafolios de inversión disponibles en Quantia Hub.', 'quantia-hub' ); ?>
                    </p>
                    <div class="mt-9 flex flex-wrap items-center gap-3">
                        <?php get_template_part( 'template-parts/components/download-app-button', null, array(
                            'variant'  => 'light',
                            'location' => 'about',
                            'class'    => '!px-7 !py-3.5 !text-base',
                        ) ); ?>
                        <a href="<?php echo esc_url( home_url( '/productos/inversiones/' ) ); ?>" class="rounded-full border border-white/30 px-7 py-3.5 text-base font-semibold text-white transition-colors hover:border-white/70 hover:bg-white/10">
                            <?php esc_html_e( 'Ver Inversiones', 'quantia-hub' ); ?>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php get_footer(); ?>