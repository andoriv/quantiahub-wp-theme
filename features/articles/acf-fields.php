<?php
/**
 * Articles ACF field group.
 *
 * This file declares the ACF field group that configures the article content.
 * It provides the visual selector for the featured card and the flexible
 * content layouts that structure the article body. The flexible layouts cover
 * paragraphs, headings, lists, definitions, tables, callouts and inline FAQs
 * that the article body view renders on the single article template.
 *
 * Grupo de campos ACF de artículos.
 *
 * Este archivo declara el grupo de campos ACF que configura el contenido del
 * artículo. Proporciona el selector visual para la tarjeta destacada y los
 * diseños de contenido flexible que estructuran el cuerpo del artículo. Los
 * diseños flexibles cubren párrafos, encabezados, listas, definiciones, tablas,
 * destacados y preguntas frecuentes en línea que la vista del cuerpo del
 * artículo renderiza en la plantilla de artículo individual.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

acf_add_local_field_group( array(
    'key'      => 'group_quantia_articulo',
    'title'    => __( 'Article, content', 'quantia-hub' ),
    'fields'   => array(
        array(
            'key'           => 'field_articulo_visual',
            'label'         => __( 'Featured visual', 'quantia-hub' ),
            'name'          => 'article_visual',
            'type'          => 'select',
            'choices'       => array(
                'riesgo'              => __( 'Risk, lines', 'quantia-hub' ),
                'diversificacion'     => __( 'Diversification, donut', 'quantia-hub' ),
                'interes-compuesto'   => __( 'Compound interest, bars', 'quantia-hub' ),
                'ahorro-primero'      => __( 'Saving first, horizontal bars', 'quantia-hub' ),
                'ahorro-vs-inversion' => __( 'Saving versus investing, lines', 'quantia-hub' ),
            ),
            'default_value' => 'riesgo',
            'required'      => 1,
        ),
        array(
            'key'          => 'field_articulo_body',
            'label'        => __( 'Article body', 'quantia-hub' ),
            'name'         => 'article_body',
            'type'         => 'flexible_content',
            'button_label' => __( 'Add block', 'quantia-hub' ),
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
        'label'      => __( 'Paragraph', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'       => 'field_p_text',
                'label'     => __( 'Text', 'quantia-hub' ),
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
        'label'      => __( 'Heading H2', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_h2_text',
                'label'    => __( 'Text', 'quantia-hub' ),
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
        'label'      => __( 'Heading H3', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_h3_text',
                'label'    => __( 'Text', 'quantia-hub' ),
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
        'label'      => __( 'Bulleted list', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_list_items',
                'label'        => __( 'Items', 'quantia-hub' ),
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => __( 'Add item', 'quantia-hub' ),
                'layout'       => 'table',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_list_item_text',
                        'label' => __( 'Text', 'quantia-hub' ),
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
        'label'      => __( 'Definitions', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_defs_items',
                'label'        => __( 'Definitions', 'quantia-hub' ),
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => __( 'Add definition', 'quantia-hub' ),
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_def_term',
                        'label' => __( 'Term', 'quantia-hub' ),
                        'name'  => 'term',
                        'type'  => 'text',
                    ),
                    array(
                        'key'   => 'field_def_text',
                        'label' => __( 'Definition', 'quantia-hub' ),
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
        'label'      => __( 'Table', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_table_head',
                'label'        => __( 'Headers', 'quantia-hub' ),
                'name'         => 'head',
                'type'         => 'repeater',
                'button_label' => __( 'Add column', 'quantia-hub' ),
                'layout'       => 'table',
                'min'          => 2,
                'max'          => 5,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_table_head_text',
                        'label' => __( 'Text', 'quantia-hub' ),
                        'name'  => 'text',
                        'type'  => 'text',
                    ),
                ),
            ),
            array(
                'key'          => 'field_table_rows',
                'label'        => __( 'Rows', 'quantia-hub' ),
                'name'         => 'rows',
                'type'         => 'repeater',
                'button_label' => __( 'Add row', 'quantia-hub' ),
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'          => 'field_table_row_cells',
                        'label'        => __( 'Cells, one per column', 'quantia-hub' ),
                        'name'         => 'cells',
                        'type'         => 'repeater',
                        'button_label' => __( 'Add cell', 'quantia-hub' ),
                        'layout'       => 'table',
                        'sub_fields'   => array(
                            array(
                                'key'   => 'field_table_cell',
                                'label' => __( 'Text', 'quantia-hub' ),
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
        'label'      => __( 'Callout', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'      => 'field_callout_text',
                'label'    => __( 'Text', 'quantia-hub' ),
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
        'label'      => __( 'Frequently asked questions', 'quantia-hub' ),
        'display'    => 'block',
        'sub_fields' => array(
            array(
                'key'          => 'field_faq_items',
                'label'        => __( 'Questions', 'quantia-hub' ),
                'name'         => 'items',
                'type'         => 'repeater',
                'button_label' => __( 'Add question', 'quantia-hub' ),
                'layout'       => 'block',
                'min'          => 1,
                'sub_fields'   => array(
                    array(
                        'key'   => 'field_faq_q',
                        'label' => __( 'Question', 'quantia-hub' ),
                        'name'  => 'q',
                        'type'  => 'text',
                    ),
                    array(
                        'key'   => 'field_faq_a',
                        'label' => __( 'Answer', 'quantia-hub' ),
                        'name'  => 'a',
                        'type'  => 'textarea',
                        'rows'  => 3,
                    ),
                ),
            ),
        ),
    );
}