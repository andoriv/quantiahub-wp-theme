<?php
/**
 * Barra de navegación principal flotante.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/*
 * Configuración.
 */
$primary_location = has_nav_menu( 'primary' );

$whatsapp_url = quantia_option(
	'contact_whatsapp_url',
	'https://wa.me/50764777460'
);

$products = quantia_get_product_nav();

$show_dropdown = (
	! empty( $products )
	&& quantia_section_visible( 'nav_products_dropdown' )
);

$show_app_cta = quantia_section_visible( 'home_app_banner' );

/*
 * Menú de respaldo.
 *
 * Este filtro permite que el módulo de visibilidad elimine
 * las páginas y secciones ocultas.
 */
$fallback_items = apply_filters(
	'quantia_primary_menu_fallback_items',
	array(
		array(
			'label' => __( 'Inicio', 'quantia-hub' ),
			'url'   => home_url( '/' ),
		),
		array(
			'label' => __( 'Aprende', 'quantia-hub' ),
			'url'   => home_url( '/aprende/' ),
		),
		array(
			'label' => __( 'Tarifas', 'quantia-hub' ),
			'url'   => home_url( '/tarifas/' ),
		),
		array(
			'label' => __( 'Nosotros', 'quantia-hub' ),
			'url'   => home_url( '/nosotros/' ),
		),
		array(
			'label' => __( 'Soporte', 'quantia-hub' ),
			'url'   => home_url( '/soporte/' ),
		),
	)
);

/*
 * Obtención del menú configurado en WordPress.
 */
$menu_items = array();

if ( $primary_location ) {
	$locations = get_nav_menu_locations();

	if ( ! empty( $locations['primary'] ) ) {
		$items = wp_get_nav_menu_items( $locations['primary'] );

		if ( is_array( $items ) ) {
			$menu_args = (object) array(
				'theme_location' => 'primary',
			);

			$items = apply_filters(
				'wp_nav_menu_objects',
				$items,
				$menu_args
			);

			foreach ( $items as $item ) {
				if ( 0 !== (int) $item->menu_item_parent ) {
					continue;
				}

				$menu_items[] = array(
					'label' => $item->title,
					'url'   => $item->url,
				);
			}
		}
	}
}

if ( empty( $menu_items ) ) {
	$menu_items = $fallback_items;
}
?>

<header
	id="qh-header"
	class="fixed inset-x-0 top-3 z-50 px-3 sm:top-4 sm:px-4"
	aria-label="<?php esc_attr_e( 'Barra de navegación principal', 'quantia-hub' ); ?>"
