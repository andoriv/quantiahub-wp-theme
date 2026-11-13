<?php
/**
 * Reset de opciones del tema.
 *
 * Elimina las opciones guardadas de la calculadora, los beneficios y los
 * perfiles para que el tema vuelva a usar los defaults en español de
 * features/settings/data/defaults.php. Acceso protegido por clave.
 *
 * @package QuantiaHub
 */

define( 'RESET_SECRET', 'quantia-reset-settings-2026' );

if ( ! isset( $_GET['key'] ) || $_GET['key'] !== RESET_SECRET ) {
    http_response_code( 403 );
    exit( 'Forbidden.' );
}

require_once __DIR__ . '/wp-load.php';

if ( ! current_user_can( 'manage_options' ) ) {
    http_response_code( 403 );
    exit( 'Necesitas iniciar sesión como administrador.' );
}

$opciones = array(
    'quantia_settings_calculator',
    'quantia_settings_benefits',
    'quantia_settings_funds',
    'quantia_settings_investment_profiles',
    'quantia_settings_retirement_profiles',
    'quantia_settings_product_data',
);

$eliminadas = array();
foreach ( $opciones as $opcion ) {
    if ( delete_option( $opcion ) ) {
        $eliminadas[] = $opcion;
    }
}

echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Reset completado</title>';
echo '<style>body{font-family:system-ui,sans-serif;max-width:640px;margin:40px auto;padding:0 20px;line-height:1.6}';
echo '.ok{padding:20px;border-left:4px solid #46b450;background:#f0fff4;border-radius:4px}';
echo 'a{display:inline-block;padding:10px 20px;background:#2271b1;color:#fff;text-decoration:none;border-radius:4px;margin-top:16px;margin-right:8px}';
echo '</style></head><body>';
echo '<div class="ok">';
echo '<h2 style="margin-top:0;color:#00a32a">Configuración restablecida</h2>';
echo '<p>Opciones eliminadas de la base de datos:</p>';
echo '<ul>';
foreach ( $eliminadas as $opcion ) {
    echo '<li><code>' . esc_html( $opcion ) . '</code></li>';
}
if ( empty( $eliminadas ) ) {
    echo '<li>Ninguna. Las opciones ya estaban usando los defaults.</li>';
}
echo '</ul>';
echo '<p>Al recargar la home, los valores se tomarán de <code>features/settings/data/defaults.php</code> que están en español.</p>';
echo '<p><strong>Importante:</strong> elimina el archivo <code>reset-settings.php</code> del servidor ahora.</p>';
echo '<a href="' . esc_url( home_url( '/' ) ) . '">Ir al inicio</a>';
echo '<a href="' . esc_url( admin_url( 'admin.php?page=quantia-settings' ) ) . '">Ir a Quantia Settings</a>';
echo '</div></body></html>';
exit;