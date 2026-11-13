<?php
/**
 * Datos del Centro de confianza.
 *
 * Normaliza los documentos del CPT `documento_legal` al formato que consumen
 * las plantillas, expone accessors, define datos de respaldo y siembra los
 * documentos base al activar el tema.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Normaliza un WP_Post de tipo documento_legal.
 *
 * @param WP_Post $post Documento.
 * @return array
 */
function quantia_get_legal_document( WP_Post $post ): array {
    $summary  = function_exists( 'get_field' ) ? (string) get_field( 'legal_summary', $post->ID ) : '';
    $updated  = function_exists( 'get_field' ) ? (string) get_field( 'legal_updated', $post->ID ) : '';
    $sections = function_exists( 'get_field' ) ? (array) get_field( 'legal_sections', $post->ID ) : array();

    return array(
        'ID'       => $post->ID,
        'slug'     => $post->post_name,
        'title'    => get_the_title( $post ),
        'summary'  => $summary,
        'updated'  => $updated,
        'sections' => quantia_normalize_legal_sections( $sections ),
    );
}

/**
 * Convierte el repeater de secciones al formato consumido por las vistas.
 *
 * @param array $rows Filas del repeater.
 * @return array
 */
function quantia_normalize_legal_sections( array $rows ): array {
    $sections = array();

    foreach ( $rows as $row ) {
        $heading = isset( $row['heading'] ) ? (string) $row['heading'] : '';
        if ( '' === $heading ) {
            continue;
        }
        $sections[] = array(
            'heading' => $heading,
            'blocks'  => quantia_normalize_legal_blocks( isset( $row['blocks'] ) && is_array( $row['blocks'] ) ? $row['blocks'] : array() ),
        );
    }

    return $sections;
}

/**
 * Convierte el flexible content de ACF en un array plano de bloques.
 *
 * @param array $rows Filas del flexible content.
 * @return array
 */
function quantia_normalize_legal_blocks( array $rows ): array {
    $blocks = array();

    foreach ( $rows as $row ) {
        $layout = isset( $row['acf_fc_layout'] ) ? $row['acf_fc_layout'] : '';

        switch ( $layout ) {
            case 'p':
                $blocks[] = array( 'type' => 'p', 'text' => (string) ( $row['text'] ?? '' ) );
                break;

            case 'list':
                $items = array();
                foreach ( $row['items'] ?? array() as $item ) {
                    $text = isset( $item['text'] ) ? (string) $item['text'] : '';
                    if ( '' !== $text ) {
                        $items[] = $text;
                    }
                }
                $blocks[] = array(
                    'type'    => 'list',
                    'ordered' => ! empty( $row['ordered'] ),
                    'items'   => $items,
                );
                break;

            case 'defs':
                $items = array();
                foreach ( $row['items'] ?? array() as $item ) {
                    $term = isset( $item['term'] ) ? (string) $item['term'] : '';
                    $text = isset( $item['text'] ) ? (string) $item['text'] : '';
                    if ( '' === $term && '' === $text ) {
                        continue;
                    }
                    $items[] = array( 'term' => $term, 'text' => $text );
                }
                $blocks[] = array( 'type' => 'defs', 'items' => $items );
                break;

            case 'contact':
                $items = array();
                foreach ( $row['items'] ?? array() as $item ) {
                    $label = isset( $item['label'] ) ? (string) $item['label'] : '';
                    if ( '' === $label ) {
                        continue;
                    }
                    $items[] = array(
                        'label' => $label,
                        'value' => (string) ( $item['value'] ?? '' ),
                        'href'  => (string) ( $item['href'] ?? '' ),
                    );
                }
                $blocks[] = array( 'type' => 'contact', 'items' => $items );
                break;

            case 'fee_tables':
                $blocks[] = array( 'type' => 'feeTables' );
                break;

            case 'ref':
                $blocks[] = array(
                    'type'   => 'ref',
                    'text'   => (string) ( $row['text'] ?? '' ),
                    'label'  => (string) ( $row['label'] ?? '' ),
                    'target' => (string) ( $row['target'] ?? '' ),
                );
                break;
        }
    }

    return $blocks;
}

/**
 * Devuelve todos los documentos legales publicados, ordenados por menu_order.
 *
 * @return array
 */
