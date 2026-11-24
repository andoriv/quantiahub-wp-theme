<?php
/**
 * Plantilla de la página de inicio (Home).
 *
 * Orquesta el orden de los bloques en la portada delegando el renderizado
 * a quantia_render_section(). Cada bloque puede ser ocultado desde
 * wp-admin → Visibilidad → Secciones de la portada sin necesidad de
 * editar esta plantilla.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

get_header();

quantia_render_section( 'home_hero',         'template-parts/home/hero' );
quantia_render_section( 'home_benefits',     'template-parts/home/account-benefits' );

if ( quantia_option( 'home_show_simulator', false ) ) {
    quantia_render_section( 'home_simulator', 'template-parts/home/simulator' );
}

quantia_render_section( 'home_calculator',   'template-parts/home/compound-calculator' );
quantia_render_section( 'home_about',        'template-parts/home/about-summary' );
quantia_render_section( 'home_trust',        'template-parts/home/trust-bank' );
quantia_render_section( 'home_demo',         'template-parts/home/demo-section' );
quantia_render_section( 'home_steps',        'template-parts/home/steps' );
quantia_render_section( 'home_testimonials', 'template-parts/home/testimonials' );
quantia_render_section( 'home_articles',     'template-parts/home/articles' );
quantia_render_section( 'home_app_banner',   'template-parts/home/app-banner' );

get_footer();