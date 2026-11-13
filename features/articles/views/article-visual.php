<?php
defined( 'ABSPATH' ) || exit;

$id = $args['id'] ?? 'riesgo';
?>

<div class="flex h-full w-full items-center justify-center" aria-hidden="true">
    <?php if ( 'riesgo' === $id ) : ?>
        <svg viewBox="0 0 200 90" class="h-full w-full">
            <polyline points="0,45 25,43 50,46 75,42 100,44 125,41 150,43 175,40 200,41" fill="none" stroke="#046B44" stroke-width="2"/>
            <polyline points="0,50 25,38 50,52 75,34 100,48 125,30 150,44 175,26 200,32" fill="none" stroke="#00AC69" stroke-width="2"/>
            <polyline points="0,55 25,20 50,70 75,15 100,62 125,10 150,58 175,8 200,22" fill="none" stroke="#0B1F17" stroke-width="2" stroke-opacity="0.35"/>
        </svg>
    <?php elseif ( 'diversificacion' === $id ) : ?>
        <svg viewBox="0 0 42 42" class="h-full max-h-28 w-auto -rotate-90">
            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#046B44" stroke-width="6" stroke-dasharray="34 66" stroke-dashoffset="0"/>
            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#00AC69" stroke-width="6" stroke-dasharray="24 76" stroke-dashoffset="-35"/>
            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#6FE0AF" stroke-width="6" stroke-dasharray="24 76" stroke-dashoffset="-60"/>
            <circle cx="21" cy="21" r="15.915" fill="none" stroke="#0B1F17" stroke-width="6" stroke-dasharray="14 86" stroke-dashoffset="-85"/>
        </svg>
    <?php elseif ( 'interes-compuesto' === $id ) : ?>
        <div class="flex h-full w-full items-end justify-between gap-2">
            <?php foreach ( array( array(18,1), array(26,4), array(34,9), array(42,16), array(50,26), array(58,40) ) as $bar ) : ?>
                <div class="flex flex-1 flex-col justify-end" style="height:100%">
                    <div class="rounded-t-md bg-qh" style="height:<?php echo (int) $bar[1]; ?>%"></div>
                    <div class="bg-ink/15" style="height:<?php echo (int) $bar[0]; ?>%"></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ( 'ahorro-primero' === $id ) : ?>
        <div class="flex h-full w-full max-w-sm flex-col justify-center gap-3">
            <?php
            $rows = array(
                array( __( 'Ahorro', 'quantia-hub' ), 20, 'bg-qh' ),
                array( __( 'Necesidades', 'quantia-hub' ), 50, 'bg-ink/25' ),
                array( __( 'Deseos', 'quantia-hub' ), 30, 'bg-qh-300' ),
            );
            foreach ( $rows as $row ) :
            ?>
                <div class="flex items-center gap-3">
                    <div class="h-4 flex-1 overflow-hidden rounded-full bg-white">
                        <div class="h-4 rounded-full <?php echo esc_attr( $row[2] ); ?>" style="width:<?php echo (int) ( $row[1] * 1.8 ); ?>%"></div>
                    </div>
                    <span class="w-10 text-right font-display text-sm font-bold text-ink"><?php echo (int) $row[1]; ?>%</span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <svg viewBox="0 0 200 90" class="h-full w-full">
            <line x1="0" y1="60" x2="200" y2="52" stroke="#6FE0AF" stroke-width="3" stroke-linecap="round"/>
            <polyline points="0,72 30,66 60,62 90,50 120,44 150,30 175,24 200,10" fill="none" stroke="#046B44" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    <?php endif; ?>
</div>