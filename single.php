<?php
/**
 * Default single template (placeholder; article/producto/legal have their own in later blocks).
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

get_header(); ?>

<article class="mx-auto max-w-3xl px-5 py-24 sm:px-6">
    <?php while ( have_posts() ) : the_post(); ?>
        <h1 class="font-display text-4xl font-extrabold tracking-[-0.03em] text-ink sm:text-5xl">
            <?php the_title(); ?>
        </h1>
        <div class="mt-8 text-lg leading-relaxed text-ink/75"><?php the_content(); ?></div>
    <?php endwhile; ?>
</article>

<?php get_footer(); ?>