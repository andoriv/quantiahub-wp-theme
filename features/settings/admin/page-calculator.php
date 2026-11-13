<?php
/**
 * Calculator settings page.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_settings_page_calculator(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $section = quantia_settings( 'calculator' );
    $options = $section['options'] ?? array();
    $saved   = isset( $_GET['saved'] ) && '1' === $_GET['saved'];
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Calculator', 'quantia-hub' ); ?></h1>

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Changes saved.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>

        <p><?php esc_html_e( 'Configure the four investment options of the compound interest calculator and their default form values. Rates are decimal, for example 0.04 for four percent.', 'quantia-hub' ); ?></p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'quantia_settings_calculator' ); ?>
            <input type="hidden" name="action" value="quantia_settings_save_calculator">

            <h2><?php esc_html_e( 'Options', 'quantia-hub' ); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width:120px"><?php esc_html_e( 'Identifier', 'quantia-hub' ); ?></th>
                        <th><?php esc_html_e( 'Label', 'quantia-hub' ); ?></th>
                        <th style="width:140px"><?php esc_html_e( 'Kind', 'quantia-hub' ); ?></th>
                        <th style="width:100px"><?php esc_html_e( 'Rate', 'quantia-hub' ); ?></th>
                        <th style="width:60px"></th>
                    </tr>
                </thead>
                <tbody id="quantia-calc-rows">
                    <?php foreach ( $options as $i => $opt ) : ?>
                        <tr>
                            <td><input type="text" name="options[<?php echo (int) $i; ?>][key]" value="<?php echo esc_attr( $opt['key'] ?? '' ); ?>" style="width:100%"></td>
                            <td><input type="text" name="options[<?php echo (int) $i; ?>][name]" value="<?php echo esc_attr( $opt['name'] ?? '' ); ?>" style="width:100%"></td>
                            <td><input type="text" name="options[<?php echo (int) $i; ?>][kind]" value="<?php echo esc_attr( $opt['kind'] ?? '' ); ?>" style="width:100%"></td>
                            <td><input type="number" step="0.0001" min="0" max="1" name="options[<?php echo (int) $i; ?>][rate]" value="<?php echo esc_attr( $opt['rate'] ?? 0 ); ?>" style="width:100%"></td>
                            <td><button type="button" class="button quantia-remove-row">×</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p><button type="button" class="button" id="quantia-add-calc-row"><?php esc_html_e( 'Add option', 'quantia-hub' ); ?></button></p>

            <h2><?php esc_html_e( 'Defaults', 'quantia-hub' ); ?></h2>
            <table class="form-table">
                <tr>
                    <th><label for="default_rate"><?php esc_html_e( 'Default selected option', 'quantia-hub' ); ?></label></th>
                    <td><input type="text" id="default_rate" name="default_rate" value="<?php echo esc_attr( $section['default_rate'] ?? 'navegante' ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="default_initial"><?php esc_html_e( 'Default initial amount', 'quantia-hub' ); ?></label></th>
                    <td><input type="number" id="default_initial" name="default_initial" value="<?php echo (int) ( $section['default_initial'] ?? 500 ); ?>" class="small-text"></td>
                </tr>
                <tr>
                    <th><label for="default_monthly"><?php esc_html_e( 'Default monthly contribution', 'quantia-hub' ); ?></label></th>
                    <td><input type="number" id="default_monthly" name="default_monthly" value="<?php echo (int) ( $section['default_monthly'] ?? 100 ); ?>" class="small-text"></td>
                </tr>
                <tr>
                    <th><label for="default_years"><?php esc_html_e( 'Default years', 'quantia-hub' ); ?></label></th>
                    <td><input type="number" id="default_years" name="default_years" value="<?php echo (int) ( $section['default_years'] ?? 15 ); ?>" min="1" max="40" class="small-text"></td>
                </tr>
            </table>

            <p class="submit"><button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'quantia-hub' ); ?></button></p>
        </form>
    </div>
    <script>
    document.getElementById('quantia-add-calc-row').addEventListener('click', function () {
        var tbody = document.getElementById('quantia-calc-rows');
        var i = tbody.children.length;
        var row = document.createElement('tr');
        row.innerHTML = '<td><input type="text" name="options[' + i + '][key]" style="width:100%"></td>'
            + '<td><input type="text" name="options[' + i + '][name]" style="width:100%"></td>'
            + '<td><input type="text" name="options[' + i + '][kind]" style="width:100%"></td>'
            + '<td><input type="number" step="0.0001" min="0" max="1" name="options[' + i + '][rate]" value="0" style="width:100%"></td>'
            + '<td><button type="button" class="button quantia-remove-row">×</button></td>';
        tbody.appendChild(row);
    });
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('quantia-remove-row')) {
            e.target.closest('tr').remove();
        }
    });
    </script>
    <?php
}