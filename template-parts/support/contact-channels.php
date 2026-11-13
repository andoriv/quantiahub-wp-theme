<?php
defined( 'ABSPATH' ) || exit;

$whatsapp_num = quantia_option( 'contact_whatsapp_number', '+507 6477-7460' );
$whatsapp_url = quantia_option( 'contact_whatsapp_url', 'https://wa.me/50764777460' );
$phone        = quantia_option( 'contact_phone', '+507 208-7024' );
$phone_url    = quantia_option( 'contact_phone_url', 'tel:+5072087024' );
$email        = quantia_option( 'contact_email', 'quantia@mmgbank.com' );
$email_url    = quantia_option( 'contact_email_url', 'mailto:quantia@mmgbank.com' );

$wa_link = $whatsapp_url . '?text=' . rawurlencode( 'Hola, necesito ayuda con Quantia Hub.' );
?>

<section id="canales" class="w-full scroll-mt-20 overflow-hidden bg-ink py-14 text-white sm:py-20 lg:py-28">
    <div class="mx-auto max-w-6xl px-5 sm:px-6">
        <h2 class="font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] sm:text-5xl">
            ¿Prefieres hablar<br>
            <span class="text-qh-300">con una persona?</span>
        </h2>

        <ul class="mt-14 grid grid-cols-1 gap-10 md:grid-cols-3 md:gap-0">

            <li class="md:pr-10" data-parallax data-parallax-speed="0.1">
                <a href="<?php echo esc_url( $wa_link ); ?>" target="_blank" rel="noopener noreferrer"
                    data-quantia-whatsapp data-product="general" data-location="support" class="group block">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-qh text-white">
                        <?php echo quantia_icon( 'message-circle', 'h-6 w-6' ); ?>
                    </span>
                    <span class="mt-6 block break-all font-display text-2xl font-bold tracking-[-0.02em]">WhatsApp</span>
                    <span class="mt-2 block max-w-xs text-sm leading-relaxed text-white/60">
                        La forma más rápida. Te atendemos todos los días, de 8:00 a.m. a 8:00 p.m.
                    </span>
                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-qh-300 transition-colors group-hover:text-white">
                        Abrir chat
                        <?php echo quantia_icon( 'arrow-right', 'h-4 w-4 transition-transform group-hover:translate-x-1' ); ?>
                    </span>
                </a>
            </li>

            <li class="md:border-l md:border-white/15 md:pl-10" data-parallax data-parallax-speed="0.25">
                <a href="<?php echo esc_url( $phone_url ); ?>" class="group block">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-qh-300">
                        <?php echo quantia_icon( 'phone', 'h-6 w-6' ); ?>
                    </span>
                    <span class="mt-6 block break-all font-display text-2xl font-bold tracking-[-0.02em]">
                        <?php echo esc_html( $phone ); ?>
                    </span>
                    <span class="mt-2 block max-w-xs text-sm leading-relaxed text-white/60">
                        Llámanos de lunes a viernes, de 8:00 a.m. a 5:00 p.m.
                    </span>
                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-qh-300 transition-colors group-hover:text-white">
                        Llamar
                        <?php echo quantia_icon( 'arrow-right', 'h-4 w-4 transition-transform group-hover:translate-x-1' ); ?>
                    </span>
                </a>
            </li>

            <li class="md:border-l md:border-white/15 md:pl-10" data-parallax data-parallax-speed="0.4">
                <a href="<?php echo esc_url( $email_url ); ?>" class="group block">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-qh-300">
                        <?php echo quantia_icon( 'mail', 'h-6 w-6' ); ?>
                    </span>
                    <span class="mt-6 block break-all font-display text-2xl font-bold tracking-[-0.02em]">
                        <?php echo esc_html( $email ); ?>
                    </span>
                    <span class="mt-2 block max-w-xs text-sm leading-relaxed text-white/60">
                        Para consultas que necesitan más detalle o documentos.
                    </span>
                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-qh-300 transition-colors group-hover:text-white">
                        Enviar correo
                        <?php echo quantia_icon( 'arrow-right', 'h-4 w-4 transition-transform group-hover:translate-x-1' ); ?>
                    </span>
                </a>
            </li>
        </ul>
    </div>
</section>