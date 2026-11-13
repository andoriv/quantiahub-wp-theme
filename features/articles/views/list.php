<?php
defined( 'ABSPATH' ) || exit;

$articles = quantia_get_all_articles( 20 );

if ( empty( $articles ) ) {
    return;
}

$categories = array(
    __( 'Todos', 'quantia-hub' ),
    __( 'Inversión', 'quantia-hub' ),
    __( 'Ahorros', 'quantia-hub' ),
    __( 'Jubilación', 'quantia-hub' ),
);
?>

<div data-article-list>
    <div role="tablist" aria-label="<?php esc_attr_e( 'Filtrar artículos por categoría', 'quantia-hub' ); ?>" class="flex flex-wrap gap-2">
        <?php foreach ( $categories as $i => $cat ) : ?>
            <button type="button"
                role="tab"
                aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
                data-article-filter="<?php echo esc_attr( $cat ); ?>"
                class="rounded-full px-4 py-2 text-sm font-semibold transition-colors <?php echo 0 === $i ? 'bg-ink text-white' : 'bg-mist text-ink/60 hover:text-ink'; ?>">
                <?php echo esc_html( $cat ); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <div data-article-list-items>
        <?php foreach ( $articles as $index => $a ) : ?>
            <?php $url = get_permalink_by_slug_safe( $a['slug'] ); ?>
            <article data-article-item
                data-article-categories="<?php echo esc_attr( implode( ',', $a['categories'] ) ); ?>"
                class="<?php echo 0 === $index ? 'mt-12' : 'border-t-2 border-ink'; ?>">

                <?php if ( 0 === $index ) : ?>
                    <a href="<?php echo esc_url( $url ); ?>"
                        class="group grid grid-cols-1 items-center gap-10 lg:grid-cols-[minmax(0,7fr)_minmax(0,5fr)] lg:gap-16">
                        <div data-parallax data-parallax-speed="0.25" data-parallax-rotate="1.5">
                            <div class="flex h-[280px] items-center justify-center rounded-[2rem] bg-mist p-10 transition-colors group-hover:bg-qh-50 sm:h-[380px] sm:p-14">
                                <?php get_template_part( 'features/articles/views/article-visual', null, array( 'id' => $a['visual'] ) ); ?>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-qh-700">
                                <?php echo esc_html( implode( ' · ', $a['categories'] ) ); ?> ·
                                <time datetime="<?php echo esc_attr( $a['iso'] ); ?>"><?php echo esc_html( $a['date'] ); ?></time>
                            </p>
                            <h3 class="mt-3 font-display text-4xl font-extrabold leading-[1.04] tracking-[-0.035em] text-ink transition-colors group-hover:text-qh-700 sm:text-5xl">
                                <?php echo esc_html( $a['title'] ); ?>
                            </h3>
                            <p class="mt-5 text-lg leading-relaxed text-ink/65"><?php echo esc_html( $a['excerpt'] ); ?></p>
                            <span class="mt-8 inline-flex items-center gap-2 font-semibold text-ink">
                                <?php esc_html_e( 'Leer artículo', 'quantia-hub' ); ?>
                                <?php echo quantia_icon( 'arrow-right', 'h-4 w-4' ); ?>
                            </span>
                        </div>
                    </a>
                <?php else : ?>
                    <div data-slide data-slide-from="<?php echo $index % 2 === 0 ? '90' : '-90'; ?>">
                        <a href="<?php echo esc_url( $url ); ?>"
                            class="group grid grid-cols-1 items-center gap-6 py-8 md:grid-cols-[minmax(0,1fr)_200px] md:gap-12">
                            <div>
                                <p class="text-sm font-semibold text-ink/50">
                                    <?php echo esc_html( implode( ' · ', $a['categories'] ) ); ?> ·
                                    <time datetime="<?php echo esc_attr( $a['iso'] ); ?>"><?php echo esc_html( $a['date'] ); ?></time>
                                </p>
                                <h3 class="mt-2 font-display text-2xl font-bold leading-tight tracking-[-0.025em] text-ink transition-colors group-hover:text-qh-700 sm:text-3xl">
                                    <?php echo esc_html( $a['title'] ); ?>
                                </h3>
                                <p class="mt-2 max-w-2xl text-base leading-relaxed text-ink/60"><?php echo esc_html( $a['excerpt'] ); ?></p>
                            </div>
                            <div data-parallax data-parallax-speed="<?php echo $index % 2 === 0 ? '0.3' : '0.15'; ?>" class="hidden md:block">
                                <div class="flex h-32 items-center justify-center rounded-2xl bg-mist p-5 transition-colors group-hover:bg-qh-50">
                                    <?php get_template_part( 'features/articles/views/article-visual', null, array( 'id' => $a['visual'] ) ); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</div>