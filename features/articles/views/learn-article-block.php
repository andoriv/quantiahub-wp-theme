<?php
defined( 'ABSPATH' ) || exit;

$article = $args['article'] ?? array();
$index   = (int) ( $args['index'] ?? 0 );

if ( empty( $article ) ) {
    return;
}

$anchor      = $article['id'] ?? 'concept-' . $index;
$eyebrow     = $article['eyebrow'] ?? '';
$title       = $article['title'] ?? '';
$description = $article['description'] ?? '';
$visual      = $article['visual'] ?? 'riesgo';
$points      = $article['points'] ?? array();

$reversed = ( $index % 2 === 1 );
?>

<article id="<?php echo esc_attr( $anchor ); ?>"
    class="grid scroll-mt-28 grid-cols-1 items-center gap-12 border-t border-ink/10 py-20 lg:grid-cols-2 lg:gap-20 lg:py-28">

    <div class="<?php echo $reversed ? 'lg:order-last' : ''; ?>">
        <div data-parallax
             data-parallax-speed="<?php echo $reversed ? '0.3' : '0.2'; ?>"
             data-parallax-rotate="<?php echo $reversed ? '-2' : '2'; ?>">
            <div class="flex items-center justify-center rounded-[2rem] bg-mist h-[300px] p-10 sm:h-[400px] sm:p-14" aria-hidden="true">
                <?php get_template_part( 'features/articles/views/article-visual', null, array( 'id' => $visual ) ); ?>
            </div>
        </div>
    </div>

    <div>
        <?php if ( $eyebrow ) : ?>
            <p class="text-sm font-semibold text-qh-700"><?php echo esc_html( $eyebrow ); ?></p>
        <?php endif; ?>

        <h2 class="mt-3 font-display text-4xl font-extrabold leading-[1.04] tracking-[-0.035em] text-ink sm:text-5xl">
            <?php echo esc_html( $title ); ?>
        </h2>

        <?php if ( $description ) : ?>
            <p class="mt-6 max-w-lg text-lg leading-relaxed text-ink/70">
                <?php echo esc_html( $description ); ?>
            </p>
        <?php endif; ?>

        <?php if ( ! empty( $points ) ) : ?>
            <ul class="mt-8 space-y-4">
                <?php foreach ( $points as $point ) : ?>
                    <li data-slide data-slide-from="<?php echo $reversed ? '-50' : '50'; ?>">
                        <span class="flex gap-4 text-base leading-relaxed text-ink/75">
                            <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-qh-100 text-qh-700">
                                <?php echo quantia_icon( 'check', 'h-3.5 w-3.5' ); ?>
                            </span>
                            <span><?php echo esc_html( $point ); ?></span>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</article>