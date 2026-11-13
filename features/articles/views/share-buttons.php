<?php
defined( 'ABSPATH' ) || exit;

$title = $args['title'] ?? get_the_title();
$dark  = ! empty( $args['dark'] );
$url   = get_permalink() ?: home_url( '/' );

$label_class  = $dark ? 'text-white/60' : 'text-ink/50';
$button_class = $dark
    ? 'border-white/15 bg-white/10 text-white hover:border-white/40 hover:bg-white/15'
    : 'border-ink/15 bg-white text-ink hover:border-ink/40';

$share_twitter  = 'https://twitter.com/intent/tweet?text=' . rawurlencode( $title ) . '&url=' . rawurlencode( $url );
$share_linkedin = 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $url );
$share_facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $url );
$share_whatsapp = 'https://wa.me/?text=' . rawurlencode( $title . ' ' . $url );
?>

<div data-share-buttons data-share-url="<?php echo esc_url( $url ); ?>">
    <p class="text-xs font-semibold uppercase tracking-widest <?php echo esc_attr( $label_class ); ?>">
        <?php esc_html_e( 'Compartir', 'quantia-hub' ); ?>
    </p>
    <ul class="mt-3 flex flex-wrap gap-2">
        <li>
            <a href="<?php echo esc_url( $share_twitter ); ?>" target="_blank" rel="noopener noreferrer"
               class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition-colors <?php echo esc_attr( $button_class ); ?>"
               aria-label="<?php esc_attr_e( 'Compartir en X', 'quantia-hub' ); ?>">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" aria-hidden="true"><path d="M18.9 2H22l-7.6 8.7L23 22h-6.9l-5.4-7.1L4.5 22H1.4l8.1-9.3L1 2h7l4.9 6.5L18.9 2zm-1.2 18h1.9L6.4 4H4.4l13.3 16z"/></svg>
            </a>
        </li>
        <li>
            <a href="<?php echo esc_url( $share_linkedin ); ?>" target="_blank" rel="noopener noreferrer"
               class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition-colors <?php echo esc_attr( $button_class ); ?>"
               aria-label="<?php esc_attr_e( 'Compartir en LinkedIn', 'quantia-hub' ); ?>">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" aria-hidden="true"><path d="M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.5 8h4v15h-4V8zm7 0h3.8v2h.1c.5-1 1.8-2 3.7-2 4 0 4.7 2.6 4.7 6v9h-4v-8c0-1.9 0-4.4-2.7-4.4s-3.1 2.1-3.1 4.2V23h-4V8z"/></svg>
            </a>
        </li>
        <li>
            <a href="<?php echo esc_url( $share_facebook ); ?>" target="_blank" rel="noopener noreferrer"
               class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition-colors <?php echo esc_attr( $button_class ); ?>"
               aria-label="<?php esc_attr_e( 'Compartir en Facebook', 'quantia-hub' ); ?>">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12z"/></svg>
            </a>
        </li>
        <li>
            <a href="<?php echo esc_url( $share_whatsapp ); ?>" target="_blank" rel="noopener noreferrer"
               class="inline-flex h-10 w-10 items-center justify-center rounded-full border transition-colors <?php echo esc_attr( $button_class ); ?>"
               aria-label="<?php esc_attr_e( 'Compartir en WhatsApp', 'quantia-hub' ); ?>">
                <?php echo quantia_icon( 'message-circle', 'h-4 w-4' ); ?>
            </a>
        </li>
        <li class="flex-1">
            <button type="button" data-share-copy
                    class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold transition-colors <?php echo esc_attr( $button_class ); ?>">
                <span data-share-icon><?php echo quantia_icon( 'arrow-right', 'h-4 w-4' ); ?></span>
                <span data-share-label><?php esc_html_e( 'Copiar enlace', 'quantia-hub' ); ?></span>
            </button>
        </li>
    </ul>
</div>