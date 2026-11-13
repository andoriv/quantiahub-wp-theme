<?php
/**
 * Settings save handlers.
 *
 * Cada sección tiene su propio handler que valida el nonce, sanitiza el array
 * recibido y guarda la opción correspondiente con update_option. Después del
 * guardado redirige a la misma página con el flag saved para mostrar el aviso.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_post_quantia_settings_save_calculator', 'quantia_settings_save_calculator' );
function quantia_settings_save_calculator(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sin permisos.' );
    }
    check_admin_referer( 'quantia_settings_calculator' );

    $options = array();
    foreach ( (array) ( $_POST['options'] ?? array() ) as $row ) {
        if ( empty( $row['key'] ) ) {
            continue;
        }
        $options[] = array(
            'key'  => sanitize_key( $row['key'] ),
            'name' => sanitize_text_field( $row['name'] ?? '' ),
            'kind' => sanitize_text_field( $row['kind'] ?? '' ),
            'rate' => (float) ( $row['rate'] ?? 0 ),
        );
    }

    $value = array(
        'options'         => $options,
        'default_rate'    => sanitize_key( $_POST['default_rate'] ?? 'navegante' ),
        'default_initial' => (int) ( $_POST['default_initial'] ?? 500 ),
        'default_monthly' => (int) ( $_POST['default_monthly'] ?? 100 ),
        'default_years'   => (int) ( $_POST['default_years'] ?? 15 ),
    );

    quantia_settings_update( 'calculator', $value );
    wp_safe_redirect( admin_url( 'admin.php?page=quantia-settings&saved=1' ) );
    exit;
}

add_action( 'admin_post_quantia_settings_save_benefits', 'quantia_settings_save_benefits' );
function quantia_settings_save_benefits(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sin permisos.' );
    }
    check_admin_referer( 'quantia_settings_benefits' );

    $rows = array();
    foreach ( (array) ( $_POST['rows'] ?? array() ) as $row ) {
        if ( empty( $row['benefit_id'] ) ) {
            continue;
        }
        $points = array();
        foreach ( preg_split( '/\r\n|\r|\n/', (string) ( $row['points_text'] ?? '' ) ) as $line ) {
            $line = trim( $line );
            if ( '' !== $line ) {
                $points[] = sanitize_text_field( $line );
            }
        }

        $rows[] = array(
            'benefit_id'   => sanitize_key( $row['benefit_id'] ),
            'product'      => sanitize_text_field( $row['product'] ?? '' ),
            'title'        => sanitize_text_field( $row['title'] ?? '' ),
            'stat'         => sanitize_text_field( $row['stat'] ?? '' ),
            'stat_prefix'  => sanitize_text_field( $row['stat_prefix'] ?? '' ),
            'stat_suffix'  => sanitize_text_field( $row['stat_suffix'] ?? '' ),
            'description'  => sanitize_textarea_field( $row['description'] ?? '' ),
            'points'       => $points,
            'screen'       => sanitize_key( $row['screen'] ?? 'inicio' ),
            'screen_label' => sanitize_text_field( $row['screen_label'] ?? '' ),
            'link_label'   => sanitize_text_field( $row['link_label'] ?? '' ),
            'link_url'     => esc_url_raw( $row['link_url'] ?? '' ),
            'funds_plan'   => sanitize_key( $row['funds_plan'] ?? '' ),
            'funds_url'    => esc_url_raw( $row['funds_url'] ?? '' ),
        );
    }

    quantia_settings_update( 'benefits', array( 'rows' => $rows ) );
    wp_safe_redirect( admin_url( 'admin.php?page=quantia-settings-benefits&saved=1' ) );
    exit;
}

add_action( 'admin_post_quantia_settings_save_funds', 'quantia_settings_save_funds' );
function quantia_settings_save_funds(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sin permisos.' );
    }
    check_admin_referer( 'quantia_settings_funds' );

    $rows = array();
    foreach ( (array) ( $_POST['rows'] ?? array() ) as $row ) {
        if ( empty( $row['fund_id'] ) ) {
            continue;
        }
        $rows[] = array(
            'fund_id'     => sanitize_key( $row['fund_id'] ),
            'fund_name'   => sanitize_text_field( $row['fund_name'] ?? '' ),
            'asset_class' => sanitize_text_field( $row['asset_class'] ?? 'Renta variable' ),
            'swatch'      => sanitize_key( $row['swatch'] ?? 'bg-qh' ),
            'plan'        => sanitize_key( $row['plan'] ?? 'inversion' ),
        );
    }

    quantia_settings_update( 'funds', array( 'rows' => $rows ) );
    wp_safe_redirect( admin_url( 'admin.php?page=quantia-settings-funds&saved=1' ) );
    exit;
}

add_action( 'admin_post_quantia_settings_save_profiles', 'quantia_settings_save_profiles' );
function quantia_settings_save_profiles(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sin permisos.' );
    }
    check_admin_referer( 'quantia_settings_profiles' );

    $type = sanitize_key( $_POST['profile_type'] ?? 'investment' );
    $section_key = 'investment' === $type ? 'investment_profiles' : 'retirement_profiles';

    $rows = array();
    foreach ( (array) ( $_POST['rows'] ?? array() ) as $row ) {
        if ( empty( $row['profile_key'] ) ) {
            continue;
        }
        $allocation = array();
        foreach ( preg_split( '/\r\n|\r|\n/', (string) ( $row['allocation_text'] ?? '' ) ) as $line ) {
            $line = trim( $line );
            if ( '' === $line ) {
                continue;
            }
            $parts = preg_split( '/\s*[|,]\s*/', $line );
            if ( count( $parts ) < 2 ) {
                continue;
            }
            $allocation[] = array(
                'fund' => sanitize_key( $parts[0] ),
                'pct'  => (float) $parts[1],
            );
        }

        $rows[] = array(
            'profile_key' => sanitize_key( $row['profile_key'] ),
            'name'        => sanitize_text_field( $row['name'] ?? '' ),
            'level'       => (int) ( $row['level'] ?? 1 ),
            'rate'        => (float) ( $row['rate'] ?? 0 ),
            'summary'     => sanitize_textarea_field( $row['summary'] ?? '' ),
            'allocation'  => $allocation,
        );
    }

    quantia_settings_update( $section_key, array( 'rows' => $rows ) );
    wp_safe_redirect( admin_url( 'admin.php?page=quantia-settings-profiles&saved=1' ) );
    exit;
}

