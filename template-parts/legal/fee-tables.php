<?php
/**
 * Tablas de tarifas (vista plana).
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$schedules = quantia_fallback_fee_schedules();

if ( empty( $schedules ) ) {
    return;
}
?>
<div class="space-y-12">
    <?php foreach ( $schedules as $index => $schedule ) : ?>
        <div>
            <h4 class="font-display text-xl font-bold tracking-[-0.02em] text-ink">
                <?php
                printf(
                    /* translators: 1: número de tarifario, 2: etiqueta del tarifario */
                    esc_html__( '%1$d. Tarifas para %2$s', 'quantia-hub' ),
                    (int) ( $index + 1 ),
                    esc_html( $schedule['label'] )
                );
                ?>
            </h4>
            <div class="mt-4 overflow-x-auto rounded-2xl border border-ink/10">
                <table class="w-full min-w-[520px] border-collapse text-left text-sm">
                    <caption class="sr-only">
                        <?php
                        printf(
                            /* translators: %s: etiqueta del tarifario */
                            esc_html__( 'Tarifas para %s', 'quantia-hub' ),
                            esc_html( $schedule['label'] )
                        );
                        ?>
                    </caption>
                    <thead>
                        <tr class="bg-ink text-white">
                            <th scope="col" class="px-5 py-3 font-semibold">
                                <?php esc_html_e( 'Descripción del cargo / comisión', 'quantia-hub' ); ?>
                            </th>
                            <th scope="col" class="w-[38%] px-5 py-3 font-semibold">
                                <?php esc_html_e( 'Tarifa', 'quantia-hub' ); ?>
                            </th>
                        </tr>
                    </thead>
                    <?php foreach ( $schedule['groups'] as $group ) : ?>
                        <tbody>
                            <tr class="bg-mist">
                                <th scope="colgroup" colspan="2" class="px-5 py-2.5 text-[13px] font-bold text-ink">
                                    <?php echo esc_html( $group['title'] ); ?>
                                </th>
                            </tr>
                            <?php foreach ( $group['rows'] as $row ) :
                                $free = quantia_is_free( $row['cost'] );
                            ?>
                                <tr class="border-t border-ink/10">
                                    <td class="px-5 py-3 align-top text-ink/75"><?php echo esc_html( $row['concept'] ); ?></td>
                                    <td class="px-5 py-3 align-top">
                                        <span class="font-semibold <?php echo $free ? 'text-qh-700' : 'text-ink'; ?>">
                                            <?php echo esc_html( $row['cost'] ); ?>
                                        </span>
                                        <?php if ( ! empty( $row['note'] ) ) : ?>
                                            <span class="mt-0.5 block text-xs text-ink/55"><?php echo esc_html( $row['note'] ); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
</div>