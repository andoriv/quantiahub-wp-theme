<?php
/**
 * Profiles settings page.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_settings_page_profiles(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $type         = isset( $_GET['type'] ) && 'retirement' === $_GET['type'] ? 'retirement' : 'investment';
    $section_key  = 'investment' === $type ? 'investment_profiles' : 'retirement_profiles';
    $rows         = quantia_settings_rows( $section_key );
    $saved        = isset( $_GET['saved'] ) && '1' === $_GET['saved'];
    $base_url     = admin_url( 'admin.php?page=quantia-settings-profiles' );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Profiles', 'quantia-hub' ); ?></h1>

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Changes saved.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>

        <p>
            <a href="<?php echo esc_url( $base_url ); ?>" class="button <?php echo 'investment' === $type ? 'button-primary' : ''; ?>"><?php esc_html_e( 'Investment', 'quantia-hub' ); ?></a>
            <a href="<?php echo esc_url( add_query_arg( 'type', 'retirement', $base_url ) ); ?>" class="button <?php echo 'retirement' === $type ? 'button-primary' : ''; ?>"><?php esc_html_e( 'Retirement', 'quantia-hub' ); ?></a>
        </p>

        <p><?php esc_html_e( 'The allocation field accepts one fund per line in the format fund-id | percentage. Example: fixed-income | 25', 'quantia-hub' ); ?></p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'quantia_settings_profiles' ); ?>
            <input type="hidden" name="action" value="quantia_settings_save_profiles">
            <input type="hidden" name="profile_type" value="<?php echo esc_attr( $type ); ?>">

            <div id="quantia-profile-rows">
                <?php foreach ( $rows as $i => $row ) :
                    $allocation_lines = array();
                    foreach ( $row['allocation'] ?? array() as $slice ) {
                        $allocation_lines[] = ( $slice['fund'] ?? '' ) . ' | ' . ( $slice['pct'] ?? 0 );
                    }
                ?>
                    <div class="card" style="max-width:none;margin-bottom:16px;padding:16px">
                        <table class="form-table">
                            <tr>
                                <th style="width:160px"><?php esc_html_e( 'Identifier', 'quantia-hub' ); ?></th>
                                <td><input type="text" name="rows[<?php echo (int) $i; ?>][profile_key]" value="<?php echo esc_attr( $row['profile_key'] ?? '' ); ?>" class="regular-text" required></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Name', 'quantia-hub' ); ?></th>
                                <td><input type="text" name="rows[<?php echo (int) $i; ?>][name]" value="<?php echo esc_attr( $row['name'] ?? '' ); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Risk level', 'quantia-hub' ); ?></th>
                                <td><input type="number" name="rows[<?php echo (int) $i; ?>][level]" value="<?php echo (int) ( $row['level'] ?? 1 ); ?>" min="1" max="3" class="small-text"></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Estimated rate', 'quantia-hub' ); ?></th>
                                <td><input type="number" step="0.0001" min="0" max="1" name="rows[<?php echo (int) $i; ?>][rate]" value="<?php echo esc_attr( $row['rate'] ?? 0 ); ?>" class="small-text"></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Summary', 'quantia-hub' ); ?></th>
                                <td><textarea name="rows[<?php echo (int) $i; ?>][summary]" rows="3" class="large-text"><?php echo esc_textarea( $row['summary'] ?? '' ); ?></textarea></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e( 'Allocation', 'quantia-hub' ); ?></th>
                                <td><textarea name="rows[<?php echo (int) $i; ?>][allocation_text]" rows="4" class="large-text" placeholder="fund-id | percentage"><?php echo esc_textarea( implode( "\n", $allocation_lines ) ); ?></textarea></td>
                            </tr>
                        </table>
                        <p><button type="button" class="button quantia-remove-profile"><?php esc_html_e( 'Remove this profile', 'quantia-hub' ); ?></button></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <p><button type="button" class="button" id="quantia-add-profile"><?php esc_html_e( 'Add profile', 'quantia-hub' ); ?></button></p>
            <p class="submit"><button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'quantia-hub' ); ?></button></p>
        </form>
    </div>
    <script>
    document.getElementById('quantia-add-profile').addEventListener('click', function () {
        var wrap = document.getElementById('quantia-profile-rows');
        var i = wrap.children.length;
        var card = document.createElement('div');
        card.className = 'card';
        card.style.cssText = 'max-width:none;margin-bottom:16px;padding:16px';
        card.innerHTML = '<table class="form-table">'
            + '<tr><th style="width:160px">Identifier</th><td><input type="text" name="rows[' + i + '][profile_key]" class="regular-text" required></td></tr>'
            + '<tr><th>Name</th><td><input type="text" name="rows[' + i + '][name]" class="regular-text"></td></tr>'
            + '<tr><th>Risk level</th><td><input type="number" name="rows[' + i + '][level]" value="1" min="1" max="3" class="small-text"></td></tr>'
            + '<tr><th>Estimated rate</th><td><input type="number" step="0.0001" min="0" max="1" name="rows[' + i + '][rate]" value="0" class="small-text"></td></tr>'
            + '<tr><th>Summary</th><td><textarea name="rows[' + i + '][summary]" rows="3" class="large-text"></textarea></td></tr>'
            + '<tr><th>Allocation</th><td><textarea name="rows[' + i + '][allocation_text]" rows="4" class="large-text" placeholder="fund-id | percentage"></textarea></td></tr>'
            + '</table>'
            + '<p><button type="button" class="button quantia-remove-profile">Remove this profile</button></p>';
        wrap.appendChild(card);
    });
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('quantia-remove-profile')) {
            e.target.closest('.card').remove();
        }
    });
    </script>
    <?php
}