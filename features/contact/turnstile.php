<?php
/**
 * Cloudflare Turnstile: configuración y validación.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Obtiene la Site Key pública de Turnstile.
 */
function quantia_turnstile_site_key(): string {
    if (
        defined( 'QUANTIA_TURNSTILE_SITE_KEY' ) &&
        QUANTIA_TURNSTILE_SITE_KEY
    ) {
        return trim( (string) QUANTIA_TURNSTILE_SITE_KEY );
    }

    $env = getenv( 'QUANTIA_TURNSTILE_SITE_KEY' );

    return is_string( $env ) ? trim( $env ) : '';
}

/**
 * Obtiene la Secret Key privada de Turnstile.
 */
function quantia_turnstile_secret_key(): string {
    if (
        defined( 'QUANTIA_TURNSTILE_SECRET_KEY' ) &&
        QUANTIA_TURNSTILE_SECRET_KEY
    ) {
        return trim( (string) QUANTIA_TURNSTILE_SECRET_KEY );
    }

    $env = getenv( 'QUANTIA_TURNSTILE_SECRET_KEY' );

    return is_string( $env ) ? trim( $env ) : '';
}

/**
 * Comprueba si Turnstile está completamente configurado.
 */
function quantia_turnstile_is_configured(): bool {
    return (
        '' !== quantia_turnstile_site_key() &&
        '' !== quantia_turnstile_secret_key()
    );
}

/**
 * Comprueba si WordPress se está ejecutando localmente.
 */
function quantia_turnstile_is_local_environment(): bool {
    $hostname = strtolower(
        (string) wp_parse_url(
            home_url( '/' ),
            PHP_URL_HOST
        )
    );

    return in_array(
        $hostname,
        array(
            'localhost',
            '127.0.0.1',
            '::1',
        ),
        true
    );
}

/**
 * Verifica un token de Turnstile mediante Siteverify.
 *
 * @param string $token Token recibido desde el formulario.
 * @param string $ip    Dirección IP opcional.
 *
 * @return bool
 */
function quantia_turnstile_verify(
    string $token,
    string $ip = ''
): bool {
    $secret = quantia_turnstile_secret_key();
    $token  = trim( $token );

    if ( '' === $secret || '' === $token ) {
        return false;
    }

    /*
     * Cloudflare establece un máximo de 2048 caracteres
     * para el token.
     */
    if ( strlen( $token ) > 2048 ) {
        return false;
    }

    $body = array(
        'secret'   => $secret,
        'response' => $token,
    );

    if ( '' !== $ip ) {
        $body['remoteip'] = $ip;
    }

    $response = wp_remote_post(
        'https://challenges.cloudflare.com/turnstile/v0/siteverify',
        array(
            'timeout' => 10,
            'body'    => $body,
        )
    );

    if ( is_wp_error( $response ) ) {
        error_log(
            'Quantia Turnstile: no fue posible conectar con Siteverify.'
        );

        return false;
    }

    $status_code = wp_remote_retrieve_response_code( $response );

    if ( 200 !== $status_code ) {
        error_log(
            'Quantia Turnstile: respuesta HTTP inesperada: '
            . (int) $status_code
        );

        return false;
    }

    $raw  = wp_remote_retrieve_body( $response );
    $data = json_decode( $raw, true );

    if (
        ! is_array( $data ) ||
        empty( $data['success'] )
    ) {
        $error_codes = array();

        if (
            isset( $data['error-codes'] ) &&
            is_array( $data['error-codes'] )
        ) {
            $error_codes = array_map(
                'sanitize_text_field',
                $data['error-codes']
            );
        }

        error_log(
            'Quantia Turnstile: verificación rechazada. Códigos: '
            . (
                $error_codes
                    ? implode( ', ', $error_codes )
                    : 'sin código'
            )
        );

        return false;
    }

    /*
     * En localhost se utilizan las claves oficiales de prueba,
     * por lo que no se exige coincidencia de hostname.
     */
    if ( quantia_turnstile_is_local_environment() ) {
        return true;
    }

    $expected_hostname = strtolower(
        (string) wp_parse_url(
            home_url( '/' ),
            PHP_URL_HOST
        )
    );

    $received_hostname = isset( $data['hostname'] )
        ? strtolower(
            sanitize_text_field(
                (string) $data['hostname']
            )
        )
        : '';

    if (
        '' !== $expected_hostname &&
        (
            '' === $received_hostname ||
            $expected_hostname !== $received_hostname
        )
    ) {
        error_log(
            'Quantia Turnstile: el hostname recibido no coincide.'
        );

        return false;
    }

    return true;
}

/**
 * Muestra un aviso administrativo si Turnstile no está configurado.
 */
add_action(
    'admin_notices',
    'quantia_turnstile_admin_notice'
);

function quantia_turnstile_admin_notice(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( quantia_turnstile_is_configured() ) {
        return;
    }

    ?>
    <div class="notice notice-error">
        <p>
            <strong>
                <?php esc_html_e(
                    'Turnstile no configurado.',
                    'quantia-hub'
                ); ?>
            </strong>

            <?php esc_html_e(
                'El formulario de contacto está bloqueado.',
                'quantia-hub'
            ); ?>

            <?php esc_html_e(
                'Agrega QUANTIA_TURNSTILE_SITE_KEY y QUANTIA_TURNSTILE_SECRET_KEY en wp-config.php o como variables de entorno.',
                'quantia-hub'
            ); ?>
        </p>
    </div>
    <?php
}