<?php
/**
 * Funds settings page.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_settings_page_funds(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $rows  = quantia_settings_rows( 'funds' );
    $saved = isset( $_GET['saved'] ) && '1' === $_GET['saved'];
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Funds', 'quantia-hub' ); ?></h1>

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Changes saved.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>

        <p><?php esc_html_e( 'Identifiers are referenced by the profiles. Rename with care.', 'quantia-hub' ); ?></p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'quantia_settings_funds' ); ?>
            <input type="hidden" name="action" value="quantia_settings_save_funds">

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width:180px"><?php esc_html_e( 'Identifier', 'quantia-hub' ); ?></th>
                        <th><?php esc_html_e( 'Display name', 'quantia-hub' ); ?></th>
                        <th style="width:160px"><?php esc_html_e( 'Asset class', 'quantia-hub' ); ?></th>
                        <th style="width:140px"><?php esc_html_e( 'Swatch', 'quantia-hub' ); ?></th>
                        <th style="width:140px"><?php esc_html_e( 'Plan', 'quantia-hub' ); ?></th>
                        <th style="width:60px"></th>
                    </tr>
                </thead>
                <tbody id="quantia-fund-rows">
                    <?php foreach ( $rows as $i => $row ) : ?>
                        <tr>
                            <td><input type="text" name="rows[<?php echo (int) $i; ?>][fund_id]" value="<?php echo esc_attr( $row['fund_id'] ?? '' ); ?>" style="width:100%"></td>
                            <td><input type="text" name="rows[<?php echo (int) $i; ?>][fund_name]" value="<?php echo esc_attr( $row['fund_name'] ?? '' ); ?>" style="width:100%"></td>
                            <td>
                                <select name="rows[<?php echo (int) $i; ?>][asset_class]" style="width:100%">
                                    <?php foreach ( array( 'Renta variable', 'Renta fija', 'Individual' ) as $a ) : ?>
                                        <option value="<?php echo esc_attr( $a ); ?>" <?php selected( $row['asset_class'] ?? '', $a ); ?>><?php echo esc_html( $a ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name="rows[<?php echo (int) $i; ?>][swatch]" style="width:100%">
                                    <?php foreach ( array( 'bg-ink' => 'Ink', 'bg-qh' => 'Green', 'bg-qh-300' => 'Light green', 'bg-qh-700' => 'Dark green' ) as $k => $v ) : ?>
                                        <option value="<?php echo esc_attr( $k ); ?>" <?php selected( $row['swatch'] ?? '', $k ); ?>><?php echo esc_html( $v ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name="rows[<?php echo (int) $i; ?>][plan]" style="width:100%">
                                    <option value="inversion" <?php selected( $row['plan'] ?? '', 'inversion' ); ?>>Investment</option>
                                    <option value="retiro" <?php selected( $row['plan'] ?? '', 'retiro' ); ?>>Retirement</option>
                                </select>
                            </td>
                            <td><button type="button" class="button quantia-remove-row">×</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p><button type="button" class="button" id="quantia-add-fund"><?php esc_html_e( 'Add fund', 'quantia-hub' ); ?></button></p>
            <p class="submit"><button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'quantia-hub' ); ?></button></p>
        </form>
    </div>
    <script>
    document.getElementById('quantia-add-fund').addEventListener('click', function () {
        var tbody = document.getElementById('quantia-fund-rows');
        var i = tbody.children.length;
        var tr = document.createElement('tr');
        tr.innerHTML = '<td><input type="text" name="rows[' + i + '][fund_id]" style="width:100%"></td>'
            + '<td><input type="text" name="rows[' + i + '][fund_name]" style="width:100%"></td>'
            + '<td><select name="rows[' + i + '][asset_class]" style="width:100%"><option>Renta variable</option><option>Renta fija</option><option>Individual</option></select></td>'
            + '<td><select name="rows[' + i + '][swatch]" style="width:100%"><option value="bg-ink">Ink</option><option value="bg-qh">Green</option><option value="bg-qh-300">Light green</option><option value="bg-qh-700">Dark green</option></select></td>'
            + '<td><select name="rows[' + i + '][plan]" style="width:100%"><option value="inversion">Investment</option><option value="retiro">Retirement</option></select></td>'
            + '<td><button type="button" class="button quantia-remove-row">×</button></td>';
        tbody.appendChild(tr);
    });
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('quantia-remove-row')) {
            e.target.closest('tr').remove();
        }
    });
    </script>
    <?php
}