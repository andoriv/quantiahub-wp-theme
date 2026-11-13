<?php
/**
 * Home benefits settings page.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_settings_page_benefits(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $rows  = quantia_settings_rows( 'benefits' );
    $saved = isset( $_GET['saved'] ) && '1' === $_GET['saved'];
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Home benefits', 'quantia-hub' ); ?></h1>

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Changes saved.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>

        <p><?php esc_html_e( 'Each row is one product card of the home page. The points field accepts one point per line.', 'quantia-hub' ); ?></p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'quantia_settings_benefits' ); ?>
            <input type="hidden" name="action" value="quantia_settings_save_benefits">

            <div id="quantia-benefit-rows">
                <?php foreach ( $rows as $i => $row ) :
                    $points_text = is_array( $row['points'] ?? null ) ? implode( "\n", $row['points'] ) : '';
                ?>
                    <div class="card" style="max-width:none;margin-bottom:16px;padding:16px">
                        <table class="form-table">
                            <tr>
                                <th style="width:160px"><?php esc_html_e( 'Identifier', 'quantia-hub' ); ?></th>
                                <td><input type="text" name="rows[<?php echo (int) $i; ?>][benefit_id]" value="<?php echo esc_attr( $row['benefit_id'] ?? '' ); ?>" class="regular-text" required></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Product label', 'quantia-hub' ); ?></th>
                                <td><input type="text" name="rows[<?php echo (int) $i; ?>][product]" value="<?php echo esc_attr( $row['product'] ?? '' ); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Headline', 'quantia-hub' ); ?></th>
                                <td><input type="text" name="rows[<?php echo (int) $i; ?>][title]" value="<?php echo esc_attr( $row['title'] ?? '' ); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Statistic', 'quantia-hub' ); ?></th>
                                <td>
                                    <input type="text" name="rows[<?php echo (int) $i; ?>][stat_prefix]" value="<?php echo esc_attr( $row['stat_prefix'] ?? '' ); ?>" placeholder="Prefix" style="width:80px">
                                    <input type="text" name="rows[<?php echo (int) $i; ?>][stat]" value="<?php echo esc_attr( $row['stat'] ?? '' ); ?>" placeholder="Value" style="width:120px">
                                    <input type="text" name="rows[<?php echo (int) $i; ?>][stat_suffix]" value="<?php echo esc_attr( $row['stat_suffix'] ?? '' ); ?>" placeholder="Suffix" style="width:200px">
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Description', 'quantia-hub' ); ?></th>
                                <td><textarea name="rows[<?php echo (int) $i; ?>][description]" rows="3" class="large-text"><?php echo esc_textarea( $row['description'] ?? '' ); ?></textarea></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Points', 'quantia-hub' ); ?></th>
                                <td><textarea name="rows[<?php echo (int) $i; ?>][points_text]" rows="4" class="large-text" placeholder="One point per line"><?php echo esc_textarea( $points_text ); ?></textarea></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Phone screen', 'quantia-hub' ); ?></th>
                                <td>
                                    <select name="rows[<?php echo (int) $i; ?>][screen]">
                                        <?php foreach ( array( 'inicio', 'inversiones', 'alcancias', 'retiro', 'tarjeta', 'kuara' ) as $s ) : ?>
                                            <option value="<?php echo esc_attr( $s ); ?>" <?php selected( $row['screen'] ?? 'inicio', $s ); ?>><?php echo esc_html( $s ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" name="rows[<?php echo (int) $i; ?>][screen_label]" value="<?php echo esc_attr( $row['screen_label'] ?? '' ); ?>" class="regular-text" placeholder="Accessibility label">
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Link', 'quantia-hub' ); ?></th>
                                <td>
                                    <input type="text" name="rows[<?php echo (int) $i; ?>][link_label]" value="<?php echo esc_attr( $row['link_label'] ?? '' ); ?>" placeholder="Label">
                                    <input type="url" name="rows[<?php echo (int) $i; ?>][link_url]" value="<?php echo esc_attr( $row['link_url'] ?? '' ); ?>" placeholder="https://" class="regular-text">
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Funds plan', 'quantia-hub' ); ?></th>
                                <td>
                                    <select name="rows[<?php echo (int) $i; ?>][funds_plan]">
                                        <option value="" <?php selected( $row['funds_plan'] ?? '', '' ); ?>>None</option>
                                        <option value="inversion" <?php selected( $row['funds_plan'] ?? '', 'inversion' ); ?>>Investment</option>
                                        <option value="retiro" <?php selected( $row['funds_plan'] ?? '', 'retiro' ); ?>>Retirement</option>
                                    </select>
                                    <input type="url" name="rows[<?php echo (int) $i; ?>][funds_url]" value="<?php echo esc_attr( $row['funds_url'] ?? '' ); ?>" placeholder="Funds URL" class="regular-text">
                                </td>
                            </tr>
                        </table>
                        <p><button type="button" class="button quantia-remove-benefit"><?php esc_html_e( 'Remove this benefit', 'quantia-hub' ); ?></button></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <p><button type="button" class="button" id="quantia-add-benefit"><?php esc_html_e( 'Add benefit', 'quantia-hub' ); ?></button></p>
            <p class="submit"><button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'quantia-hub' ); ?></button></p>
        </form>
    </div>
    <script>
    document.getElementById('quantia-add-benefit').addEventListener('click', function () {
        var wrap = document.getElementById('quantia-benefit-rows');
        var i = wrap.children.length;
        var card = document.createElement('div');
        card.className = 'card';
        card.style.cssText = 'max-width:none;margin-bottom:16px;padding:16px';
        card.innerHTML = '<table class="form-table">'
            + '<tr><th style="width:160px">Identifier</th><td><input type="text" name="rows[' + i + '][benefit_id]" class="regular-text" required></td></tr>'
            + '<tr><th>Product label</th><td><input type="text" name="rows[' + i + '][product]" class="regular-text"></td></tr>'
            + '<tr><th>Headline</th><td><input type="text" name="rows[' + i + '][title]" class="regular-text"></td></tr>'
            + '<tr><th>Statistic</th><td><input type="text" name="rows[' + i + '][stat_prefix]" placeholder="Prefix" style="width:80px"><input type="text" name="rows[' + i + '][stat]" placeholder="Value" style="width:120px"><input type="text" name="rows[' + i + '][stat_suffix]" placeholder="Suffix" style="width:200px"></td></tr>'
            + '<tr><th>Description</th><td><textarea name="rows[' + i + '][description]" rows="3" class="large-text"></textarea></td></tr>'
            + '<tr><th>Points</th><td><textarea name="rows[' + i + '][points_text]" rows="4" class="large-text" placeholder="One point per line"></textarea></td></tr>'
            + '<tr><th>Phone screen</th><td><select name="rows[' + i + '][screen]"><option value="inicio">inicio</option><option value="inversiones">inversiones</option><option value="alcancias">alcancias</option><option value="retiro">retiro</option><option value="tarjeta">tarjeta</option><option value="kuara">kuara</option></select> <input type="text" name="rows[' + i + '][screen_label]" class="regular-text" placeholder="Accessibility label"></td></tr>'
            + '<tr><th>Link</th><td><input type="text" name="rows[' + i + '][link_label]" placeholder="Label"> <input type="url" name="rows[' + i + '][link_url]" placeholder="https://" class="regular-text"></td></tr>'
            + '<tr><th>Funds plan</th><td><select name="rows[' + i + '][funds_plan]"><option value="">None</option><option value="inversion">Investment</option><option value="retiro">Retirement</option></select> <input type="url" name="rows[' + i + '][funds_url]" placeholder="Funds URL" class="regular-text"></td></tr>'
            + '</table>'
            + '<p><button type="button" class="button quantia-remove-benefit">Remove this benefit</button></p>';
        wrap.appendChild(card);
    });
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('quantia-remove-benefit')) {
            e.target.closest('.card').remove();
        }
    });
    </script>
    <?php
}