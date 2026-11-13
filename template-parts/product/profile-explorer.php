<?php
defined( 'ABSPATH' ) || exit;

$plan    = $args['plan'];
$title_a = $args['title_a'];
$title_b = $args['title_b'];
$intro   = $args['intro'];

$profiles = 'inversion' === $plan
    ? quantia_get_investment_profiles()
    : quantia_get_retirement_profiles();

$active_key = $profiles[1]['key'] ?? $profiles[0]['key'] ?? '';
$asset_info = quantia_asset_class_info();
?>

<div class="mx-auto grid max-w-6xl grid-cols-1 gap-14 px-5 sm:px-6 lg:grid-cols-[1fr_1.4fr] lg:gap-20"
     data-profile-explorer
     data-plan="<?php echo esc_attr( $plan ); ?>">

    <div data-parallax data-parallax-speed="0.2">
        <h2 class="font-display text-5xl font-extrabold leading-[0.98] tracking-[-0.04em] text-ink sm:text-6xl">
            <?php echo esc_html( $title_a ); ?><br>
            <span class="text-qh"><?php echo esc_html( $title_b ); ?></span>
        </h2>
        <p class="mt-6 max-w-sm text-base leading-relaxed text-ink/55">
            <?php echo esc_html( $intro ); ?>
        </p>

        <div role="tablist" aria-label="<?php esc_attr_e( 'Perfil de riesgo', 'quantia-hub' ); ?>" class="mt-10 space-y-1">
            <?php foreach ( $profiles as $profile ) :
                $is_active = $profile['key'] === $active_key;
            ?>
                <button type="button"
                        role="tab"
                        data-profile-tab="<?php echo esc_attr( $profile['key'] ); ?>"
                        data-profile-level="<?php echo (int) $profile['level']; ?>"
                        aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                        class="group flex w-full items-center gap-4 py-2 text-left">
                    <span class="flex gap-1" aria-label="<?php echo esc_attr( sprintf( __( 'Riesgo %d de 3', 'quantia-hub' ), (int) $profile['level'] ) ); ?>">
                        <?php for ( $step = 1; $step <= 3; $step++ ) :
                            $on  = $step <= $profile['level'];
                            $cls = $on
                                ? ( $is_active ? 'bg-qh' : 'bg-ink/25' )
                                : 'bg-ink/10';
                        ?>
                            <span data-bar class="h-4 w-1.5 rounded-full transition-colors duration-200 <?php echo esc_attr( $cls ); ?>"></span>
                        <?php endfor; ?>
                    </span>
                    <span data-title
                          class="font-display text-3xl font-bold tracking-[-0.03em] transition-colors sm:text-4xl <?php echo $is_active ? 'text-ink' : 'text-ink/25 group-hover:text-ink/55'; ?>">
                        <?php echo esc_html( $profile['name'] ); ?>
                    </span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div>
        <?php foreach ( $profiles as $profile ) :
            $groups    = quantia_group_allocation_by_asset( $profile );
            $is_active = $profile['key'] === $active_key;
        ?>
            <div data-profile-panel="<?php echo esc_attr( $profile['key'] ); ?>"
                 class="<?php echo $is_active ? '' : 'hidden'; ?>">

                <p class="text-sm font-semibold text-qh-700"><?php esc_html_e( 'Distribución del portafolio', 'quantia-hub' ); ?></p>
                <p class="mt-2 max-w-md text-lg leading-relaxed text-ink/75">
                    <?php echo esc_html( $profile['summary'] ); ?>
                </p>

                <div class="mt-8 flex h-4 w-full overflow-hidden rounded-full">
                    <?php foreach ( $profile['allocation'] as $slice ) :
                        $fund = quantia_fund_get( $slice['fund'] );
                        if ( ! $fund ) {
                            continue;
                        }
                    ?>
                        <span class="<?php echo esc_attr( $fund['swatch'] ); ?> ring-1 ring-white"
                              style="width:<?php echo esc_attr( (string) $slice['pct'] ); ?>%"></span>
                    <?php endforeach; ?>
                </div>

                <ul class="mt-10 border-t-2 border-ink">
                    <?php foreach ( $groups as $group ) :
                        $count       = count( $group['funds'] );
                        $pct_display = floor( $group['pct'] ) === $group['pct']
                            ? (int) $group['pct'] . '%'
                            : number_format( $group['pct'], 2, '.', '' ) . '%';
                    ?>
                        <li class="border-b border-ink/10" data-asset-group>
                            <div class="flex items-start justify-between gap-6 py-6">
                                <div>
                                    <p class="font-display text-2xl font-bold tracking-[-0.02em] text-ink">
                                        <?php echo esc_html( $group['asset_class'] ); ?>
                                    </p>
                                    <p class="mt-1 max-w-sm text-sm leading-relaxed text-ink/55">
                                        <?php echo esc_html( $asset_info[ $group['asset_class'] ] ?? '' ); ?>
                                    </p>
                                    <button type="button"
                                            data-asset-toggle
                                            aria-expanded="false"
                                            class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-qh-700 transition-colors hover:text-ink">
                                        <?php
                                        printf(
                                            /* translators: %d: número de activos */
                                            _n( 'Ver %d activo', 'Ver %d activos', (int) $count, 'quantia-hub' ),
                                            (int) $count
                                        );
                                        ?>
                                        <span data-asset-chevron class="transition-transform duration-200">
                                            <?php echo quantia_icon( 'chevron-down', 'h-4 w-4' ); ?>
                                        </span>
                                    </button>
                                </div>
                                <p class="shrink-0 font-display text-[clamp(1.75rem,1.2rem+2.2vw,3.5rem)] font-extrabold leading-none tracking-[-0.04em] text-qh">
                                    <?php echo esc_html( $pct_display ); ?>
                                </p>
                            </div>
                            <ul data-asset-funds hidden class="overflow-hidden">
                                <?php foreach ( $group['funds'] as $entry ) : ?>
                                    <li class="flex items-center justify-between gap-4 pb-4">
                                        <span class="flex items-center gap-3 text-base text-ink">
                                            <span class="h-2.5 w-2.5 rounded-full <?php echo esc_attr( $entry['fund']['swatch'] ); ?>"></span>
                                            <?php echo esc_html( $entry['fund']['name'] ); ?>
                                        </span>
                                        <span class="font-display text-base font-bold text-ink">
                                            <?php echo esc_html( number_format( $entry['pct'], 2, '.', '' ) ); ?>%
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</div>