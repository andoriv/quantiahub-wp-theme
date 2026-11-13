<?php
defined( 'ABSPATH' ) || exit;

$support_image = quantia_option( 'support_image', quantia_brand_image( 'support' ) );
$topics        = quantia_get_faq_categories_from_cpt();
?>

<section class="relative w-full overflow-hidden bg-mist pb-16 pt-28 md:pb-24 md:pt-36">
    <div class="mx-auto grid max-w-6xl grid-cols-1 items-center gap-14 px-5 sm:px-6 lg:grid-cols-[1.25fr_1fr]">

        <div>
            <p class="text-sm font-semibold text-qh-700"><?php esc_html_e( 'Centro de ayuda', 'quantia-hub' ); ?></p>
            <h1 class="mt-4 font-display text-[clamp(2.125rem,1.2rem+3.8vw,5rem)] font-extrabold leading-[0.96] tracking-[-0.045em] text-ink">
                <?php esc_html_e( 'Hola, ¿en qué', 'quantia-hub' ); ?><br>
                <span class="text-qh"><?php esc_html_e( 'te ayudamos?', 'quantia-hub' ); ?></span>
            </h1>

            <form role="search" data-support-search
                onsubmit="event.preventDefault(); document.getElementById('respuestas')?.scrollIntoView({behavior:'smooth'});"
                class="mt-10">
                <label for="help-search" class="sr-only">
                    <?php esc_html_e( 'Busca tu pregunta', 'quantia-hub' ); ?>
                </label>
                <div class="flex items-center gap-3 rounded-full bg-white py-2 pl-6 pr-2 shadow-[0_20px_50px_-24px_rgba(11,31,23,0.35)] focus-within:ring-2 focus-within:ring-qh">
                    <?php echo quantia_icon( 'search', 'h-5 w-5 shrink-0 text-ink/40' ); ?>
                    <input id="help-search" data-support-query type="search"
                        placeholder="<?php esc_attr_e( 'Ej. Kuara, límite de mi cuenta, bloquear tarjeta', 'quantia-hub' ); ?>"
                        class="min-w-0 flex-1 bg-transparent py-2.5 text-base text-ink outline-none placeholder:text-ink/40 [&::-webkit-search-cancel-button]:hidden">
                    <button type="submit" class="rounded-full bg-ink px-5 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-qh-700">
                        <?php esc_html_e( 'Buscar', 'quantia-hub' ); ?>
                    </button>
                </div>
            </form>

            <?php if ( ! empty( $topics ) ) : ?>
                <div class="mt-6 flex flex-wrap items-center gap-2">
                    <span class="mr-1 text-sm text-ink/50"><?php esc_html_e( 'Temas', 'quantia-hub' ); ?>:</span>
                    <?php foreach ( $topics as $topic ) : ?>
                        <button type="button" data-support-topic="<?php echo esc_attr( $topic['id'] ); ?>"
                            class="rounded-full border border-ink/15 bg-white px-4 py-1.5 text-sm font-semibold text-ink/70 transition-colors hover:border-qh hover:text-ink">
                            <?php echo esc_html( $topic['label'] ); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="relative mx-auto w-full max-w-sm lg:mr-0">
            <div class="relative aspect-[4/5] w-full overflow-hidden rounded-[2rem]">
                <?php if ( $support_image ) : ?>
                    <img src="<?php echo esc_url( $support_image ); ?>"
                        alt="<?php esc_attr_e( 'Agente de soporte de Quantia Hub atendiendo a un cliente', 'quantia-hub' ); ?>"
                        class="absolute inset-x-0 -top-[15%] h-[130%] w-full object-cover" loading="lazy" decoding="async">
                <?php endif; ?>
            </div>

            <div data-parallax data-parallax-speed="0.35" class="absolute -left-6 top-10 sm:-left-14">
                <p class="max-w-[220px] rounded-2xl rounded-bl-md bg-white px-4 py-3 text-sm text-ink shadow-[0_16px_40px_-16px_rgba(11,31,23,0.35)]">
                    <?php esc_html_e( '¡Hola! Soy Andrea, de Quantia. ¿Cómo te ayudo hoy?', 'quantia-hub' ); ?>
                </p>
            </div>
            <div data-parallax data-parallax-speed="0.6" class="absolute -right-3 bottom-24 sm:-right-8">
                <p class="max-w-[200px] rounded-2xl rounded-br-md bg-qh px-4 py-3 text-sm font-medium text-white shadow-[0_16px_40px_-16px_rgba(11,31,23,0.45)]">
                    <?php esc_html_e( 'Quiero bloquear mi tarjeta', 'quantia-hub' ); ?>
                </p>
            </div>
            <div data-parallax data-parallax-speed="0.2" class="absolute -left-4 bottom-6 sm:-left-10">
                <p class="flex items-center gap-2 rounded-full bg-ink px-4 py-2 text-xs font-semibold text-white">
                    <span class="h-2 w-2 rounded-full bg-qh-300"></span>
                    <?php esc_html_e( 'Equipo en Panamá', 'quantia-hub' ); ?>
                </p>
            </div>
        </div>
    </div>
</section>