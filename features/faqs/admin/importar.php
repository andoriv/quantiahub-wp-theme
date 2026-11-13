<?php
/**
 * FAQ import and export.
 *
 * This module renders the import screen with the template download, the file
 * upload and the direct JSON paste. It also handles every import handler and
 * the export. Each successful import is recorded through the logger of the
 * history module. Access is gated through the manage_options capability.
 *
 * Importación y exportación de FAQ.
 *
 * Este módulo renderiza la pantalla de importación con la descarga de la
 * plantilla, la carga del archivo y el pegado directo del JSON. También maneja
 * cada controlador de importación y la exportación. Cada importación exitosa
 * se registra a través del registrador del módulo de historial. El acceso está
 * limitado por la capability manage_options.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_render_faqs_importar(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $download_empty_url = wp_nonce_url(
        add_query_arg( array( 'action' => 'quantia_faq_download_template', 'type' => 'empty' ), admin_url( 'admin-post.php' ) ),
        'quantia_faq_download_template'
    );
    $download_example_url = wp_nonce_url(
        add_query_arg( array( 'action' => 'quantia_faq_download_template', 'type' => 'example' ), admin_url( 'admin-post.php' ) ),
        'quantia_faq_download_template'
    );
    $export_url = wp_nonce_url(
        add_query_arg( 'action', 'quantia_faq_export', admin_url( 'admin-post.php' ) ),
        'quantia_faq_export'
    );

    $result        = isset( $_GET['quick_result'] ) ? sanitize_text_field( wp_unslash( $_GET['quick_result'] ) ) : '';
    $historial_url = admin_url( 'admin.php?page=quantia-faqs-historial' );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Import questions', 'quantia-hub' ); ?></h1>

        <?php if ( 'ok' === $result ) : ?>
            <div class="notice notice-success is-dismissible">
                <p>
                    <?php
                    printf(
                        /* translators: 1: inserted count, 2: updated count, 3: skipped count */
                        esc_html__( 'Import completed. %1$d inserted, %2$d updated, %3$d skipped.', 'quantia-hub' ),
                        (int) ( $_GET['inserted'] ?? 0 ),
                        (int) ( $_GET['updated'] ?? 0 ),
                        (int) ( $_GET['skipped'] ?? 0 )
                    );
                    ?>
                    <?php if ( ! empty( $_GET['no_category'] ) ) : ?>
                        <br>
                        <?php
                        printf(
                            /* translators: %d: count of skipped rows */
                            esc_html__( '%d skipped because they had no category.', 'quantia-hub' ),
                            (int) $_GET['no_category']
                        );
                        ?>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( $historial_url ); ?>"><?php esc_html_e( 'View history', 'quantia-hub' ); ?></a>
                </p>
            </div>
        <?php endif; ?>

        <?php if ( isset( $_GET['imported'] ) ) : ?>
            <div class="notice notice-success is-dismissible">
                <p>
                    <?php
                    printf(
                        /* translators: 1: inserted count, 2: updated count, 3: skipped count */
                        esc_html__( 'File imported. %1$d inserted, %2$d updated, %3$d skipped.', 'quantia-hub' ),
                        (int) $_GET['imported'],
                        (int) ( $_GET['updated'] ?? 0 ),
                        (int) ( $_GET['skipped'] ?? 0 )
                    );
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width:900px;margin-top:20px">
            <h2><?php esc_html_e( '1. Download template', 'quantia-hub' ); ?></h2>
            <p>
                <a href="<?php echo esc_url( $download_empty_url ); ?>" class="button button-primary">
                    <?php esc_html_e( 'Empty template', 'quantia-hub' ); ?>
                </a>
                <a href="<?php echo esc_url( $download_example_url ); ?>" class="button">
                    <?php esc_html_e( 'Template with examples', 'quantia-hub' ); ?>
                </a>
            </p>
        </div>

        <div class="card" style="max-width:900px;margin-top:20px">
            <h2><?php esc_html_e( '2. Upload JSON file', 'quantia-hub' ); ?></h2>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
                <?php wp_nonce_field( 'quantia_faq_import' ); ?>
                <input type="hidden" name="action" value="quantia_faq_import">
                <p><input type="file" name="faq_file" accept=".json,application/json" required></p>
                <p>
                    <button type="submit" class="button button-primary">
                        <?php esc_html_e( 'Import file', 'quantia-hub' ); ?>
                    </button>
                </p>
            </form>
        </div>

        <div class="card" style="max-width:900px;margin-top:20px">
            <h2><?php esc_html_e( '3. Paste JSON directly', 'quantia-hub' ); ?></h2>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'quantia_faq_quick_import' ); ?>
                <input type="hidden" name="action" value="quantia_faq_quick_import">
                <textarea name="quick_json" rows="14" class="large-text code"
                    style="font-family:Consolas,Monaco,monospace;font-size:13px"
                    placeholder="<?php esc_attr_e( 'Paste the JSON with the questions here', 'quantia-hub' ); ?>" required></textarea>
                <p class="submit" style="margin-top:16px">
                    <button type="submit" class="button button-primary">
                        <?php esc_html_e( 'Import JSON', 'quantia-hub' ); ?>
                    </button>
                </p>
            </form>
        </div>

        <div class="card" style="max-width:900px;margin-top:20px">
            <h2><?php esc_html_e( '4. Export', 'quantia-hub' ); ?></h2>
            <p>
                <a href="<?php echo esc_url( $export_url ); ?>" class="button">
                    <?php esc_html_e( 'Download all current questions', 'quantia-hub' ); ?>
                </a>
            </p>
        </div>
    </div>
    <?php
}

