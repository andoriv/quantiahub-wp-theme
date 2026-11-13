<?php
/**
 * Static app screen mockup for demo.
 * Args: screen (inicio|alcancias|inversiones|retiro|tarjeta|kuara)
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$screen = $args['screen'] ?? 'inicio';

$tabs = array(
    array( 'key' => 'inicio',      'label' => 'Inicio',   'icon' => 'home' ),
    array( 'key' => 'alcancias',   'label' => 'Ahorro',   'icon' => 'piggy-bank' ),
    array( 'key' => 'inversiones', 'label' => 'Invertir', 'icon' => 'trending-up' ),
    array( 'key' => 'retiro',      'label' => 'Retiro',   'icon' => 'sunset' ),
    array( 'key' => 'tarjeta',     'label' => 'Tarjeta',  'icon' => 'credit-card' ),
);
?>

<div class="flex h-full flex-col">
    <div class="relative flex-1 overflow-hidden">
        <?php if ( 'inicio' === $screen ) : ?>
            <div class="px-4 pb-4">
                <div class="-mx-4 bg-white px-4 pb-4 pt-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-ink/50">Buenos días</p>
                            <p class="font-display text-sm font-bold text-ink">Hola, María</p>
                        </div>
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-qh-100 text-[10px] font-bold text-qh-700">MG</span>
                    </div>
                    <div class="mt-3 rounded-2xl bg-qh p-4 text-white">
                        <p class="text-[10px] text-white/80">Saldo disponible</p>
                        <p class="mt-0.5 font-display text-2xl font-extrabold tracking-[-0.02em]">US$3,240.50</p>
                        <p class="mt-2 text-[10px] text-white/80">Cuenta Quantia · •••• 4821</p>
                    </div>
                    <ul class="mt-3 grid grid-cols-4 gap-2">
                        <?php foreach ( array( 'Transferir', 'ACH', 'Pagar', 'Anticipo' ) as $action ) : ?>
                            <li class="flex flex-col items-center gap-1">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-mist text-ink">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><circle cx="12" cy="12" r="10"/></svg>
                                </span>
                                <span class="text-[9px] font-medium text-ink/70"><?php echo esc_html( $action ); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <p class="mt-4 text-[11px] font-semibold text-ink">Movimientos recientes</p>
                <ul class="mt-2 divide-y divide-ink/5 rounded-2xl bg-white">
                    <?php foreach ( array(
                        array( 'Super 99', 'Supermercado · Hoy', '-US$42.35' ),
                        array( 'Aporte · Viaje', 'Alcancía · Ayer', '-US$50.00' ),
                        array( 'Transferencia recibida', 'ACH · 8 oct', '+US$650.00' ),
                    ) as $m ) : ?>
                        <li class="flex items-center gap-3 px-3 py-2.5">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-mist text-[10px] font-bold text-ink/60">
                                <?php echo esc_html( mb_substr( $m[0], 0, 1 ) ); ?>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-[11px] font-semibold text-ink"><?php echo esc_html( $m[0] ); ?></span>
                                <span class="block text-[9px] text-ink/45"><?php echo esc_html( $m[1] ); ?></span>
                            </span>
                            <span class="text-[11px] font-semibold text-ink"><?php echo esc_html( $m[2] ); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ( 'tarjeta' === $screen ) : ?>
            <div class="px-4 pb-4">
                <div class="-mx-4 bg-white px-4 pb-4 pt-2">
                    <p class="font-display text-sm font-bold text-ink">Mi tarjeta</p>
                    <div class="mt-3 flex aspect-[1.586] flex-col justify-between rounded-2xl bg-ink p-4 text-white">
                        <div class="flex items-center justify-between">
                            <span class="font-display text-xs font-bold">Quantia</span>
                            <span class="text-[9px] font-semibold text-qh-300">Débito</span>
                        </div>
                        <div>
                            <p class="font-mono text-xs tracking-widest">•••• •••• •••• 0104</p>
                            <div class="mt-2 flex items-end justify-between">
                                <span class="text-[8px] text-white/70">MARÍA GONZÁLEZ · 08/29</span>
                                <span class="font-display text-sm font-extrabold italic">VISA</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between rounded-2xl bg-white p-3">
                    <span class="flex items-center gap-2 text-[11px] font-semibold text-ink">Tarjeta activa</span>
                    <span class="relative h-5 w-9 rounded-full bg-qh-200" aria-hidden="true">
                        <span class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow"></span>
                    </span>
                </div>
                <p class="mt-4 text-[11px] font-semibold text-ink">Consumos</p>
                <ul class="mt-2 divide-y divide-ink/5 rounded-2xl bg-white">
                    <?php foreach ( array(
                        array( 'Super 99', 'Supermercado · Hoy', '-US$42.35' ),
                        array( 'Café Unido', 'Restaurantes · 7 oct', '-US$6.75' ),
                        array( 'Uber', 'Transporte · 6 oct', '-US$8.90' ),
                    ) as $m ) : ?>
                        <li class="flex items-center gap-3 px-3 py-2.5">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-mist text-[10px] font-bold text-ink/60">
                                <?php echo esc_html( mb_substr( $m[0], 0, 1 ) ); ?>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-[11px] font-semibold text-ink"><?php echo esc_html( $m[0] ); ?></span>
                                <span class="block text-[9px] text-ink/45"><?php echo esc_html( $m[1] ); ?></span>
                            </span>
                            <span class="text-[11px] font-semibold text-ink"><?php echo esc_html( $m[2] ); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ( 'alcancias' === $screen ) : ?>
            <div class="px-4 pb-4">
                <div class="-mx-4 bg-white px-4 pb-4 pt-2">
                    <p class="font-display text-sm font-bold text-ink">Alcancías</p>
                    <p class="mt-3 text-[10px] text-ink/50">Total ahorrado</p>
                    <p class="font-display text-2xl font-extrabold tracking-[-0.02em] text-ink">US$1,540.00</p>
                    <p class="mt-1 text-[10px] font-semibold text-qh-600">+US$4.12 de interés este mes</p>
                </div>
                <ul class="mt-3 space-y-2">
                    <li class="rounded-2xl bg-white p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-ink">Viaje a Boquete</span>
                            <span class="rounded-full bg-qh-100 px-2 py-0.5 text-[8px] font-bold text-qh-700">META</span>
                        </div>
                        <p class="mt-1 font-display text-base font-extrabold text-ink">US$900.00 <span class="ml-1 text-[10px] font-medium text-ink/45">de US$1,500.00</span></p>
                        <div class="mt-2">
                            <div class="h-1.5 w-full rounded-full bg-mist"><div class="h-1.5 rounded-full bg-qh" style="width:60%"></div></div>
                            <p class="mt-1 text-[9px] text-ink/45">60% · Meta Mar 2027</p>
                        </div>
                    </li>
                    <li class="rounded-2xl bg-white p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-ink">Imprevistos</span>
                            <span class="rounded-full bg-mist px-2 py-0.5 text-[8px] font-bold text-ink/60">LIBRE</span>
                        </div>
                        <p class="mt-1 font-display text-base font-extrabold text-ink">US$640.00</p>
                    </li>
                </ul>
            </div>
        <?php elseif ( 'inversiones' === $screen ) : ?>
            <div class="px-4 pb-4">
                <div class="-mx-4 bg-white px-4 pb-4 pt-2">
                    <p class="font-display text-sm font-bold text-ink">Mi portafolio</p>
                    <p class="mt-3 text-[10px] text-ink/50">Valor actual</p>
                    <div class="flex items-baseline gap-2">
                        <p class="font-display text-2xl font-extrabold tracking-[-0.02em] text-ink">US$12,480.00</p>
                        <span class="rounded-full bg-qh-100 px-1.5 py-0.5 text-[9px] font-bold text-qh-700">+4.8%</span>
                    </div>
                    <svg viewBox="0 0 120 30" class="mt-3 h-14 w-full" preserveAspectRatio="none" aria-hidden="true">
                        <polyline points="0,26 12,23 24,24 36,19 48,20 60,15 72,16 84,11 96,12 108,7 120,5" fill="none" stroke="#00AC69" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/>
                    </svg>
                </div>
                <div class="mt-3 rounded-2xl bg-white p-3">
                    <p class="text-[11px] font-semibold text-ink">Perfil Navegante</p>
                    <div class="mt-3 flex h-2 w-full overflow-hidden rounded-full">
                        <span class="bg-qh-700" style="width:25%"></span>
                        <span class="bg-qh" style="width:25%"></span>
                        <span class="bg-qh-300" style="width:25%"></span>
                        <span class="bg-ink" style="width:25%"></span>
                    </div>
                </div>
            </div>
        <?php elseif ( 'retiro' === $screen ) : ?>
            <div class="px-4 pb-4">
                <div class="-mx-4 bg-white px-4 pb-4 pt-2">
                    <p class="font-display text-sm font-bold text-ink">Fondo de jubilación</p>
                    <p class="mt-3 text-[10px] text-ink/50">Saldo acumulado · Quantia AFP</p>
                    <p class="font-display text-2xl font-extrabold tracking-[-0.02em] text-ink">US$8,920.00</p>
                </div>
                <div class="mt-3 rounded-2xl bg-qh-700 p-3 text-white">
                    <p class="text-[10px] text-white/80">Aporte programado</p>
                    <p class="mt-1 font-display text-lg font-extrabold">US$100.00 <span class="text-[10px] font-medium text-white/80">mensual</span></p>
                    <p class="mt-1 text-[9px] text-white/80">Próximo aporte: 1 nov</p>
                </div>
            </div>
        <?php else : /* kuara */ ?>
            <div class="px-4 pb-4">
                <div class="-mx-4 flex items-center gap-3 bg-white px-4 pb-3 pt-2">
                    <p class="font-display text-sm font-bold text-ink">Transferir con Kuara</p>
                </div>
                <div class="mt-3 rounded-2xl bg-white p-4">
                    <p class="text-[10px] text-ink/50">Banco destino</p>
                    <ul class="mt-2 grid grid-cols-2 gap-1.5">
                        <?php foreach ( array( 'MMG Bank', 'Global Bank', 'Banesco', 'Banistmo' ) as $i => $bank ) : ?>
                            <li class="rounded-lg px-2.5 py-2 text-[10px] font-semibold <?php echo 1 === $i ? 'bg-qh text-white' : 'bg-mist text-ink/70'; ?>">
                                <?php echo esc_html( $bank ); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="mt-4 text-[10px] text-ink/50">Monto</p>
                    <p class="mt-0.5 font-display text-2xl font-extrabold tracking-[-0.02em] text-ink">US$150.00</p>
                </div>
                <div class="mt-3 rounded-2xl bg-qh p-4 text-white">
                    <p class="text-[11px] font-bold">Transferencia enviada</p>
                    <p class="text-[9px] text-white/80">Acreditada al instante · Global Bank</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <nav class="grid grid-cols-5 border-t border-ink/5 bg-white px-1 pb-3 pt-2">
        <?php foreach ( $tabs as $tab ) : ?>
            <?php $is_active = $tab['key'] === $screen; ?>
            <span class="flex flex-col items-center gap-0.5 rounded-lg py-1 transition-colors <?php echo $is_active ? 'text-qh-600' : 'text-ink/40'; ?>">
                <?php echo quantia_icon( $tab['icon'], 'h-4 w-4' ); // phpcs:ignore ?>
                <span class="text-[9px] font-semibold"><?php echo esc_html( $tab['label'] ); ?></span>
            </span>
        <?php endforeach; ?>
    </nav>
</div>