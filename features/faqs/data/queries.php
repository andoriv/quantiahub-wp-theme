<?php
/**
 * FAQ data queries and shared data helpers.
 *
 * This file exposes every read and metadata helper of the FAQ feature. It
 * handles the visibility flag of individual questions, the visibility flag of
 * categories, the query that returns published questions grouped by category
 * and the mapping of product slugs to human readable labels used by the
 * administration screens.
 *
 * Consultas de datos de FAQ y utilidades compartidas.
 *
 * Este archivo expone cada función de lectura y metadatos de la feature de FAQ.
 * Maneja la marca de visibilidad de las preguntas individuales, la marca de
 * visibilidad de las categorías, la consulta que devuelve las preguntas
 * publicadas agrupadas por categoría y el mapeo de slugs de producto a
 * etiquetas legibles usadas por las pantallas de administración.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta key that stores the visibility flag of an FAQ category.
 *
 * Clave de metadatos que almacena la marca de visibilidad de una categoría
 * de FAQ.
 */
const QUANTIA_FAQ_CAT_VISIBLE_META = 'qh_cat_visible';

/**
 * Returns whether a category is visible.
 *
 * A category without the visibility meta is considered visible, which keeps
 * newly created categories available without an explicit publish step.
 *
 * Devuelve si una categoría es visible.
 *
 * @param int $term_id Term identifier.
 * @return bool
 */
function quantia_faq_category_is_visible( int $term_id ): bool {
    if ( ! $term_id ) {
        return false;
    }
    $value = get_term_meta( $term_id, QUANTIA_FAQ_CAT_VISIBLE_META, true );
    return '0' !== $value;
}

/**
 * Returns the product labels used in the FAQ administration screens.
 *
 * Devuelve las etiquetas de producto utilizadas en las pantallas de
 * administración de FAQ.
 *
 * @return array
 */
function quantia_faq_product_labels(): array {
    return array(
        ''            => __( 'Centro de ayuda (general)', 'quantia-hub' ),
        'inversiones' => __( 'Inversiones', 'quantia-hub' ),
        'alcancias'   => __( 'Alcancías', 'quantia-hub' ),
        'retiro'      => __( 'Retiro', 'quantia-hub' ),
        'tarjeta'     => __( 'Tarjeta Visa Débito', 'quantia-hub' ),
    );
}

/**
 * Returns the published FAQs of the custom post type filtered by product and
 * visibility.
 *
 * Excludes questions marked as hidden, questions without category and
 * questions whose category has been hidden.
 *
 * Devuelve las preguntas publicadas del tipo de contenido personalizado
 * filtradas por producto y visibilidad.
 *
 * @param string $product Product slug or empty string for the help center.
 * @return array<int, array{id:int,question:string,answer:string,category:string,category_name:string}>
 */
function quantia_get_faqs( string $product = '' ): array {
    $meta_query = array();

    if ( $product ) {
        $meta_query[] = array(
            'key'     => 'faq_product',
            'value'   => $product,
            'compare' => '=',
        );
    } else {
        $meta_query[] = array(
            'relation' => 'OR',
            array( 'key' => 'faq_product', 'compare' => 'NOT EXISTS' ),
            array( 'key' => 'faq_product', 'value' => '', 'compare' => '=' ),
        );
    }

    $query = new WP_Query( array(
        'post_type'      => 'faq',
        'post_status'    => 'publish',
        'posts_per_page' => 200,
        'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
        'meta_query'     => $meta_query,
    ) );

    $faqs = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id = get_the_ID();

            if ( '0' === get_post_meta( $post_id, 'faq_visible', true ) ) {
                continue;
            }

            $terms = get_the_terms( $post_id, 'categoria_faq' );

            if ( ! $terms || is_wp_error( $terms ) ) {
                continue;
            }

            if ( ! quantia_faq_category_is_visible( (int) $terms[0]->term_id ) ) {
                continue;
            }

            $slug = $terms[0]->slug;
            $name = $terms[0]->name;

            $answer = get_the_content();
            $answer = apply_filters( 'the_content', $answer );
            $answer = wp_strip_all_tags( $answer );

            $faqs[] = array(
                'id'            => (int) $post_id,
                'question'      => get_the_title(),
                'answer'        => $answer,
                'category'      => $slug,
                'category_name' => $name,
            );
        }
        wp_reset_postdata();
    }

    return $faqs;
}

/**
 * Groups a flat FAQ list by category.
 *
 * Agrupa una lista plana de FAQ por categoría.
 *
 * @param array $faqs Flat FAQ list.
 * @return array
 */
function quantia_group_faqs_by_category( array $faqs ): array {
    $groups = array();
    foreach ( $faqs as $faq ) {
        $key = $faq['category'] ?: 'general';
        if ( ! isset( $groups[ $key ] ) ) {
            $groups[ $key ] = array(
                'id'        => $key,
                'label'     => $faq['category_name'] ?: __( 'General', 'quantia-hub' ),
                'questions' => array(),
            );
        }
        $groups[ $key ]['questions'][] = $faq;
    }
    return array_values( $groups );
}

/**
 * Returns the categories that are visible and have at least one visible
 * published question.
 *
 * Devuelve las categorías visibles que tienen al menos una pregunta publicada
 * y visible.
 *
 * @return array
 */
function quantia_get_faq_categories_from_cpt(): array {
    $terms = get_terms( array(
        'taxonomy'   => 'categoria_faq',
        'hide_empty' => true,
    ) );
    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return array();
    }

    $out = array();
    foreach ( $terms as $term ) {
        if ( ! quantia_faq_category_is_visible( (int) $term->term_id ) ) {
            continue;
        }

        $check = new WP_Query( array(
            'post_type'      => 'faq',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'categoria_faq',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            ),
            'meta_query'     => array(
                array(
                    'relation' => 'OR',
                    array( 'key' => 'faq_visible', 'value' => '0', 'compare' => '!=' ),
                    array( 'key' => 'faq_visible', 'compare' => 'NOT EXISTS' ),
                ),
            ),
        ) );

        if ( $check->have_posts() ) {
            $out[] = array( 'id' => $term->slug, 'label' => $term->name );
        }
    }

    return $out;
}

/**
 * Returns whether at least one published and visible FAQ exists.
 *
 * Devuelve si existe al menos una pregunta publicada y visible.
 *
 * @param string $product Optional product slug.
 * @return bool
 */
function quantia_has_faqs( string $product = '' ): bool {
    return ! empty( quantia_get_faqs( $product ) );
}

/**
 * Counts published and draft questions without a category.
 *
 * Cuenta las preguntas publicadas y en borrador que no tienen categoría.
 *
 * @return int
 */
function quantia_count_uncategorized_faqs(): int {
    $query = new WP_Query( array(
        'post_type'      => 'faq',
        'post_status'    => array( 'publish', 'draft' ),
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'tax_query'      => array(
            array(
                'taxonomy' => 'categoria_faq',
                'operator' => 'NOT EXISTS',
            ),
        ),
    ) );
    return (int) $query->found_posts;
}