<?php
/**
 * Marco de teléfono reutilizable.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$label  = $args['label']  ?? '';
$screen = $args['screen'] ?? 'inicio';
$class  = $args['class']  ?? '';
?>
<div role="<?php echo $label ? 'img' : ''; ?>"
    aria-label="<?php echo esc_attr( $label ); ?>"
    class="w-[280px] shrink-0 rounded-[2.6rem] bg-ink p-2.5 shadow-[0_30px_60px_-24px_rgba(11,31,23,0.5)] [zoom:0.6] min-[400px]:[zoom:0.66] sm:[zoom:0.74] md:[zoom:0.8] lg:[zoom:0.88] <?php echo esc_attr( $class ); ?>">
    <div class="overflow-hidden rounded-[2.1rem] bg-mist">
        <div class="flex items-center justify-between bg-white px-6 pb-1 pt-2.5 text-[10px] font-semibold text-ink">
            <span>9:41</span>
            <span class="h-[18px] w-20 rounded-full bg-ink" aria-hidden="true"></span>
            <span class="flex items-center gap-1" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3"><rect x="2" y="14" width="3" height="6" rx="0.5"/><rect x="7" y="10" width="3" height="10" rx="0.5"/><rect x="12" y="6" width="3" height="14" rx="0.5"/><rect x="17" y="3" width="3" height="17" rx="0.5"/></svg>
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-3 w-3"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5"><rect width="16" height="10" x="2" y="7" rx="2"/><line x1="22" y1="11" x2="22" y2="13"/></svg>
            </span>
        </div>
        <div class="h-[560px]">
            <?php get_template_part( 'template-parts/components/app-screen', null, array( 'screen' => $screen ) ); ?>
        </div>
    </div>
</div>