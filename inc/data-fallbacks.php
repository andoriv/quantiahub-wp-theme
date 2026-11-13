<?php
/**
 * Valores de respaldo del contenido de marketing del tema.
 *
 * Los textos están directamente en español. El archivo .mo de inglés actúa
 * como diccionario de traducción. El frontend resuelve la cadena final según
 * el locale que determina el helper de detección de idioma.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_fallback_hero_background(): string {
    $value = quantia_option( 'home_hero_background', '' );

    if ( is_array( $value ) && ! empty( $value['url'] ) ) {
        return $value['url'];
    }
    if ( is_numeric( $value ) ) {
        $url = wp_get_attachment_image_url( (int) $value, 'quantia-hero' );
        if ( $url ) {
            return $url;
        }
    }
    if ( is_string( $value ) && '' !== $value ) {
        return $value;
    }

    $local = QUANTIA_DIR . '/assets/img/hero-bg.jpg';
    if ( file_exists( $local ) ) {
        return quantia_asset( 'assets/img/hero-bg.jpg' );
    }

    return home_url( '/wp-content/uploads/2026/10/af429b5c-4afe-408f-904c-3b95da8b1a10.jpg' );
}

function quantia_fallback_hero_credentials(): array {
    return array(
        array( 'value' => '20+',       'label' => __( 'años de MMG Bank en banca de inversión', 'quantia-hub' ) ),
        array( 'value' => 'SBP · SMV', 'label' => __( 'Banco regulado y supervisado en Panamá', 'quantia-hub' ) ),
        array( 'value' => '4',         'label' => __( 'fondos gestionados por especialistas', 'quantia-hub' ) ),
        array( 'value' => 'Latinex',   'label' => __( 'Participante de la Bolsa de Valores', 'quantia-hub' ) ),
    );
}

function quantia_fallback_hero_products(): array {
    return array(
        array( 'slug' => 'inversiones', 'label' => __( 'Inversiones', 'quantia-hub' ),  'value' => '+4.8%',                     'detail' => __( 'Tu portafolio este año', 'quantia-hub' ),      'dark' => false, 'speed' => -140 ),
        array( 'slug' => 'alcancias',   'label' => __( 'Alcancías', 'quantia-hub' ),    'value' => 'US$900',                    'detail' => __( 'Viaje, 60% de tu meta', 'quantia-hub' ),      'progress' => 60, 'dark' => false, 'speed' => -70 ),
        array( 'slug' => 'retiro',      'label' => __( 'Retiro', 'quantia-hub' ),       'value' => 'US$8,920',                  'detail' => __( 'Fondo Quantia AFP', 'quantia-hub' ),          'dark' => false, 'speed' => -200 ),
        array( 'slug' => 'tarjeta',     'label' => __( 'Tarjetas', 'quantia-hub' ),     'value' => __( 'Visa Débito', 'quantia-hub' ), 'detail' => '•••• 0104',                            'dark' => true,  'speed' => -110 ),
    );
}

function quantia_fallback_benefits(): array {
    return array(
        array(
            'id' => 'inversiones',
            'product' => __( 'Inversiones', 'quantia-hub' ),
            'title' => __( 'Invierte con la experiencia de MMG', 'quantia-hub' ),
            'stat' => '100',
            'stat_prefix' => 'B/.',
            'stat_suffix' => __( 'para empezar', 'quantia-hub' ),
            'description' => __( 'Elige tu perfil (Pasajero, Navegante o Aventurero) y tu dinero se distribuye en fondos diversificados administrados por MMG Asset Management.', 'quantia-hub' ),
            'points' => array(
                __( 'Compra de activos sin costo', 'quantia-hub' ),
                __( 'Dividendos reinvertidos automáticamente', 'quantia-hub' ),
                __( 'Cuatro fondos gestionados por especialistas', 'quantia-hub' ),
            ),
            'screen' => 'inversiones',
            'screen_label' => __( 'Pantalla de inversiones de la app con el valor del portafolio y su distribución', 'quantia-hub' ),
            'link' => array( 'label' => __( 'Conoce Inversiones', 'quantia-hub' ), 'url' => home_url( '/productos/inversiones/' ) ),
            'funds_plan' => 'inversion',
            'funds_url' => home_url( '/productos/inversiones/#fondos' ),
        ),
        array(
            'id' => 'retiro',
            'product' => __( 'Retiro', 'quantia-hub' ),
            'title' => __( 'Tu retiro, desde hoy', 'quantia-hub' ),
            'stat' => '3',
            'stat_prefix' => '',
            'stat_suffix' => __( 'perfiles de retiro', 'quantia-hub' ),
            'description' => __( 'Conservador, Moderado o Agresivo: elige el plan que va con tu etapa y aporta mes a mes con Quantia AFP. El tiempo y el interés compuesto hacen el resto.', 'quantia-hub' ),
            'points' => array(
                __( 'Fondos Quantia Select, Fixed Income y Growth', 'quantia-hub' ),
                __( 'Aportes programados cada mes', 'quantia-hub' ),
                __( 'Saldo e historial siempre a la vista', 'quantia-hub' ),
            ),
            'screen' => 'retiro',
            'screen_label' => __( 'Pantalla de retiro de la app con el saldo acumulado del fondo', 'quantia-hub' ),
            'link' => array( 'label' => __( 'Conoce Retiro', 'quantia-hub' ), 'url' => home_url( '/productos/retiro/' ) ),
            'funds_plan' => 'retiro',
            'funds_url' => home_url( '/productos/retiro/#fondos' ),
        ),
        array(
            'id' => 'interes',
            'product' => __( 'Cuenta corriente', 'quantia-hub' ),
            'title' => __( 'Tu cuenta te paga intereses', 'quantia-hub' ),
            'stat' => '4%',
            'stat_prefix' => '',
            'stat_suffix' => __( 'anual', 'quantia-hub' ),
            'description' => __( 'Ganas un interés anualizado sobre tu saldo promedio mensual, hasta B/. 50,000. Lo recibes en tu cuenta el día 1 de cada mes. La tasa está sujeta a cambios.', 'quantia-hub' ),
            'points' => array(
                __( 'Sin monto mínimo para mantener tu cuenta activa', 'quantia-hub' ),
                __( 'Recibe por ACH, Kuara y entre usuarios Quantia', 'quantia-hub' ),
                __( 'Revisa cada movimiento en tiempo real', 'quantia-hub' ),
            ),
            'screen' => 'inicio',
            'screen_label' => __( 'Pantalla de inicio de la app con saldo disponible y productos', 'quantia-hub' ),
            'link' => array( 'label' => __( 'Ver tarifas de la cuenta', 'quantia-hub' ), 'url' => home_url( '/tarifas/' ) ),
        ),
        array(
            'id' => 'kuara',
            'product' => __( 'Kuara', 'quantia-hub' ),
            'title' => __( 'Transfiere a otros bancos al instante', 'quantia-hub' ),
            'stat' => '24/7',
            'stat_prefix' => '',
            'stat_suffix' => __( 'transferencias interbancarias', 'quantia-hub' ),
            'description' => __( 'Kuara es la plataforma para enviar dinero a otros bancos de forma instantánea. Las transacciones se procesan al momento, a cualquier hora, y no tienen comisión.', 'quantia-hub' ),
            'points' => array(
                __( 'Sin comisión por transferencia', 'quantia-hub' ),
                __( 'Acreditación inmediata', 'quantia-hub' ),
                __( 'MMG Bank, Global Bank, Banesco y Banistmo', 'quantia-hub' ),
            ),
            'screen' => 'kuara',
            'screen_label' => __( 'Pantalla de transferencia Kuara con banco destino, monto y confirmación instantánea', 'quantia-hub' ),
            'link' => array( 'label' => __( 'Preguntas sobre Kuara', 'quantia-hub' ), 'url' => home_url( '/soporte/' ) ),
        ),
        array(
            'id' => 'alcancias',
            'product' => __( 'Alcancías', 'quantia-hub' ),
            'title' => __( 'Separa tu dinero por metas', 'quantia-hub' ),
            'stat' => __( 'Sin costo', 'quantia-hub' ),
            'stat_prefix' => '',
            'stat_suffix' => __( 'crear alcancías', 'quantia-hub' ),
            'description' => __( 'Crea alcancías META con fecha y monto objetivo, o LIBRE para ahorrar a tu ritmo. Programa aportes automáticos y mira tu avance.', 'quantia-hub' ),
            'points' => array(
                __( 'Aportes y retiros sin penalización', 'quantia-hub' ),
                __( 'Aportes automáticos programados', 'quantia-hub' ),
                __( 'Avance de cada meta siempre a la vista', 'quantia-hub' ),
            ),
            'screen' => 'alcancias',
            'screen_label' => __( 'Pantalla de alcancías de la app con metas y porcentaje de avance', 'quantia-hub' ),
            'link' => array( 'label' => __( 'Conoce Alcancías', 'quantia-hub' ), 'url' => home_url( '/productos/alcancias/' ) ),
        ),
        array(
            'id' => 'tarjeta',
            'product' => __( 'Tarjeta Visa Débito', 'quantia-hub' ),
            'title' => __( 'Paga y retira con tu Visa Débito', 'quantia-hub' ),
            'stat' => '3,000',
            'stat_prefix' => 'B/.',
            'stat_suffix' => __( 'de cobertura por evento', 'quantia-hub' ),
            'description' => __( 'Usa el saldo de tu cuenta en comercios, compras online, suscripciones y cajeros. El seguro de fraude protege tu dinero ante transacciones no reconocidas.', 'quantia-hub' ),
            'points' => array(
                __( 'Bloquéala desde la app con un toque', 'quantia-hub' ),
                __( 'Vincúlala y actívala al instante', 'quantia-hub' ),
                __( 'Envío sin costo en Panamá Centro', 'quantia-hub' ),
            ),
            'screen' => 'tarjeta',
            'screen_label' => __( 'Pantalla de tarjeta de la app con la Visa Débito y la opción de bloqueo', 'quantia-hub' ),
            'link' => array( 'label' => __( 'Conoce la Tarjeta', 'quantia-hub' ), 'url' => home_url( '/productos/tarjeta/' ) ),
        ),
    );
}

function quantia_fallback_funds( string $plan ): array {
    if ( 'inversion' === $plan ) {
        return array(
            array( 'name' => 'MMG Systematic Equity',      'swatch' => 'bg-ink',    'asset' => 'Renta variable' ),
            array( 'name' => 'MMG Global Allocation Fund', 'swatch' => 'bg-qh-300', 'asset' => 'Renta variable' ),
            array( 'name' => 'MMG Panama Allocation Fund', 'swatch' => 'bg-qh',     'asset' => 'Renta variable' ),
            array( 'name' => 'MMG Fixed Income Fund',      'swatch' => 'bg-qh-700', 'asset' => 'Renta fija' ),
        );
    }
    return array(
        array( 'name' => 'Quantia Core',         'swatch' => 'bg-qh-300', 'asset' => 'Plan de retiro' ),
        array( 'name' => 'Quantia Growth',       'swatch' => 'bg-qh',     'asset' => 'Plan de retiro' ),
        array( 'name' => 'Quantia Fixed Income', 'swatch' => 'bg-qh-700', 'asset' => 'Plan de retiro' ),
        array( 'name' => 'Quantia Select',       'swatch' => 'bg-ink',    'asset' => 'Plan de retiro' ),
    );
}

function quantia_fallback_about_highlights(): array {
    return array(
        array(
            'title'       => __( 'Respaldo de MMG Bank', 'quantia-hub' ),
            'description' => __( 'Quantia es la aplicación financiera de MMG Bank Corporation, banca de inversión con trayectoria en Panamá.', 'quantia-hub' ),
        ),
        array(
            'title'       => __( 'Entidades reguladas', 'quantia-hub' ),
            'description' => __( 'Operamos bajo la supervisión de la Superintendencia de Bancos de Panamá y la Superintendencia del Mercado de Valores.', 'quantia-hub' ),
        ),
        array(
            'title'       => __( 'Tu información protegida', 'quantia-hub' ),
            'description' => __( 'Tratamos tus datos conforme a la normativa panameña de protección de datos personales.', 'quantia-hub' ),
        ),
    );
}

function quantia_fallback_onboarding_steps(): array {
    return array(
        array(
            'id'          => 'descarga',
            'title'       => __( 'Descarga Quantia Hub', 'quantia-hub' ),
            'description' => __( 'Disponible en iOS y Android, sin costo de apertura ni saldo mínimo.', 'quantia-hub' ),
        ),
        array(
            'id'          => 'verifica',
            'title'       => __( 'Verifica tu identidad', 'quantia-hub' ),
            'description' => __( 'Toma una foto de tu documento y una selfie para validar tu identidad.', 'quantia-hub' ),
        ),
        array(
            'id'          => 'activa',
            'title'       => __( 'Activa tus productos', 'quantia-hub' ),
            'description' => __( 'Crea tu primera alcancía, solicita tu tarjeta o define tu perfil de inversión.', 'quantia-hub' ),
        ),
    );
}

function quantia_fallback_testimonials(): array {
    return array(
        array(
            'name'      => 'Diego',
            'detail'    => __( '22 años, estudiante', 'quantia-hub' ),
            'product'   => __( 'Alcancías', 'quantia-hub' ),
            'quote'     => __( 'Separé mi dinero en alcancías y por primera vez llegué a fin de mes con ahorro.', 'quantia-hub' ),
            'highlight' => array( 'primera', 'vez', 'ahorro.' ),
        ),
        array(
            'name'      => 'Carlos',
            'detail'    => __( '35 años, diseñador', 'quantia-hub' ),
            'product'   => __( 'Inversiones', 'quantia-hub' ),
            'quote'     => __( 'Pensaba que invertir era para expertos. Empecé con $100 y sé exactamente dónde está mi dinero.', 'quantia-hub' ),
            'highlight' => array( 'empecé', 'con', '$100' ),
        ),
        array(
            'name'      => 'Marisol',
            'detail'    => __( '50 años, contadora', 'quantia-hub' ),
            'product'   => __( 'Retiro', 'quantia-hub' ),
            'quote'     => __( 'Saber que detrás está MMG Bank me dio la confianza para empezar mi plan de retiro.', 'quantia-hub' ),
            'highlight' => array( 'MMG', 'Bank', 'confianza' ),
        ),
        array(
            'name'      => 'Rogelio',
            'detail'    => __( '66 años, jubilado', 'quantia-hub' ),
            'product'   => __( 'Tarjeta Visa Débito', 'quantia-hub' ),
            'quote'     => __( 'La abrí en minutos con ayuda de mi nieta. Ahora pago y reviso todo sin ir al banco.', 'quantia-hub' ),
            'highlight' => array( 'minutos', 'sin', 'ir', 'al', 'banco.' ),
        ),
    );
}

function quantia_fallback_articles(): array {
    return array(
        array(
            'slug'       => 'subidas-y-bajadas-del-mercado',
            'title'      => __( 'Entender las subidas y bajadas del mercado', 'quantia-hub' ),
            'date'       => __( '12 de marzo de 2026', 'quantia-hub' ),
            'iso'        => '2026-03-12',
            'categories' => array( __( 'Inversión', 'quantia-hub' ) ),
            'excerpt'    => __( 'Una guía para inversionistas que empiezan.', 'quantia-hub' ),
            'visual'     => 'riesgo',
        ),
        array(
            'slug'       => 'portafolio-de-inversion-y-fondo-de-jubilacion',
            'title'      => __( '¿Qué es un portafolio de inversión y qué es un fondo de jubilación?', 'quantia-hub' ),
            'date'       => __( '8 de octubre de 2025', 'quantia-hub' ),
            'iso'        => '2025-10-08',
            'categories' => array( __( 'Inversión', 'quantia-hub' ), __( 'Jubilación', 'quantia-hub' ) ),
            'excerpt'    => __( 'En Quantia Hub puedes acceder a ambos productos, respaldados por MMG Asset Management.', 'quantia-hub' ),
            'visual'     => 'diversificacion',
        ),
        array(
            'slug'       => 'interes-simple-y-compuesto',
            'title'      => __( '¿Qué es el interés simple y cómo se diferencia del compuesto?', 'quantia-hub' ),
            'date'       => __( '8 de octubre de 2025', 'quantia-hub' ),
            'iso'        => '2025-10-08',
            'categories' => array( __( 'Inversión', 'quantia-hub' ) ),
            'excerpt'    => __( 'Se escucha al hablar de préstamos, ahorros e inversiones. Pero no todos los intereses son iguales.', 'quantia-hub' ),
            'visual'     => 'interes-compuesto',
        ),
        array(
            'slug'       => 'ahorro-vs-inversion',
            'title'      => __( 'Ahorro vs. inversión: ¿por qué tu dinero pierde valor si no lo inviertes?', 'quantia-hub' ),
            'date'       => __( '8 de octubre de 2025', 'quantia-hub' ),
            'iso'        => '2025-10-08',
            'categories' => array( __( 'Ahorros', 'quantia-hub' ), __( 'Inversión', 'quantia-hub' ) ),
            'excerpt'    => __( 'La inflación hace que tu dinero guardado pierda poder de compra.', 'quantia-hub' ),
            'visual'     => 'ahorro-vs-inversion',
        ),
        array(
            'slug'       => 'ahorrar-primero-gastar-despues',
            'title'      => __( '¿Por qué debes ahorrar primero y gastar después?', 'quantia-hub' ),
            'date'       => __( '7 de octubre de 2025', 'quantia-hub' ),
            'iso'        => '2025-10-07',
            'categories' => array( __( 'Ahorros', 'quantia-hub' ) ),
            'excerpt'    => __( 'La guía definitiva para tomar control de tus finanzas.', 'quantia-hub' ),
            'visual'     => 'ahorro-primero',
        ),
    );
}

function quantia_fallback_app_tabs(): array {
    return array(
        array( 'key' => 'inicio',      'label' => __( 'Inicio', 'quantia-hub' ),     'title' => __( 'Tu cuenta en una pantalla', 'quantia-hub' ),  'description' => __( 'Saldo disponible, accesos rápidos y el resumen de todos tus productos.', 'quantia-hub' ) ),
        array( 'key' => 'alcancias',   'label' => __( 'Ahorro', 'quantia-hub' ),     'title' => __( 'Alcancías META y LIBRE', 'quantia-hub' ),     'description' => __( 'Avance de cada objetivo, interés ganado y aportes programados.', 'quantia-hub' ) ),
        array( 'key' => 'inversiones', 'label' => __( 'Invertir', 'quantia-hub' ),   'title' => __( 'Tu portafolio', 'quantia-hub' ),             'description' => __( 'Rendimiento, perfil de riesgo y distribución por fondo.', 'quantia-hub' ) ),
        array( 'key' => 'retiro',      'label' => __( 'Retiro', 'quantia-hub' ),     'title' => __( 'Fondo de jubilación', 'quantia-hub' ),        'description' => __( 'Aportes, historial y saldo de tu fondo en Quantia AFP.', 'quantia-hub' ) ),
        array( 'key' => 'tarjeta',     'label' => __( 'Tarjeta', 'quantia-hub' ),    'title' => __( 'Visa Débito', 'quantia-hub' ),               'description' => __( 'Movimientos al instante y bloqueo de tu tarjeta con un toque.', 'quantia-hub' ) ),
    );
}

function quantia_fallback_fee_schedules(): array {
    $investment_rows = array(
        array( 'concept' => __( 'Comisión por compra de activo', 'quantia-hub' ), 'cost' => __( 'Sin costo', 'quantia-hub' ) ),
        array( 'concept' => __( 'Comisión por venta de activo', 'quantia-hub' ),  'cost' => __( '1% + ITBMS', 'quantia-hub' ), 'note' => __( 'Sobre el valor de la venta', 'quantia-hub' ) ),
    );

    return array(
        array(
            'id'          => 'persona-natural',
            'label'       => __( 'Clientes Persona Natural', 'quantia-hub' ),
            'short_label' => __( 'Persona Natural', 'quantia-hub' ),
            'description' => __( 'Para quienes abren su cuenta Quantia Hub de forma individual.', 'quantia-hub' ),
            'groups'      => array(
                array(
                    'id'    => 'cuenta',
                    'title' => __( 'Cuenta Corriente', 'quantia-hub' ),
                    'rows'  => array(
                        array( 'concept' => __( 'Interés sobre saldo promedio mensual', 'quantia-hub' ),              'cost' => __( '4% anual', 'quantia-hub' ), 'note' => __( 'Desde B/. 0.01 hasta B/. 50,000.00', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Anualidad por el servicio de transferencias ACH', 'quantia-hub' ),    'cost' => 'B/. 10.70' ),
                        array( 'concept' => __( 'Transacción ACH débito rechazada por fondos insuficientes', 'quantia-hub' ), 'cost' => 'B/. 5.35' ),
                    ),
                ),
                array(
                    'id'    => 'inversiones',
                    'title' => __( 'Portafolio de Inversiones', 'quantia-hub' ),
                    'rows'  => $investment_rows,
                ),
                array(
                    'id'    => 'tarjeta',
                    'title' => __( 'Tarjetas Visa Débito', 'quantia-hub' ),
                    'rows'  => array(
                        array( 'concept' => __( 'Emisión de tarjeta débito', 'quantia-hub' ),                                                    'cost' => 'B/. 21.40' ),
                        array( 'concept' => __( 'Membresía anual de la tarjeta débito', 'quantia-hub' ),                                          'cost' => 'B/. 21.40' ),
                        array( 'concept' => __( 'Reposición de tarjeta débito', 'quantia-hub' ),                                                  'cost' => 'B/. 26.75' ),
                        array( 'concept' => __( 'Reimpresión de PIN', 'quantia-hub' ),                                                            'cost' => 'B/. 5.35' ),
                        array( 'concept' => __( 'Consultas y transacciones en ATM de la red CLAVE', 'quantia-hub' ),                              'cost' => 'B/. 1.07' ),
                        array( 'concept' => __( 'Consultas y transacciones en ATM fuera de la red CLAVE', 'quantia-hub' ),                        'cost' => __( '3.21% + ITBMS', 'quantia-hub' ), 'note' => __( 'Del monto a retirar, mínimo B/. 7.49', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Seguro de fraude Visa Débito', 'quantia-hub' ),                                                  'cost' => 'B/. 1.05', 'note' => __( 'Máx. B/. 3,000 por evento. Deducible B/. 50.00', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Cargo por reclamo Visa Débito', 'quantia-hub' ),                                                 'cost' => 'B/. 21.40' ),
                        array( 'concept' => __( 'Envío de tarjeta y PIN en Panamá Centro', 'quantia-hub' ),                                       'cost' => __( 'Sin costo', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Envío de tarjeta y PIN en otras áreas de Panamá y provincias', 'quantia-hub' ),                  'cost' => __( 'Según destino + ITBMS', 'quantia-hub' ), 'note' => __( 'Mínimo B/. 6.42', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Envío de tarjeta por Courier', 'quantia-hub' ),                                                  'cost' => 'B/. 53.50' ),
                        array( 'concept' => __( 'Servicio de mensajería en Panamá Centro', 'quantia-hub' ),                                       'cost' => __( 'Sin costo', 'quantia-hub' ) ),
                    ),
                ),
            ),
        ),
        array(
            'id'          => 'corporativo',
            'label'       => __( 'Clientes Corporativos (Cuentas de Planilla)', 'quantia-hub' ),
            'short_label' => __( 'Corporativo, planilla', 'quantia-hub' ),
            'description' => __( 'Para colaboradores que reciben su salario en una cuenta de planilla Quantia Hub.', 'quantia-hub' ),
            'groups'      => array(
                array(
                    'id'    => 'cuenta',
                    'title' => __( 'Cuenta de Planilla', 'quantia-hub' ),
                    'rows'  => array(
                        array( 'concept' => __( 'Interés sobre saldo promedio mensual', 'quantia-hub' ),                                'cost' => __( '4% anual', 'quantia-hub' ), 'note' => __( 'Desde B/. 0.01 hasta B/. 50,000.00', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Anualidad por el servicio de transferencias ACH', 'quantia-hub' ),                      'cost' => __( 'Sin costo', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Transacción ACH débito rechazada por fondos insuficientes', 'quantia-hub' ),            'cost' => 'B/. 5.35' ),
                        array( 'concept' => __( 'Anticipos de quincena', 'quantia-hub' ),                                                'cost' => __( '5% + ITBMS', 'quantia-hub' ), 'note' => __( 'Del monto solicitado o renovado', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'id'    => 'inversiones',
                    'title' => __( 'Portafolio de Inversiones', 'quantia-hub' ),
                    'rows'  => $investment_rows,
                ),
                array(
                    'id'    => 'tarjeta',
                    'title' => __( 'Tarjetas Visa Débito', 'quantia-hub' ),
                    'rows'  => array(
                        array( 'concept' => __( 'Emisión de tarjeta débito', 'quantia-hub' ),                                                    'cost' => __( 'Sin costo', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Membresía anual de la tarjeta débito', 'quantia-hub' ),                                          'cost' => __( 'Sin costo', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Reposición de tarjeta débito', 'quantia-hub' ),                                                  'cost' => 'B/. 16.05' ),
                        array( 'concept' => __( 'Reimpresión de PIN', 'quantia-hub' ),                                                            'cost' => 'B/. 3.21' ),
                        array( 'concept' => __( 'Consultas y transacciones en ATM de la red CLAVE', 'quantia-hub' ),                              'cost' => 'B/. 0.00', 'note' => __( 'Máx. 8 transacciones al mes, luego B/. 1.07 c/u', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Consultas y transacciones en ATM fuera de la red CLAVE', 'quantia-hub' ),                        'cost' => __( '3.21% + ITBMS', 'quantia-hub' ), 'note' => __( 'Del monto a retirar, mínimo B/. 7.49', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Seguro de fraude Visa Débito', 'quantia-hub' ),                                                  'cost' => 'B/. 1.05', 'note' => __( 'Máx. B/. 3,000 por evento. Deducible B/. 50.00', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Cargo por reclamo Visa Débito', 'quantia-hub' ),                                                 'cost' => 'B/. 21.40' ),
                        array( 'concept' => __( 'Envío de tarjeta y PIN en Panamá Centro', 'quantia-hub' ),                                       'cost' => __( 'Sin costo', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Envío de tarjeta y PIN en otras áreas de Panamá y provincias', 'quantia-hub' ),                  'cost' => __( 'Sin costo', 'quantia-hub' ) ),
                        array( 'concept' => __( 'Envío de tarjeta por Courier', 'quantia-hub' ),                                                  'cost' => 'B/. 53.50' ),
                        array( 'concept' => __( 'Servicio de mensajería en Panamá Centro', 'quantia-hub' ),                                       'cost' => __( 'Sin costo', 'quantia-hub' ) ),
                    ),
                ),
            ),
        ),
    );
}

function quantia_is_free( string $cost ): bool {
    return __( 'Sin costo', 'quantia-hub' ) === $cost
        || __( 'No cost', 'quantia-hub' ) === $cost
        || 0 === strpos( $cost, 'B/. 0.00' );
}

function quantia_fee_schedule_counts( array $schedule ): array {
    $rows = 0;
    $free = 0;
    foreach ( $schedule['groups'] as $group ) {
        foreach ( $group['rows'] as $row ) {
            $rows++;
            if ( quantia_is_free( $row['cost'] ) ) {
                $free++;
            }
        }
    }
    return array( 'rows' => $rows, 'free' => $free );
}

function quantia_fallback_fee_legal_text(): array {
    return array(
        __( 'De acuerdo con la Ley 49 de 17 de septiembre de 2009, a todas las comisiones generadas por servicios bancarios, financieros y/o de inversión, les será aplicado el Impuesto sobre la Transferencia de Bienes Corporales Muebles y la Prestación de Servicios (ITBMS).', 'quantia-hub' ),
        __( 'Esta institución es regulada y supervisada por la Superintendencia de Bancos de Panamá y la Comisión Nacional de Valores de Panamá.', 'quantia-hub' ),
        __( 'El Cliente por este medio acepta todas y cada una de las tarifas, comisiones y cargos previstos en el presente folleto informativo de tarifas; así como pagar todas las tarifas y comisiones asociadas con los productos y servicios que obtenga del Banco, de acuerdo con la tarifa de honorarios y comisiones vigentes. El Banco podrá, de tiempo en tiempo y a su entera discreción, modificar los montos de tales tarifas.', 'quantia-hub' ),
        __( 'El Cliente autoriza al Banco para cobrar mediante el débito a cualquier cuenta que mantenga con el Banco y/o sus subsidiarias, todos los cargos, comisiones y tarifas por la apertura, manejo, cierre de cuenta y por los demás servicios y productos que ofrece el Banco de conformidad con las tarifas adoptadas de tiempo en tiempo. Queda entendido que el Cliente correrá con todos los tributos, impuestos, tasas, arbitrios o contribuciones que recaen o lleguen a recaer sobre los productos o servicios ofrecidos por el Banco.', 'quantia-hub' ),
    );
}

function quantia_fallback_fee_sheet(): array {
    return array(
        'label' => __( 'Descargar tarifario completo (PDF)', 'quantia-hub' ),
        'href'  => home_url( '/wp-content/uploads/2026/10/tarifario-quantia-hub.pdf' ),
    );
}

function quantia_fallback_products(): array {
    return array(
        'inversiones' => array(
            'slug'             => 'inversiones',
            'key'              => 'inversiones',
            'name'             => __( 'Inversiones', 'quantia-hub' ),
            'eyebrow'          => __( 'De ahorrar a invertir', 'quantia-hub' ),
            'title'            => __( 'Portafolios de', 'quantia-hub' ),
            'title_highlight'  => __( 'inversión', 'quantia-hub' ),
            'intro'            => __( 'Conoce tu perfil de riesgo, revisa la estrategia recomendada y ejecuta tus compras desde la app. Inviertes de forma diversificada a través de los fondos de MMG Bank.', 'quantia-hub' ),
            'hero_image'       => home_url( '/wp-content/uploads/2026/10/6c38f06a-7560-42c3-bfbf-906b175fdc95.jpg' ),
            'detail_image'     => home_url( '/wp-content/uploads/2026/10/6c638953-09cb-4d25-9c8b-1e8ce1c7e277.jpg' ),
            'card_summary'     => __( 'Portafolios diversificados administrados según tu perfil de riesgo.', 'quantia-hub' ),
            'what_is_title'    => __( '¿Qué es Inversiones?', 'quantia-hub' ),
            'what_is'          => array(
                __( 'Es el módulo de Quantia Hub donde inviertes en fondos administrados por MMG Bank. Completas un cuestionario de perfil, recibes una estrategia acorde a tu tolerancia al riesgo y ejecutas las compras para construir tu portafolio.', 'quantia-hub' ),
                __( 'La administración y el rebalanceo de la estrategia los realiza el equipo de MMG Bank; tú defines el monto, la frecuencia y el objetivo.', 'quantia-hub' ),
            ),
            'audience'         => array(
                __( 'Ya tienes un ahorro formado y quieres que genere rendimiento', 'quantia-hub' ),
                __( 'Buscas diversificar en mercados locales e internacionales', 'quantia-hub' ),
                __( 'Prefieres que un equipo especialista administre la estrategia', 'quantia-hub' ),
            ),
            'options_title'    => __( 'Perfiles y portafolios', 'quantia-hub' ),
            'options_intro'    => __( 'Según tu tolerancia al riesgo se te recomienda una combinación de fondos de MMG Bank.', 'quantia-hub' ),
            'options'          => array(
                array( 'title' => __( 'Pasajero', 'quantia-hub' ),        'description' => __( 'Renta fija 33.33% y renta variable 66.67%. Más estabilidad, menos variación.', 'quantia-hub' ) ),
                array( 'title' => __( 'Navegante', 'quantia-hub' ),       'description' => __( 'Renta fija 25% y renta variable 75%. Equilibrio entre estabilidad y crecimiento.', 'quantia-hub' ) ),
                array( 'title' => __( 'Aventurero', 'quantia-hub' ),      'description' => __( 'Renta variable 100%. Mayor crecimiento potencial para horizontes largos.', 'quantia-hub' ) ),
                array( 'title' => __( 'Fondos disponibles', 'quantia-hub' ), 'description' => __( 'MMG Systematic Equity, MMG Global Allocation Fund, MMG Panama Allocation Fund y MMG Fixed Income Fund.', 'quantia-hub' ) ),
            ),
            'features'         => array(
                array( 'title' => __( 'Cuestionario de perfil de riesgo', 'quantia-hub' ), 'description' => __( 'Define tu tolerancia al riesgo antes de invertir, directamente en la app.', 'quantia-hub' ) ),
                array( 'title' => __( 'Estrategia recomendada', 'quantia-hub' ),           'description' => __( 'Recibes una propuesta de portafolio acorde a tu perfil y horizonte.', 'quantia-hub' ) ),
                array( 'title' => __( 'Administración por MMG Bank', 'quantia-hub' ),      'description' => __( 'El equipo de inversión administra y da seguimiento a la estrategia.', 'quantia-hub' ) ),
                array( 'title' => __( 'Seguimiento en la app', 'quantia-hub' ),            'description' => __( 'Consulta rendimiento, distribución e historial de tus movimientos.', 'quantia-hub' ) ),
            ),
            'steps'            => array(
                array( 'title' => __( 'Completa tu perfil', 'quantia-hub' ),      'description' => __( 'Responde el cuestionario de tolerancia al riesgo dentro de Quantia Hub.', 'quantia-hub' ) ),
                array( 'title' => __( 'Revisa tu estrategia', 'quantia-hub' ),    'description' => __( 'Conoce la combinación de fondos recomendada y sus condiciones.', 'quantia-hub' ) ),
                array( 'title' => __( 'Ejecuta tu inversión', 'quantia-hub' ),    'description' => __( 'Realiza la compra y programa aportes cuando lo decidas.', 'quantia-hub' ) ),
            ),
            'fees'             => array(
                array( 'concept' => __( 'Comisión por compra de activo', 'quantia-hub' ), 'cost' => __( 'Sin costo', 'quantia-hub' ) ),
                array( 'concept' => __( 'Comisión por venta de activo', 'quantia-hub' ),  'cost' => __( '1% + ITBMS', 'quantia-hub' ), 'note' => __( 'Sobre el valor de la venta', 'quantia-hub' ) ),
                array( 'concept' => __( 'Monto mínimo de compra', 'quantia-hub' ),        'cost' => 'B/. 100.00' ),
            ),
            'faqs'             => array(
                array( 'question' => __( '¿Cuál es el monto mínimo para abrir un portafolio de inversión?', 'quantia-hub' ), 'answer' => __( 'El monto mínimo es de 100 dólares para realizar una compra en tu portafolio de inversión.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Cuándo y cómo puedo retirar el dinero invertido?', 'quantia-hub' ),                'answer' => __( 'Inicialmente debes mantener tu inversión por 3 meses, luego puedes retirar tu dinero una vez que la orden de venta haya sido procesada y completada. El proceso se refleja en tu historial de inversiones.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Qué pasa con los dividendos de mis inversiones?', 'quantia-hub' ),                  'answer' => __( 'Los dividendos se reinvierten automáticamente en tu portafolio. Quantia Hub está diseñado para que veas crecer tu patrimonio en el tiempo.', 'quantia-hub' ) ),
                array( 'question' => __( '¿En qué puedo invertir?', 'quantia-hub' ),                                          'answer' => __( 'Puedes invertir en instrumentos regulados en bolsas de valores reconocidas a nivel mundial. Inicialmente podrás invertir en fondos diversificados administrados profesionalmente por MMG Asset Management.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Cuánto tiempo tarda en procesarse mi compra o venta?', 'quantia-hub' ),             'answer' => __( 'Las órdenes pasan por un proceso de validación y ejecución en el mercado. Una vez completada, verás la transacción reflejada en tu historial. No debe tomar más de 5 días hábiles.', 'quantia-hub' ) ),
            ),
            'cta'              => 'app',
            'cta_label'        => __( 'Comienza ya', 'quantia-hub' ),
            'funds_plan'       => 'inversion',
        ),
        'alcancias' => array(
            'slug'             => 'alcancias',
            'key'              => 'alcancias',
            'name'             => __( 'Alcancías', 'quantia-hub' ),
            'eyebrow'          => __( 'Ahorro con propósito', 'quantia-hub' ),
            'title'            => __( 'Alcancías', 'quantia-hub' ),
            'title_highlight'  => __( 'META y LIBRE', 'quantia-hub' ),
            'intro'            => __( 'Separa tu dinero por objetivos dentro de tu cuenta Quantia, gana interés sobre el saldo y automatiza tus aportes para no depender de la voluntad de cada mes.', 'quantia-hub' ),
            'hero_image'       => home_url( '/wp-content/uploads/2026/10/cece73f6-16ae-46da-b9c2-29a7511db492.jpg' ),
            'detail_image'     => home_url( '/wp-content/uploads/2026/10/e9bb4a41-0739-4cbb-9ee7-4658599e4036.jpg' ),
            'card_summary'     => __( 'Ahorro META y LIBRE con interés y aportes automáticos.', 'quantia-hub' ),
            'what_is_title'    => __( '¿Qué son las Alcancías?', 'quantia-hub' ),
            'what_is'          => array(
                __( 'Son espacios dentro de tu cuenta Quantia donde apartas dinero para un objetivo específico. El saldo sigue siendo tuyo y disponible, pero queda separado de tu saldo de uso diario.', 'quantia-hub' ),
                __( 'Puedes crear una Alcancía META, con monto y fecha objetivo, o una Alcancía LIBRE, sin meta definida, para ahorrar a tu ritmo.', 'quantia-hub' ),
            ),
            'audience'         => array(
                __( 'Quieres empezar a ahorrar con un objetivo claro', 'quantia-hub' ),
                __( 'Buscas que tu ahorro genere interés sin inmovilizarlo', 'quantia-hub' ),
                __( 'Prefieres automatizar el ahorro y olvidarte del resto', 'quantia-hub' ),
            ),
            'options_title'    => __( 'Dos formas de ahorrar', 'quantia-hub' ),
            'options_intro'    => __( 'Elige la modalidad según el tipo de objetivo que tengas.', 'quantia-hub' ),
            'options'          => array(
                array( 'title' => __( 'Alcancía META', 'quantia-hub' ),  'description' => __( 'Defines monto objetivo y fecha. La app calcula el aporte sugerido y te muestra el avance.', 'quantia-hub' ) ),
                array( 'title' => __( 'Alcancía LIBRE', 'quantia-hub' ), 'description' => __( 'Sin meta ni plazo. Aportas y retiras cuando quieras, manteniendo el saldo separado.', 'quantia-hub' ) ),
            ),
            'features'         => array(
                array( 'title' => __( 'Interés sobre tu saldo', 'quantia-hub' ),      'description' => __( 'El saldo de tus alcancías genera interés según la tasa vigente publicada en Tarifas.', 'quantia-hub' ) ),
                array( 'title' => __( 'Aportes automáticos', 'quantia-hub' ),         'description' => __( 'Programa aportes semanales, quincenales o mensuales desde tu cuenta.', 'quantia-hub' ) ),
                array( 'title' => __( 'Disponibilidad inmediata', 'quantia-hub' ),    'description' => __( 'Mueve el dinero a tu saldo disponible cuando lo necesites, sin penalización.', 'quantia-hub' ) ),
                array( 'title' => __( 'Servicios de tu cuenta', 'quantia-hub' ),      'description' => __( 'Desde la misma cuenta realizas transferencias, ACH, pago de servicios y anticipo de quincena.', 'quantia-hub' ) ),
            ),
            'steps'            => array(
                array( 'title' => __( 'Abre tu cuenta Quantia', 'quantia-hub' ),  'description' => __( 'Descarga la app y completa tu registro con tu identificación vigente.', 'quantia-hub' ) ),
                array( 'title' => __( 'Crea tu alcancía', 'quantia-hub' ),        'description' => __( 'Elige META o LIBRE, ponle nombre y define el monto inicial.', 'quantia-hub' ) ),
                array( 'title' => __( 'Automatiza tus aportes', 'quantia-hub' ),  'description' => __( 'Programa la frecuencia y el monto; el aporte se realiza solo.', 'quantia-hub' ) ),
            ),
            'fees'             => array(
                array( 'concept' => __( 'Creación de alcancías', 'quantia-hub' ), 'cost' => __( 'Sin costo', 'quantia-hub' ) ),
                array( 'concept' => __( 'Aportes y retiros', 'quantia-hub' ),     'cost' => __( 'Sin costo', 'quantia-hub' ), 'note' => __( 'Sin penalización ni plazos forzosos', 'quantia-hub' ) ),
                array( 'concept' => __( 'Interés sobre saldo promedio mensual', 'quantia-hub' ), 'cost' => __( '4% anual', 'quantia-hub' ), 'note' => __( 'Desde B/. 0.01 hasta B/. 50,000.00. Tasa sujeta a cambios', 'quantia-hub' ) ),
            ),
            'faqs'             => array(
                array( 'question' => __( '¿Cuál es la diferencia entre META y LIBRE?', 'quantia-hub' ),                'answer' => __( 'La META tiene monto y fecha objetivo con seguimiento de avance; la LIBRE no tiene meta definida y la usas a tu ritmo.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Dónde está el dinero que pongo en una alcancía?', 'quantia-hub' ),          'answer' => __( 'Permanece en tu cuenta Quantia en MMG Bank, separado de tu saldo de uso diario.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Puedo retirar el dinero cuando quiera?', 'quantia-hub' ),                   'answer' => __( 'Sí, la disponibilidad es inmediata y no hay penalización por retirar.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Puedo pausar mis aportes automáticos?', 'quantia-hub' ),                    'answer' => __( 'Sí, puedes pausarlos, ajustarlos o reanudarlos desde la app.', 'quantia-hub' ) ),
            ),
            'cta'              => 'app',
            'cta_label'        => __( 'Comienza ya', 'quantia-hub' ),
        ),
        'retiro' => array(
            'slug'             => 'retiro',
            'key'              => 'retiro',
            'name'             => __( 'Retiro', 'quantia-hub' ),
            'eyebrow'          => __( 'Quantia AFP', 'quantia-hub' ),
            'title'            => __( 'Fondo de', 'quantia-hub' ),
            'title_highlight'  => __( 'jubilación', 'quantia-hub' ),
            'intro'            => __( 'Inicia el proceso desde Quantia Hub, completa tu apertura en Quantia AFP y programa aportes automáticos. Consulta tu historial y rendimiento mientras construyes el hábito que respalda tu futuro.', 'quantia-hub' ),
            'hero_image'       => home_url( '/wp-content/uploads/2026/10/49e4de75-73c4-43ea-977b-6f4f95825596.jpg' ),
            'detail_image'     => home_url( '/wp-content/uploads/2026/10/b4fe8526-17d9-455f-9505-13bc2940af4f.jpg' ),
            'card_summary'     => __( 'Fondo de jubilación administrado por Quantia AFP.', 'quantia-hub' ),
            'what_is_title'    => __( '¿Qué es el fondo de jubilación?', 'quantia-hub' ),
            'what_is'          => array(
                __( 'Es un plan de largo plazo administrado por Quantia AFP en el que aportas de forma periódica para construir un fondo destinado a tu etapa de jubilación.', 'quantia-hub' ),
                __( 'El proceso inicia en Quantia Hub y se completa con la apertura en Quantia AFP. Desde la app das seguimiento a tus aportes, historial y rendimiento.', 'quantia-hub' ),
            ),
            'audience'         => array(
                __( 'Quieres construir un fondo de largo plazo con aportes constantes', 'quantia-hub' ),
                __( 'Prefieres que la administración del fondo esté en manos de especialistas', 'quantia-hub' ),
                __( 'Buscas ordenar hoy una decisión que impacta en varios años', 'quantia-hub' ),
            ),
            'options_title'    => __( 'Cómo se crea tu fondo', 'quantia-hub' ),
            'options_intro'    => __( 'Un proceso digital que inicia en Quantia Hub y se formaliza en Quantia AFP.', 'quantia-hub' ),
            'options'          => array(
                array( 'title' => __( 'Apertura desde Quantia Hub', 'quantia-hub' ),   'description' => __( 'Solicitas el inicio del proceso en la app y se valida tu información como cliente.', 'quantia-hub' ) ),
                array( 'title' => __( 'Formalización en Quantia AFP', 'quantia-hub' ), 'description' => __( 'Completas la apertura del fondo y quedan registrados los datos de tu plan de aportes.', 'quantia-hub' ) ),
                array( 'title' => __( 'Aportes programados', 'quantia-hub' ),          'description' => __( 'Defines monto y frecuencia; el aporte se realiza de forma automática.', 'quantia-hub' ) ),
                array( 'title' => __( 'Seguimiento continuo', 'quantia-hub' ),         'description' => __( 'Consultas aportes, historial y rendimiento desde Quantia Hub.', 'quantia-hub' ) ),
            ),
            'features'         => array(
                array( 'title' => __( 'Aportes automáticos', 'quantia-hub' ),          'description' => __( 'Programas el monto y la frecuencia una sola vez.', 'quantia-hub' ) ),
                array( 'title' => __( 'Administración especializada', 'quantia-hub' ), 'description' => __( 'Quantia AFP administra el fondo bajo la regulación aplicable.', 'quantia-hub' ) ),
                array( 'title' => __( 'Aportes extraordinarios', 'quantia-hub' ),      'description' => __( 'Puedes sumar montos adicionales cuando tengas disponibilidad.', 'quantia-hub' ) ),
                array( 'title' => __( 'Visibilidad en la app', 'quantia-hub' ),        'description' => __( 'Tu fondo aparece junto a tus demás productos Quantia.', 'quantia-hub' ) ),
            ),
            'steps'            => array(
                array( 'title' => __( 'Solicita el inicio en la app', 'quantia-hub' ),        'description' => __( 'Entra a la sección de Retiro en Quantia Hub y solicita tu apertura.', 'quantia-hub' ) ),
                array( 'title' => __( 'Completa tu apertura en Quantia AFP', 'quantia-hub' ), 'description' => __( 'Firma la documentación del fondo y define tu plan de aportes.', 'quantia-hub' ) ),
                array( 'title' => __( 'Programa y da seguimiento', 'quantia-hub' ),           'description' => __( 'Automatiza tus aportes y revisa tu historial desde Quantia Hub.', 'quantia-hub' ) ),
            ),
            'fees'             => array(
                array( 'concept' => __( 'Comisión de administración', 'quantia-hub' ), 'cost' => __( '0.65% anual', 'quantia-hub' ), 'note' => __( 'Sobre saldo administrado', 'quantia-hub' ) ),
                array( 'concept' => __( 'Apertura del fondo', 'quantia-hub' ),         'cost' => 'US$0' ),
                array( 'concept' => __( 'Aportes extraordinarios', 'quantia-hub' ),    'cost' => 'US$0' ),
            ),
            'faqs'             => array(
                array( 'question' => __( '¿Quién administra el fondo?', 'quantia-hub' ),                          'answer' => __( 'Quantia AFP, entidad administradora de fondos de jubilación en Panamá.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Puedo pausar mis aportes?', 'quantia-hub' ),                            'answer' => __( 'Sí. Puedes ajustar, pausar o reanudar los aportes programados según las condiciones del plan.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Dónde consulto el tarifario del fondo?', 'quantia-hub' ),               'answer' => __( 'En la página de Tarifas de este sitio y en el tarifario oficial de Quantia AFP, enlazado en esta página.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Puedo disponer del saldo antes del plazo?', 'quantia-hub' ),            'answer' => __( 'El fondo está diseñado para el largo plazo; las disposiciones anticipadas se rigen por el contrato y la regulación aplicable.', 'quantia-hub' ) ),
            ),
            'cta'              => 'app',
            'cta_label'        => __( 'Comienza ya', 'quantia-hub' ),
            'external_link'    => array( 'label' => __( 'Ver tarifario Quantia AFP', 'quantia-hub' ), 'href' => 'https://www.quantiaafp.com' ),
            'funds_plan'       => 'retiro',
        ),
        'tarjeta' => array(
            'slug'             => 'tarjeta',
            'key'              => 'tarjeta',
            'name'             => __( 'Tarjeta', 'quantia-hub' ),
            'eyebrow'          => __( 'Visa Débito', 'quantia-hub' ),
            'title'            => __( 'Tu Tarjeta', 'quantia-hub' ),
            'title_highlight'  => __( 'Visa Débito', 'quantia-hub' ),
            'intro'            => __( 'Usa el saldo de tu cuenta Quantia en comercios y cajeros, dentro y fuera de Panamá, con control y seguridad desde la app.', 'quantia-hub' ),
            'hero_image'       => home_url( '/wp-content/uploads/2026/10/86af8fbe-dae8-4f13-9e69-6902ed1b0690.jpg' ),
            'detail_image'     => home_url( '/wp-content/uploads/2026/10/34e39827-e8c6-4f2b-a9b7-01678ef6b61a.jpg' ),
            'card_summary'     => __( 'Visa Débito para comercios y cajeros, con control desde la app.', 'quantia-hub' ),
            'what_is_title'    => __( '¿Qué es la Tarjeta Visa Débito?', 'quantia-hub' ),
            'what_is'          => array(
                __( 'Es la tarjeta vinculada a tu cuenta Quantia. Paga en comercios físicos y digitales, retira efectivo en cajeros y consulta cada movimiento en la app.', 'quantia-hub' ),
                __( 'La solicitud se realiza por WhatsApp con nuestro equipo de atención y el estado de tu tarjeta se administra desde Quantia Hub.', 'quantia-hub' ),
            ),
            'audience'         => array(
                __( 'Quieres usar tu saldo Quantia en el día a día', 'quantia-hub' ),
                __( 'Necesitas retirar efectivo en cajeros en Panamá', 'quantia-hub' ),
                __( 'Buscas control y bloqueo inmediato desde la app', 'quantia-hub' ),
            ),
            'options_title'    => __( 'Beneficios de tu tarjeta', 'quantia-hub' ),
            'options_intro'    => __( 'Lo que obtienes al usar tu Visa Débito Quantia.', 'quantia-hub' ),
            'options'          => array(
                array( 'title' => __( 'Aceptación Visa', 'quantia-hub' ),      'description' => __( 'Paga en comercios físicos y digitales que aceptan Visa, en Panamá y fuera.', 'quantia-hub' ) ),
                array( 'title' => __( 'Retiros en cajeros', 'quantia-hub' ),   'description' => __( 'Retira efectivo en la red de cajeros disponible en el país.', 'quantia-hub' ) ),
                array( 'title' => __( 'Control desde la app', 'quantia-hub' ), 'description' => __( 'Consulta movimientos y bloquea o desbloquea tu tarjeta cuando lo necesites.', 'quantia-hub' ) ),
                array( 'title' => __( 'Seguro antifraude', 'quantia-hub' ),    'description' => __( 'Cobertura ante uso no autorizado conforme a las condiciones del producto.', 'quantia-hub' ) ),
            ),
            'features'         => array(
                array( 'title' => __( 'Seguridad y monitoreo', 'quantia-hub' ),     'description' => __( 'Monitoreo de transacciones y notificaciones de cada consumo.', 'quantia-hub' ) ),
                array( 'title' => __( 'Bloqueo inmediato', 'quantia-hub' ),         'description' => __( 'Si pierdes tu tarjeta, la bloqueas desde la app sin llamar a nadie.', 'quantia-hub' ) ),
                array( 'title' => __( 'Solicitud por WhatsApp', 'quantia-hub' ),    'description' => __( 'Nuestro equipo te acompaña en la solicitud y entrega de tu tarjeta.', 'quantia-hub' ) ),
                array( 'title' => __( 'Vinculada a tu cuenta', 'quantia-hub' ),     'description' => __( 'Usa el saldo disponible de tu cuenta Quantia, sin línea de crédito.', 'quantia-hub' ) ),
            ),
            'steps'            => array(
                array( 'title' => __( 'Escríbenos por WhatsApp', 'quantia-hub' ), 'description' => __( 'Solicita tu tarjeta con el equipo de atención de Quantia.', 'quantia-hub' ) ),
                array( 'title' => __( 'Valida tus datos', 'quantia-hub' ),        'description' => __( 'Confirmamos tu información como cliente y los datos de entrega.', 'quantia-hub' ) ),
                array( 'title' => __( 'Actívala en la app', 'quantia-hub' ),      'description' => __( 'Al recibirla, la activas desde Quantia Hub y empiezas a usarla.', 'quantia-hub' ) ),
            ),
            'fees'             => array(
                array( 'concept' => __( 'Emisión de tarjeta débito', 'quantia-hub' ),                                                   'cost' => 'B/. 21.40', 'note' => __( 'Sin costo en cuentas de planilla', 'quantia-hub' ) ),
                array( 'concept' => __( 'Membresía anual', 'quantia-hub' ),                                                             'cost' => 'B/. 21.40', 'note' => __( 'Sin costo en cuentas de planilla', 'quantia-hub' ) ),
                array( 'concept' => __( 'Reposición de tarjeta débito', 'quantia-hub' ),                                                'cost' => 'B/. 26.75' ),
                array( 'concept' => __( 'Consultas y transacciones en ATM de la red CLAVE', 'quantia-hub' ),                            'cost' => 'B/. 1.07' ),
                array( 'concept' => __( 'Seguro de fraude', 'quantia-hub' ),                                                            'cost' => 'B/. 1.05', 'note' => __( 'Máx. B/. 3,000 por evento. Deducible B/. 50.00', 'quantia-hub' ) ),
                array( 'concept' => __( 'Envío de tarjeta y PIN en Panamá Centro', 'quantia-hub' ),                                    'cost' => __( 'Sin costo', 'quantia-hub' ) ),
            ),
            'faqs'             => array(
                array( 'question' => __( '¿Cómo solicito la tarjeta Visa Débito?', 'quantia-hub' ),                'answer' => __( 'Puedes solicitarla al abrir tu cuenta por correo electrónico o WhatsApp.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Cómo vinculo y activo mi tarjeta?', 'quantia-hub' ),                    'answer' => __( 'Ingresa a la app, en la opción de tarjeta, selecciona la opción para vincular tarjeta, sigue los pasos y tu tarjeta quedará activa para usarla de inmediato.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Puedo tener tarjetas digitales o usarlas en Wallet?', 'quantia-hub' ),   'answer' => __( 'Próximamente estaremos brindando nuevas opciones para tu tarjeta.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Por qué algunas transacciones aparecen en tránsito?', 'quantia-hub' ),   'answer' => __( 'Las transacciones pueden aparecer en tránsito mientras se procesan. Una vez completadas, se reflejarán en tu historial de movimientos.', 'quantia-hub' ) ),
                array( 'question' => __( '¿Cómo bloqueo mi tarjeta?', 'quantia-hub' ),                             'answer' => __( 'Puedes bloquear tu tarjeta desde la app o contactando al área de atención al cliente. En caso de fraude, también puedes solicitar el bloqueo inmediato en la app.', 'quantia-hub' ) ),
            ),
            'cta'              => 'whatsapp',
            'cta_label'        => __( 'Solicitar tarjeta', 'quantia-hub' ),
        ),
    );
}

function quantia_fallback_investment_profiles(): array {
    return array(
        array(
            'key' => 'pasajero', 'name' => __( 'Pasajero', 'quantia-hub' ), 'plan' => 'inversion', 'level' => 1, 'rate' => 0.05,
            'summary' => __( 'Prefieres un viaje tranquilo, más estabilidad y menos variación.', 'quantia-hub' ),
            'allocation' => array(
                array( 'fund' => 'fixed-income',      'pct' => 33.33 ),
                array( 'fund' => 'panama-allocation', 'pct' => 33.33 ),
                array( 'fund' => 'global-allocation', 'pct' => 33.34 ),
            ),
        ),
        array(
            'key' => 'navegante', 'name' => __( 'Navegante', 'quantia-hub' ), 'plan' => 'inversion', 'level' => 2, 'rate' => 0.07,
            'summary' => __( 'Buscas equilibrio entre estabilidad y crecimiento.', 'quantia-hub' ),
            'allocation' => array(
                array( 'fund' => 'fixed-income',      'pct' => 25 ),
                array( 'fund' => 'panama-allocation', 'pct' => 25 ),
                array( 'fund' => 'global-allocation', 'pct' => 25 ),
                array( 'fund' => 'systematic-equity', 'pct' => 25 ),
            ),
        ),
        array(
            'key' => 'aventurero', 'name' => __( 'Aventurero', 'quantia-hub' ), 'plan' => 'inversion', 'level' => 3, 'rate' => 0.09,
            'summary' => __( 'Aceptas subidas y bajadas a cambio de mayor crecimiento a largo plazo.', 'quantia-hub' ),
            'allocation' => array(
                array( 'fund' => 'panama-allocation', 'pct' => 25 ),
                array( 'fund' => 'global-allocation', 'pct' => 25 ),
                array( 'fund' => 'systematic-equity', 'pct' => 50 ),
            ),
        ),
    );
}

function quantia_fallback_retirement_profiles(): array {
    return array(
        array(
            'key' => 'conservador', 'name' => __( 'Conservador', 'quantia-hub' ), 'plan' => 'retiro', 'level' => 1, 'rate' => 0.05,
            'summary' => __( 'Priorizas cuidar lo acumulado para tu retiro.', 'quantia-hub' ),
            'allocation' => array( array( 'fund' => 'quantia-select', 'pct' => 100 ) ),
        ),
        array(
            'key' => 'moderado', 'name' => __( 'Moderado', 'quantia-hub' ), 'plan' => 'retiro', 'level' => 2, 'rate' => 0.06,
            'summary' => __( 'Buscas un crecimiento constante con variación moderada.', 'quantia-hub' ),
            'allocation' => array( array( 'fund' => 'quantia-fixed-income', 'pct' => 100 ) ),
        ),
        array(
            'key' => 'agresivo', 'name' => __( 'Agresivo', 'quantia-hub' ), 'plan' => 'retiro', 'level' => 3, 'rate' => 0.08,
            'summary' => __( 'Tienes tiempo por delante y buscas el mayor crecimiento posible.', 'quantia-hub' ),
            'allocation' => array( array( 'fund' => 'quantia-growth', 'pct' => 100 ) ),
        ),
    );
}

function quantia_fallback_fund_lookup(): array {
    return array(
        'systematic-equity'    => array( 'id' => 'systematic-equity',    'name' => 'MMG Systematic Equity',      'asset_class' => 'Renta variable', 'swatch' => 'bg-ink' ),
        'global-allocation'    => array( 'id' => 'global-allocation',    'name' => 'MMG Global Allocation Fund', 'asset_class' => 'Renta variable', 'swatch' => 'bg-qh-300' ),
        'panama-allocation'    => array( 'id' => 'panama-allocation',    'name' => 'MMG Panama Allocation Fund', 'asset_class' => 'Renta variable', 'swatch' => 'bg-qh' ),
        'fixed-income'         => array( 'id' => 'fixed-income',         'name' => 'MMG Fixed Income Fund',      'asset_class' => 'Renta fija',     'swatch' => 'bg-qh-700' ),
        'quantia-core'         => array( 'id' => 'quantia-core',         'name' => 'Quantia Core',               'asset_class' => 'Individual',     'swatch' => 'bg-qh-300' ),
        'quantia-growth'       => array( 'id' => 'quantia-growth',       'name' => 'Quantia Growth',             'asset_class' => 'Individual',     'swatch' => 'bg-qh' ),
        'quantia-fixed-income' => array( 'id' => 'quantia-fixed-income', 'name' => 'Quantia Fixed Income',       'asset_class' => 'Individual',     'swatch' => 'bg-qh-700' ),
        'quantia-select'       => array( 'id' => 'quantia-select',       'name' => 'Quantia Select',             'asset_class' => 'Individual',     'swatch' => 'bg-ink' ),
    );
}

function quantia_fund_get( string $id ): ?array {
    $lookup = function_exists( 'quantia_get_fund_lookup' )
        ? quantia_get_fund_lookup()
        : quantia_fallback_fund_lookup();
    return $lookup[ $id ] ?? null;
}

function quantia_group_allocation_by_asset( array $profile ): array {
    $order  = array( 'Renta fija', 'Renta variable', 'Individual' );
    $groups = array();

    foreach ( $profile['allocation'] as $slice ) {
        $fund = quantia_fund_get( $slice['fund'] );
        if ( ! $fund ) {
            continue;
        }
        $class = $fund['asset_class'];
        if ( ! isset( $groups[ $class ] ) ) {
            $groups[ $class ] = array( 'asset_class' => $class, 'pct' => 0, 'funds' => array() );
        }
        $groups[ $class ]['pct'] += $slice['pct'];
        $groups[ $class ]['funds'][] = array( 'fund' => $fund, 'pct' => $slice['pct'] );
    }

    $sorted = array();
    foreach ( $order as $key ) {
        if ( isset( $groups[ $key ] ) ) {
            $sorted[] = $groups[ $key ];
        }
    }
    return $sorted;
}

function quantia_asset_class_info(): array {
    return array(
        'Renta variable' => __( 'Inversiones en acciones de empresas, cuyo valor fluctúa según el mercado.', 'quantia-hub' ),
        'Renta fija'     => __( 'Inversiones en bonos para obtener ingresos con menor riesgo.', 'quantia-hub' ),
        'Individual'     => __( 'Tu plan de retiro invierte en un solo fondo de Quantia AFP.', 'quantia-hub' ),
    );
}

function quantia_fallback_product_image( string $slug, string $kind = 'hero' ): string {
    $map = array(
        'inversiones' => array(
            'hero'   => home_url( '/wp-content/uploads/2026/10/6c38f06a-7560-42c3-bfbf-906b175fdc95.jpg' ),
            'detail' => home_url( '/wp-content/uploads/2026/10/6c638953-09cb-4d25-9c8b-1e8ce1c7e277.jpg' ),
        ),
        'alcancias' => array(
            'hero'   => home_url( '/wp-content/uploads/2026/10/cece73f6-16ae-46da-b9c2-29a7511db492.jpg' ),
            'detail' => home_url( '/wp-content/uploads/2026/10/e9bb4a41-0739-4cbb-9ee7-4658599e4036.jpg' ),
        ),
        'retiro' => array(
            'hero'   => home_url( '/wp-content/uploads/2026/10/49e4de75-73c4-43ea-977b-6f4f95825596.jpg' ),
            'detail' => home_url( '/wp-content/uploads/2026/10/b4fe8526-17d9-455f-9505-13bc2940af4f.jpg' ),
        ),
        'tarjeta' => array(
            'hero'   => home_url( '/wp-content/uploads/2026/10/86af8fbe-dae8-4f13-9e69-6902ed1b0690.jpg' ),
            'detail' => home_url( '/wp-content/uploads/2026/10/34e39827-e8c6-4f2b-a9b7-01678ef6b61a.jpg' ),
        ),
    );
    return $map[ $slug ][ $kind ] ?? '';
}

function quantia_fallback_regulators(): array {
    return array(
        array(
            'name'        => 'Superintendencia de Bancos de Panamá',
            'short'       => 'SBP',
            'description' => __( 'Supervisa la actividad bancaria de MMG Bank Corporation.', 'quantia-hub' ),
        ),
        array(
            'name'        => 'Superintendencia del Mercado de Valores',
            'short'       => 'SMV',
            'description' => __( 'Regula la actividad de valores e inversiones.', 'quantia-hub' ),
        ),
        array(
            'name'        => 'Latinex',
            'short'       => 'Latinex',
            'description' => __( 'Bolsa de valores en la que participa MMG Bank.', 'quantia-hub' ),
        ),
    );
}