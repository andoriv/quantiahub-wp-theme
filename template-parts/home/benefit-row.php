<?php
defined( 'ABSPATH' ) || exit;

$b        = $args['benefit'];
$index    = (int) $args['index'];
$reversed = ( $index % 2 === 1 );
$funds    = ! empty( $b['funds_plan'] ) ? quantia_get_funds( $b['funds_plan'] ) : null;

$marquee_text = esc_html( $b['product'] ) . ' ·';
$marquee_x5   = trim( str_repeat( $marquee_text . ' ', 5 ) );
?>

<article id="beneficio-<?php echo esc_attr( $b['id'] ); ?>"
    class="grid grid-cols-1 items-center gap-10 border-t border-ink/10 py-14 first:border-t-0 first:pt-6 sm:gap-14 sm:py-20 lg:grid-cols-2 lg:gap-20 lg:py-28">

    <div class="<?php echo $reversed ? 'lg:order-last' : ''; ?>">
        <p class="text-sm font-semibold text-qh-700"><?php echo esc_html( $b['product'] ); ?></p>
        <h3 class="mt-4 font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-5xl">
            <?php echo esc_html( $b['title'] ); ?>
        </h3>

        <div data-parallax data-parallax-speed="0.25" class="mt-8 sm:mt-10">
            <p class="font-display font-extrabold leading-none tracking-[-0.05em] text-qh">
                <?php if ( ! empty( $b['stat_prefix'] ) ) : ?>
                    <span class="mr-2 text-[clamp(1.125rem,0.8rem+1.6vw,2.5rem)] tracking-[-0.03em]"><?php echo esc_html( $b['stat_prefix'] ); ?></span>
                <?php endif; ?>
                <span class="text-[clamp(2.75rem,1.4rem+6vw,7.5rem)]"><?php echo esc_html( $b['stat'] ); ?></span>
                <span class="mt-2 block text-base font-bold tracking-[-0.01em] text-ink/50 sm:text-xl"><?php echo esc_html( $b['stat_suffix'] ); ?></span>
            </p>
        </div>

        <p class="mt-8 max-w-md text-base leading-relaxed text-ink/65"><?php echo esc_html( $b['description'] ); ?></p>

        <ul class="mt-8 space-y-3">
            <?php foreach ( $b['points'] as $point ) : ?>
                <li>
                    <span class="flex items-start gap-3 text-[15px] font-medium text-ink/80">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-qh-100 text-qh-700">
                            <?php echo quantia_icon( 'check', 'h-3 w-3' ); ?>
                        </span>
                        <?php echo esc_html( $point ); ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ( ! empty( $funds ) ) : ?>
            <div class="mt-10 border-t border-ink/10 pt-6">
                <p class="text-sm font-semibold text-ink/45"><?php esc_html_e( 'Los fondos detrás', 'quantia-hub' ); ?></p>
                <ul class="mt-3 flex flex-wrap gap-x-5 gap-y-2">
                    <?php foreach ( $funds as $fund ) : ?>
                        <li>
                            <a href="<?php echo esc_url( $b['funds_url'] ?? '' ); ?>"
                                class="inline-flex items-center gap-2 font-display text-base font-bold tracking-[-0.01em] text-ink transition-colors hover:text-qh-700">
                                <span class="h-2.5 w-2.5 rounded-full <?php echo esc_attr( $fund['swatch'] ); ?>"></span>
                                <?php echo esc_html( $fund['name'] ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $b['link'] ) ) : ?>
            <a href="<?php echo esc_url( $b['link']['url'] ); ?>"
                class="group mt-10 inline-flex items-center gap-4 rounded-full bg-ink py-2 pl-7 pr-2 text-base font-semibold text-white shadow-[0_18px_40px_-18px_rgba(11,31,23,0.6)] transition-colors hover:bg-qh-700 sm:mt-12 sm:text-lg">
                <span class="whitespace-nowrap"><?php echo esc_html( $b['link']['label'] ); ?></span>
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-qh text-white transition-transform duration-200 group-hover:rotate-45 sm:h-12 sm:w-12">
                    <?php echo quantia_icon( 'arrow-up-right', 'h-5 w-5' ); ?>
                </span>
            </a>
        <?php endif; ?>
    </div>

    <div class="relative flex items-center justify-center py-6 sm:py-10">

        <div data-marquee data-marquee-distance="25"
            <?php echo $reversed ? 'data-marquee-reverse="true"' : ''; ?>
            aria-hidden="true"
            class="pointer-events-none absolute inset-x-[-30%] top-1/2 -translate-y-1/2 select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(5rem,12vw,10rem)] text-ink/[0.05]">
                <?php echo $marquee_x5; // phpcs:ignore ?>
            </p>
        </div>

        <div class="relative flex items-center justify-center">
            <div aria-hidden="true"
                class="absolute left-1/2 top-1/2 h-[260px] w-[calc(100%+3rem)] -translate-x-1/2 -translate-y-1/2 rounded-[2rem] bg-mist min-[400px]:h-[290px] sm:h-[360px] sm:w-[calc(100%+6rem)] sm:rounded-[2.5rem] lg:h-[420px] lg:w-[calc(100%+8rem)] lg:rounded-[3rem]"></div>

            <div data-parallax data-parallax-speed="<?php echo $reversed ? '0.55' : '0.45'; ?>"
                 data-parallax-rotate="<?php echo $reversed ? '-4' : '4'; ?>"
                 class="relative">
                <?php get_template_part( 'template-parts/components/phone-frame', null, array(
                    'label'  => $b['screen_label'],
                    'screen' => $b['screen'],
                ) ); ?>
            </div>
        </div>
    </div>
</article>