function quantia_get_all_legal_documents(): array {
    $query = new WP_Query( array(
        'post_type'      => 'documento_legal',
        'post_status'    => 'publish',
        'posts_per_page' => 50,
        'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
    ) );

    $docs = array();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $docs[] = quantia_get_legal_document( get_post() );
        }
        wp_reset_postdata();
    }

    return $docs;
}

/**
 * Contenido de respaldo del Centro de confianza.
 *
 * @return array
 */
function quantia_fallback_legal_documents(): array {
    return array(
        'terminos-y-condiciones' => array(
            'title'    => __( 'Términos y condiciones', 'quantia-hub' ),
            'summary'  => __( 'Reglas de uso de Quantia Hub, derechos y obligaciones del cliente y del banco.', 'quantia-hub' ),
            'updated'  => __( '12 de marzo de 2026', 'quantia-hub' ),
            'sections' => array(
                array(
                    'heading' => __( 'Aceptación de los términos', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'Al descargar, instalar o utilizar la aplicación Quantia Hub, usted acepta estos Términos y Condiciones en su totalidad. Si no está de acuerdo, debe abstenerse de usar el servicio.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Descripción del servicio', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'Quantia Hub es la aplicación financiera de MMG Bank Corporation que permite abrir y administrar una cuenta, acceder a productos de ahorro, inversión, retiro y medios de pago, así como realizar transferencias y consultar movimientos.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Registro y uso de la cuenta', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'Para abrir una cuenta es necesario completar el proceso de onboarding, verificar la identidad con documento vigente y aceptar los documentos regulatorios aplicables. El cliente es responsable de mantener la confidencialidad de sus credenciales y de notificar cualquier uso no autorizado.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Uso aceptable', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'El servicio debe utilizarse únicamente para fines lícitos. Se prohíbe el uso de la cuenta para actividades relacionadas con lavado de dinero, financiamiento del terrorismo, fraude o cualquier delito tipificado en la legislación panameña.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Tarifas y comisiones', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'Las tarifas y comisiones aplicables a cada producto se detallan en el tarifario vigente, disponible en la sección Tarifas de este sitio y en el anexo correspondiente.', 'quantia-hub' ) ),
                        array( 'type' => 'ref', 'text' => __( 'Consulte el detalle completo de los cargos aplicables a su cuenta.', 'quantia-hub' ), 'label' => __( 'Ver tarifario completo', 'quantia-hub' ), 'target' => 'tarifario' ),
                    ),
                ),
                array(
                    'heading' => __( 'Propiedad intelectual', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'La aplicación, su diseño, código, marcas y contenidos son propiedad de MMG Bank Corporation o de sus licenciantes. Queda prohibida su reproducción total o parcial sin autorización previa por escrito.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Limitación de responsabilidad', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'MMG Bank no será responsable por daños indirectos, incidentales o consecuenciales derivados del uso o imposibilidad de uso del servicio, salvo en los casos expresamente previstos por la ley aplicable.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Modificaciones', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'Estos términos pueden ser modificados en cualquier momento. Las modificaciones se notificarán por los canales oficiales y entrarán en vigencia en la fecha que se indique en la notificación.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Contacto', 'quantia-hub' ),
                    'blocks'  => array(
                        array(
                            'type'  => 'contact',
                            'items' => array(
                                array( 'label' => __( 'Correo electrónico', 'quantia-hub' ), 'value' => 'quantia@mmgbank.com', 'href' => 'mailto:quantia@mmgbank.com' ),
                                array( 'label' => __( 'Teléfono', 'quantia-hub' ), 'value' => '+507 208-7024', 'href' => 'tel:+5072087024' ),
                                array( 'label' => __( 'Reclamos', 'quantia-hub' ), 'value' => 'reclamos@mmgbank.com', 'href' => 'mailto:reclamos@mmgbank.com' ),
                                array( 'label' => __( 'Sitio web', 'quantia-hub' ), 'value' => 'quantiahub.com', 'href' => 'https://quantiahub.com' ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        'aviso-de-privacidad' => array(
            'title'    => __( 'Aviso de privacidad', 'quantia-hub' ),
            'summary'  => __( 'Cómo tratamos sus datos personales, con qué finalidades y cuáles son sus derechos.', 'quantia-hub' ),
            'updated'  => __( '12 de marzo de 2026', 'quantia-hub' ),
            'sections' => array(
                array(
                    'heading' => __( 'Responsable del tratamiento', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'MMG Bank Corporation, con domicilio en Ave. Paseo del Mar, Costa del Este, Ciudad de Panamá, es el responsable del tratamiento de los datos personales recopilados a través de Quantia Hub.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Datos que recopilamos', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'Recopilamos datos de identificación, contacto, información financiera y datos de uso del servicio, necesarios para la prestación de los servicios contratados.', 'quantia-hub' ) ),
                        array( 'type' => 'list', 'items' => array(
                            __( 'Datos de identificación y contacto.', 'quantia-hub' ),
                            __( 'Información financiera y transaccional.', 'quantia-hub' ),
                            __( 'Datos de uso de la aplicación.', 'quantia-hub' ),
                            __( 'Información del dispositivo.', 'quantia-hub' ),
                        ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Finalidades del tratamiento', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'Los datos se utilizan para la gestión de la cuenta, el cumplimiento de obligaciones regulatorias, la prevención de fraude y la mejora del servicio.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Derechos del titular', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'Usted puede solicitar acceso, rectificación, cancelación u oposición al tratamiento de sus datos en cualquier momento, escribiendo al correo indicado en la sección de contacto.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Contacto', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'contact', 'items' => array(
                            array( 'label' => __( 'Correo electrónico', 'quantia-hub' ), 'value' => 'privacidad@mmgbank.com', 'href' => 'mailto:privacidad@mmgbank.com' ),
                            array( 'label' => __( 'Teléfono', 'quantia-hub' ), 'value' => '+507 208-7024', 'href' => 'tel:+5072087024' ),
                        ) ),
                    ),
                ),
            ),
        ),
        'aclaraciones-y-reclamaciones' => array(
            'title'    => __( 'Aclaraciones y reclamaciones', 'quantia-hub' ),
            'summary'  => __( 'Procedimiento para presentar aclaraciones o reclamaciones y plazos de respuesta.', 'quantia-hub' ),
            'updated'  => __( '12 de marzo de 2026', 'quantia-hub' ),
            'sections' => array(
                array(
                    'heading' => __( 'Cómo presentar un reclamo', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'Puede presentar su reclamo por escrito al correo reclamos@mmgbank.com o mediante nota escrita entregada en nuestras oficinas. Recibirá constancia de la presentación por el mismo canal.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Plazos y respuesta', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'El Oficial de Reclamos investigará su caso y emitirá respuesta motivada dentro de los treinta (30) días siguientes a la presentación. Si no está conforme, dispone de cinco (5) días hábiles para exponer sus razones por escrito.', 'quantia-hub' ) ),
                    ),
                ),
                array(
                    'heading' => __( 'Contacto', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'contact', 'items' => array(
                            array( 'label' => __( 'Oficial de Reclamos', 'quantia-hub' ), 'value' => 'reclamos@mmgbank.com', 'href' => 'mailto:reclamos@mmgbank.com' ),
                            array( 'label' => __( 'Teléfono', 'quantia-hub' ), 'value' => '+507 208-7024', 'href' => 'tel:+5072087024' ),
                        ) ),
                    ),
                ),
            ),
        ),
        'tarifario' => array(
            'title'    => __( 'Tarifario', 'quantia-hub' ),
            'summary'  => __( 'Listado completo de tarifas y comisiones aplicables a los productos de Quantia Hub.', 'quantia-hub' ),
            'updated'  => __( '12 de marzo de 2026', 'quantia-hub' ),
            'sections' => array(
                array(
                    'heading' => __( 'Tarifas vigentes', 'quantia-hub' ),
                    'blocks'  => array(
                        array( 'type' => 'p', 'text' => __( 'A continuación se detallan las tarifas aplicables a cada uno de los productos ofrecidos por Quantia Hub. Todos los cargos están sujetos al Impuesto sobre la Transferencia de Bienes Corporales Muebles y la Prestación de Servicios (ITBMS).', 'quantia-hub' ) ),
                        array( 'type' => 'feeTables' ),
                        array( 'type' => 'p', 'text' => __( 'Las tarifas pueden ser modificadas de tiempo en tiempo conforme a los términos y condiciones aplicables.', 'quantia-hub' ) ),
                    ),
                ),
            ),
        ),
    );
}

