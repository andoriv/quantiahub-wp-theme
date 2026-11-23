<?php
/**
 * Procesamiento AJAX del formulario de contacto.
 *
 * Guarda la consulta en WordPress y envía una notificación HTML
 * al correo configurado para Quantia Hub mediante wp_mail().
 *
 * WP Mail SMTP controla el proveedor y el remitente real.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;


/**
 * Endpoint AJAX para visitantes.
 */
add_action(
    'wp_ajax_nopriv_quantia_contact',
    'quantia_handle_contact_form'
);


/**
 * Endpoint AJAX para usuarios autenticados.
 */
add_action(
    'wp_ajax_quantia_contact',
    'quantia_handle_contact_form'
);


/**
 * Variable para conservar el último error de wp_mail().
 *
 * @var WP_Error|null
 */
$GLOBALS['quantia_contact_mail_error'] = null;


/**
 * Captura errores de wp_mail().
 *
 * @param WP_Error $error Error generado durante el envío.
 */
function quantia_capture_contact_mail_error(
    WP_Error $error
): void {
    $GLOBALS['quantia_contact_mail_error'] = $error;
}

add_action(
    'wp_mail_failed',
    'quantia_capture_contact_mail_error'
);


/**
 * Envía una respuesta AJAX de error.
 *
 * @param string $message Mensaje para el usuario.
 * @param int    $status  Código HTTP.
 */
function quantia_contact_send_error(
    string $message,
    int $status = 400
): void {
    wp_send_json_error(
        array(
            'message' => $message,
        ),
        $status
    );
}


/**
 * Obtiene el correo que recibirá las notificaciones.
 *
 * Prioridad:
 * 1. Constante QUANTIA_CONTACT_EMAIL.
 * 2. Configuración del tema.
 * 3. Correo administrativo de WordPress.
 *
 * @return string
 */
function quantia_contact_notification_email(): string {
    $email = '';

    /*
     * Primera opción:
     * QUANTIA_CONTACT_EMAIL definida en wp-config.php.
     */
    if (
        function_exists( 'quantia_contact_config_get' )
    ) {
        $email = quantia_contact_config_get(
            'QUANTIA_CONTACT_EMAIL'
        );
    } elseif (
        defined( 'QUANTIA_CONTACT_EMAIL' )
    ) {
        $email = (string) QUANTIA_CONTACT_EMAIL;
    }

    /*
     * Segunda opción:
     * configuración del tema.
     */
    if (
        '' === trim( $email ) &&
        function_exists( 'quantia_option' )
    ) {
        $email = (string) quantia_option(
            'contact_email',
            ''
        );
    }

    /*
     * Última opción:
     * correo administrativo de WordPress.
     */
    if ( '' === trim( $email ) ) {
        $email = (string) get_option(
            'admin_email',
            ''
        );
    }

    return sanitize_email(
        trim( $email )
    );
}


/**
 * Limpia un valor antes de utilizarlo en un encabezado.
 *
 * @param string $value Valor que será utilizado en el encabezado.
 *
 * @return string
 */
function quantia_contact_sanitize_header(
    string $value
): string {
    return str_replace(
        array( "\r", "\n" ),
        '',
        sanitize_text_field( $value )
    );
}


/**
 * Devuelve una respuesta de éxito para solicitudes detectadas
 * mediante el honeypot, sin revelar la detección.
 */
function quantia_contact_fake_success(): void {
    wp_send_json_success(
        array(
            'message' => __(
                'Consulta enviada correctamente.',
                'quantia-hub'
            ),
        )
    );
}


/**
 * Procesa el formulario de contacto.
 */
