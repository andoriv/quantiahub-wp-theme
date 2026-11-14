<?php
/**
 * Plantilla de la página Centro legal (Trust Center).
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

get_header();

$documents = quantia_get_all_legal_documents();

$doc_icons = array(
    'terminos-legales'             => 'file-text',
    'aviso-de-privacidad'          => 'shield-check',
    'aclaraciones-y-reclamaciones' => 'message-square-warning',
    'costos-y-comisiones'          => 'receipt',
);

$marquee_text = trim( str_repeat( __( 'Términos · Privacidad · Reclamos · Tarifas · ', 'quantia-hub' ), 3 ) );

$contact_email    = quantia_option( 'contact_email', 'quantia@mmgbank.com' );
$contact_email_ur = quantia_option( 'contact_email_url', 'mailto:quantia@mmgbank.com' );
$contact_wa_num   = quantia_option( 'contact_whatsapp_number', '+507 6477-7460' );
?>

<main class="w-full bg-white">

    <section aria-labelledby="trust-hero-title"
        class="qh-page-hero relative flex w-full flex-col justify-end overflow-hidden bg-ink pt-32 text-white">

        <div class="relative mx-auto grid w-full max-w-6xl flex-1 grid-cols-1 items-center gap-12 px-5 pb-10 sm:px-6 lg:grid-cols-[1.2fr_1fr]">
            <div data-parallax data-parallax-speed="-0.1">
                <p class="text-sm font-semibold text-qh-300"><?php esc_html_e( 'Centro legal', 'quantia-hub' ); ?></p>
                <h1 id="trust-hero-title"
                    class="mt-5 font-display text-[clamp(2.25rem,1.2rem+4.4vw,6rem)] font-extrabold leading-[0.94] tracking-[-0.045em]">
                    <?php esc_html_e( 'Todo lo legal.', 'quantia-hub' ); ?><br>
                    <span class="text-qh-300"><?php esc_html_e( 'En un solo lugar.', 'quantia-hub' ); ?></span>
                </h1>
                <p class="mt-7 max-w-lg text-lg leading-relaxed text-white/70">
                    <?php esc_html_e( 'Términos, privacidad, reclamos y tarifas de Quantia, completos y siempre disponibles.', 'quantia-hub' ); ?>
                </p>
            </div>

            <?php if ( ! empty( $documents ) ) : ?>
                <div class="relative mx-auto w-full max-w-md lg:ml-auto lg:mr-0">
                    <ul class="space-y-3">
                        <?php foreach ( $documents as $index => $doc ) :
                            $icon = $doc_icons[ $doc['slug'] ] ?? 'file-text';
                        ?>
                            <li data-parallax data-parallax-speed="<?php echo $index % 2 === 0 ? '0.12' : '0.28'; ?>">
                                <a href="#<?php echo esc_attr( $doc['slug'] ); ?>"
                                   class="group flex items-center gap-4 rounded-2xl bg-white/[0.06] px-5 py-4 transition-colors hover:bg-white/[0.12]">
                                    <?php echo quantia_icon( $icon, 'h-5 w-5 shrink-0 text-qh-300' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <span class="flex-1 font-display text-lg font-bold"><?php echo esc_html( $doc['title'] ); ?></span>
                                    <?php echo quantia_icon( 'arrow-down-right', 'h-5 w-5 text-white/40 transition-transform duration-200 ease-out group-hover:translate-x-0.5 group-hover:translate-y-0.5 group-hover:text-white' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
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

        <!-- Nav para móvil -->
        <nav aria-label="<?php esc_attr_e( 'Documentos legales', 'quantia-hub' ); ?>"
            class="sticky top-16 z-20 border-b border-ink/10 bg-white/95 backdrop-blur lg:hidden">
            <ul class="mx-auto flex max-w-6xl gap-2 overflow-x-auto px-5 py-3 sm:px-6">
                <?php foreach ( $documents as $doc ) :
                    $is_active = ( $doc === $documents[0] );
                ?>
                    <li class="shrink-0">
                        <a href="#<?php echo esc_attr( $doc['slug'] ); ?>"
                           data-legal-nav-mobile="<?php echo esc_attr( $doc['slug'] ); ?>"
                           class="block whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition-colors <?php echo $is_active ? 'bg-ink text-white' : 'bg-mist text-ink/65 hover:text-ink'; ?>">
                            <?php echo esc_html( $doc['title'] ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- Grid Principal: lg:items-start es CLAVE para que el sidebar no se estire -->
        <div class="mx-auto grid max-w-6xl grid-cols-1 gap-12 px-5 py-16 sm:px-6 md:py-24 lg:grid-cols-[240px_1fr] lg:gap-20 lg:items-start">

            <!-- Sidebar: lg:self-start y lg:sticky para que se quede fijo arriba -->
            <aside class="hidden lg:block lg:sticky lg:top-28 lg:self-start">
                <nav aria-label="<?php esc_attr_e( 'Documentos legales', 'quantia-hub' ); ?>">
                    <p class="text-sm font-semibold text-ink/45"><?php esc_html_e( 'Documentos', 'quantia-hub' ); ?></p>
                    <ul class="mt-4 space-y-1 border-l border-ink/10">
                        <?php foreach ( $documents as $i => $doc ) :
                            $is_active = ( $doc === $documents[0] );
                            $base_cls  = $is_active ? 'border-qh text-ink' : 'border-transparent text-ink/50 hover:text-ink';
                        ?>
                            <li>
                                <a href="#<?php echo esc_attr( $doc['slug'] ); ?>"
                                   data-legal-nav="<?php echo esc_attr( $doc['slug'] ); ?>"
                                   class="-ml-px flex items-start gap-3 border-l-2 py-2.5 pl-4 text-[15px] font-semibold transition-colors <?php echo esc_attr( $base_cls ); ?>">
                                    <span class="font-display text-xs text-qh-700 shrink-0 mt-0.5"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
                                    <span class="leading-snug"><?php echo esc_html( $doc['title'] ); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="mt-10 border-t border-ink/10 pt-6">
                        <p class="text-sm font-semibold text-ink"><?php esc_html_e( '¿Tienes dudas?', 'quantia-hub' ); ?></p>
                        <p class="mt-2 text-sm leading-relaxed text-ink/60">
                            <?php
                            printf(
                                esc_html__( 'Escríbenos a %1$s o por WhatsApp al %2$s.', 'quantia-hub' ),
                                '<a href="' . esc_url( $contact_email_ur ) . '" class="font-semibold text-qh-700 hover:text-ink">' . esc_html( $contact_email ) . '</a>',
                                esc_html( $contact_wa_num )
                            );
                            ?>
                        </p>
                    </div>
                </nav>
            </aside>

            <!-- Contenido -->
            <div class="min-w-0">
                <?php foreach ( $documents as $i => $doc ) :
                    set_query_var( 'legal_doc', $doc );
                    set_query_var( 'legal_number', $i + 1 );
                    get_template_part( 'template-parts/legal/document' );
                endforeach; ?>
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