/**
 * Siembra los documentos legales base al activar el tema.
 *
 * Idempotente: no hace nada si ya existe al menos un documento.
 */
function quantia_seed_legal_documents(): void {
    if ( ! current_user_can( 'manage_options' ) && ! doing_action( 'after_switch_theme' ) ) {
        return;
    }

    $existing = get_posts( array(
        'post_type'      => 'documento_legal',
        'posts_per_page' => 1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ) );

    if ( ! empty( $existing ) ) {
        return;
    }

    $order = 0;

    foreach ( quantia_fallback_legal_documents() as $slug => $doc ) {
        $post_id = wp_insert_post( array(
            'post_type'    => 'documento_legal',
            'post_status'  => 'publish',
            'post_title'   => $doc['title'],
            'post_name'    => $slug,
            'post_content' => '',
            'menu_order'   => $order++,
        ), true );

        if ( is_wp_error( $post_id ) || ! $post_id ) {
            continue;
        }

        $sections_acf = quantia_legal_sections_to_acf( $doc['sections'] );

        if ( function_exists( 'update_field' ) ) {
            update_field( 'legal_summary', $doc['summary'], $post_id );
            update_field( 'legal_updated', $doc['updated'], $post_id );
            update_field( 'legal_sections', $sections_acf, $post_id );
        } else {
            update_post_meta( $post_id, 'legal_summary', $doc['summary'] );
            update_post_meta( $post_id, 'legal_updated', $doc['updated'] );
            update_post_meta( $post_id, 'legal_sections', $sections_acf );
        }
    }

    flush_rewrite_rules();
}
add_action( 'admin_init', 'quantia_seed_legal_documents' );
add_action( 'after_switch_theme', 'quantia_seed_legal_documents' );

