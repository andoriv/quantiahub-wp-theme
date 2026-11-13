<?php
/**
 * SEO and Open Graph meta tags of the theme.
 *
 * This file generates the description, Open Graph and Twitter Card meta tags
 * when no SEO plugin is active. If Yoast or RankMath is detected the function
 * is disabled automatically to avoid duplicate tags.
 *
 * Metaetiquetas de SEO y Open Graph del tema.
 *
 * Este archivo genera las metaetiquetas de descripción, Open Graph y Twitter
 * Card cuando no hay un plugin de SEO activo. Si se detecta Yoast o RankMath,
 * la función se desactiva automáticamente para no duplicar etiquetas.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Prints the SEO meta tags in the document head.
 *
 * Se ejecuta en el hook wp_head con prioridad cinco para salir antes que
 * cualquier otro plugin.
 */
add_action( 'wp_head', 'quantia_seo_meta', 5 );
function quantia_seo_meta(): void {
    if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
        return;
    }

    $title       = wp_get_document_title();
    $description = '';
    $image       = '';

    if ( is_singular() ) {
        $post_id     = get_queried_object_id();
        $description = get_the_excerpt( $post_id );
        $image       = get_the_post_thumbnail_url( $post_id, 'quantia-hero' );
    } else {
        $description = get_bloginfo( 'description' );
    }

    echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $description ) ) . '">' . "\n";
    echo '<meta property="og:type" content="' . ( is_singular() ? 'article' : 'website' ) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( wp_strip_all_tags( $description ) ) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( home_url( add_query_arg( array() ) ) ) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
    if ( $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
}