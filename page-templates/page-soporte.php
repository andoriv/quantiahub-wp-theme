<?php
/**
 * Plantilla de la página Soporte.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="w-full bg-white">
    <?php get_template_part( 'features/contact/views/help-hero' ); ?>
    <?php get_template_part( 'features/contact/views/urgent-help' ); ?>
    <?php get_template_part( 'features/faqs/views/support-faq' ); ?>
    <?php get_template_part( 'features/contact/views/channels' ); ?>

    <section id="contacto" aria-labelledby="contacto-titulo"
        class="w-full scroll-mt-20 overflow-x-clip py-14 sm:py-20 lg:py-28">
        <div class="mx-auto grid max-w-6xl grid-cols-1 gap-14 px-5 sm:px-6 lg:grid-cols-[1fr_1.3fr] lg:gap-20">

            <div class="lg:sticky lg:top-28 lg:self-start">
                <h2 id="contacto-titulo"
                    class="font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-5xl">
                    <?php esc_html_e( 'Escríbenos', 'quantia-hub' ); ?><br>
                    <span class="text-qh"><?php esc_html_e( 'tu consulta.', 'quantia-hub' ); ?></span>
                </h2>
                <p class="mt-6 max-w-sm text-base leading-relaxed text-ink/65">
                    <?php esc_html_e( 'Un ejecutivo te contacta lo antes posible. Los campos con', 'quantia-hub' ); ?>
                    <span class="font-semibold text-qh">*</span>
                    <?php esc_html_e( 'son obligatorios.', 'quantia-hub' ); ?>
                </p>
                <p class="mt-8 max-w-sm border-l-2 border-qh pl-4 text-sm leading-relaxed text-ink/65">
                    <?php esc_html_e( '¿Es urgente? Escríbenos por', 'quantia-hub' ); ?>
                    <a href="<?php echo esc_url( quantia_option( 'contact_whatsapp_url', 'https://wa.me/50764777460' ) ); ?>"
                        target="_blank" rel="noopener noreferrer"
                        class="font-semibold text-qh-700 underline underline-offset-4">
                        WhatsApp
                    </a>,
                    <?php esc_html_e( 'todos los días de 8:00 a.m. a 8:00 p.m.', 'quantia-hub' ); ?>
                </p>
            </div>

            <?php get_template_part( 'features/contact/views/form' ); ?>
        </div>
    </section>

    <?php get_template_part( 'features/contact/views/claims' ); ?>
    <?php get_template_part( 'features/contact/views/location' ); ?>
</main>

<?php get_footer(); ?>