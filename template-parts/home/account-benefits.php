<?php
defined( 'ABSPATH' ) || exit;

$benefits = quantia_get_benefits();

if ( empty( $benefits ) ) {
    return;
}

$marquee_text = sprintf(
    /* translators: banda de marquesina con beneficios de productos */
    __( '%1$s · %2$s · %3$s · %4$s · %5$s · %6$s · ', 'quantia-hub' ),
    __( '4% anual', 'quantia-hub' ),
    __( 'Kuara 24/7', 'quantia-hub' ),
    __( 'Desde B/. 100', 'quantia-hub' ),
    __( 'Alcancías', 'quantia-hub' ),
    __( 'Jubilación', 'quantia-hub' ),
    __( 'Visa Débito', 'quantia-hub' )
);
$marquee_x3 = trim( str_repeat( $marquee_text, 3 ) );
?>

<section id="productos" aria-labelledby="productos-title"
    class="relative w-full scroll-mt-20 overflow-x-clip bg-white py-20 md:py-32">

    <div data-marquee data-marquee-distance="30" aria-hidden="true"
        class="pointer-events-none select-none overflow-hidden">
        <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(4rem,10vw,9rem)] text-ink/[0.04]">
            <?php echo esc_html( $marquee_x3 ); ?>
        </p>
    </div>

    <div class="mx-auto -mt-10 max-w-6xl px-5 sm:px-6">
        <div data-parallax data-parallax-speed="0.12">
            <p class="text-sm font-semibold text-qh-700"><?php esc_html_e( 'Nuestros productos', 'quantia-hub' ); ?></p>
            <h2 id="productos-title"
                class="mt-4 font-display text-[clamp(2.5rem,6vw,4.75rem)] font-extrabold leading-[0.98] tracking-[-0.045em] text-ink">
                <?php esc_html_e( 'Todo tu dinero,', 'quantia-hub' ); ?><br>
                <span class="text-qh"><?php esc_html_e( 'en una sola app.', 'quantia-hub' ); ?></span>
            </h2>
        </div>

        <nav aria-label="<?php esc_attr_e( 'Ir a un producto', 'quantia-hub' ); ?>" class="mt-10 flex flex-wrap gap-2">
            <?php foreach ( $benefits as $b ) : ?>
                <a href="#beneficio-<?php echo esc_attr( $b['id'] ); ?>"
                    class="whitespace-nowrap rounded-full bg-mist px-4 py-2 text-sm font-semibold text-ink/65 transition-colors hover:bg-qh-100 hover:text-ink">
                    <?php echo esc_html( $b['product'] ); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="mt-14">
            <?php foreach ( $benefits as $i => $benefit ) :
                get_template_part( 'template-parts/home/benefit-row', null, array(
                    'benefit' => $benefit,
                    'index'   => $i,
                ) );
            endforeach; ?>
        </div>
    </div>
</section>