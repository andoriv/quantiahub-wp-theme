<?php
/**
 * Renderiza un documento legal (con acordeón si tiene más de 6 secciones).
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$doc    = $args['doc'] ?? get_query_var( 'legal_doc' );
$number = (int) ( $args['number'] ?? get_query_var( 'legal_number', 0 ) );

if ( empty( $doc ) || ! is_array( $doc ) ) {
    return;
}

$sections     = $doc['sections'] ?? array();
$collapsible  = count( $sections ) > 6;
$has_sections = ! empty( $sections );

// Sin línea superior en ningún documento. Solo mantenemos el padding.
$top_classes = ( 1 === $number ) ? 'pt-0' : 'pt-16 md:pt-24';

$plus_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>';
?>
<!-- IMPORTANTE: scroll-mt-32 md:scroll-mt-40 compensa el header fijo para que no tape el título -->
<section id="<?php echo esc_attr( $doc['slug'] ); ?>"
    aria-labelledby="<?php echo esc_attr( $doc['slug'] . '-title' ); ?>"
    data-legal-doc
    class="scroll-mt-32 md:scroll-mt-40 <?php echo esc_attr( $top_classes ); ?> pb-16 md:pb-24">

    <!-- Título del documento -->
    <div>
        <p class="font-display text-sm font-bold text-qh-700">
            <?php echo esc_html( sprintf( '%02d', $number ) ); ?> · <?php esc_html_e( 'Documento', 'quantia-hub' ); ?>
        </p>
        <h2 id="<?php echo esc_attr( $doc['slug'] . '-title' ); ?>"
            class="mt-2 font-display text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.1] tracking-[-0.02em] text-ink">
            <?php echo esc_html( $doc['title'] ); ?>
        </h2>
    </div>

    <?php if ( ! empty( $doc['summary'] ) ) : ?>
        <p class="mt-4 max-w-xl text-base leading-relaxed text-ink/65">
            <?php echo esc_html( $doc['summary'] ); ?>
        </p>
    <?php endif; ?>

    <div class="mt-5 flex flex-wrap items-center gap-x-6 gap-y-2">
        <?php if ( ! empty( $doc['updated'] ) ) : ?>
            <p class="text-sm text-ink/45">
                <?php
                printf(
                    /* translators: %s: fecha de última actualización */
                    esc_html__( 'Última actualización: %s', 'quantia-hub' ),
                    esc_html( $doc['updated'] )
                );
                ?>
            </p>
        <?php endif; ?>
        <?php if ( $collapsible ) : ?>
            <button type="button"
                data-legal-toggle-all
                class="text-sm font-semibold text-qh-700 underline-offset-4 transition-colors hover:text-ink hover:underline">
                <?php esc_html_e( 'Expandir todo', 'quantia-hub' ); ?>
            </button>
        <?php endif; ?>
    </div>

    <?php if ( $has_sections ) : ?>
        <div class="<?php echo $collapsible ? 'mt-10 border-t border-ink/10' : 'mt-12 space-y-12'; ?>">
            <?php foreach ( $sections as $index => $section ) :
                $panel_id = $doc['slug'] . '-clausula-' . $index;
                $is_open  = false; // TODO CERRADO POR DEFECTO
            ?>
                <?php if ( ! $collapsible ) : ?>
                    <div>
                        <h3 class="mb-4 font-display text-xl font-bold tracking-[-0.02em] text-ink">
                            <?php echo esc_html( $section['heading'] ); ?>
                        </h3>
                        <?php 
                        set_query_var( 'legal_blocks', $section['blocks'] );
                        get_template_part( 'template-parts/legal/blocks' ); 
                        ?>
                    </div>
                <?php else : ?>
                    <div class="border-b border-ink/10" data-legal-section id="<?php echo esc_attr( $panel_id ); ?>">
                        <h3>
                            <button type="button"
                                data-legal-section-toggle
                                aria-expanded="false"
                                aria-controls="<?php echo esc_attr( $panel_id ); ?>"
                                class="group flex w-full items-center justify-between gap-6 py-5 text-left">
                                <span class="font-display text-lg font-bold tracking-[-0.01em] text-ink transition-colors group-hover:text-qh-700">
                                    <?php echo esc_html( $section['heading'] ); ?>
                                </span>
                                <span data-legal-section-plus
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full transition-all duration-200 ease-out bg-mist text-ink">
                                    <?php echo $plus_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                            </button>
                        </h3>
                        <div data-legal-section-panel class="pb-8 pr-2 md:pr-14" hidden>
                            <?php 
                            set_query_var( 'legal_blocks', $section['blocks'] );
                            get_template_part( 'template-parts/legal/blocks' ); 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>