/**
 * Convierte el array normalizado de secciones al formato ACF para guardar.
 *
 * @param array $sections Secciones normalizadas.
 * @return array
 */
function quantia_legal_sections_to_acf( array $sections ): array {
    $rows = array();
    foreach ( $sections as $section ) {
        $blocks_acf = array();
        foreach ( $section['blocks'] ?? array() as $block ) {
            $blocks_acf[] = quantia_legal_block_to_acf( $block );
        }
        $rows[] = array(
            'heading' => $section['heading'] ?? '',
            'blocks'  => $blocks_acf,
        );
    }
    return $rows;
}

/**
 * Convierte un bloque normalizado al formato ACF.
 *
 * @param array $block Bloque.
 * @return array
 */
function quantia_legal_block_to_acf( array $block ): array {
    $type = $block['type'] ?? '';

    switch ( $type ) {
        case 'p':
            return array( 'acf_fc_layout' => 'p', 'text' => $block['text'] ?? '' );

        case 'list':
            $items = array();
            foreach ( $block['items'] ?? array() as $text ) {
                $items[] = array( 'text' => $text );
            }
            return array( 'acf_fc_layout' => 'list', 'ordered' => ! empty( $block['ordered'] ), 'items' => $items );

        case 'defs':
            $items = array();
            foreach ( $block['items'] ?? array() as $item ) {
                $items[] = array( 'term' => $item['term'] ?? '', 'text' => $item['text'] ?? '' );
            }
            return array( 'acf_fc_layout' => 'defs', 'items' => $items );

        case 'contact':
            $items = array();
            foreach ( $block['items'] ?? array() as $item ) {
                $items[] = array(
                    'label' => $item['label'] ?? '',
                    'value' => $item['value'] ?? '',
                    'href'  => $item['href'] ?? '',
                );
            }
            return array( 'acf_fc_layout' => 'contact', 'items' => $items );

        case 'feeTables':
            return array( 'acf_fc_layout' => 'fee_tables' );

        case 'ref':
            return array(
                'acf_fc_layout' => 'ref',
                'text'          => $block['text'] ?? '',
                'label'         => $block['label'] ?? '',
                'target'        => $block['target'] ?? '',
            );
    }

    return array( 'acf_fc_layout' => 'p', 'text' => '' );
}