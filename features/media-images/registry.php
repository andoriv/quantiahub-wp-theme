<?php
/**
 * Registro central de imágenes del sitio.
 *
 * Cada imagen tiene: label, description (opcional), default (URL),
 * legacy_key (opcional: lee de quantia_option() si no hay override),
 * size (opcional: tamaño WP para attachment), alt (opcional).
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_media_registry(): array {
    return array(
        'marca' => array(
            'label'       => __( 'Marca e identidad', 'quantia-hub' ),
            'description' => __( 'Logo, imagen social y logos de entidades reguladoras.', 'quantia-hub' ),
            'items'       => array(
                'brand_logo' => array(
                    'label'       => __( 'Logo principal', 'quantia-hub' ),
                    'description' => __( 'Logo que aparece en el header y el footer.', 'quantia-hub' ),
                    'default'     => content_url( '/uploads/2026/10/logo-de-quantia.png' ),
                    'alt'         => 'Quantia Hub',
                ),
                'og_default' => array(
                    'label'       => __( 'Imagen para compartir (Open Graph)', 'quantia-hub' ),
                    'description' => __( 'Imagen que se muestra al compartir el sitio en redes y WhatsApp.', 'quantia-hub' ),
                    'default'     => content_url( '/uploads/2026/11/quantia-og-default.jpg' ),
                    'alt'         => 'Quantia Hub',
                ),
                'regulator_sbp' => array(
                    'label'   => __( 'Logo Superintendencia de Bancos', 'quantia-hub' ),
                    'default' => content_url( '/uploads/2026/11/Logo_Superintendencia-de-Bancos-de-Panama-1.png' ),
                    'alt'     => 'SBP',
                ),
                'regulator_smv' => array(
                    'label'   => __( 'Logo Superintendencia del Mercado de Valores', 'quantia-hub' ),
                    'default' => content_url( '/uploads/2026/11/logo_Superintendencia-del-Mercado-de-Valores.png' ),
                    'alt'     => 'SMV',
                ),
                'regulator_latinex' => array(
                    'label'   => __( 'Logo Latinex', 'quantia-hub' ),
                    'default' => content_url( '/uploads/2026/11/Logo_Latinex.png' ),
                    'alt'     => 'Latinex',
                ),
            ),
        ),

        'inicio' => array(
            'label'       => __( 'Inicio', 'quantia-hub' ),
            'description' => __( 'Imágenes que aparecen en la portada y en secciones destacadas.', 'quantia-hub' ),
            'items'       => array(
                'home_hero_background' => array(
                    'label'       => __( 'Fondo del hero', 'quantia-hub' ),
                    'description' => __( 'Imagen grande de fondo al inicio de la portada.', 'quantia-hub' ),
                    'default'     => home_url( '/wp-content/uploads/2026/11/MMG.webp' ),
                    'legacy_key'  => 'home_hero_background',
                    'size'        => 'quantia-hero',
                ),
                'home_team_image' => array(
                    'label'       => __( 'Equipo (Nosotros)', 'quantia-hub' ),
                    'description' => __( 'Imagen del equipo en la página Nosotros.', 'quantia-hub' ),
                    'default'     => home_url( '/wp-content/uploads/2026/10/7e9ca160-e5f6-4a1a-af25-902c93113a67.jpg' ),
                    'legacy_key'  => 'home_team_image',
                ),
                'home_learn_image' => array(
                    'label'       => __( 'Aprende', 'quantia-hub' ),
                    'description' => __( 'Imagen lateral del hero en la página Aprende.', 'quantia-hub' ),
                    'default'     => home_url( '/wp-content/uploads/2026/10/7732df65-c2c8-43c1-9d99-d9456e4a2947.jpg' ),
                    'legacy_key'  => 'home_learn_image',
                ),
                'home_trust_bank' => array(
                    'label'       => __( 'Confianza (MMG)', 'quantia-hub' ),
                    'description' => __( 'Imagen de la sección "Detrás de la app hay un banco real".', 'quantia-hub' ),
                    'default'     => home_url( '/wp-content/uploads/2026/11/MMG.webp' ),
                ),
            ),
        ),

        'productos' => array(
            'label'       => __( 'Productos', 'quantia-hub' ),
            'description' => __( 'Imágenes de portada y detalle para cada producto.', 'quantia-hub' ),
            'items'       => array(
                'producto_inversiones_hero' => array(
                    'label'   => __( 'Inversiones — Hero', 'quantia-hub' ),
                    'default' => home_url( '/wp-content/uploads/2026/10/6c38f06a-7560-42c3-bfbf-906b175fdc95.jpg' ),
                ),
                'producto_inversiones_detail' => array(
                    'label'   => __( 'Inversiones — Detalle', 'quantia-hub' ),
                    'default' => home_url( '/wp-content/uploads/2026/10/6c638953-09cb-4d25-9c8b-1e8ce1c7e277.jpg' ),
                ),
                'producto_alcancias_hero' => array(
                    'label'   => __( 'Alcancías — Hero', 'quantia-hub' ),
                    'default' => home_url( '/wp-content/uploads/2026/10/cece73f6-16ae-46da-b9c2-29a7511db492.jpg' ),
                ),
                'producto_alcancias_detail' => array(
                    'label'   => __( 'Alcancías — Detalle', 'quantia-hub' ),
                    'default' => home_url( '/wp-content/uploads/2026/10/e9bb4a41-0739-4cbb-9ee7-4658599e4036.jpg' ),
                ),
                'producto_jubilacion_hero' => array(
                    'label'   => __( 'Jubilación — Hero', 'quantia-hub' ),
                    'default' => home_url( '/wp-content/uploads/2026/10/49e4de75-73c4-43ea-977b-6f4f95825596.jpg' ),
                ),
                'producto_jubilacion_detail' => array(
                    'label'   => __( 'Jubilación — Detalle', 'quantia-hub' ),
                    'default' => home_url( '/wp-content/uploads/2026/10/b4fe8526-17d9-455f-9505-13bc2940af4f.jpg' ),
                ),
                'producto_tarjeta_hero' => array(
                    'label'   => __( 'Tarjeta — Hero', 'quantia-hub' ),
                    'default' => home_url( '/wp-content/uploads/2026/10/86af8fbe-dae8-4f13-9e69-6902ed1b0690.jpg' ),
                ),
                'producto_tarjeta_detail' => array(
                    'label'   => __( 'Tarjeta — Detalle', 'quantia-hub' ),
                    'default' => home_url( '/wp-content/uploads/2026/10/34e39827-e8c6-4f2b-a9b7-01678ef6b61a.jpg' ),
                ),
            ),
        ),

        'contacto' => array(
            'label'       => __( 'Contacto y soporte', 'quantia-hub' ),
            'description' => __( 'Imágenes del Centro de ayuda y Soporte.', 'quantia-hub' ),
            'items'       => array(
                'contact_support_image' => array(
                    'label'       => __( 'Centro de ayuda', 'quantia-hub' ),
                    'description' => __( 'Foto del agente atendiendo. Aparece en /soporte/.', 'quantia-hub' ),
                    'default'     => home_url( '/wp-content/uploads/2026/10/5962edf7-e9c9-467e-ab1c-87fade080c22.jpg' ),
                    'legacy_key'  => 'support_image',
                ),
            ),
        ),
    );
}

/**
 * Devuelve la definición de una imagen por key, o null.
 */
function quantia_media_registry_item( string $key ): ?array {
    foreach ( quantia_media_registry() as $group ) {
        if ( isset( $group['items'][ $key ] ) ) {
            return $group['items'][ $key ];
        }
    }
    return null;
}