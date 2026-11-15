<?php
/**
 * Fila individual de un paso de onboarding.
 * 
 * Contexto de Arquitectura:
 * - Consumido por: steps.php.
 * - Dependencias visuales: quantia_icon() para SVGs, y la lógica condicional $visual para dibujar las tarjetas del lado derecho.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$step   = $args['step'];
$index  = (int) $args['index'];
$visual = $step['visual'] ?? '';
?>

<li class="relative grid grid-cols-[40px_1fr] items-start gap-x-4 gap-y-5 py-8 md:grid-cols-[56px_1fr_280px] md:items-center md:gap-10 md:py-12">
    
    <span class="relative z-10 flex h-10 w-10 items-center justify-center rounded-full bg-ink font-display text-base font-extrabold text-qh-300 ring-1 ring-white/15 md:row-auto md:h-14 md:w-14 md:text-xl">
        <?php echo esc_html( $index + 1 ); ?>
    </span>

    <div class="pt-1 md:pt-0" data-slide data-slide-from="30">
        <h3 class="font-display text-2xl font-extrabold tracking-[-0.03em] sm:text-3xl md:text-4xl">
            <?php echo esc_html( $step['title'] ); ?>
        </h3>
        <p class="mt-3 max-w-md text-sm leading-relaxed text-white/60">
            <?php echo esc_html( $step['description'] ); ?>
        </p>
    </div>

    <div class="col-start-2 md:col-start-auto" data-parallax data-parallax-speed="0.08">
        <div class="h-36 rounded-2xl bg-white/[0.05] px-4 sm:h-40 sm:px-5">
            <?php if ( 'app' === $visual ) : ?>
                <div class="flex h-full items-center justify-center gap-4" aria-hidden="true">            
                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-[1.1rem]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024" class="block h-full w-full">
                            <defs>
                                <style>
                                    .cls-1 { fill: #00ac69; }
                                    .cls-2 { fill: #92d0aa; }
                                </style>
                            </defs>
                            <rect width="1024" height="1024" rx="230" ry="230" fill="#044b37" />
                                <g transform="translate(140, 140) scale(0.6)">
                                <path class="cls-2" d="M1074.77,919.32c-60.6,0-109.72,49.13-109.72,109.72s49.13,109.72,109.72,109.72,109.72-49.12,109.72-109.72-49.12-109.72-109.72-109.72Z"/>
                                <path class="cls-1" d="M965.04,1029.04c0-60.59,49.12-109.71,109.71-109.72h-87.46c16.32-25.63,30.79-53.28,43.2-83.21,24.05-57.97,37.58-124.04,40.59-198.17,0-.02,0-.05,0-.07.42-10.43.63-21.04.63-31.79,0-10.69-.2-21.24-.61-31.6-.01-.45-.05-.89-.07-1.34-.41-9.95-.99-19.77-1.76-29.43-.08-.98-.19-1.94-.26-2.91-.77-9.14-1.68-18.16-2.77-27.03-.13-1.04-.28-2.04-.41-3.07-1.14-8.89-2.43-17.66-3.9-26.28-.13-.76-.27-1.52-.4-2.28-1.58-9.06-3.33-17.98-5.28-26.74-.04-.18-.08-.37-.13-.56-6.2-27.73-14.26-53.98-24.16-78.76-26.51-66.27-62.85-121.99-108.99-167.16-27.84-27.23-58.57-50.28-92.11-69.3-5.55-3.07-11.15-6.09-16.87-8.94-52.03-26.03-108.83-44.04-169.72-51.9-26.29-3.51-53.15-5.35-80.66-5.35-67.74,0-132.32,11.3-193.67,33.88-61.38,22.59-115.39,56.46-162.01,101.61-46.65,45.17-83.72,100.89-111.19,167.16-24.05,57.98-37.58,124.04-40.6,198.16v.08c-.42,10.44-.63,21.04-.64,31.79.01,10.69.21,21.23.61,31.61.01.45.05.88.07,1.33.4,9.95.98,19.77,1.75,29.44.08.98.19,1.93.27,2.91.77,9.14,1.68,18.16,2.77,27.04.13,1.03.28,2.04.41,3.07,1.14,8.88,2.43,17.66,3.9,26.27.13.77.27,1.53.4,2.29,1.59,9.05,3.33,17.98,5.28,26.74.04.18.08.36.13.55,6.21,27.73,14.26,53.98,24.16,78.76,26.51,66.27,62.85,121.99,108.99,167.16,27.83,27.23,58.57,50.28,92.11,69.3,5.55,3.08,11.16,6.09,16.87,8.94,52.03,26.03,108.83,44.04,169.72,51.9,26.28,3.52,53.15,5.35,80.65,5.35h511.12c-60.58-.01-109.69-49.13-109.69-109.72h0ZM834.61,606.54c0,50.08-6.14,95.25-18.41,135.5-12.28,40.27-29.97,74.88-53.02,103.82-20.6,25.85-45.73,46.12-74.91,61.41-3.28,1.78-6.39,3.78-9.77,5.42-33.37,16.21-71.66,24.29-114.88,24.29s-80.27-7.86-114.14-23.56c-33.87-15.71-62.34-38.06-85.42-67.02-18.72-23.52-33.7-51.12-45.32-82.39-2.69-7.19-5.39-14.38-7.7-21.97-12.29-40.25-18.41-85.42-18.41-135.5,0-.16.02-.3.02-.46s-.02-.3-.02-.47c0-50.08,6.14-95.25,18.41-135.5,12.29-40.27,29.97-74.88,53.02-103.82,20.6-25.85,45.74-46.13,74.92-61.42,3.28-1.77,6.38-3.77,9.76-5.41,33.37-16.21,71.66-24.3,114.88-24.3s80.27,7.87,114.15,23.56c33.87,15.71,62.33,38.06,85.42,67.02,18.73,23.54,33.72,51.17,45.34,82.48,2.69,7.16,5.37,14.33,7.67,21.89,12.29,40.25,18.41,85.42,18.41,135.5,0,.16-.01.3-.01.46s.01.31.01.47h0Z"/>
                            </g>
                        </svg>
                    </div>

                    <span>
                        <span class="block font-display text-sm font-semibold text-white">Quantia Hub</span>
                        <span class="block text-[11px] text-white/50">Finanzas · MMG Bank</span>
                        <a href="#" 
                        data-smart-download 
                        data-ios-url="https://apps.apple.com/us/app/quantia-hub/id1499843860" 
                        data-android-url="https://play.google.com/store/apps/details?id=com.quantia" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="mt-2 inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 text-[11px] font-semibold text-ink transition-transform hover:scale-105">
                            <?php echo quantia_icon( 'download', 'h-3 w-3' ); ?> Obtener
                        </a>
                    </span>
                </div>

            <?php elseif ( 'id' === $visual ) : ?>
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

            <?php elseif ( 'fondeo' === $visual ) : ?>
                <div class="flex h-full flex-col justify-center gap-2.5" aria-hidden="true">
                    <div class="grid grid-cols-3 gap-2">
                        <span class="flex flex-col items-center justify-center gap-1 rounded-xl px-1.5 py-2 text-center ring-1 bg-white/[0.06] ring-white/10">
                            <?php echo quantia_icon( 'zap', 'h-4 w-4 text-white/70' ); ?>
                            <span class="text-[11px] font-bold leading-none text-white">Kuara</span>
                        </span>
                        <span class="flex flex-col items-center justify-center gap-1 rounded-xl px-1.5 py-2 text-center ring-1 bg-qh-300/10 ring-qh-300/60">
                            <?php echo quantia_icon( 'landmark', 'h-4 w-4 text-qh-300' ); ?>
                            <span class="text-[11px] font-bold leading-none text-white">ACH</span>
                        </span>
                        <span class="flex flex-col items-center justify-center gap-1 rounded-xl px-1.5 py-2 text-center ring-1 bg-white/[0.06] ring-white/10">
                            <?php echo quantia_icon( 'wallet', 'h-4 w-4 text-white/70' ); ?>
                            <span class="text-[11px] font-bold leading-none text-white">Billetera</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2.5 rounded-xl bg-white/[0.06] px-3 py-2.5 ring-1 ring-white/10">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-qh text-white">
                            <?php echo quantia_icon( 'arrow-down-left', 'h-4 w-4' ); ?>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-[11px] font-semibold text-white">Depósito recibido</span>
                            <!-- <span class="block truncate text-[10px] text-white/50">Vía Kuara · al instante</span> -->
                        </span>
                        <span class="shrink-0 font-display text-sm font-extrabold text-qh-300">+B/. 10.00</span>
                    </div>
                </div>

            <?php elseif ( 'productos' === $visual ) : ?>
                <ul class="flex h-full flex-col justify-center gap-2" aria-hidden="true">
                    <li class="flex items-center justify-between rounded-lg bg-white/[0.06] px-3 py-2 text-[11px] font-medium text-white/80">
                        Alcancía META
                        <span class="relative h-4 w-7 rounded-full bg-qh">
                            <span class="absolute right-0.5 top-0.5 h-3 w-3 rounded-full bg-white"></span>
                        </span>
                    </li>
                    <li class="flex items-center justify-between rounded-lg bg-white/[0.06] px-3 py-2 text-[11px] font-medium text-white/80">
                        Tarjeta Visa Débito
                        <span class="relative h-4 w-7 rounded-full bg-qh">
                            <span class="absolute right-0.5 top-0.5 h-3 w-3 rounded-full bg-white"></span>
                        </span>
                    </li>
                    <li class="flex items-center justify-between rounded-lg bg-white/[0.06] px-3 py-2 text-[11px] font-medium text-white/80">
                        Perfil de inversión
                        <span class="relative h-4 w-7 rounded-full bg-qh">
                            <span class="absolute right-0.5 top-0.5 h-3 w-3 rounded-full bg-white"></span>
                        </span>
                    </li>
                </ul>
            <?php endif; ?>
            
        </div>
    </div>
</li>