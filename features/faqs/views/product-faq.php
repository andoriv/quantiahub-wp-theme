<?php
defined( 'ABSPATH' ) || exit;

$product      = $args['product'] ?? array();
$product_slug = $product['slug'] ?? '';

$product_faqs = quantia_get_faqs( $product_slug );

if ( empty( $product_faqs ) ) {
    return;
}
?>

<section class="w-full bg-mist py-14 md:py-20" data-product-faq>
    <div class="mx-auto grid max-w-6xl grid-cols-1 gap-10 px-5 sm:px-6 lg:grid-cols-[0.8fr_1.6fr]">

        <div>
            <h2 class="font-display text-2xl font-extrabold tracking-[-0.02em] text-ink sm:text-3xl">
                <?php esc_html_e( 'Preguntas frecuentes', 'quantia-hub' ); ?>
            </h2>
            <a href="<?php echo esc_url( home_url( '/soporte/' ) ); ?>"
               class="mt-4 inline-block text-sm font-semibold text-qh transition-colors hover:text-qh-700">
                <?php esc_html_e( 'Ver todas en Soporte', 'quantia-hub' ); ?>
            </a>
        </div>

        <ul class="divide-y divide-ink/10 border-y border-ink/10">
            <?php foreach ( $product_faqs as $faq ) : ?>
                <li class="py-4">
                    <button type="button"
                            data-faq-toggle
                            aria-expanded="false"
                            class="flex w-full items-start justify-between gap-4 text-left">
                        <span class="text-sm font-semibold text-ink">
                            <?php echo esc_html( $faq['question'] ); ?>
                        </span>
                        <span data-faq-chevron class="mt-0.5 shrink-0 text-ink/50 transition-transform duration-200">
                            <?php echo quantia_icon( 'chevron-down-full', 'h-4 w-4' ); ?>
                        </span>
                    </button>
                    <div data-faq-answer hidden class="overflow-hidden">
                        <p class="mt-3 pr-6 text-sm leading-relaxed text-ink/65">
                            <?php echo esc_html( $faq['answer'] ); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>