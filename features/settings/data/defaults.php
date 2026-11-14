<?php
/**
 * Valores por defecto de todas las opciones del panel de configuración.
 *
 * Los textos están en español. El archivo .po/.mo de inglés actúa como
 * diccionario de traducción.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_settings_default( string $key ) {
    $defaults = array(
        'calculator' => array(
            'options' => array(
                array( 'key' => 'alcancia',   'name' => __( 'Alcancía', 'quantia-hub' ),   'kind' => __( 'Ahorro', 'quantia-hub' ),    'rate' => 0.04 ),
                array( 'key' => 'pasajero',   'name' => __( 'Pasajero', 'quantia-hub' ),   'kind' => __( 'Inversión', 'quantia-hub' ), 'rate' => 0.05 ),
                array( 'key' => 'navegante',  'name' => __( 'Navegante', 'quantia-hub' ),  'kind' => __( 'Inversión', 'quantia-hub' ), 'rate' => 0.07 ),
                array( 'key' => 'aventurero', 'name' => __( 'Aventurero', 'quantia-hub' ), 'kind' => __( 'Inversión', 'quantia-hub' ), 'rate' => 0.09 ),
            ),
            'default_rate'    => 'navegante',
            'default_initial' => 500,
            'default_monthly' => 100,
            'default_years'   => 15,
        ),
        'benefits' => array(
            'rows' => quantia_fallback_benefits(),
        ),
        'funds' => array(
            'rows' => array(
                array( 'fund_id' => 'systematic-equity',    'fund_name' => 'MMG Systematic Equity',      'asset_class' => 'Renta variable', 'swatch' => 'bg-ink',    'plan' => 'inversion' ),
                array( 'fund_id' => 'global-allocation',    'fund_name' => 'MMG Global Allocation Fund', 'asset_class' => 'Renta variable', 'swatch' => 'bg-qh-300', 'plan' => 'inversion' ),
                array( 'fund_id' => 'panama-allocation',    'fund_name' => 'MMG Panama Allocation Fund', 'asset_class' => 'Renta variable', 'swatch' => 'bg-qh',     'plan' => 'inversion' ),
                array( 'fund_id' => 'fixed-income',         'fund_name' => 'MMG Fixed Income Fund',      'asset_class' => 'Renta fija',     'swatch' => 'bg-qh-700', 'plan' => 'inversion' ),
                array( 'fund_id' => 'quantia-core',         'fund_name' => 'Quantia Core',               'asset_class' => 'Individual',     'swatch' => 'bg-qh-300', 'plan' => 'jubilación' ),
                array( 'fund_id' => 'quantia-growth',       'fund_name' => 'Quantia Growth',             'asset_class' => 'Individual',     'swatch' => 'bg-qh',     'plan' => 'jubilación' ),
                array( 'fund_id' => 'quantia-fixed-income', 'fund_name' => 'Quantia Fixed Income',       'asset_class' => 'Individual',     'swatch' => 'bg-qh-700', 'plan' => 'jubilación' ),
                array( 'fund_id' => 'quantia-select',       'fund_name' => 'Quantia Select',             'asset_class' => 'Individual',     'swatch' => 'bg-ink',    'plan' => 'jubilación' ),
            ),
        ),
        'investment_profiles' => array(
            'rows' => array(
                array(
                    'profile_key' => 'pasajero', 'name' => __( 'Pasajero', 'quantia-hub' ), 'level' => 1, 'rate' => 0.05,
                    'summary' => __( 'Prefieres un viaje tranquilo, más estabilidad y menos variación.', 'quantia-hub' ),
                    'allocation' => array(
                        array( 'fund' => 'fixed-income',      'pct' => 33.33 ),
                        array( 'fund' => 'panama-allocation', 'pct' => 33.33 ),
                        array( 'fund' => 'global-allocation', 'pct' => 33.34 ),
                    ),
                ),
                array(
                    'profile_key' => 'navegante', 'name' => __( 'Navegante', 'quantia-hub' ), 'level' => 2, 'rate' => 0.07,
                    'summary' => __( 'Buscas equilibrio entre estabilidad y crecimiento.', 'quantia-hub' ),
                    'allocation' => array(
                        array( 'fund' => 'fixed-income',      'pct' => 25 ),
                        array( 'fund' => 'panama-allocation', 'pct' => 25 ),
                        array( 'fund' => 'global-allocation', 'pct' => 25 ),
                        array( 'fund' => 'systematic-equity', 'pct' => 25 ),
                    ),
                ),
                array(
                    'profile_key' => 'aventurero', 'name' => __( 'Aventurero', 'quantia-hub' ), 'level' => 3, 'rate' => 0.09,
                    'summary' => __( 'Aceptas subidas y bajadas a cambio de mayor crecimiento a largo plazo.', 'quantia-hub' ),
                    'allocation' => array(
                        array( 'fund' => 'panama-allocation', 'pct' => 25 ),
                        array( 'fund' => 'global-allocation', 'pct' => 25 ),
                        array( 'fund' => 'systematic-equity', 'pct' => 50 ),
                    ),
                ),
            ),
        ),
        'retirement_profiles' => array(
            'rows' => array(
                array(
                    'profile_key' => 'conservador', 'name' => __( 'Conservador', 'quantia-hub' ), 'level' => 1, 'rate' => 0.05,
                    'summary' => __( 'Priorizas cuidar lo acumulado para tu jubilación.', 'quantia-hub' ),
                    'allocation' => array( array( 'fund' => 'quantia-select', 'pct' => 100 ) ),
                ),
                array(
                    'profile_key' => 'moderado', 'name' => __( 'Moderado', 'quantia-hub' ), 'level' => 2, 'rate' => 0.06,
                    'summary' => __( 'Buscas un crecimiento constante con variación moderada.', 'quantia-hub' ),
                    'allocation' => array( array( 'fund' => 'quantia-fixed-income', 'pct' => 100 ) ),
                ),
                array(
                    'profile_key' => 'agresivo', 'name' => __( 'Agresivo', 'quantia-hub' ), 'level' => 3, 'rate' => 0.08,
                    'summary' => __( 'Tienes tiempo por delante y buscas el mayor crecimiento posible.', 'quantia-hub' ),
                    'allocation' => array( array( 'fund' => 'quantia-growth', 'pct' => 100 ) ),
                ),
            ),
        ),
        'product_data' => array(
            'account_rate'         => 0.04,
            'account_max_balance'  => 50000,
            'investment_minimum'   => 100,
            'investment_sale_fee'  => '1% + ITBMS',
            'card_fraud_coverage'  => 3000,
            'card_emission_fee'    => 'B/. 21.40',
            'card_annual_fee'      => 'B/. 21.40',
            'card_replacement_fee' => 'B/. 26.75',
        ),
        'learn_concepts' => array(
            'rows' => array(
                array(
                    'concept_id'  => 'riesgo',
                    'label'       => __( 'Riesgo', 'quantia-hub' ),
                    'eyebrow'     => __( 'Fundamentos', 'quantia-hub' ),
                    'title'       => __( '¿Qué es el riesgo y por qué define tu portafolio?', 'quantia-hub' ),
                    'description' => __( 'El riesgo es la posibilidad de que el valor de tu inversión varíe frente a lo esperado. No se elimina: se administra según tu horizonte y tu tolerancia.', 'quantia-hub' ),
                    'visual'      => 'riesgo',
                    'points'      => array(
                        __( 'A mayor plazo, mayor capacidad de absorber variaciones del mercado.', 'quantia-hub' ),
                        __( 'Tu perfil de riesgo define la combinación de renta fija y renta variable recomendada.', 'quantia-hub' ),
                        __( 'El cuestionario de perfil en la app existe precisamente para ordenar esta decisión.', 'quantia-hub' ),
                    ),
                ),
                array(
                    'concept_id'  => 'diversificacion',
                    'label'       => __( 'Diversificación', 'quantia-hub' ),
                    'eyebrow'     => __( 'Estrategia', 'quantia-hub' ),
                    'title'       => __( 'Diversificación: no concentres tu patrimonio', 'quantia-hub' ),
                    'description' => __( 'Diversificar es distribuir tu inversión entre distintos instrumentos, sectores y mercados para que un solo evento no defina tu resultado.', 'quantia-hub' ),
                    'visual'      => 'diversificacion',
                    'points'      => array(
                        __( 'Un fondo ya invierte en múltiples instrumentos, lo que facilita diversificar con montos accesibles.', 'quantia-hub' ),
                        __( 'Combinar mercado local e internacional reduce la dependencia de una sola economía.', 'quantia-hub' ),
                        __( 'La diversificación reduce la volatilidad, no garantiza rendimientos.', 'quantia-hub' ),
                    ),
                ),
                array(
                    'concept_id'  => 'interes-compuesto',
                    'label'       => __( 'Interés compuesto', 'quantia-hub' ),
                    'eyebrow'     => __( 'Hábito', 'quantia-hub' ),
                    'title'       => __( 'Interés compuesto: el aporte constante pesa más que el monto', 'quantia-hub' ),
                    'description' => __( 'El interés compuesto es el rendimiento que genera tu rendimiento. Su efecto depende más del tiempo y la constancia que del monto inicial.', 'quantia-hub' ),
                    'visual'      => 'interes-compuesto',
                    'points'      => array(
                        __( 'Aportar de forma automática elimina la decisión mensual y sostiene el hábito.', 'quantia-hub' ),
                        __( 'Empezar temprano tiene más impacto que esperar a tener más dinero.', 'quantia-hub' ),
                        __( 'Retirar antes de tiempo interrumpe el efecto compuesto.', 'quantia-hub' ),
                    ),
                ),
                array(
                    'concept_id'  => 'ahorro-vs-inversion',
                    'label'       => __( 'Ahorro vs. inversión', 'quantia-hub' ),
                    'eyebrow'     => __( 'Fundamentos', 'quantia-hub' ),
                    'title'       => __( 'Ahorro e inversión cumplen funciones distintas', 'quantia-hub' ),
                    'description' => __( 'El ahorro te da liquidez y tranquilidad ante imprevistos; la inversión busca crecimiento en el mediano y largo plazo.', 'quantia-hub' ),
                    'visual'      => 'ahorro-vs-inversion',
                    'points'      => array(
                        __( 'Primero un fondo de imprevistos disponible, luego inversión de largo plazo.', 'quantia-hub' ),
                        __( 'Las Alcancías ordenan la liquidez; Inversiones y Jubilación trabajan el plazo.', 'quantia-hub' ),
                        __( 'Ambos pueden convivir dentro de la misma app.', 'quantia-hub' ),
                    ),
                ),
            ),
        ),
    );

    return $defaults[ $key ] ?? null;
}