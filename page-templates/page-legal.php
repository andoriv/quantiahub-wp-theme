<?php
/**
 * Plantilla de la página Centro de confianza (Trust Center).
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

get_header();

$documents    = quantia_get_all_legal_documents();
$marquee_text = trim( str_repeat( __( 'Términos · Privacidad · Reclamos · Tarifas · ', 'quantia-hub' ), 3 ) );
?>

<main class="w-full bg-white">

    <section aria-labelledby="trust-hero-title"
        class="qh-page-hero relative flex w-full flex-col justify-end overflow-hidden bg-ink pt-32 text-white">

        <div class="relative mx-auto grid w-full max-w-6xl flex-1 grid-cols-1 items-center px-5 pb-10 sm:px-6">
            <div data-parallax data-parallax-speed="-0.1">
                <p class="text-sm font-semibold text-qh-300"><?php esc_html_e( 'Centro de confianza', 'quantia-hub' ); ?></p>
                <h1 id="trust-hero-title"
                    class="mt-5 font-display text-[clamp(2.25rem,1.2rem+4.4vw,6rem)] font-extrabold leading-[0.94] tracking-[-0.045em]">
                    <?php esc_html_e( 'Transparencia', 'quantia-hub' ); ?><br>
                    <span class="text-qh-300"><?php esc_html_e( 'en cada documento.', 'quantia-hub' ); ?></span>
                </h1>
                <p class="mt-7 max-w-lg text-lg leading-relaxed text-white/70">
                    <?php esc_html_e( 'Términos, privacidad, reclamos y tarifas. Todo lo que rige tu relación con Quantia Hub y MMG Bank, en un solo lugar.', 'quantia-hub' ); ?>
                </p>
            </div>
        </div>

        <div data-marquee data-marquee-distance="35" aria-hidden="true"
            class="pointer-events-none select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-white/[0.06] -mb-[0.18em]"
                style="font-size:clamp(4rem,11vw,10rem);">
                <?php echo esc_html( $marquee_text ); ?>
            </p>
        </div>
    </section>

    <?php if ( ! empty( $documents ) ) : ?>
        <div class="mx-auto max-w-6xl px-5 sm:px-6">
            <div class="grid grid-cols-1 gap-14 py-14 md:py-20 lg:grid-cols-[240px_1fr] lg:gap-16">

                <nav aria-label="<?php esc_attr_e( 'Índice de documentos', 'quantia-hub' ); ?>"
                    class="lg:sticky lg:top-28 lg:self-start">
                    <p class="text-xs font-semibold uppercase tracking-widest text-ink/40">
                        <?php esc_html_e( 'Documentos', 'quantia-hub' ); ?>
                    </p>
                    <ul class="mt-4 space-y-1 border-l border-ink/10">
                        <?php foreach ( $documents as $i => $doc ) : ?>
                            <li>
                                <a href="#<?php echo esc_attr( $doc['slug'] ); ?>"
                                   class="block border-l-2 border-transparent py-1.5 pl-4 text-sm font-semibold text-ink/55 transition-colors hover:border-qh hover:text-ink">
                                    <?php echo esc_html( sprintf( '%02d', $i + 1 ) . ' · ' . $doc['title'] ); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <div>
                    <?php foreach ( $documents as $i => $doc ) :
                        get_template_part( 'template-parts/legal/document', null, array(
                            'doc'    => $doc,
                            'number' => $i + 1,
                        ) );
                    endforeach; ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <section class="mx-auto max-w-3xl px-5 py-24 sm:px-6">
            <p class="text-base leading-relaxed text-ink/60">
                <?php esc_html_e( 'Aún no hay documentos legales publicados. Los verás aquí cuando estén disponibles.', 'quantia-hub' ); ?>
            </p>
        </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>