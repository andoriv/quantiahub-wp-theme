<?php
/**
 * Pantalla de administración de imágenes.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'quantia_media_images_menu', 25 );
function quantia_media_images_menu(): void {
    global $admin_page_hooks;

    if ( empty( $admin_page_hooks['quantia-hub'] ) ) {
        add_menu_page(
            'Quantia Hub',
            'Quantia Hub',
            'manage_options',
            'quantia-hub',
            '__return_null',
            'dashicons-admin-generic',
            3
        );
    }

    add_submenu_page(
        'quantia-hub',
        __( 'Imágenes', 'quantia-hub' ),
        __( 'Imágenes', 'quantia-hub' ),
        'manage_options',
        'quantia-imagenes',
        'quantia_render_media_images_page'
    );
}

function quantia_render_media_images_page(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    wp_enqueue_media();

    wp_enqueue_script(
        'quantia-media-images-admin',
        quantia_asset( 'assets/js/media-images-admin.js' ),
        array( 'media-upload' ),
        QUANTIA_VERSION,
        true
    );

    $registry = quantia_media_registry();
    $saved    = quantia_managed_images_option();
    $updated  = isset( $_GET['updated'] ) && '1' === $_GET['updated'];
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Imágenes del sitio', 'quantia-hub' ); ?></h1>
        <p style="max-width:780px;color:#50575e">
            <?php esc_html_e( 'Cambia las imágenes que aparecen en la web. Puedes elegir una imagen de la biblioteca de WordPress o pegar una URL externa. Al dejar un campo vacío se usará la imagen predeterminada del tema.', 'quantia-hub' ); ?>
        </p>

        <?php if ( $updated ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Imágenes actualizadas.', 'quantia-hub' ); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'quantia_save_managed_images', 'quantia_managed_images_nonce' ); ?>
            <input type="hidden" name="action" value="quantia_save_managed_images">

            <?php foreach ( $registry as $group ) : ?>
                <?php if ( empty( $group['items'] ) ) { continue; } ?>
                <h2 class="title" style="margin-top:32px"><?php echo esc_html( $group['label'] ); ?></h2>
                <?php if ( ! empty( $group['description'] ) ) : ?>
                    <p style="max-width:780px;color:#50575e;margin-top:0">
                        <?php echo esc_html( $group['description'] ); ?>
                    </p>
                <?php endif; ?>

                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;margin-top:16px">
                    <?php foreach ( $group['items'] as $key => $item ) :
                        $entry   = $saved[ $key ] ?? array();
                        $id      = ! empty( $entry['id'] )  ? (int) $entry['id'] : 0;
                        $url     = ! empty( $entry['url'] ) ? (string) $entry['url'] : '';
                        $preview = $id > 0 ? wp_get_attachment_image_url( $id, 'medium' ) : ( $url ?: ( $item['default'] ?? '' ) );
                    ?>
                        <div class="card" data-image-key="<?php echo esc_attr( $key ); ?>" style="margin:0;max-width:none;padding:16px">
                            <div data-image-preview style="aspect-ratio:16/9;background:#f6f7f7;border-radius:6px;overflow:hidden;display:flex;align-items:center;justify-content:center;margin-bottom:12px">
                                <?php if ( $preview ) : ?>
                                    <img src="<?php echo esc_url( $preview ); ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
                                <?php else : ?>
                                    <span style="color:#8c8f94;font-size:13px"><?php esc_html_e( 'Sin imagen', 'quantia-hub' ); ?></span>
                                <?php endif; ?>
                            </div>

                            <h3 style="margin:0 0 4px;font-size:14px;font-weight:600">
                                <?php echo esc_html( $item['label'] ); ?>
                            </h3>
                            <?php if ( ! empty( $item['description'] ) ) : ?>
                                <p style="margin:0 0 10px;color:#50575e;font-size:12px;line-height:1.5">
                                    <?php echo esc_html( $item['description'] ); ?>
                                </p>
                            <?php endif; ?>

                            <input type="hidden" name="img_<?php echo esc_attr( $key ); ?>_id" value="<?php echo (int) $id; ?>" data-image-id>
                            <input type="text" name="img_<?php echo esc_attr( $key ); ?>_url" value="<?php echo esc_attr( $url ); ?>"
                                   placeholder="https://…" class="regular-text" style="width:100%;margin-bottom:8px" data-image-url>

                            <div style="display:flex;flex-wrap:wrap;gap:6px">
                                <button type="button" class="button" data-image-pick><?php esc_html_e( 'Biblioteca', 'quantia-hub' ); ?></button>
                                <button type="button" class="button" data-image-clear><?php esc_html_e( 'Quitar', 'quantia-hub' ); ?></button>
                                <button type="button" class="button-link" data-image-reset style="margin-left:auto"><?php esc_html_e( 'Predeterminado', 'quantia-hub' ); ?></button>
                            </div>
                            <p style="margin:8px 0 0;color:#8c8f94;font-size:11px" data-image-status></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <p class="submit" style="margin-top:24px">
                <button type="submit" class="button button-primary">
                    <?php esc_html_e( 'Guardar imágenes', 'quantia-hub' ); ?>
                </button>
            </p>
        </form>
    </div>
    <?php
}