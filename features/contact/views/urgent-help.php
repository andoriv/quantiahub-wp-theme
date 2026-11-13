<?php
defined( 'ABSPATH' ) || exit;

$phone     = quantia_option( 'contact_phone', '+507 208-7024' );
$phone_url = quantia_option( 'contact_phone_url', 'tel:+5072087024' );
$wa_base   = quantia_option( 'contact_whatsapp_url', 'https://wa.me/50764777460' );
$wa_msg    = rawurlencode( __( 'Hola, necesito reportar mi tarjeta Quantia, pérdida, robo o un cargo que no reconozco.', 'quantia-hub' ) );
$wa_url    = $wa_base . '?text=' . $wa_msg;
?>

<section aria-labelledby="urgente-titulo" class="relative w-full overflow-hidden bg-ink py-16 text-white md:py-20">
    <div class="mx-auto grid max-w-6xl grid-cols-1 items-center gap-12 px-5 sm:px-6 lg:grid-cols-[1.4fr_1fr]">

        <div>
            <p class="inline-flex items-center gap-2 text-sm font-semibold text-qh-300">
                <?php echo quantia_icon( 'lock', 'h-4 w-4' ); ?>
                <?php esc_html_e( 'Ayuda urgente', 'quantia-hub' ); ?>
            </p>
            <h2 id="urgente-titulo"
                class="mt-4 font-display text-[clamp(1.75rem,1.1rem+2.6vw,3.5rem)] font-extrabold leading-[1.02] tracking-[-0.04em]">
                <?php esc_html_e( '¿Perdiste tu tarjeta o ves un cargo', 'quantia-hub' ); ?>
                <span class="text-qh-300"><?php esc_html_e( 'que no reconoces?', 'quantia-hub' ); ?></span>
            </h2>

            <ol class="mt-8 space-y-4">
                <li class="flex gap-4">
                    <span class="font-display text-lg font-extrabold text-qh-300">1</span>
                    <p class="text-base leading-relaxed text-white/75">
                        <span class="font-semibold text-white"><?php esc_html_e( 'Bloquéala ahora desde la app.', 'quantia-hub' ); ?></span>
                        <?php esc_html_e( 'Entra a Tarjeta y toca Bloquear. Es inmediato.', 'quantia-hub' ); ?>
                    </p>
                </li>
                <li class="flex gap-4">
                    <span class="font-display text-lg font-extrabold text-qh-300">2</span>
                    <p class="text-base leading-relaxed text-white/75">
                        <span class="font-semibold text-white"><?php esc_html_e( 'Repórtala con nosotros', 'quantia-hub' ); ?></span>
                        <?php esc_html_e( 'para gestionar tu caso o una reposición.', 'quantia-hub' ); ?>
                    </p>
                </li>
            </ol>

            <div class="mt-9 flex flex-wrap gap-3">
                <a href="<?php echo esc_url( $phone_url ); ?>"
                    class="inline-flex items-center gap-2 rounded-full bg-qh px-6 py-3.5 text-sm font-semibold text-white transition-colors hover:bg-qh-600">
                    <?php echo quantia_icon( 'phone', 'h-4 w-4' ); ?>
                    <?php
                    printf(
                        /* translators: %s: número de teléfono */
                        esc_html__( 'Llamar al %s', 'quantia-hub' ),
                        esc_html( $phone )
                    );
                    ?>
                </a>
                <a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer"
                    data-quantia-whatsapp data-product="general" data-location="support"
                    class="inline-flex items-center gap-2 rounded-full bg-white/10 px-6 py-3.5 text-sm font-semibold text-white transition-colors hover:bg-white/20">
                    <?php echo quantia_icon( 'message-circle', 'h-4 w-4' ); ?>
                    <?php esc_html_e( 'Reportar por WhatsApp', 'quantia-hub' ); ?>
                </a>
            </div>

            <p class="mt-5 max-w-lg text-sm leading-relaxed text-white/55">
                <?php esc_html_e( 'WhatsApp, todos los días de 8:00 a.m. a 8:00 p.m. Avísanos cuanto antes. Según los términos, eres responsable de los consumos hechos con tu tarjeta hasta 2 horas después de tu reporte.', 'quantia-hub' ); ?>
            </p>
        </div>

        <div class="relative hidden justify-center lg:flex" data-parallax data-parallax-speed="0.3" data-parallax-rotate="-4">
            <?php get_template_part( 'template-parts/components/phone-frame', null, array(
                'label'  => __( 'Pantalla de tarjeta de la app con la opción de bloqueo', 'quantia-hub' ),
                'screen' => 'tarjeta',
            ) ); ?>
        </div>
    </div>
</section>