add_action( 'admin_post_quantia_faq_download_template', 'quantia_handle_faq_download_template' );
function quantia_handle_faq_download_template(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    check_admin_referer( 'quantia_faq_download_template' );

    $type = isset( $_GET['type'] ) ? sanitize_key( $_GET['type'] ) : 'empty';

    if ( 'example' === $type ) {
        $data = array(
            array(
                'question' => __( 'How do I open a Quantia Hub account', 'quantia-hub' ),
                'answer'   => __( 'Download the app, follow the onboarding steps and complete the registration.', 'quantia-hub' ),
                'category' => __( 'Checking account', 'quantia-hub' ),
                'product'  => '',
                'visible'  => 1,
                'order'    => 0,
            ),
            array(
                'question' => __( 'What is the minimum amount to invest', 'quantia-hub' ),
                'answer'   => __( 'The minimum amount is 100 dollars.', 'quantia-hub' ),
                'category' => __( 'Investments', 'quantia-hub' ),
                'product'  => 'inversiones',
                'visible'  => 1,
                'order'    => 10,
            ),
        );
    } else {
        $data = array(
            array(
                'question' => '',
                'answer'   => '',
                'category' => '',
                'product'  => '',
                'visible'  => 1,
                'order'    => 0,
            ),
        );
    }

    nocache_headers();
    header( 'Content-Type: application/json; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename="faq-template-' . $type . '.json"' );
    echo wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    exit;
}

add_action( 'admin_post_quantia_faq_quick_import', 'quantia_handle_faq_quick_import' );
function quantia_handle_faq_quick_import(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    check_admin_referer( 'quantia_faq_quick_import' );

    $raw  = isset( $_POST['quick_json'] ) ? wp_unslash( $_POST['quick_json'] ) : '';
    $data = json_decode( $raw, true );
    $back_url = admin_url( 'admin.php?page=quantia-faqs-importar' );

    if ( ! is_array( $data ) ) {
        wp_safe_redirect( add_query_arg( 'quick_result', 'error', $back_url ) );
        exit;
    }
    if ( empty( $data ) ) {
        wp_safe_redirect( add_query_arg( 'quick_result', 'empty', $back_url ) );
        exit;
    }

    $stats = quantia_process_faq_import( $data );
    quantia_log_faq_import( array(
        'source'      => 'paste',
        'total_rows'  => count( $data ),
        'inserted'    => $stats['inserted'],
        'updated'     => $stats['updated'],
        'skipped'     => $stats['skipped'],
        'no_category' => $stats['no_category'],
    ) );

    wp_safe_redirect( add_query_arg(
        array(
            'quick_result' => 'ok',
            'inserted'     => $stats['inserted'],
            'updated'      => $stats['updated'],
            'skipped'      => $stats['skipped'],
            'no_category'  => $stats['no_category'],
        ),
        $back_url
    ) );
    exit;
}

