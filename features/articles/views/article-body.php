<?php
defined( 'ABSPATH' ) || exit;

$blocks = $args['blocks'] ?? array();

if ( empty( $blocks ) ) {
    return;
}
?>

<div class="article-body space-y-6">
    <?php foreach ( $blocks as $block ) :
        $type = $block['type'] ?? '';

        if ( 'p' === $type ) : ?>
            <p class="text-lg leading-relaxed text-ink/75"><?php echo esc_html( $block['text'] ?? '' ); ?></p>
        <?php elseif ( 'h2' === $type ) : ?>
            <h2 class="mt-12 font-display text-3xl font-extrabold leading-tight tracking-[-0.03em] text-ink sm:text-4xl"><?php echo esc_html( $block['text'] ?? '' ); ?></h2>
        <?php elseif ( 'h3' === $type ) : ?>
            <h3 class="mt-10 font-display text-2xl font-bold leading-tight tracking-[-0.02em] text-ink sm:text-3xl"><?php echo esc_html( $block['text'] ?? '' ); ?></h3>
        <?php elseif ( 'list' === $type && ! empty( $block['items'] ) ) : ?>
            <ul class="space-y-3">
                <?php foreach ( $block['items'] as $item ) : ?>
                    <li class="flex gap-3 text-lg leading-relaxed text-ink/75">
                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-qh"></span>
                        <span><?php echo esc_html( $item ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif ( 'defs' === $type && ! empty( $block['items'] ) ) : ?>
            <dl class="divide-y divide-ink/10 border-y border-ink/10">
                <?php foreach ( $block['items'] as $def ) : ?>
                    <div class="py-4">
                        <dt class="font-display text-lg font-bold text-ink"><?php echo esc_html( $def['term'] ?? '' ); ?></dt>
                        <dd class="mt-1 text-base leading-relaxed text-ink/65"><?php echo esc_html( $def['text'] ?? '' ); ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        <?php elseif ( 'table' === $type && ! empty( $block['head'] ) ) : ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="border-b-2 border-ink">
                            <?php foreach ( $block['head'] as $th ) : ?>
                                <th class="py-3 pr-4 text-left font-display font-bold text-ink"><?php echo esc_html( $th ); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $block['rows'] ?? array() as $row ) : ?>
                            <tr class="border-b border-ink/10">
                                <?php foreach ( $row as $cell ) : ?>
                                    <td class="py-3 pr-4 text-ink/75"><?php echo esc_html( $cell ); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ( 'callout' === $type ) : ?>
            <aside class="rounded-2xl border-l-4 border-qh bg-mist p-6 text-base leading-relaxed text-ink/80">
                <?php echo esc_html( $block['text'] ?? '' ); ?>
            </aside>
        <?php elseif ( 'faq' === $type && ! empty( $block['items'] ) ) : ?>
            <div class="divide-y divide-ink/10 border-y border-ink/10">
                <?php foreach ( $block['items'] as $faq ) : ?>
                    <details class="group py-4">
                        <summary class="flex cursor-pointer items-start justify-between gap-4 text-left">
                            <span class="font-display text-lg font-semibold text-ink"><?php echo esc_html( $faq['q'] ?? '' ); ?></span>
                            <span class="mt-1 shrink-0 text-ink/50 transition-transform group-open:rotate-45">
                                <?php echo quantia_icon( 'chevron-down-full', 'h-4 w-4' ); ?>
                            </span>
                        </summary>
                        <p class="mt-3 text-base leading-relaxed text-ink/70"><?php echo esc_html( $faq['a'] ?? '' ); ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        <?php endif;
    endforeach; ?>
</div>