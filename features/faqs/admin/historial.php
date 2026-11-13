<?php
/**
 * FAQ import history.
 *
 * This module records every FAQ import operation in a WordPress option and
 * exposes the administration screen that lists the history. It is loaded
 * before the importer because the importer calls the logger on every processed
 * file or pasted JSON. Access is gated through the manage_options capability.
 *
 * Historial de importaciones de FAQ.
 *
 * Este módulo registra cada operación de importación de FAQ en una opción de
 * WordPress y expone la pantalla de administración que lista el historial. Se
 * carga antes que el importador porque el importador llama al registrador en
 * cada archivo procesado o JSON pegado. El acceso está limitado por la
 * capability manage_options.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

const QUANTIA_FAQ_LOG_OPTION = 'quantia_faq_import_log';
const QUANTIA_FAQ_LOG_LIMIT  = 200;

function quantia_log_faq_import( array $entry ): void {
    $log = get_option( QUANTIA_FAQ_LOG_OPTION, array() );
    if ( ! is_array( $log ) ) {
        $log = array();
    }
    $user = wp_get_current_user();
    $entry = wp_parse_args( $entry, array(
        'id'          => 'imp_' . time() . '_' . substr( md5( uniqid( '', true ) ), 0, 8 ),
        'timestamp'   => time(),
        'date'        => current_time( 'mysql' ),
        'user_id'     => get_current_user_id(),
        'user_name'   => $user && $user->exists() ? $user->display_name : '—',
        'source'      => 'manual',
        'filename'    => '',
        'file_size'   => 0,
        'total_rows'  => 0,
        'inserted'    => 0,
        'updated'     => 0,
        'skipped'     => 0,
        'no_category' => 0,
    ) );
    array_unshift( $log, $entry );
    update_option( QUANTIA_FAQ_LOG_OPTION, array_slice( $log, 0, QUANTIA_FAQ_LOG_LIMIT ), false );
}

function quantia_get_faq_import_log(): array {
    $log = get_option( QUANTIA_FAQ_LOG_OPTION, array() );
    return is_array( $log ) ? $log : array();
}

function quantia_render_faqs_historial(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $log      = quantia_get_faq_import_log();
    $importar = admin_url( 'admin.php?page=quantia-faqs-importar' );

    $clear_url = wp_nonce_url(
        add_query_arg( 'action', 'quantia_faq_clear_log', admin_url( 'admin-post.php' ) ),
        'quantia_faq_clear_log'
    );
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e( 'Import history', 'quantia-hub' ); ?></h1>
        <hr class="wp-header-end">

        <p style="margin:16px 0;color:#50575e">
            <?php
            printf(
                /* translators: %d: limit of stored entries */
                esc_html__( 'Last %d import operations.', 'quantia-hub' ),
                (int) QUANTIA_FAQ_LOG_LIMIT
            );
            ?>
        </p>

        <?php if ( empty( $log ) ) : ?>
            <div class="card" style="max-width:640px">
                <h2><?php esc_html_e( 'No imports yet', 'quantia-hub' ); ?></h2>
                <p>
                    <a href="<?php echo esc_url( $importar ); ?>" class="button button-primary">
                        <?php esc_html_e( 'Go to import', 'quantia-hub' ); ?>
                    </a>
                </p>
            </div>
        <?php else : ?>
            <p>
                <a href="<?php echo esc_url( $clear_url ); ?>" class="button" style="color:#b32d2e;border-color:#b32d2e"
                   onclick="return confirm('<?php echo esc_js( __( 'Clear the entire history?', 'quantia-hub' ) ); ?>')">
                    <?php esc_html_e( 'Clear history', 'quantia-hub' ); ?>
                </a>
            </p>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width:160px"><?php esc_html_e( 'Date', 'quantia-hub' ); ?></th>
                        <th style="width:140px"><?php esc_html_e( 'User', 'quantia-hub' ); ?></th>
                        <th style="width:110px"><?php esc_html_e( 'Source', 'quantia-hub' ); ?></th>
                        <th><?php esc_html_e( 'File or origin', 'quantia-hub' ); ?></th>
                        <th style="width:80px"><?php esc_html_e( 'Total', 'quantia-hub' ); ?></th>
                        <th style="width:90px"><?php esc_html_e( 'Inserted', 'quantia-hub' ); ?></th>
                        <th style="width:100px"><?php esc_html_e( 'Updated', 'quantia-hub' ); ?></th>
                        <th style="width:90px"><?php esc_html_e( 'Skipped', 'quantia-hub' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $log as $entry ) :
                        $is_file = 'file' === ( $entry['source'] ?? '' );
                        $source_labels = array(
                            'file'   => __( 'JSON file', 'quantia-hub' ),
                            'paste'  => __( 'Pasted', 'quantia-hub' ),
                            'manual' => __( 'Manual', 'quantia-hub' ),
                        );
                        $source_label = $source_labels[ $entry['source'] ?? 'manual' ] ?? ucfirst( $entry['source'] ?? '' );
                    ?>
                        <tr>
                            <td><strong><?php echo esc_html( $entry['date'] ); ?></strong></td>
                            <td><?php echo esc_html( $entry['user_name'] ); ?></td>
                            <td>
                                <span style="display:inline-block;padding:2px 8px;border-radius:10px;font-weight:600;font-size:12px;<?php echo $is_file ? 'background:#e6f4ea;color:#046b44' : 'background:#f0f0f1;color:#50575e'; ?>">
                                    <?php echo esc_html( $source_label ); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ( $is_file && ! empty( $entry['filename'] ) ) : ?>
                                    <code style="background:#f6f7f7;padding:2px 6px;border-radius:3px"><?php echo esc_html( $entry['filename'] ); ?></code>
                                <?php else : ?>
                                    <span style="color:#666"><?php esc_html_e( 'Pasted in the form', 'quantia-hub' ); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo (int) $entry['total_rows']; ?></td>
                            <td><strong style="color:#00a32a">+<?php echo (int) $entry['inserted']; ?></strong></td>
                            <td><strong style="color:#2271b1"><?php echo (int) $entry['updated']; ?></strong></td>
                            <td>
                                <strong style="color:#d63638"><?php echo (int) $entry['skipped']; ?></strong>
                                <?php if ( ! empty( $entry['no_category'] ) ) : ?>
                                    <div style="color:#996800;font-size:11px">
                                        <?php
                                        printf(
                                            /* translators: %d: count of skipped rows without category */
                                            esc_html__( '%d without category', 'quantia-hub' ),
                                            (int) $entry['no_category']
                                        );
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}

add_action( 'admin_post_quantia_faq_clear_log', 'quantia_handle_faq_clear_log' );
function quantia_handle_faq_clear_log(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    check_admin_referer( 'quantia_faq_clear_log' );
    update_option( QUANTIA_FAQ_LOG_OPTION, array(), false );
    wp_safe_redirect( admin_url( 'admin.php?page=quantia-faqs-historial' ) );
    exit;
}