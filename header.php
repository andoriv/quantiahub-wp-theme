<?php
/**
 * Site header.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-white font-sans text-ink antialiased' ); ?>>
<?php wp_body_open(); ?>

<a class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-full focus:bg-ink focus:px-5 focus:py-3 focus:text-sm focus:font-semibold focus:text-white"
   href="#main">
    <?php esc_html_e( 'Saltar al contenido', 'quantia-hub' ); ?>
</a>

<div id="top" class="flex min-h-screen w-full flex-col bg-white font-sans">
    <?php get_template_part( 'template-parts/header/nav' ); ?>

    <main id="main" class="flex-1">