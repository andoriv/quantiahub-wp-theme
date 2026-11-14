<?php
/**
 * Plantilla de un documento legal individual.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

get_header();

$post_obj = get_queried_object();

// Si no es un documento legal válido, devolvemos 404
if ( ! $post_obj instanceof WP_Post || 'documento_legal' !== $post_obj->post_type ) {
    global $wp_query;
    if ( $wp_query ) {
        $wp_query->set_404();
    }
    status_header( 404 );
    get_template_part( '404' );
    get_footer();
    return;
}

// Obtenemos los datos normalizados del documento
$doc = quantia_get_legal_document( $post_obj );
?>

<main class="w-full bg-white">

    <!-- Hero del documento individual -->
    <section class="relative w-full overflow-hidden bg-ink pb-16 pt-28 text-white sm:pb-20 md:pb-28 md:pt-36">
        <div class="mx-auto max-w-6xl px-5 sm:px-6">
            <nav aria-label="<?php esc_attr_e( 'Ruta', 'quantia-hub' ); ?>" class="text-xs text-white/50">
                <a href="<?php echo esc_url( home_url( '/legal/' ) ); ?>" class="transition-colors hover:text-white">
                    <?php esc_html_e( 'Centro legal', 'quantia-hub' ); ?>
                </a>
                <span class="mx-2" aria-hidden="true">/</span>
                <span class="text-white/80"><?php echo esc_html( $doc['title'] ); ?></span>
            </nav>

            <h1 class="mt-4 font-display text-[clamp(1.875rem,1.1rem+3.3vw,4.75rem)] font-extrabold leading-[1] tracking-[-0.045em]">
                <?php echo esc_html( $doc['title'] ); ?>
            </h1>

            <?php if ( ! empty( $doc['summary'] ) ) : ?>
                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/70">
                    <?php echo esc_html( $doc['summary'] ); ?>
                </p>
            <?php endif; ?>

            <?php if ( ! empty( $doc['updated'] ) ) : ?>
                <p class="mt-4 text-sm text-white/50">
                    <?php
                    printf(
                        /* translators: %s: fecha de última actualización */
                        esc_html__( 'Última actualización: %s', 'quantia-hub' ),
                        esc_html( $doc['updated'] )
                    );
                    ?>
                </p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contenido del documento -->
    <div class="mx-auto max-w-3xl px-5 py-16 sm:px-6 md:py-24">
        <?php if ( ! empty( $doc['sections'] ) ) : ?>
            <div class="space-y-12">
                <?php foreach ( $doc['sections'] as $section ) : ?>
                    <div>
                        <h2 class="mb-4 font-display text-2xl font-extrabold tracking-[-0.02em] text-ink sm:text-3xl">
                            <?php echo esc_html( $section['heading'] ); ?>
                        </h2>
                        <?php 
                        // Le pasamos los bloques al template part usando set_query_var
                        set_query_var( 'legal_blocks', $section['blocks'] );
                        get_template_part( 'template-parts/legal/blocks' ); 
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="text-base leading-relaxed text-ink/60">
                <?php esc_html_e( 'Este documento no tiene contenido todavía.', 'quantia-hub' ); ?>
            </p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>