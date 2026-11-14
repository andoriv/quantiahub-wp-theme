<?php
/**
 * Sección de Preguntas Frecuentes para la página de Soporte.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$all_faqs = quantia_get_faqs( '' );

if ( empty( $all_faqs ) ) {
    return;
}

$categories = quantia_group_faqs_by_category( $all_faqs );

$faqs_for_js = array_map(
    static function ( $f ) {
        return array(
            'question'      => $f['question'],
            'answer'        => $f['answer'],
            'category'      => $f['category'],
            'category_name' => $f['category_name'],
        );
    },
    $all_faqs
);
?>

<!-- overflow-x-clip evita que la página haga scroll horizontal hacia los lados -->
<section id="respuestas" class="w-full scroll-mt-20 overflow-x-clip bg-white py-12 sm:py-16 lg:py-24"
    data-support-faq data-support-all='<?php echo esc_attr( wp_json_encode( $faqs_for_js ) ); ?>'>

    <div class="mx-auto w-full max-w-4xl px-5 sm:px-6">
        
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <h2 data-support-faq-title class="font-display text-3xl font-extrabold tracking-[-0.03em] text-ink sm:text-4xl">
                <?php esc_html_e( 'Preguntas frecuentes', 'quantia-hub' ); ?>
            </h2>
            <p data-support-faq-count class="text-sm text-ink/50" aria-live="polite">
                <?php
                printf(
                    /* translators: %d: total de respuestas */
                    esc_html__( '%d respuestas', 'quantia-hub' ),
                    (int) count( $all_faqs )
                );
                ?>
            </p>
        </div>

        <!-- Margen negativo solo en móvil para que el scroll toque los bordes -->
        <div class="qh-faq-tabs-wrap -mx-5 mt-6 sm:mx-0"
             data-faq-tabs-wrap
             data-at-start="true"
             data-at-end="true">

            <!-- Flecha izquierda: nace oculta. El JS la muestra si hay scroll hacia la izquierda -->
            <button type="button"
                    data-faq-tabs-prev
                    aria-label="<?php esc_attr_e( 'Ver temas anteriores', 'quantia-hub' ); ?>"
                    hidden
                    class="qh-faq-tabs-nav qh-faq-tabs-nav--prev">
                <?php echo quantia_icon( 'arrow-left', 'h-4 w-4' ); ?>
            </button>

            <!-- Contenedor de los botones con padding normal (px-5 sm:px-0) -->
            <div role="tablist"
                 aria-label="<?php esc_attr_e( 'Filtrar por tema', 'quantia-hub' ); ?>"
                 data-faq-tabs
                 class="qh-faq-tabs flex gap-2 overflow-x-auto px-5 pb-1 sm:px-0">

                <button type="button"
                        data-support-faq-tab="todos"
                        aria-selected="true"
                        class="whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition-colors bg-ink text-white">
                    <?php esc_html_e( 'Todos', 'quantia-hub' ); ?>
                </button>

                <?php foreach ( $categories as $cat ) : ?>
                    <button type="button"
                            data-support-faq-tab="<?php echo esc_attr( $cat['id'] ); ?>"
                            aria-selected="false"
                            class="whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition-colors bg-mist text-ink/60 hover:text-ink">
                        <?php echo esc_html( $cat['label'] ); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Flecha derecha: nace oculta. El JS la muestra si hay más contenido a la derecha -->
            <button type="button"
                    data-faq-tabs-next
                    aria-label="<?php esc_attr_e( 'Ver más temas', 'quantia-hub' ); ?>"
                    hidden
                    class="qh-faq-tabs-nav qh-faq-tabs-nav--next">
                <?php echo quantia_icon( 'arrow-right', 'h-4 w-4' ); ?>
            </button>
        </div>

        <ul data-support-faq-list class="mt-8 border-t border-ink/10"></ul>
        
    </div>
</section>