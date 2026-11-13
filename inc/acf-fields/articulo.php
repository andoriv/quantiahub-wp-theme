<?php
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key'      => 'group_quantia_articulo',
    'title'    => 'Artículo · Contenido',
    'fields'   => array(
        array(
            'key'           => 'field_articulo_visual',
            'label'         => 'Visual destacado',
            'name'          => 'article_visual',
            'type'          => 'select',
            'choices'       => array(
                'riesgo'             => 'Riesgo (líneas)',
                'diversificacion'    => 'Diversificación (dona)',
                'interes-compuesto'  => 'Interés compuesto (barras)',
                'ahorro-primero'     => 'Ahorro primero (barras horizontales)',
                'ahorro-vs-inversion'=> 'Ahorro vs. inversión (líneas)',
            ),
            'default_value' => 'riesgo',
            'required'      => 1,
        ),
        array(
            'key'          => 'field_articulo_body',
            'label'        => 'Cuerpo del artículo',
            'name'         => 'article_body',
            'type'         => 'flexible_content',
            'button_label' => 'Añadir bloque',
            'layouts'      => array(
                'layout_p'        => quantia_acf_layout_p(),
                'layout_h2'       => quantia_acf_layout_h2(),
                'layout_h3'       => quantia_acf_layout_h3(),
                'layout_list'     => quantia_acf_layout_list(),
                'layout_defs'     => quantia_acf_layout_defs(),
                'layout_table'    => quantia_acf_layout_table(),
                'layout_callout'  => quantia_acf_layout_callout(),
                'layout_faq'      => quantia_acf_layout_faq(),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'articulo',
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

function quantia_acf_layout_p(): array {
    return array(
        'key'        => 'layout_p',
        'name'       => 'p',
        'label'      => 'Párrafo',
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_p_text',
                'label'        => 'Texto',
                'name'         => 'text',
                'type'         => 'textarea',
                'rows'         => 4,
                'new_lines'    => '',
                'required'     => 1,
            ),
        ),
    );
}

function quantia_acf_layout_h2(): array {
    return array(
        'key'        => 'layout_h2',
        'name'       => 'h2',
        'label'      => 'Subtítulo (H2)',
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_h2_text',
                'label'    => 'Texto',
                'name'     => 'text',
                'type'     => 'text',
                'required' => 1,
            ),
        ),
    );
}

function quantia_acf_layout_h3(): array {
    return array(
        'key'        => 'layout_h3',
        'name'       => 'h3',
        'label'      => 'Subtítulo (H3)',
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_h3_text',
                'label'    => 'Texto',
                'name'     => 'text',
                'type'     => 'text',
                'required' => 1,
            ),
        ),
    );
}

function quantia_acf_layout_list(): array {
    return array(
        'key'        => 'layout_list',
        'name'       => 'list',
        'label'      => 'Lista con viñetas',
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_list_items',
                'label'        => 'Elementos',
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => 'Añadir elemento',
                'layout'       => 'table',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_list_item_text',
                        'label' => 'Texto',
                        'name'  => 'text',
                        'type'  => 'text',
                    ),
                ),
            ),
        ),
    );
}

function quantia_acf_layout_defs(): array {
    return array(
        'key'        => 'layout_defs',
        'name'       => 'defs',
        'label'      => 'Definiciones (término + texto)',
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_defs_items',
                'label'        => 'Definiciones',
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => 'Añadir definición',
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_def_term',
                        'label' => 'Término',
                        'name'  => 'term',
                        'type'  => 'text',
                    ),
                    array(
                        'key'   => 'field_def_text',
                        'label' => 'Definición',
                        'name'  => 'text',
                        'type'  => 'textarea',
                        'rows'  => 3,
                    ),
                ),
            ),
        ),
    );
}

function quantia_acf_layout_table(): array {
    return array(
        'key'        => 'layout_table',
        'name'       => 'table',
        'label'      => 'Tabla',
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_table_head',
                'label'        => 'Encabezados',
                'name'         => 'head',
                'type'         => 'repeater',
                'button_label' => 'Añadir columna',
                'layout'       => 'table',
                'min'          => 2,
                'max'          => 5,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_table_head_text',
                        'label' => 'Texto',
                        'name'  => 'text',
                        'type'  => 'text',
                    ),
                ),
            ),
            array(
                'key'          => 'field_table_rows',
                'label'        => 'Filas',
                'name'         => 'rows',
                'type'         => 'repeater',
                'button_label' => 'Añadir fila',
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_table_row_cells',
                        'label' => 'Celdas (una por columna)',
                        'name'  => 'cells',
                        'type'  => 'repeater',
                        'button_label' => 'Añadir celda',
                        'layout'       => 'table',
                        'sub_fields'   => array(
                            array(
                                'key'   => 'field_table_cell',
                                'label' => 'Texto',
                                'name'  => 'text',
                                'type'  => 'text',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    );
}

function quantia_acf_layout_callout(): array {
    return array(
        'key'        => 'layout_callout',
        'name'       => 'callout',
        'label'      => 'Destacado (callout)',
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_callout_text',
                'label'    => 'Texto',
                'name'     => 'text',
                'type'     => 'textarea',
                'rows'     => 3,
                'required' => 1,
            ),
        ),
    );
}

function quantia_acf_layout_faq(): array {
    return array(
        'key'        => 'layout_faq',
        'name'       => 'faq',
        'label'      => 'Preguntas frecuentes',
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_faq_items',
                'label'        => 'Preguntas',
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => 'Añadir pregunta',
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_faq_q',
                        'label' => 'Pregunta',
                        'name'  => 'q',
                        'type'  => 'text',
                    ),
                    array(
                        'key'   => 'field_faq_a',
                        'label' => 'Respuesta',
                        'name'  => 'a',
                        'type'  => 'textarea',
                        'rows'  => 3,
                    ),
                ),
            ),
        ),
    );
}