function quantia_handle_contact_form(): void {

    /*
     * Solo se permiten solicitudes POST.
     */
    $request_method = isset( $_SERVER['REQUEST_METHOD'] )
        ? strtoupper(
            sanitize_text_field(
                wp_unslash(
                    $_SERVER['REQUEST_METHOD']
                )
            )
        )
        : '';

    if ( 'POST' !== $request_method ) {
        quantia_contact_send_error(
            __(
                'Método de solicitud no permitido.',
                'quantia-hub'
            ),
            405
        );
    }


    /*
     * Validar nonce.
     */
    if (
        ! check_ajax_referer(
            'quantia_contact',
            'nonce',
            false
        )
    ) {
        quantia_contact_send_error(
            __(
                'La sesión expiró. Recarga la página e intenta nuevamente.',
                'quantia-hub'
            ),
            403
        );
    }


    /*
     * Confirmar que Turnstile esté configurado.
     */
    if (
        ! function_exists(
            'quantia_turnstile_is_configured'
        ) ||
        ! quantia_turnstile_is_configured()
    ) {
        quantia_contact_send_error(
            __(
                'El formulario no está disponible en este momento.',
                'quantia-hub'
            ),
            503
        );
    }


    /*
     * Confirmar que el CPT contacto exista.
     */
    if ( ! post_type_exists( 'contacto' ) ) {
        error_log(
            'Quantia Contact: el post type "contacto" no está registrado.'
        );

        quantia_contact_send_error(
            __(
                'El formulario no está disponible en este momento.',
                'quantia-hub'
            ),
            500
        );
    }


    /*
     * Dirección IP utilizada para Turnstile
     * y control de frecuencia.
     */
    $ip = isset( $_SERVER['REMOTE_ADDR'] )
        ? sanitize_text_field(
            wp_unslash(
                $_SERVER['REMOTE_ADDR']
            )
        )
        : '';


    /*
     * Honeypot antispam.
     *
     * El campo website debe permanecer vacío.
     */
    $honeypot = isset( $_POST['website'] )
        ? trim(
            sanitize_text_field(
                wp_unslash(
                    $_POST['website']
                )
            )
        )
        : '';

    if ( '' !== $honeypot ) {
        quantia_contact_fake_success();
    }


    /*
     * Time-trap.
     *
     * El formulario no puede enviarse en menos de dos segundos.
     */
    $started = isset( $_POST['form_started_at'] )
        ? absint( $_POST['form_started_at'] )
        : 0;

    $elapsed = $started > 0
        ? time() - $started
        : 0;

    if (
        $started <= 0 ||
        $elapsed < 2
    ) {
        quantia_contact_send_error(
            __(
                'No pudimos procesar la solicitud. Espera unos segundos e intenta nuevamente.',
                'quantia-hub'
            )
        );
    }

    if ( $elapsed > DAY_IN_SECONDS ) {
        quantia_contact_send_error(
            __(
                'La sesión del formulario expiró. Recarga la página.',
                'quantia-hub'
            )
        );
    }


    /*
     * Verificación de Cloudflare Turnstile.
     */
    $turnstile_token = isset(
        $_POST['cf-turnstile-response']
    )
        ? sanitize_text_field(
            wp_unslash(
                $_POST['cf-turnstile-response']
            )
        )
        : '';

    if (
        ! function_exists(
            'quantia_turnstile_verify'
        ) ||
        ! quantia_turnstile_verify(
            $turnstile_token,
            $ip
        )
    ) {
        quantia_contact_send_error(
            __(
                'No pudimos completar la validación de seguridad. Reinicia la validación e intenta nuevamente.',
                'quantia-hub'
            ),
            400
        );
    }


    /*
     * Recibir y sanear los campos.
     */
    $first_name = isset( $_POST['firstName'] )
        ? sanitize_text_field(
            wp_unslash(
                $_POST['firstName']
            )
        )
        : '';

    $last_name = isset( $_POST['lastName'] )
        ? sanitize_text_field(
            wp_unslash(
                $_POST['lastName']
            )
        )
        : '';

    $email = isset( $_POST['email'] )
        ? sanitize_email(
            wp_unslash(
                $_POST['email']
            )
        )
        : '';

    $phone = isset( $_POST['phone'] )
        ? sanitize_text_field(
            wp_unslash(
                $_POST['phone']
            )
        )
        : '';

    $message = isset( $_POST['message'] )
        ? sanitize_textarea_field(
            wp_unslash(
                $_POST['message']
            )
        )
        : '';


    /*
     * Validación del nombre.
     */
    if (
        mb_strlen( $first_name ) < 2 ||
        mb_strlen( $first_name ) > 80
    ) {
        quantia_contact_send_error(
            __(
                'Ingresa un nombre válido.',
                'quantia-hub'
            )
        );
    }


    /*
     * Validación del apellido.
     */
    if (
        mb_strlen( $last_name ) < 2 ||
        mb_strlen( $last_name ) > 80
    ) {
        quantia_contact_send_error(
            __(
                'Ingresa un apellido válido.',
                'quantia-hub'
            )
        );
    }


    /*
     * Validación del correo del cliente.
     */
    if (
        ! is_email( $email ) ||
        strlen( $email ) > 180
    ) {
        quantia_contact_send_error(
            __(
                'El correo electrónico no es válido.',
                'quantia-hub'
            )
        );
    }


    /*
     * Validación del teléfono.
     */
    if (
        '' !== $phone &&
        ! preg_match(
            '/^[0-9+\s().-]{7,25}$/',
            $phone
        )
    ) {
        quantia_contact_send_error(
            __(
                'El teléfono no es válido.',
                'quantia-hub'
            )
        );
    }

    $phone_clean = preg_replace(
        '/[^\d+]/',
        '',
        $phone
    );

    if (
        ! is_string( $phone_clean ) ||
        strlen( $phone_clean ) < 7 ||
        strlen( $phone_clean ) > 20
    ) {
        quantia_contact_send_error(
            __(
                'El teléfono debe contener entre 7 y 20 dígitos.',
                'quantia-hub'
            )
        );
    }


    /*
     * Validación del mensaje.
     */
    if (
        mb_strlen( $message ) < 10 ||
        mb_strlen( $message ) > 3000
    ) {
        quantia_contact_send_error(
            __(
                'El mensaje debe contener entre 10 y 3000 caracteres.',
                'quantia-hub'
            )
        );
    }


    /*
     * Rate limiting.
     *
     * Impide que la misma combinación de correo e IP
     * envíe nuevamente durante cinco minutos.
     */
    $rate_key = 'quantia_contact_'
        . md5(
            strtolower( $email )
            . '|'
            . $ip
        );

    if ( get_transient( $rate_key ) ) {
        quantia_contact_send_error(
            __(
                'Ya recibimos una consulta reciente. Espera cinco minutos antes de enviar otra.',
                'quantia-hub'
            ),
            429
        );
    }

    set_transient(
        $rate_key,
        1,
        5 * MINUTE_IN_SECONDS
    );


    /*
     * Nombre completo del cliente.
     */
    $title = trim(
        sprintf(
            '%s %s',
            $first_name,
            $last_name
        )
    );


    /*
     * Correo que recibirá la notificación.
     */
    $notification_email =
        quantia_contact_notification_email();


    /*
     * Guardar el contacto.
     *
     * Se utiliza private porque contiene
     * información personal.
     */
    $post_id = wp_insert_post(
        array(
            'post_type'    => 'contacto',
            'post_status'  => 'private',
            'post_title'   => $title,
            'post_content' => $message,
            'meta_input'   => array(
                '_quantia_contact_first_name'
                    => $first_name,

                '_quantia_contact_last_name'
                    => $last_name,

                '_quantia_contact_email'
                    => $email,

                '_quantia_contact_phone'
                    => $phone,

                '_quantia_contact_ip'
                    => $ip,

                '_quantia_contact_submitted'
                    => current_time( 'mysql' ),

                '_quantia_contact_mail_status'
                    => 'pending',

                '_quantia_contact_mail_recipient'
                    => $notification_email,
            ),
        ),
        true
    );


    /*
     * Validar que se haya guardado.
     */
    if (
        is_wp_error( $post_id ) ||
        ! $post_id
    ) {
        delete_transient( $rate_key );

        $storage_error = is_wp_error( $post_id )
            ? $post_id->get_error_message()
            : 'ID de contacto no generado';

        error_log(
            'Quantia Contact: no fue posible guardar el contacto. '
            . sanitize_text_field( $storage_error )
        );

        quantia_contact_send_error(
            __(
                'No pudimos guardar tu consulta. Intenta nuevamente.',
                'quantia-hub'
            ),
            500
        );
    }


    /*
     * Validar destinatario.
     */
    if (
        '' === $notification_email ||
        ! is_email( $notification_email )
    ) {
        update_post_meta(
            $post_id,
            '_quantia_contact_mail_status',
            'invalid_recipient'
        );

        update_post_meta(
            $post_id,
            '_quantia_contact_mail_error',
            'El correo destinatario no está configurado o no es válido.'
        );

        error_log(
            'Quantia Contact: destinatario inválido. '
            . 'Verifica QUANTIA_CONTACT_EMAIL en wp-config.php.'
        );

        wp_send_json_success(
            array(
                'message' => __(
                    'Consulta registrada correctamente.',
                    'quantia-hub'
                ),
                'id'           => $post_id,
                'notification' => 'invalid_recipient',
            )
        );
    }


    /*
     * Asunto del correo.
     */
    $subject = sprintf(
        __(
            'Quantia Web - Nueva consulta de %s',
            'quantia-hub'
        ),
        $title
    );


    /*
     * Enlace interno para abrir el contacto.
     */
    $contact_url = admin_url(
        'post.php?post='
        . absint( $post_id )
        . '&action=edit'
    );


    /*
     * Valores escapados para el HTML.
     */
    $safe_first_name = esc_html( $first_name );
    $safe_last_name  = esc_html( $last_name );
    $safe_email      = esc_html( $email );

    $safe_phone = '' !== $phone
        ? esc_html( $phone )
        : esc_html__(
            'No indicado',
            'quantia-hub'
        );

    $safe_message = nl2br(
        esc_html( $message )
    );

    $safe_contact_url = esc_url(
        $contact_url
    );

    $safe_mailto = esc_attr(
        'mailto:' . $email
    );

    $received_at = current_time(
        'd/m/Y H:i'
    );


    /*
     * Plantilla HTML del correo.
     *
     * Se utilizan tablas y estilos inline para mayor
     * compatibilidad con Gmail y Outlook.
     */
    $email_body = '
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <meta
        name="color-scheme"
        content="light"
    >
    <meta
        name="supported-color-schemes"
        content="light"
    >
    <title>Nueva consulta - Quantia Hub</title>
</head>

<body
    style="
        margin:0;
        padding:0;
        width:100%;
        background-color:#f4f7f6;
        font-family:Arial,Helvetica,sans-serif;
        color:#1f2937;
    "
>

<table
    role="presentation"
    width="100%"
    cellspacing="0"
    cellpadding="0"
    border="0"
    style="
        width:100%;
        margin:0;
        padding:0;
        background-color:#f4f7f6;
    "
>
<tr>
<td
    align="center"
    style="padding:40px 16px;"
>

    <table
        role="presentation"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        border="0"
        style="
            width:100%;
            max-width:640px;
            margin:0 auto;
            background-color:#ffffff;
            border:1px solid #e5e7eb;
            border-radius:14px;
            overflow:hidden;
        "
    >

        <tr>
            <td
                style="
                    padding:30px 34px;
                    background-color:#00AC69;
                "
            >
                <div
                    style="
                        margin:0;
                        color:#ffffff;
                        font-size:24px;
                        font-weight:700;
                        line-height:1.2;
                    "
                >
                    Quantia Hub
                </div>

                <div
                    style="
                        margin-top:7px;
                        color:#eafff5;
                        font-size:13px;
                        line-height:1.5;
                    "
                >
                    Notificación del formulario de contacto
                </div>
            </td>
        </tr>

        <tr>
            <td style="padding:34px;">

                <table
                    role="presentation"
                    cellspacing="0"
                    cellpadding="0"
                    border="0"
                    style="margin-bottom:18px;"
                >
                    <tr>
                        <td
                            style="
                                padding:7px 12px;
                                background-color:#e6f8f0;
                                border-radius:20px;
                                color:#008e57;
                                font-size:11px;
                                line-height:1;
                                font-weight:700;
                                letter-spacing:0.5px;
                            "
                        >
                            NUEVA CONSULTA
                        </td>
                    </tr>
                </table>

                <h1
                    style="
                        margin:0 0 10px 0;
                        padding:0;
                        color:#111827;
                        font-size:25px;
                        line-height:1.3;
                        font-weight:700;
                    "
                >
                    Alguien quiere contactar a Quantia Hub
                </h1>

                <p
                    style="
                        margin:0 0 28px 0;
                        padding:0;
                        color:#6b7280;
                        font-size:15px;
                        line-height:1.65;
                    "
                >
                    Se recibió una nueva solicitud mediante
                    el formulario de contacto del sitio web.
                </p>

                <table
                    role="presentation"
                    width="100%"
                    cellspacing="0"
                    cellpadding="0"
                    border="0"
                    style="
                        width:100%;
                        margin:0 0 28px 0;
                        border:1px solid #e5e7eb;
                        border-collapse:separate;
                        border-spacing:0;
                        border-radius:10px;
                        overflow:hidden;
                    "
                >

                    <tr>
                        <td
                            colspan="2"
                            style="
                                padding:13px 16px;
                                background-color:#f0faf6;
                                border-bottom:1px solid #d8eee5;
                                color:#007c4c;
                                font-size:12px;
                                font-weight:700;
                                letter-spacing:0.4px;
                            "
                        >
                            DATOS DEL CONTACTO
                        </td>
                    </tr>

                    <tr>
                        <td
                            width="140"
                            style="
                                width:140px;
                                padding:15px 16px;
                                border-bottom:1px solid #e5e7eb;
                                color:#6b7280;
                                font-size:13px;
                                vertical-align:top;
                            "
                        >
                            Nombre
                        </td>

                        <td
                            style="
                                padding:15px 16px;
                                border-bottom:1px solid #e5e7eb;
                                color:#111827;
                                font-size:14px;
                                line-height:1.5;
                                font-weight:600;
                            "
                        >
                            ' . $safe_first_name . '
                            ' . $safe_last_name . '
                        </td>
                    </tr>

                    <tr>
                        <td
                            width="140"
                            style="
                                width:140px;
                                padding:15px 16px;
                                border-bottom:1px solid #e5e7eb;
                                color:#6b7280;
                                font-size:13px;
                                vertical-align:top;
                            "
                        >
                            Correo
                        </td>

                        <td
                            style="
                                padding:15px 16px;
                                border-bottom:1px solid #e5e7eb;
                                font-size:14px;
                                line-height:1.5;
                            "
                        >
                            <a
                                href="' . $safe_mailto . '"
                                style="
                                    color:#00AC69;
                                    font-weight:600;
                                    text-decoration:none;
                                "
                            >
                                ' . $safe_email . '
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td
                            width="140"
                            style="
                                width:140px;
                                padding:15px 16px;
                                border-bottom:1px solid #e5e7eb;
                                color:#6b7280;
                                font-size:13px;
                                vertical-align:top;
                            "
                        >
                            Teléfono
                        </td>

                        <td
                            style="
                                padding:15px 16px;
                                border-bottom:1px solid #e5e7eb;
                                color:#111827;
                                font-size:14px;
                                line-height:1.5;
                                font-weight:600;
                            "
                        >
                            ' . $safe_phone . '
                        </td>
                    </tr>

                    <tr>
                        <td
                            width="140"
                            style="
                                width:140px;
                                padding:15px 16px;
                                color:#6b7280;
                                font-size:13px;
                                vertical-align:top;
                            "
                        >
                            Recibido
                        </td>

                        <td
                            style="
                                padding:15px 16px;
                                color:#111827;
                                font-size:14px;
                                line-height:1.5;
                            "
                        >
                            ' . esc_html( $received_at ) . '
                        </td>
                    </tr>

                </table>

                <div
                    style="
                        margin:0 0 10px 0;
                        color:#374151;
                        font-size:12px;
                        line-height:1.4;
                        font-weight:700;
                        letter-spacing:0.4px;
                    "
                >
                    MENSAJE DEL CLIENTE
                </div>

                <div
                    style="
                        margin:0 0 30px 0;
                        padding:20px;
                        background-color:#f8faf9;
                        border-left:4px solid #00AC69;
                        border-radius:0 8px 8px 0;
                        color:#374151;
                        font-size:15px;
                        line-height:1.7;
                        word-break:break-word;
                    "
                >
                    ' . $safe_message . '
                </div>

                <table
                    role="presentation"
                    cellspacing="0"
                    cellpadding="0"
                    border="0"
                >
                    <tr>
                        <td
                            bgcolor="#00AC69"
                            style="border-radius:7px;"
                        >
                            <a
                                href="' . $safe_contact_url . '"
                                style="
                                    display:inline-block;
                                    padding:13px 20px;
                                    color:#ffffff;
                                    font-size:14px;
                                    line-height:1.2;
                                    font-weight:700;
                                    text-decoration:none;
                                "
                            >
                                Ver contacto en WordPress
                            </a>
                        </td>

                        <td
                            width="10"
                            style="width:10px;"
                        >
                            &nbsp;
                        </td>

                        <td
                            style="
                                border:1px solid #00AC69;
                                border-radius:7px;
                            "
                        >
                            <a
                                href="' . $safe_mailto . '"
                                style="
                                    display:inline-block;
                                    padding:12px 20px;
                                    color:#008e57;
                                    font-size:14px;
                                    line-height:1.2;
                                    font-weight:700;
                                    text-decoration:none;
                                "
                            >
                                Responder al cliente
                            </a>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>

        <tr>
            <td
                style="
                    padding:22px 34px;
                    background-color:#f8faf9;
                    border-top:1px solid #e5e7eb;
                "
            >
                <p
                    style="
                        margin:0 0 6px 0;
                        color:#374151;
                        font-size:12px;
                        line-height:1.5;
                        font-weight:600;
                    "
                >
                    Quantia Hub
                </p>

                <p
                    style="
                        margin:0;
                        color:#9ca3af;
                        font-size:11px;
                        line-height:1.6;
                    "
                >
                    Esta notificación fue generada automáticamente
                    por el formulario de contacto de Quantia Hub.
                    Puedes responder directamente al cliente
                    utilizando el botón anterior o la función
                    Responder de tu correo.
                </p>
            </td>
        </tr>

    </table>

    <table
        role="presentation"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        border="0"
        style="
            width:100%;
            max-width:640px;
        "
    >
        <tr>
            <td
                align="center"
                style="
                    padding:18px 20px 0 20px;
                    color:#9ca3af;
                    font-size:11px;
                    line-height:1.5;
                "
            >
                Notificación interna de Quantia Hub
            </td>
        </tr>
    </table>

</td>
</tr>
</table>

</body>
</html>
';


    /*
     * El correo del cliente se utiliza como Reply-To.
     *
     * WP Mail SMTP mantiene el remitente autenticado.
     */
    $safe_title = quantia_contact_sanitize_header(
        $title
    );

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',

        sprintf(
            'Reply-To: %s <%s>',
            $safe_title,
            $email
        ),
    );


    /*
     * Limpiar cualquier error anterior.
     */
    $GLOBALS['quantia_contact_mail_error'] = null;


    /*
     * Enviar una sola notificación HTML.
     */
    $mail_sent = wp_mail(
        $notification_email,
        $subject,
        $email_body,
        $headers
    );


    /*
     * Guardar resultado de la notificación.
     */
    update_post_meta(
        $post_id,
        '_quantia_contact_mail_status',
        $mail_sent
            ? 'sent'
            : 'failed'
    );


    /*
     * Envío exitoso.
     */
    if ( $mail_sent ) {
        update_post_meta(
            $post_id,
            '_quantia_contact_mail_sent_at',
            current_time( 'mysql' )
        );

        update_post_meta(
            $post_id,
            '_quantia_contact_mail_recipient',
            $notification_email
        );

        delete_post_meta(
            $post_id,
            '_quantia_contact_mail_error'
        );
    }


    /*
     * Envío fallido.
     */
    if ( ! $mail_sent ) {
        $mail_error =
            'wp_mail() no pudo enviar la notificación.';

        if (
            isset(
                $GLOBALS['quantia_contact_mail_error']
            ) &&
            $GLOBALS['quantia_contact_mail_error']
                instanceof WP_Error
        ) {
            $mail_error =
                $GLOBALS['quantia_contact_mail_error']
                    ->get_error_message();
        }

        update_post_meta(
            $post_id,
            '_quantia_contact_mail_error',
            sanitize_text_field(
                $mail_error
            )
        );

        error_log(
            'Quantia Contact: el contacto fue guardado, '
            . 'pero la notificación no pudo enviarse. '
            . sanitize_text_field( $mail_error )
        );
    }


    /*
     * Respuesta final.
     *
     * La consulta se considera recibida porque el contacto
     * quedó guardado, incluso si la notificación interna falla.
     */
    wp_send_json_success(
        array(
            'message' => __(
                'Consulta enviada. Un ejecutivo te contactará pronto.',
                'quantia-hub'
            ),
            'id'           => $post_id,
            'notification' => $mail_sent
                ? 'sent'
                : 'failed',
        )
    );
}