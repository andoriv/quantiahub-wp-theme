<?php
defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/account-benefits' );

if ( quantia_option( 'home_show_simulator', false ) ) {
    get_template_part( 'template-parts/home/simulator' );
}

get_template_part( 'template-parts/home/compound-calculator' );
get_template_part( 'template-parts/home/about-summary' );
get_template_part( 'template-parts/home/demo-section' );
get_template_part( 'template-parts/home/steps' );
get_template_part( 'template-parts/home/testimonials' );
get_template_part( 'template-parts/home/articles' );
get_template_part( 'template-parts/home/app-banner' );

get_footer();