add_action( 'admin_post_quantia_settings_save_product_data', 'quantia_settings_save_product_data' );
function quantia_settings_save_product_data(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sin permisos.' );
    }
    check_admin_referer( 'quantia_settings_product_data' );

    $value = array(
        'account_rate'         => (float) ( $_POST['account_rate'] ?? 0.04 ),
        'account_max_balance'  => (int) ( $_POST['account_max_balance'] ?? 50000 ),
        'investment_minimum'   => (int) ( $_POST['investment_minimum'] ?? 100 ),
        'investment_sale_fee'  => sanitize_text_field( $_POST['investment_sale_fee'] ?? '1% + ITBMS' ),
        'card_fraud_coverage'  => (int) ( $_POST['card_fraud_coverage'] ?? 3000 ),
        'card_emission_fee'    => sanitize_text_field( $_POST['card_emission_fee'] ?? 'B/. 21.40' ),
        'card_annual_fee'      => sanitize_text_field( $_POST['card_annual_fee'] ?? 'B/. 21.40' ),
        'card_replacement_fee' => sanitize_text_field( $_POST['card_replacement_fee'] ?? 'B/. 26.75' ),
    );

    quantia_settings_update( 'product_data', $value );
    wp_safe_redirect( admin_url( 'admin.php?page=quantia-settings-product-data&saved=1' ) );
    exit;
}

add_action( 'admin_post_quantia_settings_save_learn_concepts', 'quantia_settings_save_learn_concepts' );
function quantia_settings_save_learn_concepts(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sin permisos.' );
    }
    check_admin_referer( 'quantia_settings_learn_concepts' );

    $rows = array();
    foreach ( (array) ( $_POST['rows'] ?? array() ) as $row ) {
        if ( empty( $row['concept_id'] ) ) {
            continue;
        }

        $points = array();
        foreach ( preg_split( '/\r\n|\r|\n/', (string) ( $row['points_text'] ?? '' ) ) as $line ) {
            $line = trim( $line );
            if ( '' !== $line ) {
                $points[] = sanitize_text_field( $line );
            }
        }

        $rows[] = array(
            'concept_id'  => sanitize_key( $row['concept_id'] ),
            'label'       => sanitize_text_field( $row['label'] ?? '' ),
            'eyebrow'     => sanitize_text_field( $row['eyebrow'] ?? '' ),
            'title'       => sanitize_text_field( $row['title'] ?? '' ),
            'description' => sanitize_textarea_field( $row['description'] ?? '' ),
            'visual'      => sanitize_key( $row['visual'] ?? 'riesgo' ),
            'points'      => $points,
        );
    }

    quantia_settings_update( 'learn_concepts', array( 'rows' => $rows ) );
    wp_safe_redirect( admin_url( 'admin.php?page=quantia-settings-learn&saved=1' ) );
    exit;
}