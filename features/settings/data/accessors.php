<?php
/**
 * Settings accessors.
 *
 * Funciones de acceso a las opciones personalizadas. Devuelven el valor
 * guardado en la base de datos cuando existe y el valor por defecto cuando no.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Devuelve el array de una sección completa con su default si está vacía.
 *
 * @param string $key Clave de la sección. Ejemplo: calculator, benefits.
 * @return array
 */
function quantia_settings( string $key ): array {
    $stored = get_option( 'quantia_settings_' . $key, null );

    if ( is_array( $stored ) && ! empty( $stored ) ) {
        return $stored;
    }

    $default = quantia_settings_default( $key );
    return is_array( $default ) ? $default : array();
}

/**
 * Devuelve el array de filas de un repeater.
 *
 * @param string $key Clave de la sección.
 * @return array
 */
function quantia_settings_rows( string $key ): array {
    $section = quantia_settings( $key );
    return isset( $section['rows'] ) && is_array( $section['rows'] ) ? $section['rows'] : array();
}

/**
 * Actualiza el array de una sección completa.
 *
 * @param string $key   Clave de la sección.
 * @param array  $value Datos a guardar.
 */
function quantia_settings_update( string $key, array $value ): void {
    update_option( 'quantia_settings_' . $key, $value, false );
}

/**
 * Devuelve el array de opciones de la calculadora.
 *
 * @return array
 */
function quantia_get_calculator_options(): array {
    $section = quantia_settings( 'calculator' );
    $options = $section['options'] ?? array();

    if ( empty( $options ) ) {
        return array();
    }

    $out = array();
    foreach ( $options as $row ) {
        $out[] = array(
            'key'  => sanitize_key( $row['key'] ?? '' ),
            'name' => (string) ( $row['name'] ?? '' ),
            'kind' => (string) ( $row['kind'] ?? '' ),
            'rate' => (float) ( $row['rate'] ?? 0 ),
        );
    }
    return $out;
}

/**
 * Devuelve los valores por defecto de la calculadora.
 *
 * @return array
 */
function quantia_get_calculator_defaults(): array {
    $section = quantia_settings( 'calculator' );
    return array(
        'rate'    => (string) ( $section['default_rate'] ?? 'navegante' ),
        'initial' => (int) ( $section['default_initial'] ?? 500 ),
        'monthly' => (int) ( $section['default_monthly'] ?? 100 ),
        'years'   => (int) ( $section['default_years'] ?? 15 ),
    );
}

/**
 * Devuelve las tarjetas de beneficio del home.
 *
 * @return array
 */
function quantia_get_benefits(): array {
    $rows = quantia_settings_rows( 'benefits' );

    if ( empty( $rows ) ) {
        return array();
    }

    $out = array();
    foreach ( $rows as $row ) {
        $benefit = array(
            'id'           => sanitize_key( $row['benefit_id'] ?? '' ),
            'product'      => (string) ( $row['product'] ?? '' ),
            'title'        => (string) ( $row['title'] ?? '' ),
            'stat'         => (string) ( $row['stat'] ?? '' ),
            'stat_prefix'  => (string) ( $row['stat_prefix'] ?? '' ),
            'stat_suffix'  => (string) ( $row['stat_suffix'] ?? '' ),
            'description'  => (string) ( $row['description'] ?? '' ),
            'points'       => is_array( $row['points'] ?? null ) ? array_values( array_filter( array_map( 'strval', $row['points'] ) ) ) : array(),
            'screen'       => sanitize_key( $row['screen'] ?? 'inicio' ),
            'screen_label' => (string) ( $row['screen_label'] ?? '' ),
        );

        if ( ! empty( $row['link_label'] ) && ! empty( $row['link_url'] ) ) {
            $benefit['link'] = array(
                'label' => (string) $row['link_label'],
                'url'   => (string) $row['link_url'],
            );
        }

        if ( ! empty( $row['funds_plan'] ) ) {
            $benefit['funds_plan'] = (string) $row['funds_plan'];
            $benefit['funds_url']  = (string) ( $row['funds_url'] ?? '' );
        }

        $out[] = $benefit;
    }
    return $out;
}

