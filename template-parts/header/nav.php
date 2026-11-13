<?php
/**
 * Header nav, pill flotante fijo.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$product_nav = quantia_get_product_nav();
?>

<header class="fixed inset-x-0 top-3 z-50 px-3 sm:top-4 sm:px-4">
    <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 rounded-full border border-ink/10 bg-white/95 px-4 py-2 shadow-[0_8px_30px_rgba(11,31,23,0.08)] backdrop-blur">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Inicio de Quantia Hub', 'quantia-hub' ); ?>" class="shrink-0">
            <?php get_template_part( 'template-parts/components/logo', null, array(
                'variant' => 'light',
                'height'  => 'h-7',
            ) ); ?>
        </a>

        <nav aria-label="<?php esc_attr_e( 'Navegación principal', 'quantia-hub' ); ?>" class="hidden items-center gap-0.5 lg:flex">
            <?php
            $home_class = is_front_page()
                ? 'bg-qh-50 text-qh-700'
                : 'text-ink/65 hover:bg-mist hover:text-ink';
            ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
               class="rounded-full px-3 py-2 text-[13px] font-semibold transition-colors <?php echo esc_attr( $home_class ); ?>">
                <?php esc_html_e( 'Inicio', 'quantia-hub' ); ?>
            </a>

            <div class="relative" data-quantia-dropdown>
                <button type="button"
                        aria-expanded="false"
                        aria-controls="menu-productos"
                        data-quantia-dropdown-trigger
                        class="flex items-center gap-1 rounded-full px-3 py-2 text-[13px] font-semibold text-ink/65 transition-colors hover:bg-mist hover:text-ink">
                    <?php esc_html_e( 'Productos', 'quantia-hub' ); ?>
                    <span data-quantia-dropdown-icon class="transition-transform duration-200">
                        <?php echo quantia_icon( 'chevron-down', 'h-3.5 w-3.5' ); // phpcs:ignore ?>
                    </span>
                </button>

                <div class="absolute left-1/2 top-full -translate-x-1/2 pt-3" hidden data-quantia-dropdown-panel>
                    <div id="menu-productos" class="w-[460px] origin-top rounded-2xl border border-ink/10 bg-white p-2 shadow-[0_20px_50px_-12px_rgba(11,31,23,0.2)]">
                        <ul class="grid grid-cols-2 gap-1">
                            <?php foreach ( $product_nav as $item ) : ?>
                                <li>
                                    <a href="<?php echo esc_url( $item['url'] ); ?>"
                                       class="flex gap-3 rounded-xl p-3 transition-colors hover:bg-mist">
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-qh-100 text-qh-700">
                                            <?php echo quantia_icon( $item['icon'] ?? 'trending-up', 'h-4 w-4' ); // phpcs:ignore ?>
                                        </span>
                                        <span>
                                            <span class="block text-sm font-semibold text-ink">
                                                <?php echo esc_html( $item['label'] ); ?>
                                            </span>
                                            <?php if ( ! empty( $item['description'] ) ) : ?>
                                                <span class="mt-0.5 block text-xs leading-snug text-ink/55">
                                                    <?php echo esc_html( $item['description'] ); ?>
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <?php
            $section_links = array(
                array( 'label' => __( 'Tarifas', 'quantia-hub' ),  'url' => home_url( '/tarifas/' ),  'match' => 'tarifas' ),
                array( 'label' => __( 'Aprende', 'quantia-hub' ),  'url' => home_url( '/aprende/' ),  'match' => 'aprende' ),
                array( 'label' => __( 'Soporte', 'quantia-hub' ),  'url' => home_url( '/soporte/' ),  'match' => 'soporte' ),
                array( 'label' => __( 'Nosotros', 'quantia-hub' ), 'url' => home_url( '/nosotros/' ), 'match' => 'nosotros' ),
            );
            foreach ( $section_links as $link ) :
                $is_active = is_page( $link['match'] ) || is_post_type_archive( $link['match'] );
                $class     = $is_active ? 'bg-qh-50 text-qh-700' : 'text-ink/65 hover:bg-mist hover:text-ink';
                ?>
                <a href="<?php echo esc_url( $link['url'] ); ?>"
                   class="rounded-full px-3 py-2 text-[13px] font-semibold transition-colors <?php echo esc_attr( $class ); ?>">
                    <?php echo esc_html( $link['label'] ); ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="flex items-center gap-2">
            <?php get_template_part( 'template-parts/components/download-app-button', null, array( 'class' => 'hidden !px-5 !py-2.5 !text-[13px] sm:inline-flex' ) ); ?>
            <button type="button"
                    data-quantia-mobile-open
                    aria-label="<?php esc_attr_e( 'Abrir menú', 'quantia-hub' ); ?>"
                    aria-expanded="false"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-mist text-ink transition-colors hover:bg-qh-100 lg:hidden">
                <?php echo quantia_icon( 'menu', 'h-5 w-5' ); // phpcs:ignore ?>
            </button>
        </div>
    </div>

    <div data-quantia-mobile-panel hidden class="fixed inset-0 z-50 overflow-y-auto bg-white lg:hidden">
        <div class="flex items-center justify-between border-b border-ink/10 px-5 py-3">
            <?php get_template_part( 'template-parts/components/logo', null, array(
                'variant' => 'light',
                'height'  => 'h-7',
            ) ); ?>
            <button type="button"
                    data-quantia-mobile-close
                    aria-label="<?php esc_attr_e( 'Cerrar menú', 'quantia-hub' ); ?>"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-mist text-ink">
                <?php echo quantia_icon( 'x', 'h-5 w-5' ); // phpcs:ignore ?>
            </button>
        </div>
        <nav aria-label="<?php esc_attr_e( 'Menú móvil', 'quantia-hub' ); ?>" class="px-5 py-5">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block py-3 font-display text-lg font-semibold text-ink">
                <?php esc_html_e( 'Inicio', 'quantia-hub' ); ?>
            </a>

            <p class="mt-4 text-[11px] font-semibold uppercase tracking-widest text-ink/40">
                <?php esc_html_e( 'Productos', 'quantia-hub' ); ?>
            </p>
            <ul class="mt-3 grid grid-cols-2 gap-2">
                <?php foreach ( $product_nav as $item ) : ?>
                    <li>
                        <a href="<?php echo esc_url( $item['url'] ); ?>"
                           class="flex items-center gap-2 rounded-xl bg-mist px-4 py-3 font-display text-base font-semibold text-ink">
                            <?php echo quantia_icon( $item['icon'] ?? 'trending-up', 'h-4 w-4 text-qh-700' ); // phpcs:ignore ?>
                            <?php echo esc_html( $item['label'] ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p class="mt-6 text-[11px] font-semibold uppercase tracking-widest text-ink/40">
                <?php esc_html_e( 'Sitio', 'quantia-hub' ); ?>
            </p>
            <ul class="mt-2 divide-y divide-ink/10">
                <?php foreach ( $section_links as $link ) : ?>
                    <li>
                        <a href="<?php echo esc_url( $link['url'] ); ?>"
                           class="block py-3.5 font-display text-lg font-semibold text-ink">
                            <?php echo esc_html( $link['label'] ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <?php get_template_part( 'template-parts/components/download-app-button', null, array( 'class' => 'mt-6 w-full' ) ); ?>
        </nav>
    </div>
</header>