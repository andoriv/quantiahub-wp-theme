<?php
/**
 * Settings administration menu.
 *
 * Registra el menú principal de configuración con cinco subpáginas: calculadora,
 * beneficios del home, fondos, perfiles y datos de producto. Cada subpágina
 * usa la capability nativa manage_options.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'quantia_settings_admin_menu', 5 );
function quantia_settings_admin_menu(): void {
    add_menu_page(
        __( 'Quantia Settings', 'quantia-hub' ),
        __( 'Quantia Settings', 'quantia-hub' ),
        'manage_options',
        'quantia-settings',
        'quantia_settings_page_calculator',
        'dashicons-admin-generic',
        3
    );

    add_submenu_page(
        'quantia-settings',
        __( 'Calculator', 'quantia-hub' ),
        __( 'Calculator', 'quantia-hub' ),
        'manage_options',
        'quantia-settings',
        'quantia_settings_page_calculator'
    );

    add_submenu_page(
        'quantia-settings',
        __( 'Home benefits', 'quantia-hub' ),
        __( 'Home benefits', 'quantia-hub' ),
        'manage_options',
        'quantia-settings-benefits',
        'quantia_settings_page_benefits'
    );

    add_submenu_page(
        'quantia-settings',
        __( 'Funds', 'quantia-hub' ),
        __( 'Funds', 'quantia-hub' ),
        'manage_options',
        'quantia-settings-funds',
        'quantia_settings_page_funds'
    );

    add_submenu_page(
        'quantia-settings',
        __( 'Profiles', 'quantia-hub' ),
        __( 'Profiles', 'quantia-hub' ),
        'manage_options',
        'quantia-settings-profiles',
        'quantia_settings_page_profiles'
    );

    add_submenu_page(
        'quantia-settings',
        __( 'Product data', 'quantia-hub' ),
        __( 'Product data', 'quantia-hub' ),
        'manage_options',
        'quantia-settings-product-data',
        'quantia_settings_page_product_data'
    );

        add_submenu_page(
        'quantia-settings',
        __( 'Aprende · Conceptos', 'quantia-hub' ),
        __( 'Aprende · Conceptos', 'quantia-hub' ),
        'manage_options',
        'quantia-settings-learn',
        'quantia_settings_page_learn'
    );
}