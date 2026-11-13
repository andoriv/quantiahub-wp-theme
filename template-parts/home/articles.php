<?php
defined( 'ABSPATH' ) || exit;

$articles = quantia_get_all_articles( 6 );

if ( empty( $articles ) ) {
    return;
}
?>

<section aria-labelledby="articulos-title" class="w-full bg-white py-20 md:py-32">
    <div class="mx-auto max-w-6xl px-5 sm:px-6">
        <h2 id="articulos-title" class="font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-5xl">
            <?php esc_html_e( 'Finance', 'quantia-hub' ); ?><br>
            <span class="text-qh"><?php esc_html_e( 'in plain words.', 'quantia-hub' ); ?></span>
        </h2>
        <p class="mt-6 max-w-md text-sm leading-relaxed text-ink/60">
            <?php esc_html_e( 'Our readings to decide better.', 'quantia-hub' ); ?>
        </p>

        <ul class="mt-10 flex snap-x snap-mandatory gap-6 overflow-x-auto pb-4">
            <?php foreach ( $articles as $a ) : ?>
                <?php $url = get_permalink_by_slug_safe( $a['slug'] ); ?>
                <li class="w-[80vw] max-w-[420px] shrink-0 snap-start">
                    <a href="<?php echo esc_url( $url ); ?>" class="group block">
                        <div class="flex h-44 items-center justify-center rounded-3xl bg-mist p-6">
                            <?php get_template_part( 'features/articles/views/article-visual', null, array( 'id' => $a['visual'] ) ); ?>
                        </div>
                        <span class="mt-5 block text-sm font-semibold text-qh-700">
                            <?php echo esc_html( implode( ' · ', $a['categories'] ) ); ?>
                        </span>
                        <span class="mt-1 block font-display text-2xl font-bold leading-tight tracking-[-0.02em] text-ink transition-colors group-hover:text-qh-700">
                            <?php echo esc_html( $a['title'] ); ?>
                        </span>
                        <span class="mt-2 block text-sm leading-relaxed text-ink/60"><?php echo esc_html( $a['excerpt'] ); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <a href="<?php echo esc_url( home_url( '/aprende/' ) ); ?>"
            class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-qh-700 transition-colors hover:text-ink">
            <?php esc_html_e( 'See all in Learn', 'quantia-hub' ); ?>
            <?php echo quantia_icon( 'arrow-right', 'h-4 w-4' ); ?>
        </a>
    </div>
</section>