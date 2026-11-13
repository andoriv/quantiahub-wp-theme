<?php
/**
 * ACF field group del artículo.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key'      => 'group_quantia_articulo',
    'title'    => __( 'Artículo · Contenido', 'quantia-hub' ),
    'fields'   => array(
        array(
            'key'           => 'field_articulo_visual',
            'label'         => __( 'Visual destacado', 'quantia-hub' ),
            'name'          => 'article_visual',
            'type'          => 'select',
            'choices'       => array(
                'riesgo'              => __( 'Riesgo (líneas)', 'quantia-hub' ),
                'diversificacion'     => __( 'Diversificación (dona)', 'quantia-hub' ),
                'interes-compuesto'   => __( 'Interés compuesto (barras)', 'quantia-hub' ),
                'ahorro-primero'      => __( 'Ahorro primero (barras horizontales)', 'quantia-hub' ),
                'ahorro-vs-inversion' => __( 'Ahorro vs. inversión (líneas)', 'quantia-hub' ),
            ),
            'default_value' => 'riesgo',
            'required'      => 1,
        ),
        array(
            'key'          => 'field_articulo_body',
            'label'        => __( 'Cuerpo del artículo', 'quantia-hub' ),
            'name'         => 'article_body',
            'type'         => 'flexible_content',
            'button_label' => __( 'Añadir bloque', 'quantia-hub' ),
            'layouts'      => array(
                'layout_p'       => quantia_acf_layout_p(),
                'layout_h2'      => quantia_acf_layout_h2(),
                'layout_h3'      => quantia_acf_layout_h3(),
                'layout_list'    => quantia_acf_layout_list(),
                'layout_defs'    => quantia_acf_layout_defs(),
                'layout_table'   => quantia_acf_layout_table(),
                'layout_callout' => quantia_acf_layout_callout(),
                'layout_faq'     => quantia_acf_layout_faq(),
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
        'label'      => __( 'Párrafo', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'       => 'field_p_text',
                'label'     => __( 'Texto', 'quantia-hub' ),
                'name'      => 'text',
                'type'      => 'textarea',
                'rows'      => 4,
                'new_lines' => '',
                'required'  => 1,
            ),
        ),
    );
}

function quantia_acf_layout_h2(): array {
    return array(
        'key'        => 'layout_h2',
        'name'       => 'h2',
        'label'      => __( 'Subtítulo (H2)', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_h2_text',
                'label'    => __( 'Texto', 'quantia-hub' ),
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
        'label'      => __( 'Subtítulo (H3)', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_h3_text',
                'label'    => __( 'Texto', 'quantia-hub' ),
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
        'label'      => __( 'Lista con viñetas', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_list_items',
                'label'        => __( 'Elementos', 'quantia-hub' ),
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => __( 'Añadir elemento', 'quantia-hub' ),
                'layout'       => 'table',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_list_item_text',
                        'label' => __( 'Texto', 'quantia-hub' ),
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
        'label'      => __( 'Definiciones (término + texto)', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_defs_items',
                'label'        => __( 'Definiciones', 'quantia-hub' ),
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => __( 'Añadir definición', 'quantia-hub' ),
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_def_term',
                        'label' => __( 'Término', 'quantia-hub' ),
                        'name'  => 'term',
                        'type'  => 'text',
                    ),
                    array(
                        'key'   => 'field_def_text',
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

function quantia_acf_layout_table(): array {
    return array(
        'key'        => 'layout_table',
        'name'       => 'table',
        'label'      => __( 'Tabla', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_table_head',
                'label'        => __( 'Encabezados', 'quantia-hub' ),
                'name'         => 'head',
                'type'         => 'repeater',
                'button_label' => __( 'Añadir columna', 'quantia-hub' ),
                'layout'       => 'table',
                'min'          => 2,
                'max'          => 5,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_table_head_text',
                        'label' => __( 'Texto', 'quantia-hub' ),
                        'name'  => 'text',
                        'type'  => 'text',
                    ),
                ),
            ),
            array(
                'key'          => 'field_table_rows',
                'label'        => __( 'Filas', 'quantia-hub' ),
                'name'         => 'rows',
                'type'         => 'repeater',
                'button_label' => __( 'Añadir fila', 'quantia-hub' ),
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'          => 'field_table_row_cells',
                        'label'        => __( 'Celdas (una por columna)', 'quantia-hub' ),
                        'name'         => 'cells',
                        'type'         => 'repeater',
                        'button_label' => __( 'Añadir celda', 'quantia-hub' ),
                        'layout'       => 'table',
                        'sub_fields'   => array(
                            array(
                                'key'   => 'field_table_cell',
                                'label' => __( 'Texto', 'quantia-hub' ),
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
        'label'      => __( 'Destacado (callout)', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_callout_text',
                'label'    => __( 'Texto', 'quantia-hub' ),
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
        'label'      => __( 'Preguntas frecuentes', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_faq_items',
                'label'        => __( 'Preguntas', 'quantia-hub' ),
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => __( 'Añadir pregunta', 'quantia-hub' ),
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_faq_q',
                        'label' => __( 'Pregunta', 'quantia-hub' ),
                        'name'  => 'q',
                        'type'  => 'text',
                    ),
                    array(
                        'key'   => 'field_faq_a',
                        'label' => __( 'Respuesta', 'quantia-hub' ),
                        'name'  => 'a',
                        'type'  => 'textarea',
                        'rows'  => 3,
                    ),
                ),
            ),
        ),
    );
}