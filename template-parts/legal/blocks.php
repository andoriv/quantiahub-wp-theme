<?php
/**
 * Renderiza los bloques de contenido de una sección legal.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$blocks = $args['blocks'] ?? array();

if ( empty( $blocks ) ) {
    return;
}
?>
<div class="space-y-4">
    <?php foreach ( $blocks as $block ) :
        $type = $block['type'] ?? '';

        if ( 'p' === $type ) : ?>
            <p class="text-[15px] leading-relaxed text-ink/70"><?php echo esc_html( $block['text'] ?? '' ); ?></p>

        <?php elseif ( 'list' === $type && ! empty( $block['items'] ) ) :
            $tag   = ! empty( $block['ordered'] ) ? 'ol' : 'ul';
            $class = ! empty( $block['ordered'] )
                ? 'space-y-2.5 pl-5 text-[15px] leading-relaxed text-ink/70 list-decimal marker:font-semibold marker:text-qh-700'
                : 'space-y-2.5 pl-5 text-[15px] leading-relaxed text-ink/70 list-disc marker:text-qh';
        ?>
            <<?php echo $tag; ?> class="<?php echo esc_attr( $class ); ?>">
                <?php foreach ( $block['items'] as $item ) : ?>
                    <li class="pl-1"><?php echo esc_html( $item ); ?></li>
                <?php endforeach; ?>
            </<?php echo $tag; ?>>

        <?php elseif ( 'defs' === $type && ! empty( $block['items'] ) ) : ?>
            <dl class="divide-y divide-ink/10 border-y border-ink/10">
                <?php foreach ( $block['items'] as $item ) : ?>
                    <div class="grid grid-cols-1 gap-1 py-4 md:grid-cols-[220px_1fr] md:gap-6">
                        <dt class="font-display text-[15px] font-bold text-ink"><?php echo esc_html( $item['term'] ?? '' ); ?></dt>
                        <dd class="text-[15px] leading-relaxed text-ink/70"><?php echo esc_html( $item['text'] ?? '' ); ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>

        <?php elseif ( 'contact' === $type && ! empty( $block['items'] ) ) : ?>
            <dl class="grid grid-cols-1 gap-x-8 gap-y-4 rounded-2xl bg-mist p-5 sm:grid-cols-2">
                <?php foreach ( $block['items'] as $item ) :
                    $href        = $item['href'] ?? '';
                    $is_external = $href && 0 === strpos( $href, 'http' );
                ?>
                    <div>
                        <dt class="text-xs font-semibold text-ink/50"><?php echo esc_html( $item['label'] ?? '' ); ?></dt>
                        <dd class="mt-1 text-[15px] font-semibold text-ink">
                            <?php if ( $href ) : ?>
                                <a href="<?php echo esc_url( $href ); ?>"
                                   <?php echo $is_external ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
                                   class="text-qh-700 underline-offset-4 transition-colors hover:text-ink hover:underline">
                                    <?php echo esc_html( $item['value'] ?? '' ); ?>
                                </a>
                            <?php else : ?>
                                <?php echo esc_html( $item['value'] ?? '' ); ?>
                            <?php endif; ?>
                        </dd>
                    </div>
                <?php endforeach; ?>
            </dl>

        <?php elseif ( 'feeTables' === $type ) : ?>
            <?php get_template_part( 'template-parts/legal/fee-tables' ); ?>

        <?php elseif ( 'ref' === $type ) :
            $target = $block['target'] ?? '';
            $url    = $target ? home_url( '/legal/' . $target . '/' ) : '';
        ?>
            <div class="flex flex-col items-start gap-4 rounded-2xl bg-mist p-5">
                <p class="text-[15px] leading-relaxed text-ink/70"><?php echo esc_html( $block['text'] ?? '' ); ?></p>
                <?php if ( $url && ! empty( $block['label'] ) ) : ?>
                    <a href="<?php echo esc_url( $url ); ?>"
                       class="group inline-flex items-center gap-2 text-sm font-semibold text-qh-700 transition-colors hover:text-ink">
                        <?php echo esc_html( $block['label'] ); ?>
                        <?php echo quantia_icon( 'arrow-right', 'h-4 w-4 transition-transform duration-200 ease-out group-hover:translate-x-1' ); ?>
                    </a>
                <?php endif; ?>
            </div>

        <?php endif;
    endforeach; ?>
</div>