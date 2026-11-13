<?php
/**
 * Grupo ACF del documento legal.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key'      => 'group_quantia_documento_legal',
    'title'    => __( 'Documento legal · Contenido', 'quantia-hub' ),
    'fields'   => array(
        array(
            'key'      => 'field_legal_summary',
            'label'    => __( 'Resumen', 'quantia-hub' ),
            'name'     => 'legal_summary',
            'type'     => 'textarea',
            'rows'     => 3,
            'required' => 1,
        ),
        array(
            'key'          => 'field_legal_updated',
            'label'        => __( 'Última actualización', 'quantia-hub' ),
            'name'         => 'legal_updated',
            'type'         => 'text',
            'instructions' => __( 'Ejemplo: 12 de marzo de 2026', 'quantia-hub' ),
            'required'     => 1,
        ),
        array(
            'key'          => 'field_legal_sections',
            'label'        => __( 'Secciones', 'quantia-hub' ),
            'name'         => 'legal_sections',
            'type'         => 'repeater',
            'button_label' => __( 'Añadir sección', 'quantia-hub' ),
            'layout'       => 'block',
            'min'          => 1,
            'sub_fields'   => array(
                array(
                    'key'      => 'field_legal_section_heading',
                    'label'    => __( 'Título de la sección', 'quantia-hub' ),
                    'name'     => 'heading',
                    'type'     => 'text',
                    'required' => 1,
                ),
                array(
                    'key'          => 'field_legal_section_blocks',
                    'label'        => __( 'Bloques', 'quantia-hub' ),
                    'name'         => 'blocks',
                    'type'         => 'flexible_content',
                    'button_label' => __( 'Añadir bloque', 'quantia-hub' ),
                    'layouts'      => array(
                        'layout_p'          => quantia_legal_layout_p(),
                        'layout_list'       => quantia_legal_layout_list(),
                        'layout_defs'       => quantia_legal_layout_defs(),
                        'layout_contact'    => quantia_legal_layout_contact(),
                        'layout_fee_tables' => quantia_legal_layout_fee_tables(),
                        'layout_ref'        => quantia_legal_layout_ref(),
                    ),
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'documento_legal',
            ),
        ),
    ),
    'menu_order'            => 0,
    'position'              => 'normal',
    'style'                 => 'default',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen'        => array( 'the_content', 'excerpt', 'discussion', 'comments', 'custom_fields', 'slug' ),
) );

function quantia_legal_layout_p(): array {
    return array(
        'key'        => 'layout_p',
        'name'       => 'p',
        'label'      => __( 'Párrafo', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_legal_p_text',
                'label'    => __( 'Texto', 'quantia-hub' ),
                'name'     => 'text',
                'type'     => 'textarea',
                'rows'     => 4,
                'required' => 1,
            ),
        ),
    );
}

function quantia_legal_layout_list(): array {
    return array(
        'key'        => 'layout_list',
        'name'       => 'list',
        'label'      => __( 'Lista', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'   => 'field_legal_list_ordered',
                'label' => __( 'Numerada', 'quantia-hub' ),
                'name'  => 'ordered',
                'type'  => 'true_false',
                'ui'    => 1,
            ),
            array(
                'key'          => 'field_legal_list_items',
                'label'        => __( 'Elementos', 'quantia-hub' ),
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => __( 'Añadir elemento', 'quantia-hub' ),
                'layout'       => 'table',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_legal_list_item_text',
                        'label' => __( 'Texto', 'quantia-hub' ),
                        'name'  => 'text',
                        'type'  => 'text',
                    ),
                ),
            ),
        ),
    );
}

function quantia_legal_layout_defs(): array {
    return array(
        'key'        => 'layout_defs',
        'name'       => 'defs',
        'label'      => __( 'Definiciones', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_legal_defs_items',
                'label'        => __( 'Definiciones', 'quantia-hub' ),
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => __( 'Añadir definición', 'quantia-hub' ),
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_legal_def_term',
                        'label' => __( 'Término', 'quantia-hub' ),
                        'name'  => 'term',
                        'type'  => 'text',
                    ),
                    array(
                        'key'   => 'field_legal_def_text',
                        'label' => __( 'Definición', 'quantia-hub' ),
                        'name'  => 'text',
                        'type'  => 'textarea',
                        'rows'  => 3,
                    ),
                ),
            ),
        ),
    );
}

function quantia_legal_layout_contact(): array {
    return array(
        'key'        => 'layout_contact',
        'name'       => 'contact',
        'label'      => __( 'Datos de contacto', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_legal_contact_items',
                'label'        => __( 'Datos', 'quantia-hub' ),
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => __( 'Añadir dato', 'quantia-hub' ),
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_legal_contact_label',
                        'label' => __( 'Etiqueta', 'quantia-hub' ),
                        'name'  => 'label',
                        'type'  => 'text',
                    ),
                    array(
                        'key'   => 'field_legal_contact_value',
                        'label' => __( 'Valor', 'quantia-hub' ),
                        'name'  => 'value',
                        'type'  => 'text',
                    ),
                    array(
                        'key'          => 'field_legal_contact_href',
                        'label'        => __( 'Enlace', 'quantia-hub' ),
                        'name'         => 'href',
                        'type'         => 'text',
                        'instructions' => __( 'mailto:, tel: o https://. Opcional.', 'quantia-hub' ),
                    ),
                ),
            ),
        ),
    );
}

function quantia_legal_layout_fee_tables(): array {
    return array(
        'key'        => 'layout_fee_tables',
        'name'       => 'fee_tables',
        'label'      => __( 'Tarifario (tablas de tarifas)', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'     => 'field_legal_fee_tables_notice',
                'label'   => __( 'Contenido automático', 'quantia-hub' ),
                'name'    => '',
                'type'    => 'message',
                'message' => __( 'Este bloque muestra las tablas de tarifas vigentes definidas en el tema. No requiere configuración.', 'quantia-hub' ),
            ),
        ),
    );
}

function quantia_legal_layout_ref(): array {
    return array(
        'key'        => 'layout_ref',
        'name'       => 'ref',
        'label'      => __( 'Referencia a otro documento', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_legal_ref_text',
                'label'    => __( 'Texto', 'quantia-hub' ),
                'name'     => 'text',
                'type'     => 'textarea',
                'rows'     => 2,
                'required' => 1,
            ),
            array(
                'key'      => 'field_legal_ref_label',
                'label'    => __( 'Etiqueta del enlace', 'quantia-hub' ),
                'name'     => 'label',
                'type'     => 'text',
                'required' => 1,
            ),
            array(
                'key'          => 'field_legal_ref_target',
                'label'        => __( 'Slug del documento destino', 'quantia-hub' ),
                'name'         => 'target',
                'type'         => 'text',
                'instructions' => __( 'Ejemplo: tarifario', 'quantia-hub' ),
                'required'     => 1,
            ),
        ),
    );
}