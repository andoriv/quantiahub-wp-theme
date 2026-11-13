<?php
/**
 * ACF diagnostic.
 *
 * Reports whether ACF is active, whether it is the Pro version that ships
 * options pages, and whether the Quantia Hub subpage is registered in the
 * current request. Access is gated by a secret so a visitor cannot run it.
 *
 * Diagnóstico de ACF.
 *
 * Reporta si ACF está activo, si es la versión Pro que incluye las páginas de
 * opciones, y si la subpágina de Quantia Hub está registrada en la petición
 * actual. El acceso está protegido por una clave secreta para que un visitante
 * no pueda ejecutarlo.
 *
 * @package WordPress
 */

define( 'DIAG_SECRET', 'quantia-diag-2026' );

if ( ! isset( $_GET['key'] ) || $_GET['key'] !== DIAG_SECRET ) {
    http_response_code( 403 );
    exit( 'Forbidden.' );
}

require_once __DIR__ . '/wp-load.php';

$acf_active       = function_exists( 'acf_add_local_field_group' );
$acf_pro_active   = function_exists( 'acf_add_options_page' );
$subpage_function = function_exists( 'acf_add_options_sub_page' );

$menu_slugs       = array();
$submenu_slugs    = array();
global $menu, $submenu;
if ( isset( $menu ) && is_array( $menu ) ) {
    foreach ( $menu as $item ) {
        if ( ! empty( $item[2] ) ) {
            $menu_slugs[] = $item[2];
        }
    }
}
if ( isset( $submenu ) && is_array( $submenu ) ) {
    foreach ( $submenu as $parent => $items ) {
        foreach ( $items as $item ) {
            if ( ! empty( $item[2] ) ) {
                $submenu_slugs[] = $item[2];
            }
        }
    }
}

$current_user = wp_get_current_user();
$user_roles   = (array) $current_user->roles;
$can_manage   = current_user_can( 'manage_options' );

$plugins = get_option( 'active_plugins', array() );
$acf_plugin_file = '';
foreach ( $plugins as $plugin ) {
    if ( false !== strpos( $plugin, 'advanced-custom-fields' ) ) {
        $acf_plugin_file = $plugin;
        break;
    }
}

echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>ACF diagnostic</title>';
echo '<style>body{font-family:system-ui,sans-serif;max-width:760px;margin:40px auto;padding:0 20px;line-height:1.6}';
echo 'h1{margin-top:0}h2{font-size:1.1rem;margin-top:24px;border-bottom:1px solid #ddd;padding-bottom:6px}';
echo '.ok{color:#00a32a;font-weight:600}.fail{color:#d63638;font-weight:600}';
echo 'code{background:#f6f7f7;padding:2px 6px;border-radius:3px}';
echo 'table{border-collapse:collapse;width:100%}td{padding:6px 0;border-bottom:1px solid #f0f0f1}';
echo 'td:first-child{width:260px;color:#50575e}</style></head><body>';

echo '<h1>ACF diagnostic</h1>';

echo '<h2>Plugin</h2>';
echo '<table>';
echo '<tr><td>ACF field groups available</td><td>' . ( $acf_active ? '<span class="ok">yes</span>' : '<span class="fail">no, ACF is not active</span>' ) . '</td></tr>';
echo '<tr><td>ACF options pages available</td><td>' . ( $acf_pro_active ? '<span class="ok">yes, ACF Pro is active</span>' : '<span class="fail">no, ACF Pro is required</span>' ) . '</td></tr>';
echo '<tr><td>ACF subpage helper available</td><td>' . ( $subpage_function ? '<span class="ok">yes</span>' : '<span class="fail">no</span>' ) . '</td></tr>';
echo '<tr><td>Detected ACF plugin file</td><td>' . ( $acf_plugin_file ? '<code>' . esc_html( $acf_plugin_file ) . '</code>' : '<span class="fail">none</span>' ) . '</td></tr>';
echo '</table>';

echo '<h2>User</h2>';
echo '<table>';
echo '<tr><td>Logged in as</td><td><code>' . esc_html( $current_user->user_login ) . '</code></td></tr>';
echo '<tr><td>Roles</td><td><code>' . esc_html( implode( ', ', $user_roles ) ) . '</code></td></tr>';
echo '<tr><td>manage_options</td><td>' . ( $can_manage ? '<span class="ok">yes</span>' : '<span class="fail">no, this is the problem</span>' ) . '</td></tr>';
echo '</table>';

echo '<h2>Registered menu items</h2>';
echo '<p>Top level slugs currently registered:</p>';
echo '<p><code>' . esc_html( implode( '</code> <code>', $menu_slugs ) ) . '</code></p>';
echo '<p>Subpage slugs currently registered:</p>';
echo '<p><code>' . esc_html( implode( '</code> <code>', $submenu_slugs ) ) . '</code></p>';

echo '<h2>Interpretation</h2>';
echo '<ul>';
if ( ! $acf_pro_active ) {
    echo '<li><strong>ACF Pro is not active.</strong> The Quantia Hub menu and its subpages do not exist. Options: install ACF Pro, or use the native options page that avoids the ACF dependency entirely.</li>';
} elseif ( ! $can_manage ) {
    echo '<li><strong>ACF Pro is active but the user lacks manage_options.</strong> Switch the account to the administrator role.</li>';
} elseif ( ! in_array( 'quantia-hub-home', $submenu_slugs, true ) ) {
    echo '<li><strong>ACF Pro is active but the subpage is not registered.</strong> The acf/init hook may not have fired, or the theme bootstrap did not load app/helpers/acf-options.php.</li>';
} else {
    echo '<li><strong>Everything is registered.</strong> The URL should work. If the page loads but shows no fields, the field group files are not being loaded by the acf-options loader.</li>';
}
echo '</ul>';

echo '</body></html>';
exit;