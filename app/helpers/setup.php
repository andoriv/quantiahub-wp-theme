<?php
/**
 * Base WordPress configuration for the Quantia Hub theme.
 *
 * This file declares theme supports, registers navigation menus, defines
 * custom image sizes, sets the global content width and exposes the idempotent
 * seeders for pages, products and articles that run on theme activation or
 * through URL parameters.
 *
 * Configuración base de WordPress para el tema Quantia Hub.
 *
 * Este archivo declara los soportes del tema, registra menús de navegación,
 * define tamaños de imagen personalizados, establece el ancho de contenido
 * global y expone los sembradores idempotentes de páginas, productos y
 * artículos que se ejecutan al activar el tema o mediante parámetros de URL.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers theme supports, navigation menus and image sizes.
 *
 * Runs on the after_setup_theme hook. It is the canonical place to declare the
 * title tag, post thumbnails, HTML5 support, the custom logo and the four
 * navigation menus required by the design.
 *
 * Registra los soportes del tema, los menús de navegación y los tamaños de
 * imagen. Se ejecuta en el hook after_setup_theme.
 */
add_action( 'after_setup_theme', 'quantia_setup' );
function quantia_setup(): void {
    load_theme_textdomain( 'quantia-hub', QUANTIA_DIR . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );

    add_theme_support(
        'custom-logo',
        array(
            'height'      => 48,
            'width'       => 160,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    register_nav_menus(
        array(
            'primary'           => __( 'Primary navigation', 'quantia-hub' ),
            'footer_products'   => __( 'Footer, products', 'quantia-hub' ),
            'footer_site'       => __( 'Footer, site', 'quantia-hub' ),
            'footer_regulatory' => __( 'Footer, regulatory', 'quantia-hub' ),
        )
    );

    add_image_size( 'quantia-hero',        1600, 1200, true );
    add_image_size( 'quantia-detail',      1200, 1500, true );
    add_image_size( 'quantia-card',         800,  800, true );
    add_image_size( 'quantia-testimonial',  680,  850, true );
    add_image_size( 'quantia-article',     1200,  800, true );
}

/**
 * Navigation menu arguments filter.
 *
 * Kept as an extension point for future customizations of the default wp_nav_menu
 * arguments.
 *
 * Filtro de argumentos de los menús de navegación.
 *
 * Se conserva como punto de extensión para personalizaciones futuras de los
 * argumentos por defecto de wp_nav_menu.
 */
add_filter( 'wp_nav_menu_args', function ( $args ) {
    return $args;
} );

/**
 * Sets the global content width when it is not already defined.
 *
 * WordPress uses this value to constrain the width of images and embeds
 * inserted through the classic editor.
 *
 * Define el ancho de contenido global cuando no está ya establecido.
 */
add_action( 'after_setup_theme', function () {
    if ( ! isset( $GLOBALS['content_width'] ) ) {
        $GLOBALS['content_width'] = 1200;
    }
}, 0 );

/**
 * Declares the theme color for mobile browsers.
 *
 * Declara el color del tema para los navegadores móviles.
 */
add_action( 'wp_head', function () {
    echo '<meta name="theme-color" content="#0B1F17">' . "\n";
}, 1 );

/**
 * Removes the WordPress emoji script and styles.
 *
 * Reduces requests and page weight without affecting site functionality.
 *
 * Elimina el script y los estilos de emojis de WordPress.
 */
add_action( 'init', function () {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
} );

/**
 * Seeds the required pages on theme activation.
 *
 * Creates the Tarifas, Aprende, Soporte, Nosotros and Centro legal pages when
 * they do not exist and assigns the corresponding page template. Idempotent.
 *
 * Siembra las páginas requeridas al activar el tema.
 */
add_action( 'after_switch_theme', 'quantia_seed_required_pages' );
function quantia_seed_required_pages(): void {
    $pages = array(
        array(
            'title'    => __( 'Rates', 'quantia-hub' ),
            'slug'     => 'tarifas',
            'template' => 'page-templates/page-tarifas.php',
        ),
        array(
            'title'    => __( 'Learn', 'quantia-hub' ),
            'slug'     => 'aprende',
            'template' => 'page-templates/page-aprende.php',
        ),
        array(
            'title'    => __( 'Support', 'quantia-hub' ),
            'slug'     => 'soporte',
            'template' => 'page-templates/page-soporte.php',
        ),
        array(
            'title'    => __( 'About us', 'quantia-hub' ),
            'slug'     => 'nosotros',
            'template' => 'page-templates/page-nosotros.php',
        ),
        array(
            'title'    => __( 'Legal center', 'quantia-hub' ),
            'slug'     => 'legal',
            'template' => 'page-templates/page-legal.php',
        ),
    );

    foreach ( $pages as $page ) {
        $existing = get_page_by_path( $page['slug'] );
        if ( $existing ) {
            continue;
        }

        $page_id = wp_insert_post( array(
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => $page['title'],
            'post_name'    => $page['slug'],
            'post_content' => '',
        ) );

        if ( is_wp_error( $page_id ) || ! $page_id ) {
            continue;
        }

        update_post_meta( $page_id, '_wp_page_template', $page['template'] );
    }

    flush_rewrite_rules();
}

/**
 * Seeds the products of the producto custom post type when they do not exist.
 *
 * Publishes the four base products of the site. Existing drafts are republished.
 * Runs on admin_init and theme activation.
 *
 * Siembra los productos del CPT producto cuando no existen.
 */
add_action( 'admin_init', 'quantia_seed_products' );
add_action( 'after_switch_theme', 'quantia_seed_products' );
function quantia_seed_products(): void {
    if ( ! current_user_can( 'manage_options' ) && ! doing_action( 'after_switch_theme' ) ) {
        return;
    }

    $products = array(
        'inversiones' => __( 'Investments', 'quantia-hub' ),
        'alcancias'   => __( 'Saving jars', 'quantia-hub' ),
        'jubilación'      => __( 'Retirement', 'quantia-hub' ),
        'tarjeta'     => __( 'Visa debit card', 'quantia-hub' ),
    );

    $created = 0;

    foreach ( $products as $slug => $title ) {
        $existing = get_page_by_path( $slug, OBJECT, 'producto' );

        if ( $existing ) {
            if ( 'publish' !== $existing->post_status ) {
                wp_update_post( array(
                    'ID'          => $existing->ID,
                    'post_status' => 'publish',
                ) );
            }
            continue;
        }

        $result = wp_insert_post( array(
            'post_type'   => 'producto',
            'post_status' => 'publish',
            'post_title'  => $title,
            'post_name'   => $slug,
        ) );

        if ( ! is_wp_error( $result ) && $result ) {
            $created++;
        }
    }

    if ( $created > 0 ) {
        flush_rewrite_rules();
    }
}

/**
 * Allows triggering the page seeder manually through the URL.
 *
 * Activated with the query parameter quantia_seed set to pages and requires
 * administration capability.
 *
 * Permite disparar manualmente el sembrador de páginas desde la URL.
 */
add_action( 'init', 'quantia_force_seed_pages' );
function quantia_force_seed_pages(): void {
    if ( ! isset( $_GET['quantia_seed'] ) || 'pages' !== $_GET['quantia_seed'] ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    quantia_seed_required_pages();
}

/**
 * Allows triggering the product seeder manually through the URL.
 *
 * Activated with the query parameter quantia_seed set to products and requires
 * administration capability.
 *
 * Permite disparar manualmente el sembrador de productos desde la URL.
 */
add_action( 'init', 'quantia_force_seed_products' );
function quantia_force_seed_products(): void {
    if ( ! isset( $_GET['quantia_seed'] ) || 'products' !== $_GET['quantia_seed'] ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    delete_option( 'quantia_products_seeded' );
    quantia_seed_products();
}

/**
 * Intercepts requests to virtual products without a database entry.
 *
 * When a request reaches productos with a slug that has no producto post type
 * entry, a synthetic WP_Post object is built from the fallback data and injected
 * into the main query so that single-producto.php renders the product as if it
 * were published.
 *
 * Intercepta peticiones a productos virtuales sin entrada en la base de datos.
 */
add_action( 'template_redirect', 'quantia_virtual_product_redirect', 1 );
function quantia_virtual_product_redirect(): void {
    if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
        return;
    }

    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
    $home_path   = trim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
    $path        = trim( (string) wp_parse_url( $request_uri, PHP_URL_PATH ), '/' );

    if ( $home_path && strpos( $path, $home_path ) === 0 ) {
        $path = trim( substr( $path, strlen( $home_path ) ), '/' );
    }

    if ( ! preg_match( '#^productos/([a-z0-9\-]+)$#', $path, $matches ) ) {
        return;
    }

    $slug     = $matches[1];
    $fallback = quantia_fallback_products();

    if ( ! isset( $fallback[ $slug ] ) ) {
        return;
    }

    if ( get_page_by_path( $slug, OBJECT, 'producto' ) ) {
        return;
    }

    $product = $fallback[ $slug ];

    $fake_post = new WP_Post( (object) array(
        'ID'                    => 0,
        'post_author'           => 0,
        'post_date'             => current_time( 'mysql' ),
        'post_date_gmt'         => current_time( 'mysql', 1 ),
        'post_content'          => '',
        'post_title'            => $product['name'],
        'post_excerpt'          => $product['intro'],
        'post_status'           => 'publish',
        'comment_status'        => 'closed',
        'ping_status'           => 'closed',
        'post_password'         => '',
        'post_name'             => $slug,
        'to_ping'               => '',
        'pinged'                => '',
        'post_modified'         => current_time( 'mysql' ),
        'post_modified_gmt'     => current_time( 'mysql', 1 ),
        'post_content_filtered' => '',
        'post_parent'           => 0,
        'guid'                  => home_url( '/productos/' . $slug . '/' ),
        'menu_order'            => 0,
        'post_type'             => 'producto',
        'post_mime_type'        => '',
        'comment_count'         => '0',
        'filter'                => 'raw',
    ) );

    global $wp_query;
    if ( ! $wp_query instanceof WP_Query ) {
        return;
    }

    $wp_query->post              = $fake_post;
    $wp_query->posts             = array( $fake_post );
    $wp_query->post_count        = 1;
    $wp_query->found_posts       = 1;
    $wp_query->max_num_pages     = 1;
    $wp_query->is_404            = false;
    $wp_query->is_singular       = true;
    $wp_query->is_single         = true;
    $wp_query->is_page           = false;
    $wp_query->is_home           = false;
    $wp_query->is_archive        = false;
    $wp_query->queried_object    = $fake_post;
    $wp_query->queried_object_id = 0;

    $GLOBALS['post'] = $fake_post;
    setup_postdata( $fake_post );

    status_header( 200 );
}

/**
 * Forces the single-producto template for virtual products.
 *
 * Detects that the queried object is a synthetic product with zero ID and
 * returns the path to single-producto.php instead of the template WordPress
 * would have resolved by default.
 *
 * Fuerza la plantilla single-producto para productos virtuales.
 */
add_filter( 'template_include', 'quantia_virtual_product_template', 99 );
function quantia_virtual_product_template( $template ) {
    global $wp_query;

    if ( ! $wp_query instanceof WP_Query ) {
        return $template;
    }

    if (
        $wp_query->queried_object instanceof WP_Post &&
        'producto' === $wp_query->queried_object->post_type &&
        0 === (int) $wp_query->queried_object->ID
    ) {
        $candidate = QUANTIA_DIR . '/single-producto.php';
        if ( file_exists( $candidate ) ) {
            return $candidate;
        }
    }

    return $template;
}

/**
 * Allows triggering the article seeder manually through the URL.
 *
 * Activated with the query parameter quantia_seed set to articles and requires
 * administration capability.
 *
 * Permite disparar manualmente el sembrador de artículos desde la URL.
 */
add_action( 'init', 'quantia_force_seed_articles' );
function quantia_force_seed_articles(): void {
    if ( ! isset( $_GET['quantia_seed'] ) || 'articles' !== $_GET['quantia_seed'] ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    quantia_seed_articles();
}

/**
 * Seeds the sample articles of the Learn blog.
 *
 * Publishes five articles with visual, flexible body and categories assigned.
 * Does nothing when at least one article already exists as published or draft.
 *
 * Siembra los artículos de ejemplo del blog Aprende.
 */
function quantia_seed_articles(): void {
    $existing = get_posts( array(
        'post_type'      => 'articulo',
        'posts_per_page' => 1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ) );
    if ( ! empty( $existing ) ) {
        return;
    }

    $seeds = array(
        'subidas-y-bajadas-del-mercado' => array(
            'title'      => __( 'Understanding market rises and falls', 'quantia-hub' ),
            'excerpt'    => __( 'A guide for new investors.', 'quantia-hub' ),
            'visual'     => 'riesgo',
            'categories' => array( __( 'Investment', 'quantia-hub' ) ),
            'body'       => array(
                array( 'acf_fc_layout' => 'p', 'text' => __( 'If you are starting to invest, you have probably noticed that financial markets constantly rise and fall.', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'h2', 'text' => __( 'Why do markets rise and fall', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'p', 'text' => __( 'Prices in the market change because information changes.', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'callout', 'text' => __( 'At Quantia Hub, understanding these fluctuations is key.', 'quantia-hub' ) ),
            ),
        ),
        'portafolio-de-inversion-y-fondo-de-jubilacion' => array(
            'title'      => __( 'What is an investment portfolio and what is a retirement fund', 'quantia-hub' ),
            'excerpt'    => __( 'At Quantia Hub you can access both products, backed by MMG Asset Management.', 'quantia-hub' ),
            'visual'     => 'diversificacion',
            'categories' => array( __( 'Investment', 'quantia-hub' ), __( 'Retirement', 'quantia-hub' ) ),
            'body'       => array(
                array( 'acf_fc_layout' => 'h2', 'text' => __( 'What is an investment portfolio', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'p', 'text' => __( 'An investment portfolio is a set of financial assets.', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'h2', 'text' => __( 'What is a retirement fund', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'p', 'text' => __( 'An investment tool designed for your retirement.', 'quantia-hub' ) ),
            ),
        ),
        'interes-simple-y-compuesto' => array(
            'title'      => __( 'What is simple interest and how does it differ from compound interest', 'quantia-hub' ),
            'excerpt'    => __( 'You hear about it when discussing loans, savings and investments. Not all interests are equal.', 'quantia-hub' ),
            'visual'     => 'interes-compuesto',
            'categories' => array( __( 'Investment', 'quantia-hub' ) ),
            'body'       => array(
                array( 'acf_fc_layout' => 'h2', 'text' => __( 'The power of interest', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'p', 'text' => __( 'Understanding the difference can change the way you look at your money.', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'callout', 'text' => __( 'Formula. Final amount equals capital multiplied by one plus rate raised to time.', 'quantia-hub' ) ),
            ),
        ),
        'ahorro-vs-inversion' => array(
            'title'      => __( 'Saving versus investing. Why does your money lose value if you do not invest', 'quantia-hub' ),
            'excerpt'    => __( 'Inflation makes your saved money lose purchasing power.', 'quantia-hub' ),
            'visual'     => 'ahorro-vs-inversion',
            'categories' => array( __( 'Savings', 'quantia-hub' ), __( 'Investment', 'quantia-hub' ) ),
            'body'       => array(
                array( 'acf_fc_layout' => 'h2', 'text' => __( 'The challenge of saved money', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'p', 'text' => __( 'There is a silent problem. Inflation.', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'callout', 'text' => __( 'Saving protects your money, investing multiplies it.', 'quantia-hub' ) ),
            ),
        ),
        'ahorrar-primero-gastar-despues' => array(
            'title'      => __( 'Why you should save first and spend later', 'quantia-hub' ),
            'excerpt'    => __( 'The definitive guide to taking control of your finances.', 'quantia-hub' ),
            'visual'     => 'ahorro-primero',
            'categories' => array( __( 'Savings', 'quantia-hub' ) ),
            'body'       => array(
                array( 'acf_fc_layout' => 'h2', 'text' => __( 'The art of prioritizing savings', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'p', 'text' => __( 'Changing the logic of spending first is the key.', 'quantia-hub' ) ),
                array( 'acf_fc_layout' => 'callout', 'text' => __( 'The time to start is now.', 'quantia-hub' ) ),
            ),
        ),
    );

    foreach ( $seeds as $slug => $data ) {
        $post_id = wp_insert_post( array(
            'post_type'    => 'articulo',
            'post_status'  => 'publish',
            'post_title'   => $data['title'],
            'post_name'    => $slug,
            'post_excerpt' => $data['excerpt'],
        ) );

        if ( is_wp_error( $post_id ) || ! $post_id ) {
            continue;
        }

        if ( function_exists( 'update_field' ) ) {
            update_field( 'article_visual', $data['visual'], $post_id );
            update_field( 'article_body', $data['body'], $post_id );
        } else {
            update_post_meta( $post_id, 'article_visual', $data['visual'] );
            update_post_meta( $post_id, 'article_body', $data['body'] );
        }

        wp_set_object_terms( $post_id, $data['categories'], 'categoria_articulo' );
    }
}