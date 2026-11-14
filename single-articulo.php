<?php
defined( 'ABSPATH' ) || exit;

get_header();

$post_obj = get_queried_object();

if ( ! $post_obj instanceof WP_Post || 'articulo' !== $post_obj->post_type ) {
    global $wp_query;
    if ( $wp_query ) {
        $wp_query->set_404();
    }
    status_header( 404 );
    get_template_part( '404' );
    get_footer();
    return;
}

$article = quantia_get_article_from_post( $post_obj );

$word_count = quantia_count_words_in_blocks( $article['body'] );
$minutes    = max( 1, (int) round( $word_count / 200 ) );

$related      = array();
$all_articles = quantia_get_all_articles( 20 );
foreach ( $all_articles as $item ) {
    if ( $item['slug'] === $article['slug'] ) {
        continue;
    }
    $shared          = count( array_intersect( $item['categories'], $article['categories'] ) );
    $item['_shared'] = $shared;
    $related[]       = $item;
}
usort( $related, function ( $a, $b ) {
    return ( $b['_shared'] ?? 0 ) - ( $a['_shared'] ?? 0 );
} );
$related = array_slice( $related, 0, 3 );
?>

<main class="w-full bg-white">

    <div data-reading-progress aria-hidden="true" class="fixed inset-x-0 top-0 z-50 h-1 origin-left bg-qh" style="transform:scaleX(0)"></div>

    <header class="relative w-full overflow-hidden bg-ink pb-16 pt-28 text-white sm:pb-20 md:pb-28 md:pt-36">
        <div class="mx-auto grid max-w-6xl grid-cols-1 items-end gap-12 px-5 sm:px-6 lg:grid-cols-[minmax(0,7fr)_minmax(0,4fr)]">
            <div data-parallax data-parallax-speed="0.12">
                <a href="<?php echo esc_url( home_url( '/aprende/#articulos' ) ); ?>"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-white/60 transition-colors hover:text-white">
                    <?php echo quantia_icon( 'arrow-left', 'h-4 w-4' ); ?>
                    <?php esc_html_e( 'Artículos', 'quantia-hub' ); ?>
                </a>
                <p class="mt-8 text-sm font-semibold text-qh-300">
                    <?php echo esc_html( implode( ' · ', $article['categories'] ) ); ?> ·
                    <time datetime="<?php echo esc_attr( $article['iso'] ); ?>"><?php echo esc_html( $article['date'] ); ?></time> ·
                    <?php
                    printf(
                        /* translators: %d: minutos de lectura */
                        esc_html__( '%d min de lectura', 'quantia-hub' ),
                        (int) $minutes
                    );
                    ?>
                </p>
                <h1 class="mt-4 font-display text-[clamp(1.875rem,1.1rem+3.3vw,4.75rem)] font-extrabold leading-[1] tracking-[-0.045em]">
                    <?php echo esc_html( $article['title'] ); ?>
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/70">
                    <?php echo esc_html( $article['excerpt'] ); ?>
                </p>
                <div class="mt-8">
                    <?php get_template_part( 'features/articles/views/share-buttons', null, array(
                        'title' => $article['title'],
                        'dark'  => true,
                    ) ); ?>
                </div>
            </div>

            <div data-parallax data-parallax-speed="0.3" data-parallax-rotate="3" class="hidden lg:block">
                <div class="flex h-[300px] items-center justify-center rounded-[2rem] bg-mist p-10">
                    <?php get_template_part( 'features/articles/views/article-visual', null, array( 'id' => $article['visual'] ) ); ?>
                </div>
            </div>
        </div>
    </header>

    <div data-reading-body class="mx-auto max-w-3xl px-5 py-16 sm:px-6 md:py-24">
        <?php get_template_part( 'features/articles/views/article-body', null, array( 'blocks' => $article['body'] ) ); ?>

        <div class="mt-16 border-t border-ink/10 pt-8">
            <?php get_template_part( 'features/articles/views/share-buttons', null, array(
                'title' => $article['title'],
            ) ); ?>
        </div>
    </div>

    <?php if ( ! empty( $related ) ) : ?>
        <section aria-labelledby="relacionados" class="w-full overflow-hidden bg-mist py-14 sm:py-20 lg:py-28">
            <div class="mx-auto max-w-6xl px-5 sm:px-6">
                <h2 id="relacionados" class="font-display text-3xl font-extrabold tracking-[-0.03em] text-ink sm:text-4xl">
                    <?php esc_html_e( 'Sigue aprendiendo', 'quantia-hub' ); ?>
                </h2>
                <ul class="mt-10 border-t-2 border-ink">
                    <?php foreach ( $related as $i => $item ) : ?>
                        <li class="border-b border-ink/10">
                            <div data-slide data-slide-from="<?php echo $i % 2 === 0 ? '80' : '-80'; ?>">
                                <a href="<?php echo esc_url( get_permalink_by_slug_safe( $item['slug'] ) ); ?>"
                                    class="group flex items-center justify-between gap-6 py-7">
                                    <span>
                                        <span class="block text-sm font-semibold text-ink/50"><?php echo esc_html( implode( ' · ', $item['categories'] ) ); ?></span>
                                        <span class="mt-1 block font-display text-2xl font-bold leading-tight tracking-[-0.025em] text-ink transition-colors group-hover:text-qh-700 sm:text-3xl">
                                            <?php echo esc_html( $item['title'] ); ?>
                                        </span>
                                    </span>
                                    <span class="shrink-0 text-ink/30 transition-[transform,color] duration-200 ease-out group-hover:translate-x-1 group-hover:text-qh">
                                        <?php echo quantia_icon( 'arrow-right', 'h-6 w-6' ); ?>
                                    </span>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    <?php endif; ?>

    <div class="bg-mist" data-reveal-frame>
        <section class="relative overflow-hidden bg-qh-700 py-14 text-white sm:py-20 lg:py-28">
            <div data-marquee data-marquee-distance="30" aria-hidden="true" class="pointer-events-none select-none overflow-hidden">
                <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(3.5rem,9vw,8rem)] text-white/10">
                    <?php esc_html_e( 'Desde $30 · Desde $100 · Empieza hoy · Desde $30 · Desde $100 · Empieza hoy ·', 'quantia-hub' ); ?>
                </p>
            </div>
            <div class="mx-auto -mt-4 max-w-6xl px-5 sm:px-6">
                <div data-parallax data-parallax-speed="0.2">
                    <h2 class="font-display text-5xl font-extrabold leading-[0.96] tracking-[-0.045em] sm:text-6xl">
                        <?php esc_html_e( 'Ponlo en práctica', 'quantia-hub' ); ?><br><?php esc_html_e( 'desde la app.', 'quantia-hub' ); ?>
                    </h2>
                    <p class="mt-6 max-w-md text-base leading-relaxed text-white/80">
                        <?php esc_html_e( 'Abre tu cuenta Quantia Hub y empieza con una alcancía, un portafolio o tu plan de jubilación.', 'quantia-hub' ); ?>
                    </p>
                    <div class="mt-9">
                        <?php get_template_part( 'template-parts/components/download-app-button', null, array(
                            'variant'  => 'light',
                            'location' => 'about',
                            'class'    => '!px-7 !py-3.5 !text-base',
                        ) ); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php get_footer(); ?>