/**
 * Devuelve los fondos de un plan.
 *
 * @param string $plan inversion o retiro.
 * @return array
 */
function quantia_get_funds( string $plan = 'inversion' ): array {
    $rows = quantia_settings_rows( 'funds' );

    if ( empty( $rows ) ) {
        return array();
    }

    $out = array();
    foreach ( $rows as $row ) {
        $row_plan = (string) ( $row['plan'] ?? 'inversion' );
        if ( $row_plan !== $plan ) {
            continue;
        }
        $out[] = array(
            'id'     => sanitize_key( $row['fund_id'] ?? '' ),
            'name'   => (string) ( $row['fund_name'] ?? '' ),
            'asset'  => (string) ( $row['asset_class'] ?? '' ),
            'swatch' => (string) ( $row['swatch'] ?? 'bg-qh' ),
        );
    }
    return $out;
}

/**
 * Devuelve el lookup de fondos indexado por identificador.
 *
 * @return array
 */
function quantia_get_fund_lookup(): array {
    $rows = quantia_settings_rows( 'funds' );

    if ( empty( $rows ) ) {
        return array();
    }

    $out = array();
    foreach ( $rows as $row ) {
        $id = sanitize_key( $row['fund_id'] ?? '' );
        if ( ! $id ) {
            continue;
        }
        $out[ $id ] = array(
            'id'          => $id,
            'name'        => (string) ( $row['fund_name'] ?? '' ),
            'asset_class' => (string) ( $row['asset_class'] ?? '' ),
            'swatch'      => (string) ( $row['swatch'] ?? 'bg-qh' ),
        );
    }
    return $out;
}

/**
 * Devuelve los perfiles de inversión.
 *
 * @return array
 */
function quantia_get_investment_profiles(): array {
    return quantia_build_profiles( quantia_settings_rows( 'investment_profiles' ), 'inversion' );
}

/**
 * Devuelve los perfiles de retiro.
 *
 * @return array
 */
function quantia_get_retirement_profiles(): array {
    return quantia_build_profiles( quantia_settings_rows( 'retirement_profiles' ), 'retiro' );
}

/**
 * Normaliza una lista de filas de perfil al formato que consumen las vistas.
 *
 * @param array  $rows Filas guardadas.
 * @param string $plan inversion o retiro.
 * @return array
 */
function quantia_build_profiles( array $rows, string $plan ): array {
    if ( empty( $rows ) ) {
        return array();
    }

    $out = array();
    foreach ( $rows as $row ) {
        $allocation = array();
        foreach ( $row['allocation'] ?? array() as $slice ) {
            if ( ! empty( $slice['fund'] ) ) {
                $allocation[] = array(
                    'fund' => sanitize_key( $slice['fund'] ),
                    'pct'  => (float) ( $slice['pct'] ?? 0 ),
                );
            }
        }

        $out[] = array(
            'key'        => sanitize_key( $row['profile_key'] ?? '' ),
            'name'       => (string) ( $row['name'] ?? '' ),
            'plan'       => $plan,
            'level'      => (int) ( $row['level'] ?? 1 ),
            'rate'       => (float) ( $row['rate'] ?? 0 ),
            'summary'    => (string) ( $row['summary'] ?? '' ),
            'allocation' => $allocation,
        );
    }
    return $out;
}

/**
 * Devuelve un valor específico de product_data.
 *
 * @param string $key     Clave dentro de product_data.
 * @param mixed  $default Valor de respaldo.
 * @return mixed
 */
function quantia_product_data( string $key, $default = null ) {
    $section = quantia_settings( 'product_data' );
    return $section[ $key ] ?? $default;
}