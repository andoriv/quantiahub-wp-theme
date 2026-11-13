<?php
defined( 'ABSPATH' ) || exit;

get_header();

$home_url      = home_url( '/' );
$products      = quantia_get_product_nav();
$current_path  = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';

$whatsapp_base = quantia_option( 'contact_whatsapp_url', 'https://wa.me/50764777460' );
$whatsapp_msg  = rawurlencode( sprintf( __( 'Hola, intenté abrir %s en el sitio de Quantia y no encontré la página.', 'quantia-hub' ), $current_path ) );
$whatsapp_url  = $whatsapp_base . '?text=' . $whatsapp_msg;

$shortcuts = array();
foreach ( $products as $item ) {
    $shortcuts[] = array(
        'label'       => $item['label'],
        'url'         => $item['url'],
        'description' => $item['description'] ?? '',
    );
}
$shortcuts[] = array( 'label' => __( 'Tarifas', 'quantia-hub' ), 'url' => home_url( '/tarifas/' ), 'description' => __( 'Todos los costos, sin letra pequeña', 'quantia-hub' ) );
$shortcuts[] = array( 'label' => __( 'Soporte', 'quantia-hub' ), 'url' => home_url( '/soporte/' ), 'description' => __( 'Preguntas frecuentes y contacto', 'quantia-hub' ) );
?>

