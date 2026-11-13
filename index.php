<?php
/**
 * Fallback template.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div class="mx-auto max-w-3xl px-5 py-24 sm:px-6">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class( 'border-b border-ink/10 py-10 first:border-t' ); ?>>
                <h2 class="font-display text-2xl font-bold">
                    <a href="<?php the_permalink(); ?>" class="hover:text-qh-700"><?php the_title(); ?></a>
                </h2>
                <div class="mt-3 text-ink/70"><?php the_excerpt(); ?></div>
            </article>
        <?php endwhile; ?>

        <div class="mt-10"><?php the_posts_pagination(); ?></div>
    <?php else : ?>
        <p class="text-ink/70"><?php esc_html_e( 'No hay contenido todavía.', 'quantia-hub' ); ?></p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>