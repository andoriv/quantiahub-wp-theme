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

add_action( 'wp_head', 'quantia_robots_meta', 1 );
function quantia_robots_meta(): void {

    $noindex = false;

    if ( is_search() || is_404() || is_date() || is_author() ) {
        $noindex = true;
    }
    if ( is_paged() && get_query_var( 'paged' ) > 1 ) {
        $noindex = true;
    }

    $content = $noindex ? 'noindex, nofollow' : 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1';
    echo '<meta name="robots" content="' . esc_attr( $content ) . '">' . "\n";
}

add_action( 'wp_head', 'quantia_canonical', 2 );
function quantia_canonical(): void {
    if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
        return;
    }

    $canonical = '';

    if ( is_singular() ) {
        $canonical = get_permalink();
    } elseif ( is_front_page() ) {
        $canonical = home_url( '/' );
    } elseif ( is_home() ) {
        $canonical = get_permalink( get_option( 'page_for_posts' ) );
    } elseif ( is_tax() || is_category() || is_tag() ) {
        $canonical = get_term_link( get_queried_object() );
    } elseif ( is_paged() ) {
        $canonical = get_pagenum_link( get_query_var( 'paged' ) );
    }

    if ( $canonical && ! is_wp_error( $canonical ) ) {
        echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
    }
}

add_action( 'wp_head', 'quantia_seo_meta', 5 );
function quantia_seo_meta(): void {
    if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
        return;
    }

    $site_name   = get_bloginfo( 'name' );
    $locale      = get_locale();
    $og_locale   = str_replace( '-', '_', $locale );
    $title       = wp_get_document_title();
    $description = '';
    $image_url   = '';
    $image_alt   = $site_name;
    $image_w     = 0;
    $image_h     = 0;
    $og_type     = 'website';
    $article_meta = array();

    $fallback_image = content_url( '/uploads/2026/11/quantia-og-default.jpg' );

    if ( is_singular() ) {
        $post_id     = get_queried_object_id();
        $post        = get_post( $post_id );
        $description = get_the_excerpt( $post_id );

        $thumbnail_id = get_post_thumbnail_id( $post_id );
        if ( $thumbnail_id ) {
            $image_data  = wp_get_attachment_image_src( $thumbnail_id, 'quantia-hero' );
            $image_url   = $image_data ? $image_data[0] : '';
            $image_w     = $image_data ? (int) $image_data[1] : 0;
            $image_h     = $image_data ? (int) $image_data[2] : 0;
            $image_alt   = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) ?: $site_name;
        }

        if ( 'post' === get_post_type( $post_id ) ) {
            $og_type = 'article';
            $article_meta = array(
                'published_time' => get_the_date( 'c', $post ),
                'modified_time'  => get_the_modified_date( 'c', $post ),
                'author'         => get_the_author_meta( 'display_name', (int) $post->post_author ),
                'section'        => ( $cats = get_the_category( $post_id ) ) ? $cats[0]->name : '',
            );
        }
    } else {
        $description = get_bloginfo( 'description' );
    }

    $description = wp_strip_all_tags( $description );
    if ( mb_strlen( $description ) > 160 ) {
        $description = mb_substr( $description, 0, 157 ) . '...';
    }

    if ( ! $image_url ) {
        $image_url = $fallback_image;
    }

    $current_url = home_url( add_query_arg( array() ) );

    echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";

    echo '<meta property="og:locale" content="' . esc_attr( $og_locale ) . '">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $current_url ) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";

    if ( $image_url ) {
        echo '<meta property="og:image" content="' . esc_url( $image_url ) . '">' . "\n";
        echo '<meta property="og:image:alt" content="' . esc_attr( $image_alt ) . '">' . "\n";

        if ( $image_w && $image_h ) {
            echo '<meta property="og:image:width" content="' . esc_attr( $image_w ) . '">' . "\n";
            echo '<meta property="og:image:height" content="' . esc_attr( $image_h ) . '">' . "\n";
        }
        $ext = strtolower( pathinfo( $image_url, PATHINFO_EXTENSION ) );
        $mime_map = array( 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif' );
        if ( isset( $mime_map[ $ext ] ) ) {
            echo '<meta property="og:image:type" content="' . esc_attr( $mime_map[ $ext ] ) . '">' . "\n";
        }
    }

    if ( 'article' === $og_type && $article_meta ) {
        echo '<meta property="article:published_time" content="' . esc_attr( $article_meta['published_time'] ) . '">' . "\n";
        echo '<meta property="article:modified_time" content="' . esc_attr( $article_meta['modified_time'] ) . '">' . "\n";
        if ( $article_meta['author'] ) {
            echo '<meta property="article:author" content="' . esc_attr( $article_meta['author'] ) . '">' . "\n";
        }
        if ( $article_meta['section'] ) {
            echo '<meta property="article:section" content="' . esc_attr( $article_meta['section'] ) . '">' . "\n";
        }
    }

    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
    if ( $image_url ) {
        echo '<meta name="twitter:image" content="' . esc_url( $image_url ) . '">' . "\n";
        echo '<meta name="twitter:image:alt" content="' . esc_attr( $image_alt ) . '">' . "\n";
    }

    if ( defined( 'QUANTIA_TWITTER_HANDLE' ) && QUANTIA_TWITTER_HANDLE ) {
        echo '<meta name="twitter:site" content="' . esc_attr( '@' . ltrim( QUANTIA_TWITTER_HANDLE, '@' ) ) . '">' . "\n";
    }
}

