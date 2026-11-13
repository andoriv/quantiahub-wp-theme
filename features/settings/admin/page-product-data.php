<?php
/**
 * Product data settings page.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_settings_page_product_data(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $data  = quantia_settings( 'product_data' );
    $saved = isset( $_GET['saved'] ) && '1' === $_GET['saved'];
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Product data', 'quantia-hub' ); ?></h1>

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Changes saved.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'quantia_settings_product_data' ); ?>
            <input type="hidden" name="action" value="quantia_settings_save_product_data">

            <table class="form-table">
                <tr>
                    <th><label for="account_rate"><?php esc_html_e( 'Account annual rate', 'quantia-hub' ); ?></label></th>
                    <td><input type="number" step="0.0001" min="0" max="1" id="account_rate" name="account_rate" value="<?php echo esc_attr( $data['account_rate'] ?? 0.04 ); ?>" class="small-text"> <span class="description">Decimal, 0.04 = 4%</span></td>
                </tr>
                <tr>
                    <th><label for="account_max_balance"><?php esc_html_e( 'Maximum interest balance', 'quantia-hub' ); ?></label></th>
                    <td><input type="number" id="account_max_balance" name="account_max_balance" value="<?php echo (int) ( $data['account_max_balance'] ?? 50000 ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="investment_minimum"><?php esc_html_e( 'Minimum investment', 'quantia-hub' ); ?></label></th>
                    <td><input type="number" id="investment_minimum" name="investment_minimum" value="<?php echo (int) ( $data['investment_minimum'] ?? 100 ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="investment_sale_fee"><?php esc_html_e( 'Investment sale fee', 'quantia-hub' ); ?></label></th>
                    <td><input type="text" id="investment_sale_fee" name="investment_sale_fee" value="<?php echo esc_attr( $data['investment_sale_fee'] ?? '1% + ITBMS' ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="card_fraud_coverage"><?php esc_html_e( 'Fraud coverage', 'quantia-hub' ); ?></label></th>
                    <td><input type="number" id="card_fraud_coverage" name="card_fraud_coverage" value="<?php echo (int) ( $data['card_fraud_coverage'] ?? 3000 ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="card_emission_fee"><?php esc_html_e( 'Card emission fee', 'quantia-hub' ); ?></label></th>
                    <td><input type="text" id="card_emission_fee" name="card_emission_fee" value="<?php echo esc_attr( $data['card_emission_fee'] ?? 'B/. 21.40' ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="card_annual_fee"><?php esc_html_e( 'Card annual fee', 'quantia-hub' ); ?></label></th>
                    <td><input type="text" id="card_annual_fee" name="card_annual_fee" value="<?php echo esc_attr( $data['card_annual_fee'] ?? 'B/. 21.40' ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th><label for="card_replacement_fee"><?php esc_html_e( 'Card replacement fee', 'quantia-hub' ); ?></label></th>
                    <td><input type="text" id="card_replacement_fee" name="card_replacement_fee" value="<?php echo esc_attr( $data['card_replacement_fee'] ?? 'B/. 26.75' ); ?>" class="regular-text"></td>
                </tr>
            </table>

            <p class="submit"><button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'quantia-hub' ); ?></button></p>
        </form>
    </div>
    <?php
}