add_action( 'admin_post_quantia_faq_export', 'quantia_handle_faq_export' );
function quantia_handle_faq_export(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    check_admin_referer( 'quantia_faq_export' );

    $faqs = get_posts( array(
        'post_type'      => 'faq',
        'post_status'    => array( 'publish', 'draft' ),
        'posts_per_page' => -1,
        'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
    ) );

    $rows = array();
    foreach ( $faqs as $f ) {
        $terms = get_the_terms( $f->ID, 'categoria_faq' );
        $cat   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
        $rows[] = array(
            'question' => $f->post_title,
            'answer'   => $f->post_content,
            'category' => $cat,
            'product'  => (string) get_post_meta( $f->ID, 'faq_product', true ),
            'visible'  => '0' === get_post_meta( $f->ID, 'faq_visible', true ) ? 0 : 1,
            'order'    => (int) $f->menu_order,
        );
    }

    nocache_headers();
    header( 'Content-Type: application/json; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename="faqs-' . gmdate( 'Ymd-His' ) . '.json"' );
    echo wp_json_encode( $rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
    exit;
}

add_action( 'admin_post_quantia_faq_import', 'quantia_handle_faq_import' );
function quantia_handle_faq_import(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    check_admin_referer( 'quantia_faq_import' );

    if ( empty( $_FILES['faq_file']['tmp_name'] ) || ! is_uploaded_file( $_FILES['faq_file']['tmp_name'] ) ) {
        wp_safe_redirect( admin_url( 'admin.php?page=quantia-faqs-importar&import_error=archivo' ) );
        exit;
    }

    $filename = ! empty( $_FILES['faq_file']['name'] ) ? sanitize_file_name( wp_unslash( $_FILES['faq_file']['name'] ) ) : 'file.json';
    $filesize = (int) ( $_FILES['faq_file']['size'] ?? 0 );

    $raw  = file_get_contents( $_FILES['faq_file']['tmp_name'] );
    $data = json_decode( $raw, true );

    if ( ! is_array( $data ) ) {
        wp_safe_redirect( admin_url( 'admin.php?page=quantia-faqs-importar&import_error=json' ) );
        exit;
    }

    $stats = quantia_process_faq_import( $data );
    quantia_log_faq_import( array(
        'source'      => 'file',
        'filename'    => $filename,
        'file_size'   => $filesize,
        'total_rows'  => count( $data ),
        'inserted'    => $stats['inserted'],
        'updated'     => $stats['updated'],
        'skipped'     => $stats['skipped'],
        'no_category' => $stats['no_category'],
    ) );

    wp_safe_redirect( add_query_arg(
        array(
            'page'        => 'quantia-faqs-importar',
            'imported'    => $stats['inserted'],
            'updated'     => $stats['updated'],
            'skipped'     => $stats['skipped'],
            'no_category' => $stats['no_category'],
        ),
        admin_url( 'admin.php' )
    ) );
    exit;
}

function quantia_process_faq_import( array $data ): array {
    $inserted    = 0;
    $updated     = 0;
    $skipped     = 0;
    $no_category = 0;

    foreach ( $data as $row ) {
        if ( empty( $row['question'] ) || empty( $row['answer'] ) ) {
            $skipped++;
            continue;
        }
        if ( empty( $row['category'] ) ) {
            $no_category++;
            $skipped++;
            continue;
        }

        $existing = get_posts( array(
            'post_type'      => 'faq',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'title'          => $row['question'],
        ) );

        $post_data = array(
            'post_type'    => 'faq',
            'post_status'  => 'publish',
            'post_title'   => sanitize_text_field( $row['question'] ),
            'post_content' => sanitize_textarea_field( $row['answer'] ),
            'menu_order'   => isset( $row['order'] ) ? (int) $row['order'] : 0,
        );

        if ( ! empty( $existing ) ) {
            $post_data['ID'] = (int) $existing[0];
            $post_id    = wp_update_post( $post_data, true );
            $did_update = true;
        } else {
            $post_id    = wp_insert_post( $post_data, true );
            $did_update = false;
        }

        if ( is_wp_error( $post_id ) || ! $post_id ) {
            $skipped++;
            continue;
        }

        update_post_meta( $post_id, 'faq_product', isset( $row['product'] ) ? sanitize_key( $row['product'] ) : '' );
        update_post_meta( $post_id, 'faq_visible', isset( $row['visible'] ) ? (string) (int) $row['visible'] : '1' );

        $cat  = sanitize_text_field( $row['category'] );
        $term = get_term_by( 'name', $cat, 'categoria_faq' );
        if ( $term ) {
            wp_set_object_terms( $post_id, array( (int) $term->term_id ), 'categoria_faq' );
        } else {
            $new = wp_insert_term( $cat, 'categoria_faq' );
            if ( ! is_wp_error( $new ) && isset( $new['term_id'] ) ) {
                wp_set_object_terms( $post_id, array( (int) $new['term_id'] ), 'categoria_faq' );
                update_term_meta( (int) $new['term_id'], QUANTIA_FAQ_CAT_VISIBLE_META, '1' );
            }
        }

        $did_update ? $updated++ : $inserted++;
    }

    return array(
        'inserted'    => $inserted,
        'updated'     => $updated,
        'skipped'     => $skipped,
        'no_category' => $no_category,
    );
}