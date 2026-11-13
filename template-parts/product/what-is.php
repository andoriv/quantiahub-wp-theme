<?php
defined( 'ABSPATH' ) || exit;

$p = $args['product'];
?>

<section class="w-full bg-white py-14 md:py-24">
    <div class="mx-auto grid max-w-6xl grid-cols-1 items-center gap-10 px-5 sm:px-6 lg:grid-cols-[1fr_1.1fr] lg:gap-16">

        <div class="relative" data-slide data-slide-from="-40">
            <div class="relative aspect-[4/5] w-full overflow-hidden rounded-3xl bg-mist sm:aspect-[4/3] lg:aspect-[4/5]">
                <?php if ( $p['detail_image'] ) : ?>
                    <img src="<?php echo esc_url( $p['detail_image'] ); ?>" alt=""
                        class="h-full w-full object-cover" loading="lazy" decoding="async">
                <?php endif; ?>
            </div>

            <div class="relative -mt-16 mx-4 rounded-2xl bg-white p-5 shadow-[0_20px_50px_-20px_rgba(11,31,23,0.35)] sm:mx-8 lg:absolute lg:-right-8 lg:bottom-8 lg:mx-0 lg:mt-0 lg:w-[300px]">
                <h3 class="font-display text-base font-semibold text-ink">
                    <?php esc_html_e( '¿A quién le sirve?', 'quantia-hub' ); ?>
                </h3>
                <ul class="mt-3 space-y-2.5">
                    <?php foreach ( $p['audience'] as $item ) : ?>
                        <li class="flex gap-2.5 text-sm leading-snug text-ink/70">
                            <span class="mt-0.5 text-qh"><?php echo quantia_icon( 'check', 'h-4 w-4' ); ?></span>
                            <?php echo esc_html( $item ); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="lg:pl-6">
            <h2 class="font-display text-3xl font-extrabold tracking-[-0.03em] text-ink sm:text-4xl">
                <?php echo esc_html( $p['what_is_title'] ); ?>
            </h2>
            <p class="mt-5 max-w-xl text-base leading-relaxed text-ink/75">
                <?php echo esc_html( $p['what_is'][0] ); ?>
            </p>
            <?php foreach ( array_slice( $p['what_is'], 1 ) as $paragraph ) : ?>
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-ink/60">
                    <?php echo esc_html( $paragraph ); ?>
                </p>
            <?php endforeach; ?>
        </div>
    </div>
</section>