>
	<div class="mx-auto flex w-full max-w-5xl items-center justify-between gap-4 rounded-full border border-ink/10 bg-white/95 px-4 py-2 shadow-[0_8px_30px_rgba(11,31,23,0.08)] backdrop-blur">

		<a
			href="<?php echo esc_url( home_url( '/' ) ); ?>"
			aria-label="<?php esc_attr_e( 'Inicio de Quantia Hub', 'quantia-hub' ); ?>"
			class="shrink-0"
		>
			<?php
			get_template_part(
				'template-parts/components/logo',
				null,
				array(
					'variant' => 'light',
					'height'  => 'h-7',
				)
			);
			?>
		</a>

        <nav
            class="hidden items-center gap-0.5 lg:flex"
            aria-label="<?php esc_attr_e( 'Navegación principal', 'quantia-hub' ); ?>"
        >
            <?php if ( ! empty( $menu_items[0] ) ) : ?>
                <a
                    href="<?php echo esc_url( $menu_items[0]['url'] ); ?>"
                    class="rounded-full px-3 py-2 text-[13px] font-semibold text-ink/65 transition-colors hover:bg-mist hover:text-ink"
                >
                    <?php echo esc_html( $menu_items[0]['label'] ); ?>
                </a>
            <?php endif; ?>

            <?php if ( $show_dropdown ) : ?>
                <div class="relative" data-quantia-dropdown>
                    <button
                        type="button"
                        data-quantia-dropdown-trigger
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-controls="qh-products-panel"
                        class="flex items-center gap-1 rounded-full px-3 py-2 text-[13px] font-semibold text-ink/65 transition-colors hover:bg-mist hover:text-ink"
                    >
                        <?php esc_html_e( 'Productos', 'quantia-hub' ); ?>

                        <span
                            data-quantia-dropdown-icon
                            class="transition-transform duration-200"
                        >
                            <?php echo quantia_icon( 'chevron-down', 'h-3.5 w-3.5' ); ?>
                        </span>
                    </button>

                    <div
                        data-quantia-dropdown-panel
                        hidden
                        class="absolute left-1/2 top-full -translate-x-1/2 pt-3"
                    >
                        <div
                            id="qh-products-panel"
                            class="w-[460px] max-w-[90vw] rounded-2xl border border-ink/10 bg-white p-2 shadow-[0_20px_50px_-12px_rgba(11,31,23,0.2)]"
                        >
                            <ul class="grid grid-cols-2 gap-1">
                                <?php foreach ( $products as $product ) : ?>
                                    <li>
                                        <a
                                            href="<?php echo esc_url( $product['url'] ); ?>"
                                            class="flex gap-3 rounded-xl p-3 transition-colors hover:bg-mist"
                                        >
                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-qh-100 text-qh-700">
                                                <?php
                                                echo quantia_icon(
                                                    $product['icon'] ?? 'trending-up',
                                                    'h-4 w-4'
                                                );
                                                ?>
                                            </span>

                                            <span class="min-w-0">
                                                <span class="block text-sm font-semibold text-ink">
                                                    <?php echo esc_html( $product['label'] ); ?>
                                                </span>

                                                <?php if ( ! empty( $product['description'] ) ) : ?>
                                                    <span class="mt-0.5 block text-xs leading-snug text-ink/55">
                                                        <?php echo esc_html( $product['description'] ); ?>
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
            <?php endif; ?>

            <?php foreach ( array_slice( $menu_items, 1 ) as $item ) : ?>
                <a
                    href="<?php echo esc_url( $item['url'] ); ?>"
                    class="rounded-full px-3 py-2 text-[13px] font-semibold text-ink/65 transition-colors hover:bg-mist hover:text-ink"
                >
                    <?php echo esc_html( $item['label'] ); ?>
                </a>
            <?php endforeach; ?>
        </nav>

		<div class="flex items-center gap-2">
			<a
				href="<?php echo esc_url( $whatsapp_url ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				data-quantia-whatsapp
				data-product="general"
				data-location="header"
				class="hidden items-center gap-2 rounded-full px-3 py-2 text-[13px] font-semibold text-ink/70 transition-colors hover:bg-mist hover:text-ink xl:inline-flex"
			>
				<?php echo quantia_icon( 'message-circle', 'h-4 w-4' ); ?>
				<span><?php esc_html_e( 'WhatsApp', 'quantia-hub' ); ?></span>
			</a>

			<?php if ( $show_app_cta ) : ?>
				<a
					href="<?php echo esc_url( home_url( '/#descarga' ) ); ?>"
					data-quantia-app-download
					data-product="general"
					data-location="header"
					class="group hidden items-center gap-3 rounded-full bg-ink py-1.5 pl-5 pr-1.5 text-[13px] font-semibold text-white transition-colors hover:bg-qh-700 sm:inline-flex"
				>
					<span><?php esc_html_e( 'Descargar app', 'quantia-hub' ); ?></span>

					<span class="flex h-8 w-8 items-center justify-center rounded-full bg-qh text-white">
						<?php echo quantia_icon( 'arrow-right', 'h-4 w-4' ); ?>
					</span>
				</a>
			<?php endif; ?>

			<button
				type="button"
				data-quantia-mobile-open
				aria-expanded="false"
				aria-controls="qh-mobile-panel"
				aria-label="<?php esc_attr_e( 'Abrir menú', 'quantia-hub' ); ?>"
				class="flex h-10 w-10 items-center justify-center rounded-full bg-mist text-ink lg:hidden"
			>
				<?php echo quantia_icon( 'menu', 'h-5 w-5' ); ?>
			</button>
		</div>
	</div>

	<div
		id="qh-mobile-panel"
		data-quantia-mobile-panel
		hidden
		class="fixed inset-0 z-50 flex flex-col overflow-y-auto bg-white lg:hidden"
	>
		<div class="flex items-center justify-between border-b border-ink/10 px-5 py-3">
			<a
				href="<?php echo esc_url( home_url( '/' ) ); ?>"
				aria-label="<?php esc_attr_e( 'Inicio de Quantia Hub', 'quantia-hub' ); ?>"
			>
				<?php
				get_template_part(
					'template-parts/components/logo',
					null,
					array(
						'variant' => 'light',
						'height'  => 'h-7',
					)
				);
				?>
			</a>

			<button
				type="button"
				data-quantia-mobile-close
				aria-label="<?php esc_attr_e( 'Cerrar menú', 'quantia-hub' ); ?>"
				class="flex h-10 w-10 items-center justify-center rounded-full bg-mist text-ink"
			>
				<?php echo quantia_icon( 'x', 'h-5 w-5' ); ?>
			</button>
		</div>

		<nav
			aria-label="<?php esc_attr_e( 'Menú móvil', 'quantia-hub' ); ?>"
			class="flex-1 px-5 py-5"
		>
			<ul class="divide-y divide-ink/10">
				<?php foreach ( $menu_items as $item ) : ?>
					<li>
						<a
							href="<?php echo esc_url( $item['url'] ); ?>"
							class="flex items-center justify-between py-4 text-lg font-semibold text-ink"
						>
							<?php echo esc_html( $item['label'] ); ?>
							<?php echo quantia_icon( 'arrow-right', 'h-4 w-4 text-ink/30' ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php if ( $show_dropdown ) : ?>
				<div class="mt-8">
					<p class="text-xs font-semibold uppercase tracking-widest text-ink/40">
						<?php esc_html_e( 'Productos', 'quantia-hub' ); ?>
					</p>

					<ul class="mt-3 grid grid-cols-2 gap-2">
						<?php foreach ( $products as $product ) : ?>
							<li>
								<a
									href="<?php echo esc_url( $product['url'] ); ?>"
									class="flex items-center gap-2 rounded-xl bg-mist px-4 py-3 font-semibold text-ink"
								>
									<?php
									echo quantia_icon(
										$product['icon'] ?? 'trending-up',
										'h-4 w-4 text-qh-700'
									);
									?>

									<?php echo esc_html( $product['label'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</nav>

		<div class="border-t border-ink/10 px-5 py-4">
			<div class="flex flex-col gap-3">
				<?php if ( $show_app_cta ) : ?>
					<a
						href="<?php echo esc_url( home_url( '/#descarga' ) ); ?>"
						data-quantia-app-download
						data-product="general"
						data-location="mobile-menu"
						class="inline-flex items-center justify-center rounded-full bg-ink px-6 py-3 font-semibold text-white"
					>
						<?php esc_html_e( 'Descargar app', 'quantia-hub' ); ?>
					</a>
				<?php endif; ?>

				<a
					href="<?php echo esc_url( $whatsapp_url ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					data-quantia-whatsapp
					data-product="general"
					data-location="mobile-menu"
					class="inline-flex items-center justify-center gap-2 rounded-full border border-ink/15 px-4 py-3 text-sm font-semibold text-ink"
				>
					<?php echo quantia_icon( 'message-circle', 'h-4 w-4' ); ?>
					<?php esc_html_e( 'Escríbenos por WhatsApp', 'quantia-hub' ); ?>
				</a>
			</div>
		</div>
	</div>
</header>