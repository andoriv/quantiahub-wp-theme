<?php
defined( 'ABSPATH' ) || exit;

$id          = $args['id']          ?? '';
$title       = $args['title']       ?? '';
$description = $args['description'] ?? '';
$index       = (int) ( $args['index'] ?? 0 );
?>

<li class="relative grid grid-cols-[40px_1fr] items-start gap-x-4 gap-y-5 py-8 md:grid-cols-[56px_1fr_280px] md:items-center md:gap-10 md:py-12">
    <span class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full bg-ink font-display text-base font-extrabold text-qh-300 ring-1 ring-white/15 md:row-auto md:h-14 md:w-14 md:text-xl">
        <?php echo (int) ( $index + 1 ); ?>
    </span>

    <div data-slide data-slide-from="60" class="pt-1 md:pt-0">
        <h3 class="font-display text-2xl font-extrabold tracking-[-0.03em] sm:text-3xl md:text-4xl">
            <?php echo esc_html( $title ); ?>
        </h3>
        <p class="mt-3 max-w-md text-sm leading-relaxed text-white/60">
            <?php echo esc_html( $description ); ?>
        </p>
    </div>

    <div data-parallax data-parallax-speed="0.35" class="col-start-2 md:col-start-auto">
        <div class="h-36 rounded-2xl bg-white/[0.05] px-4 sm:h-40 sm:px-5">
            <?php if ( 'descarga' === $id ) : ?>
                <div class="flex h-full items-center justify-center gap-4" aria-hidden="true">
                    <span class="flex h-16 w-16 items-center justify-center rounded-[1.1rem] bg-qh font-display text-2xl font-extrabold text-white">Q</span>
                    <span>
                        <span class="block font-display text-sm font-semibold text-white">Quantia Hub</span>
                        <span class="block text-[11px] text-white/50">Finanzas · MMG Bank</span>
                        <span class="mt-2 inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 text-[11px] font-semibold text-ink">
                            Obtener
                        </span>
                    </span>
                </div>
            <?php elseif ( 'verifica' === $id ) : ?>
                <div class="flex h-full items-center justify-center" aria-hidden="true">
                    <div class="relative w-44 rounded-xl border border-white/15 bg-white/[0.06] p-3">
                        <div class="flex gap-3">
                            <span class="flex h-12 w-10 items-center justify-center rounded-md bg-white/10 text-white/50">
                                <?php echo quantia_icon( 'scan-face', 'h-5 w-5' ); ?>
                            </span>
                            <span class="flex-1 space-y-1.5 pt-1">
                                <span class="block h-1.5 w-full rounded-full bg-white/20"></span>
                                <span class="block h-1.5 w-3/4 rounded-full bg-white/20"></span>
                                <span class="block h-1.5 w-1/2 rounded-full bg-white/20"></span>
                            </span>
                        </div>
                        <span class="absolute -right-3 -top-3 flex h-8 w-8 items-center justify-center rounded-full bg-qh text-white ring-4 ring-ink">
                            <?php echo quantia_icon( 'check', 'h-4 w-4' ); ?>
                        </span>
                    </div>
                </div>
            <?php else : ?>
                <ul class="flex h-full flex-col justify-center gap-2" aria-hidden="true">
                    <?php foreach ( array( 'Alcancía META', 'Tarjeta Visa Débito', 'Perfil de inversión' ) as $label ) : ?>
                        <li class="flex items-center justify-between rounded-lg bg-white/[0.06] px-3 py-2 text-[11px] font-medium text-white/80">
                            <?php echo esc_html( $label ); ?>
                            <span class="relative h-4 w-7 rounded-full bg-qh">
                                <span class="absolute right-0.5 top-0.5 h-3 w-3 rounded-full bg-white"></span>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</li>