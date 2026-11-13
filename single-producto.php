<?php
defined( 'ABSPATH' ) || exit;

get_header();

$post_id   = get_the_ID();
$post_slug = $post_id ? get_post_field( 'post_name', $post_id ) : '';
$fallback  = quantia_fallback_products();
$product   = $fallback[ $post_slug ] ?? null;

if ( ! $product ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( '404' );
    get_footer();
    return;
}

$product['name']            = get_the_title() ?: $product['name'];
$product['intro']           = get_the_excerpt() ?: $product['intro'];
$product['hero_image']      = get_the_post_thumbnail_url( $post_id, 'quantia-hero' ) ?: $product['hero_image'];
$product['detail_image']    = get_post_meta( $post_id, '_quantia_detail_image', true ) ?: $product['detail_image'];
$product['eyebrow']         = get_post_meta( $post_id, '_quantia_eyebrow', true ) ?: $product['eyebrow'];
$product['title']           = get_post_meta( $post_id, '_quantia_title', true ) ?: $product['title'];
$product['title_highlight'] = get_post_meta( $post_id, '_quantia_title_highlight', true ) ?: $product['title_highlight'];
$product['cta']             = get_post_meta( $post_id, '_quantia_cta', true ) ?: $product['cta'];
$product['cta_label']       = get_post_meta( $post_id, '_quantia_cta_label', true ) ?: $product['cta_label'];
?>

<main class="w-full">
    <?php get_template_part( 'template-parts/product/hero', null, array( 'product' => $product ) ); ?>
    <?php get_template_part( 'template-parts/product/what-is', null, array( 'product' => $product ) ); ?>
    <?php get_template_part( 'template-parts/product/options', null, array( 'product' => $product ) ); ?>

    <?php if ( ! empty( $product['funds_plan'] ) ) : ?>
        <?php get_template_part( 'template-parts/product/funds', null, array(
            'product' => $product,
            'plan'    => $product['funds_plan'],
        ) ); ?>
    <?php endif; ?>

    <?php get_template_part( 'template-parts/product/features', null, array( 'product' => $product ) ); ?>
    <?php get_template_part( 'template-parts/product/steps', null, array( 'product' => $product ) ); ?>
    <?php get_template_part( 'template-parts/product/fees', null, array( 'product' => $product ) ); ?>
    <?php get_template_part( 'features/faqs/views/product-faq', null, array( 'product' => $product ) ); ?>
</main>

<?php get_footer(); ?>