<?php
defined( 'ABSPATH' ) || exit;

$p      = $args['product'];
$icons  = quantia_product_option_icons( $p['slug'] );
$count  = count( $p['options'] );
$cols   = $count > 2 ? 'lg:grid-cols-4' : '';
$speeds = array( 0.1, 0.25, 0.15, 0.3 );
?>

<section class="w-full overflow-hidden bg-mist py-14 sm:py-20 lg:py-28">
    <div class="mx-auto max-w-6xl px-5 sm:px-6">

        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <h2 class="font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-5xl">
                <?php echo esc_html( $p['options_title'] ); ?>
            </h2>
            <p class="max-w-sm text-sm leading-relaxed text-ink/60">
                <?php echo esc_html( $p['options_intro'] ); ?>
            </p>
        </div>

        <ul class="mt-14 grid grid-cols-1 gap-x-10 gap-y-12 sm:grid-cols-2 <?php echo esc_attr( $cols ); ?>">
            <?php foreach ( $p['options'] as $i => $option ) :
                $icon_name = $icons[ $i ] ?? 'circle-alert';
                $speed     = $speeds[ $i % 4 ];
            ?>
                <li data-parallax data-parallax-speed="<?php echo esc_attr( (string) $speed ); ?>">
                    <div class="border-t-2 border-ink pt-6">
                        <span class="text-qh"><?php echo quantia_icon( $icon_name, 'h-8 w-8' ); ?></span>
                        <h3 class="mt-6 font-display text-2xl font-bold leading-tight tracking-[-0.02em] text-ink">
                            <?php echo esc_html( $option['title'] ); ?>
                        </h3>
                        <p class="mt-3 text-sm leading-relaxed text-ink/60">
                            <?php echo esc_html( $option['description'] ); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>