<?php
/**
 * Footer, cuatro columnas.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$product_nav = quantia_get_product_nav();
$contact     = array(
    'whatsapp_number' => quantia_option( 'contact_whatsapp_number', '+507 6477-7460' ),
    'whatsapp_url'    => quantia_option( 'contact_whatsapp_url', 'https://wa.me/50764777460' ),
    'phone'           => quantia_option( 'contact_phone', '+507 208-7024' ),
    'phone_url'       => quantia_option( 'contact_phone_url', 'tel:+5072087024' ),
    'email'           => quantia_option( 'contact_email', 'quantia@mmgbank.com' ),
    'email_url'       => quantia_option( 'contact_email_url', 'mailto:quantia@mmgbank.com' ),
    'claims_url'      => quantia_option( 'contact_claims_url', 'mailto:reclamos@mmgbank.com' ),
);

$site_links = array(
    array( 'label' => __( 'Tarifas', 'quantia-hub' ),  'url' => home_url( '/tarifas/' ) ),
    array( 'label' => __( 'Aprende', 'quantia-hub' ),  'url' => home_url( '/aprende/' ) ),
    array( 'label' => __( 'Soporte', 'quantia-hub' ),  'url' => home_url( '/soporte/' ) ),
    array( 'label' => __( 'Nosotros', 'quantia-hub' ), 'url' => home_url( '/nosotros/' ) ),
);

$legal_docs = get_posts(
    array(
        'post_type'      => 'documento_legal',
        'posts_per_page' => 8,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    )
);

$regulators = quantia_option_rows( 'regulators' );
if ( empty( $regulators ) ) {
    $regulators = array(
        array( 'name' => 'Superintendencia de Bancos de Panamá',    'short' => 'SBP' ),
        array( 'name' => 'Superintendencia del Mercado de Valores', 'short' => 'SMV' ),
        array( 'name' => 'Latinex',                                 'short' => 'Latinex' ),
    );
}

$full_brand = quantia_option( 'site_full_brand', 'Quantia Hub' );
$bank       = quantia_option( 'site_bank', 'MMG Bank Corporation' );
$bank_url   = quantia_option( 'site_bank_url', 'https://www.mmgbank.com' );
$market     = quantia_option( 'site_market', 'Panamá' );
$domain     = quantia_option( 'site_domain', 'quantiahub.com' );
$site_url   = quantia_option( 'site_url', 'https://quantiahub.com' );
?>

<footer class="w-full bg-ink py-14 text-white">
    <div class="mx-auto max-w-6xl px-5 sm:px-6">

        <div class="flex flex-wrap items-center justify-between gap-6 border-b border-white/10 pb-8">
            <?php get_template_part( 'template-parts/components/logo', null, array(
                'variant' => 'dark',
                'height'  => 'h-8',
            ) ); ?>
            <div class="flex items-center gap-3">
                <a href="<?php echo esc_url( $bank_url ); ?>" target="_blank" rel="noopener noreferrer"
                   aria-label="Instagram"
                   class="flex h-9 w-9 items-center justify-center rounded-full border border-white/15 text-white/70 transition-colors hover:border-qh hover:text-qh-300">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                </a>
                <a href="<?php echo esc_url( $bank_url ); ?>" target="_blank" rel="noopener noreferrer"
                   aria-label="Facebook"
                   class="flex h-9 w-9 items-center justify-center rounded-full border border-white/15 text-white/70 transition-colors hover:border-qh hover:text-qh-300">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                <a href="<?php echo esc_url( $bank_url ); ?>" target="_blank" rel="noopener noreferrer"
                   aria-label="LinkedIn"
                   class="flex h-9 w-9 items-center justify-center rounded-full border border-white/15 text-white/70 transition-colors hover:border-qh hover:text-qh-300">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                </a>
                <a href="<?php echo esc_url( $bank_url ); ?>" target="_blank" rel="noopener noreferrer"
                   aria-label="YouTube"
                   class="flex h-9 w-9 items-center justify-center rounded-full border border-white/15 text-white/70 transition-colors hover:border-qh hover:text-qh-300">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/></svg>
                </a>
                <a href="#top" aria-label="<?php esc_attr_e( 'Volver arriba', 'quantia-hub' ); ?>"
                   class="flex h-9 w-9 items-center justify-center rounded-full bg-qh text-white transition-colors hover:bg-qh-600">
                    <?php echo quantia_icon( 'arrow-up', 'h-4 w-4' ); // phpcs:ignore ?>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 py-10 md:grid-cols-4">
            <div>
                <h3 class="text-[11px] font-semibold uppercase tracking-widest text-qh-300">
                    <?php esc_html_e( 'Productos', 'quantia-hub' ); ?>
                </h3>
                <ul class="mt-4 space-y-2">
                    <?php foreach ( $product_nav as $item ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $item['url'] ); ?>" class="text-xs text-white/60 transition-colors hover:text-white">
                                <?php echo esc_html( $item['label'] ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h3 class="text-[11px] font-semibold uppercase tracking-widest text-qh-300">
                    <?php esc_html_e( 'Sitio', 'quantia-hub' ); ?>
                </h3>
                <ul class="mt-4 space-y-2">
                    <?php foreach ( $site_links as $item ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $item['url'] ); ?>" class="text-xs text-white/60 transition-colors hover:text-white">
                                <?php echo esc_html( $item['label'] ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h3 class="text-[11px] font-semibold uppercase tracking-widest text-qh-300">
                    <?php esc_html_e( 'Regulatorio', 'quantia-hub' ); ?>
                </h3>
                <ul class="mt-4 space-y-2">
                    <li>
                        <a href="<?php echo esc_url( home_url( '/legal/' ) ); ?>" class="text-xs font-semibold text-white/80 transition-colors hover:text-white">
                            <?php esc_html_e( 'Centro legal', 'quantia-hub' ); ?>
                        </a>
                    </li>
                    <?php foreach ( $legal_docs as $doc ) : ?>
                        <li>
                            <a href="<?php echo esc_url( get_permalink( $doc ) ); ?>" class="text-xs text-white/60 transition-colors hover:text-white">
                                <?php echo esc_html( get_the_title( $doc ) ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h3 class="text-[11px] font-semibold uppercase tracking-widest text-qh-300">
                    <?php esc_html_e( 'Contacto', 'quantia-hub' ); ?>
                </h3>
                <ul class="mt-4 space-y-2 text-xs text-white/60">
                    <li>
                        <a href="<?php echo esc_url( $contact['whatsapp_url'] ); ?>" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 transition-colors hover:text-white">
                            <?php echo quantia_icon( 'message-circle', 'h-3.5 w-3.5' ); // phpcs:ignore ?>
                            <?php echo esc_html( $contact['whatsapp_number'] ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( $contact['phone_url'] ); ?>" class="inline-flex items-center gap-2 transition-colors hover:text-white">
                            <?php echo quantia_icon( 'phone', 'h-3.5 w-3.5' ); // phpcs:ignore ?>
                            <?php echo esc_html( $contact['phone'] ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( $contact['email_url'] ); ?>" class="inline-flex items-center gap-2 break-all transition-colors hover:text-white">
                            <?php echo quantia_icon( 'mail', 'h-3.5 w-3.5' ); // phpcs:ignore ?>
                            <?php echo esc_html( $contact['email'] ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( $contact['claims_url'] ); ?>" class="transition-colors hover:text-white">
                            <?php esc_html_e( 'Atención de reclamos', 'quantia-hub' ); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-3 border-t border-white/10 pt-8">
            <a href="<?php echo esc_url( $site_url ); ?>" class="rounded-full bg-white px-6 py-2.5 text-xs font-semibold text-ink transition-colors hover:bg-qh-100">
                <?php echo esc_html( $domain ); ?>
            </a>
            <a href="<?php echo esc_url( $bank_url ); ?>" target="_blank" rel="noopener noreferrer"
               class="rounded-full border border-white/20 px-6 py-2.5 text-xs font-semibold text-white/80 transition-colors hover:border-qh hover:text-qh-300">
                MMG Bank
            </a>
        </div>

        <div class="mt-10">
            <p class="text-[11px] font-semibold uppercase tracking-widest text-white/40">
                <?php esc_html_e( 'Regulado y supervisado por', 'quantia-hub' ); ?>
            </p>
            <ul class="mt-3 flex flex-wrap gap-x-6 gap-y-2">
                <?php foreach ( $regulators as $regulator ) : ?>
                    <li class="text-xs text-white/60"><?php echo esc_html( $regulator['name'] ); ?></li>
                <?php endforeach; ?>
            </ul>
            <p class="mt-6 text-[10px] leading-relaxed text-white/40">
                <?php
                printf(
                    /* translators: 1: brand, 2: bank, 3: market */
                    esc_html__( '%1$s es la aplicación financiera de %2$s, entidad regulada en Panamá. La información de este sitio es de carácter informativo y no constituye una oferta ni una recomendación de inversión. Consulta tarifas vigentes y documentos regulatorios antes de contratar. Operación exclusiva para %3$s.', 'quantia-hub' ),
                    esc_html( $full_brand ),
                    esc_html( $bank ),
                    esc_html( $market )
                );
                ?>
            </p>
            <p class="mt-4 text-[10px] text-white/40">
                © <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $bank ); ?>.
                <?php esc_html_e( 'Todos los derechos reservados.', 'quantia-hub' ); ?>
            </p>
        </div>
    </div>
</footer>