add_action( 'wp_head', 'quantia_jsonld', 10 );
function quantia_jsonld(): void {
    if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
        return;
    }

    $graphs = array();
    $home   = home_url( '/' );

    // CORREGIDO: Los IDs ahora tienen su string completo y no rompen el array
    $graphs[] = array(
        '@type'            => 'Organization',
        '@id'              => $home . '#organization',
        'name'             => 'MMG Bank Corporation',
        'alternateName'    => 'Quantia Hub',
        'url'              => $home,
        'logo'             => array(
            '@type'  => 'ImageObject',
            'url'    => content_url( '/uploads/2026/10/logo-de-quantia.png' ),
            'width'  => 300,
            'height' => 60,
        ),
        'contactPoint'     => array(
            '@type'             => 'ContactPoint',
            'telephone'         => '+507-208-7024',
            'contactType'       => 'customer service',
            'areaServed'        => 'PA',
            'availableLanguage' => 'Spanish',
        ),
        // CORREGIDO: Las URLs de redes sociales ahora están completas
        'sameAs'           => array(
            'https://www.instagram.com/quantiahub/',
            'https://www.facebook.com/p/Quantia-Hub-61554626611782/',
            'https://www.linkedin.com/company/quantia-hub/home/',
            'https://www.youtube.com/@QuantiaHub',
        ),
    );

    $graphs[] = array(
        '@type'            => 'WebSite',
        '@id'              => $home . '#website',
        'url'              => $home,
        'name'             => get_bloginfo( 'name' ),
        'description'      => get_bloginfo( 'description' ),
        'publisher'        => array( '@id' => $home . '#organization' ),
        'inLanguage'       => 'es-PA',
        'potentialAction'  => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'       => 'EntryPoint',
                'urlTemplate' => $home . '?s={search_term_string}',
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );

    $current_url = home_url( add_query_arg( array() ) );
    $webpage = array(
        '@type'           => 'WebPage',
        '@id'             => $current_url . '#webpage',
        'url'             => $current_url,
        'name'            => wp_get_document_title(),
        'isPartOf'        => array( '@id' => $home . '#website' ),
        'about'           => array( '@id' => $home . '#organization' ),
        'inLanguage'      => 'es-PA',
        'datePublished'   => get_bloginfo( 'date' ),
    );

    if ( is_singular() ) {
        $post_id = get_queried_object_id();
        $post    = get_post( $post_id );

        $webpage['datePublished'] = get_the_date( 'c', $post );
        $webpage['dateModified']  = get_the_modified_date( 'c', $post );
        $webpage['description']   = wp_strip_all_tags( get_the_excerpt( $post_id ) );

        if ( $thumb = get_the_post_thumbnail_url( $post_id, 'quantia-hero' ) ) {
            $webpage['thumbnailUrl'] = $thumb;
            $webpage['image']        = array(
                '@type' => 'ImageObject',
                'url'   => $thumb,
            );
        }

        if ( 'post' === get_post_type( $post_id ) ) {
            $webpage['@type'] = 'Article';

            $webpage['headline']        = get_the_title( $post_id );
            $webpage['author']          = array(
                '@type' => 'Person',
                'name'  => get_the_author_meta( 'display_name', (int) $post->post_author ),
            );
            $webpage['publisher']       = array( '@id' => $home . '#organization' );
            $webpage['mainEntityOfPage'] = array( '@id' => $current_url . '#webpage' );
        }
    }

    $graphs[] = $webpage;

    if ( ! is_front_page() ) {
        $items   = array();
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => get_bloginfo( 'name' ),
            'item'     => $home,
        );

        if ( is_singular() ) {
            $post_id = get_queried_object_id();
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => get_the_title( $post_id ),
                'item'     => get_permalink( $post_id ),
            );
        } elseif ( is_tax() || is_category() || is_tag() ) {
            $term    = get_queried_object();
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => $term->name,
                'item'     => get_term_link( $term ),
            );
        }

        $graphs[] = array(
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        );
    }

    if ( is_front_page() ) {
        $graphs[] = array(
            '@type'          => 'FinancialService',
            '@id'            => $home . '#financialservice',
            'name'           => 'Quantia Hub',
            'url'            => $home,
            'description'    => get_bloginfo( 'description' ),
            'provider'       => array( '@id' => $home . '#organization' ),
            'areaServed'     => array(
                '@type' => 'Country',
                'name'  => 'Panama',
            ),
            'serviceType'    => array( 'Inversiones', 'Alcancías', 'Retiro', 'Tarjetas' ),
            'currenciesAccepted' => 'USD',
            'knowsAbout'     => array(
                'Inversiones financieras',
                'Fondos de inversión',
                'Ahorro programado',
                'Mercado de valores de Panamá',
            ),
        );
    }

    $json = array(
        '@context' => 'https://schema.org',
        '@graph'   => $graphs,
    );

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    echo "\n" . '</script>' . "\n";
}

add_filter( 'document_title_parts', function( array $title ): array {
    if ( is_front_page() || is_home() ) {
        return array( 'title' => 'Quantia Hub' );
    }
    if ( isset( $title['site'] ) ) {
        $title['site'] = 'Quantia Hub';
    }
    return $title;
} );

add_filter( 'document_title_separator', function(): string {
    return '|';
} );