<main class="w-full bg-white">

    <section id="nf-hero"
        aria-labelledby="nf-title"
        data-pointer-parallax
        class="qh-404-hero relative flex w-full items-center overflow-hidden bg-ink text-white">

        <div class="pointer-events-none absolute inset-0 flex items-center justify-center select-none" aria-hidden="true">
            <p data-pointer-layer
                data-pointer-x="-40"
                data-pointer-y="-24"
                class="qh-404-giant text-center font-display font-extrabold leading-none tracking-[-0.07em] text-white/[0.05]">
                404
            </p>
        </div>

        <div class="relative mx-auto grid w-full max-w-6xl grid-cols-1 items-center gap-14 px-5 py-20 sm:px-6 lg:grid-cols-[1.25fr_1fr] lg:gap-10">

            <div data-pointer-layer data-pointer-x="18" data-pointer-y="12">
                <p class="text-sm font-semibold text-qh-300"><?php esc_html_e( 'Error 404', 'quantia-hub' ); ?></p>

                <h1 id="nf-title"
                    class="mt-5 font-display text-[clamp(2.25rem,1.2rem+4.3vw,5.75rem)] font-extrabold leading-[0.94] tracking-[-0.05em]">
                    <?php esc_html_e( 'Esta página', 'quantia-hub' ); ?><br>
                    <?php esc_html_e( 'no existe.', 'quantia-hub' ); ?><br>
                    <span class="text-qh-300"><?php esc_html_e( 'Tu dinero sí.', 'quantia-hub' ); ?></span>
                </h1>

                <p class="mt-7 max-w-md text-lg leading-relaxed text-white/65">
                    <?php esc_html_e( 'El enlace puede estar roto o la página cambió de lugar. Todo lo demás sigue funcionando como siempre.', 'quantia-hub' ); ?>
                </p>

                <div class="mt-10 flex flex-wrap items-center gap-4">
                    <a href="<?php echo esc_url( $home_url ); ?>"
                        class="group inline-flex items-center gap-4 rounded-full bg-qh py-2 pl-7 pr-2 text-base font-semibold text-white transition-colors duration-200 ease-out hover:bg-qh-600 sm:text-lg">
                        <span class="whitespace-nowrap"><?php esc_html_e( 'Volver al inicio', 'quantia-hub' ); ?></span>
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-qh-700 transition-transform duration-200 ease-out group-hover:translate-x-0.5 sm:h-12 sm:w-12">
                            <?php echo quantia_icon( 'arrow-right', 'h-5 w-5' ); ?>
                        </span>
                    </a>

                    <a href="<?php echo esc_url( $whatsapp_url ); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        data-quantia-whatsapp
                        data-product="general"
                        data-location="support"
                        class="inline-flex items-center gap-2 whitespace-nowrap rounded-full px-5 py-3 text-sm font-semibold text-white/80 transition-colors hover:bg-white/10 hover:text-white">
                        <?php echo quantia_icon( 'message-circle', 'h-4 w-4' ); ?>
                        <?php esc_html_e( 'Avísanos por WhatsApp', 'quantia-hub' ); ?>
                    </a>
                </div>
            </div>

            <div data-pointer-layer
                data-pointer-x="36"
                data-pointer-y="26"
                data-pointer-rotate="-6"
                class="mx-auto w-full max-w-sm">
                <div class="rounded-[2rem] bg-white p-7 text-ink shadow-[0_40px_80px_-30px_rgba(0,0,0,0.6)]">
                    <div class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                            <?php echo quantia_icon( 'circle-alert', 'h-5 w-5' ); ?>
                        </span>
                        <div>
                            <p class="font-display text-lg font-bold tracking-[-0.02em]"><?php esc_html_e( 'Destino no encontrado', 'quantia-hub' ); ?></p>
                            <p class="text-sm text-ink/55"><?php esc_html_e( 'Hoy, ahora mismo', 'quantia-hub' ); ?></p>
                        </div>
                    </div>

                    <dl class="mt-7 divide-y divide-ink/10 border-y border-ink/10 text-sm">
                        <div class="flex items-baseline justify-between gap-4 py-3.5">
                            <dt class="text-ink/55"><?php esc_html_e( 'Dirección', 'quantia-hub' ); ?></dt>
                            <dd class="max-w-[60%] truncate font-mono text-[13px] font-semibold" title="<?php echo esc_attr( $current_path ); ?>">
                                <?php echo esc_html( $current_path ); ?>
                            </dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4 py-3.5">
                            <dt class="text-ink/55"><?php esc_html_e( 'Monto perdido', 'quantia-hub' ); ?></dt>
                            <dd class="font-display text-base font-bold text-qh-700">B/. 0.00</dd>
                        </div>
                        <div class="flex items-baseline justify-between gap-4 py-3.5">
                            <dt class="text-ink/55"><?php esc_html_e( 'Estado', 'quantia-hub' ); ?></dt>
                            <dd class="font-semibold"><?php esc_html_e( 'Tu cuenta está intacta', 'quantia-hub' ); ?></dd>
                        </div>
                    </dl>

                    <p class="mt-5 text-xs leading-relaxed text-ink/50">
                        <?php esc_html_e( 'Este comprobante es una broma, no se movió ningún fondo.', 'quantia-hub' ); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section aria-labelledby="nf-shortcuts" class="w-full py-14 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-6xl px-5 sm:px-6">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <h2 id="nf-shortcuts"
                    class="font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-5xl">
                    <?php esc_html_e( 'Quizás buscabas', 'quantia-hub' ); ?><span class="text-qh"><?php esc_html_e( ' esto.', 'quantia-hub' ); ?></span>
                </h2>

                <a href="<?php echo esc_url( home_url( '/soporte/' ) ); ?>"
                    class="group inline-flex items-center gap-4 rounded-full bg-ink py-2 pl-7 pr-2 text-base font-semibold text-white shadow-[0_18px_40px_-18px_rgba(11,31,23,0.6)] transition-colors duration-200 ease-out hover:bg-qh-700 sm:text-lg">
                    <span class="whitespace-nowrap"><?php esc_html_e( 'Ir al centro de ayuda', 'quantia-hub' ); ?></span>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-qh text-white transition-transform duration-200 ease-out group-hover:rotate-45 sm:h-12 sm:w-12">
                        <?php echo quantia_icon( 'arrow-up-right', 'h-5 w-5' ); ?>
                    </span>
                </a>
            </div>

            <ul class="mt-12 grid grid-cols-1 border-t border-ink/10 md:grid-cols-2 md:gap-x-12">
                <?php foreach ( $shortcuts as $item ) : ?>
                    <li class="border-b border-ink/10">
                        <a href="<?php echo esc_url( $item['url'] ); ?>"
                            class="group flex items-center justify-between gap-6 py-6">
                            <span>
                                <span class="block font-display text-2xl font-bold tracking-[-0.025em] text-ink transition-colors group-hover:text-qh-700">
                                    <?php echo esc_html( $item['label'] ); ?>
                                </span>
                                <?php if ( ! empty( $item['description'] ) ) : ?>
                                    <span class="mt-1 block text-sm text-ink/55">
                                        <?php echo esc_html( $item['description'] ); ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <span class="shrink-0 text-ink/30 transition-[transform,color] duration-200 ease-out group-hover:translate-x-1 group-hover:text-qh">
                                <?php echo quantia_icon( 'arrow-right', 'h-5 w-5' ); ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
</main>

<?php get_footer(); ?>