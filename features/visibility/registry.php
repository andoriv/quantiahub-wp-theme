<?php
/**
 * Registro central de elementos ocultables.
 *
 * Cada entrada describe un elemento que el administrador puede ocultar del
 * sitio público. Los elementos disponibles son páginas completas, productos,
 * secciones dentro de una plantilla y elementos de navegación.
 *
 * Campos por entrada:
 *
 *   - label       Nombre legible mostrado en wp-admin.
 *   - description Texto de ayuda mostrado en wp-admin.
 *   - type        'page' | 'producto' | 'section' | 'nav'.
 *   - slug        Slug del post cuando type es 'page' o 'producto'.
 *   - template    Ruta del template part cuando type es 'section'.
 *   - anchors     Fragmentos (#) que identifican la sección en un menú.
 *   - group       Agrupación visual en wp-admin.
 *
 * Para añadir un elemento nuevo se usa el filtro `quantia_visibility_registry`
 * en lugar de editar este archivo.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Devuelve el registro completo de elementos ocultables.
 *
 * @return array<string,array<string,mixed>>
 */
function quantia_visibility_registry(): array {

    $registry = array(
        'page_tarifas' => array(
            'label'       => __( 'Tarifas', 'quantia-hub' ),
            'description' => __( 'Página de tarifas y comisiones.', 'quantia-hub' ),
            'type'        => 'page',
            'slug'        => 'tarifas',
            'group'       => __( 'Páginas completas', 'quantia-hub' ),
        ),
        'page_aprende' => array(
            'label'       => __( 'Aprende', 'quantia-hub' ),
            'description' => __( 'Blog y centro de aprendizaje.', 'quantia-hub' ),
            'type'        => 'page',
            'slug'        => 'aprende',
            'group'       => __( 'Páginas completas', 'quantia-hub' ),
        ),
        'page_soporte' => array(
            'label'       => __( 'Soporte', 'quantia-hub' ),
            'description' => __( 'Centro de soporte, FAQ y contacto.', 'quantia-hub' ),
            'type'        => 'page',
            'slug'        => 'soporte',
            'group'       => __( 'Páginas completas', 'quantia-hub' ),
        ),
        'page_nosotros' => array(
            'label'       => __( 'Nosotros', 'quantia-hub' ),
            'description' => __( 'Página sobre la empresa.', 'quantia-hub' ),
            'type'        => 'page',
            'slug'        => 'nosotros',
            'group'       => __( 'Páginas completas', 'quantia-hub' ),
        ),
        'page_legal' => array(
            'label'       => __( 'Centro legal', 'quantia-hub' ),
            'description' => __( 'Documentos legales y regulatorios.', 'quantia-hub' ),
            'type'        => 'page',
            'slug'        => 'legal',
            'group'       => __( 'Páginas completas', 'quantia-hub' ),
        ),
        'producto_inversiones' => array(
            'label'       => __( 'Inversiones', 'quantia-hub' ),
            'description' => __( 'Producto de portafolios de inversión.', 'quantia-hub' ),
            'type'        => 'producto',
            'slug'        => 'inversiones',
            'group'       => __( 'Productos', 'quantia-hub' ),
        ),
        'producto_alcancias' => array(
            'label'       => __( 'Alcancías', 'quantia-hub' ),
            'description' => __( 'Producto de ahorro programado.', 'quantia-hub' ),
            'type'        => 'producto',
            'slug'        => 'alcancias',
            'group'       => __( 'Productos', 'quantia-hub' ),
        ),
        'producto_jubilacion' => array(
            'label'       => __( 'Jubilación', 'quantia-hub' ),
            'description' => __( 'Producto de fondo de jubilación.', 'quantia-hub' ),
            'type'        => 'producto',
            'slug'        => 'jubilación',
            'group'       => __( 'Productos', 'quantia-hub' ),
        ),
        'producto_tarjeta' => array(
            'label'       => __( 'Tarjeta Visa Débito', 'quantia-hub' ),
            'description' => __( 'Producto de tarjeta de débito.', 'quantia-hub' ),
            'type'        => 'producto',
            'slug'        => 'tarjeta',
            'group'       => __( 'Productos', 'quantia-hub' ),
        ),
        'home_hero' => array(
            'label'       => __( 'Hero', 'quantia-hub' ),
            'description' => __( 'Bloque principal de la portada con el título y la llamada a la acción.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/hero',
            'anchors'     => array( 'hero', 'inicio' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'home_benefits' => array(
            'label'       => __( 'Producto', 'quantia-hub' ),
            'description' => __( 'Bloque de beneficios de la cuenta y productos destacados.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/account-benefits',
            'anchors'     => array( 'producto', 'productos', 'beneficios' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'home_simulator' => array(
            'label'       => __( 'Simulador', 'quantia-hub' ),
            'description' => __( 'Bloque del simulador de rendimiento.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/simulator',
            'anchors'     => array( 'simulador' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'home_calculator' => array(
            'label'       => __( 'Calculadora de interés compuesto', 'quantia-hub' ),
            'description' => __( 'Bloque de cálculo de interés compuesto.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/compound-calculator',
            'anchors'     => array( 'calculadora' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'home_about' => array(
            'label'       => __( 'Nosotros (resumen)', 'quantia-hub' ),
            'description' => __( 'Bloque resumen de la empresa en la portada.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/about-summary',
            'anchors'     => array( 'nosotros' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'home_trust' => array(
            'label'       => __( 'Respaldo bancario', 'quantia-hub' ),
            'description' => __( 'Bloque de confianza y respaldo del banco.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/trust-bank',
            'anchors'     => array( 'respaldo' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'home_demo' => array(
            'label'       => __( 'Demo', 'quantia-hub' ),
            'description' => __( 'Bloque de demostración con pestañas interactivas.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/demo-section',
            'anchors'     => array( 'demo', 'demostracion' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'home_steps' => array(
            'label'       => __( 'Pasos para abrir cuenta', 'quantia-hub' ),
            'description' => __( 'Bloque de pasos del proceso de apertura.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/steps',
            'anchors'     => array( 'pasos' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'home_testimonials' => array(
            'label'       => __( 'Testimonios', 'quantia-hub' ),
            'description' => __( 'Bloque de testimonios de clientes.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/testimonials',
            'anchors'     => array( 'testimonios' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'home_articles' => array(
            'label'       => __( 'Artículos', 'quantia-hub' ),
            'description' => __( 'Bloque de artículos recientes del blog.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/articles',
            'anchors'     => array( 'articulos' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'home_app_banner' => array(
            'label'       => __( 'Descarga la app', 'quantia-hub' ),
            'description' => __( 'Banner final de descarga de la aplicación móvil.', 'quantia-hub' ),
            'type'        => 'section',
            'template'    => 'template-parts/home/app-banner',
            'anchors'     => array( 'descarga', 'app', 'descargaya' ),
            'group'       => __( 'Secciones de la portada', 'quantia-hub' ),
        ),
        'nav_products_dropdown' => array(
            'label'       => __( 'Dropdown de productos', 'quantia-hub' ),
            'description' => __( 'Menú desplegable de productos en la barra de navegación. Ocultarlo elimina el dropdown completo aunque los productos individuales sigan visibles.', 'quantia-hub' ),
            'type'        => 'nav',
            'anchors'     => array(),
            'group'       => __( 'Navegación', 'quantia-hub' ),
        ),
    );

    /**
     * Permite a otros módulos del tema añadir elementos ocultables.
     *
     * @param array<string,array<string,mixed>> $registry Registro base.
     */
    return apply_filters( 'quantia_visibility_registry', $registry );
}