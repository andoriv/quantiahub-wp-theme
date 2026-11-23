<?php
/**
 * Administración de contactos.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Agrega el menú de Contactos dentro de Quantia Hub.
 */
add_action( 'admin_menu', 'quantia_add_contacts_menu', 20 );

function quantia_add_contacts_menu(): void {
    if ( ! function_exists( 'acf_add_options_page' ) ) {
        add_menu_page(
            __( 'Quantia Hub', 'quantia-hub' ),
            __( 'Quantia Hub', 'quantia-hub' ),
            'manage_options',
            'quantia-hub',
            '__return_null',
            'dashicons-admin-generic',
            3
        );
    }

    add_submenu_page(
        'quantia-hub',
        __( 'Contactos', 'quantia-hub' ),
        __( 'Contactos', 'quantia-hub' ),
        'manage_options',
        'edit.php?post_type=contacto'
    );
}


/**
 * Personaliza las columnas de la lista de contactos.
 */
add_filter(
    'manage_contacto_posts_columns',
    'quantia_contact_columns'
);

function quantia_contact_columns( $columns ): array {
    $new = array();

    if ( isset( $columns['cb'] ) ) {
        $new['cb'] = $columns['cb'];
    }

    $new['title']       = __( 'Nombre', 'quantia-hub' );
    $new['email']       = __( 'Email', 'quantia-hub' );
    $new['phone']       = __( 'Teléfono', 'quantia-hub' );
    $new['mail_status'] = __( 'Estado correo', 'quantia-hub' );
    $new['date']        = __( 'Fecha', 'quantia-hub' );

    return $new;
}


/**
 * Muestra el contenido de las columnas personalizadas.
 */
add_action(
    'manage_contacto_posts_custom_column',
    'quantia_contact_column_content',
    10,
    2
);

function quantia_contact_column_content(
    $column,
    $post_id
): void {

    switch ( $column ) {

        case 'email':

            $email = get_post_meta(
                $post_id,
                '_quantia_contact_email',
                true
            );

            if ( $email ) {
                echo '<a href="mailto:'
                    . esc_attr( $email )
                    . '">'
                    . esc_html( $email )
                    . '</a>';
            } else {
                echo '—';
            }

            break;


        case 'phone':

            $phone = get_post_meta(
                $post_id,
                '_quantia_contact_phone',
                true
            );

            echo $phone
                ? esc_html( $phone )
                : '—';

            break;


        case 'mail_status':

            $status = get_post_meta(
                $post_id,
                '_quantia_contact_mail_status',
                true
            );

            switch ( $status ) {

                case 'sent':

                    echo '<span style="
                        color:#008a20;
                        font-weight:600;
                    ">';

                    echo '✓ Enviado';

                    echo '</span>';

                    break;


                case 'failed':

                    echo '<span style="
                        color:#d63638;
                        font-weight:600;
                    ">';

                    echo '✕ Fallido';

                    echo '</span>';

                    break;


                case 'invalid_recipient':

                    echo '<span style="
                        color:#d63638;
                        font-weight:600;
                    ">';

                    echo '✕ Destinatario inválido';

                    echo '</span>';

                    break;


                case 'pending':

                    echo '<span style="
                        color:#996800;
                        font-weight:600;
                    ">';

                    echo '● Pendiente';

                    echo '</span>';

                    break;


                default:

                    echo '<span style="
                        color:#646970;
                    ">';

                    echo '— Sin información';

                    echo '</span>';

                    break;
            }

            break;
    }
}


/**
 * Agrega la caja con el detalle del contacto.
 */
add_action(
    'add_meta_boxes',
    'quantia_contact_metabox'
);

function quantia_contact_metabox(): void {

    add_meta_box(
        'quantia_contact_details',
        __( 'Detalle del contacto', 'quantia-hub' ),
        'quantia_render_contact_metabox',
        'contacto',
        'normal',
        'high'
    );
}


/**
 * Renderiza el detalle completo del contacto.
 */
function quantia_render_contact_metabox( $post ): void {

    $first = get_post_meta(
        $post->ID,
        '_quantia_contact_first_name',
        true
    );

    $last = get_post_meta(
        $post->ID,
        '_quantia_contact_last_name',
        true
    );

    /*
     * Compatibilidad con contactos antiguos.
     */
    if ( $first === '' && $last === '' ) {

        $legacy_name = get_post_meta(
            $post->ID,
            '_quantia_contact_name',
            true
        );

        if ( $legacy_name ) {
            $first = $legacy_name;
        }
    }


    /*
     * Información del contacto.
     */
    $email = get_post_meta(
        $post->ID,
        '_quantia_contact_email',
        true
    );

    $phone = get_post_meta(
        $post->ID,
        '_quantia_contact_phone',
        true
    );

    $ip = get_post_meta(
        $post->ID,
        '_quantia_contact_ip',
        true
    );

    $submitted = get_post_meta(
        $post->ID,
        '_quantia_contact_submitted',
        true
    );


    /*
     * Estado del envío del correo.
     */
    $mail_status = get_post_meta(
        $post->ID,
        '_quantia_contact_mail_status',
        true
    );


    switch ( $mail_status ) {

        case 'sent':

            $mail_status_label =
                '✓ Enviado correctamente';

            $mail_status_color =
                '#008a20';

            break;


        case 'failed':

            $mail_status_label =
                '✕ Falló el envío';

            $mail_status_color =
                '#d63638';

            break;


        case 'invalid_recipient':

            $mail_status_label =
                '✕ Destinatario inválido';

            $mail_status_color =
                '#d63638';

            break;


        case 'pending':

            $mail_status_label =
                '● Pendiente';

            $mail_status_color =
                '#996800';

            break;


        default:

            $mail_status_label =
                '— Sin información';

            $mail_status_color =
                '#646970';

            break;
    }


    /**
     * Tabla con información general.
     */
    echo '<table class="form-table" role="presentation">';
    echo '<tbody>';


    /*
     * Nombre.
     */
    echo '<tr>';

    echo '<th scope="row" style="width:180px;">';
    echo '<strong>';
    echo esc_html__(
        'Nombre',
        'quantia-hub'
    );
    echo '</strong>';
    echo '</th>';

    echo '<td>';
    echo $first
        ? esc_html( $first )
        : '—';
    echo '</td>';

    echo '</tr>';


    /*
     * Apellido.
     */
    echo '<tr>';

    echo '<th scope="row">';
    echo '<strong>';
    echo esc_html__(
        'Apellido',
        'quantia-hub'
    );
    echo '</strong>';
    echo '</th>';

    echo '<td>';
    echo $last
        ? esc_html( $last )
        : '—';
    echo '</td>';

    echo '</tr>';


    /*
     * Email.
     */
    echo '<tr>';

    echo '<th scope="row">';
    echo '<strong>';
    echo esc_html__(
        'Email',
        'quantia-hub'
    );
    echo '</strong>';
    echo '</th>';

    echo '<td>';

    if ( $email ) {

        echo '<a href="mailto:'
            . esc_attr( $email )
            . '">';

        echo esc_html( $email );

        echo '</a>';

    } else {

        echo '—';
    }

    echo '</td>';

    echo '</tr>';


    /*
     * Teléfono.
     */
    echo '<tr>';

    echo '<th scope="row">';
    echo '<strong>';
    echo esc_html__(
        'Teléfono',
        'quantia-hub'
    );
    echo '</strong>';
    echo '</th>';

    echo '<td>';

    echo $phone
        ? esc_html( $phone )
        : '—';

    echo '</td>';

    echo '</tr>';


    /*
     * IP.
     */
    echo '<tr>';

    echo '<th scope="row">';
    echo '<strong>';
    echo esc_html__(
        'IP',
        'quantia-hub'
    );
    echo '</strong>';
    echo '</th>';

    echo '<td>';

    echo $ip
        ? esc_html( $ip )
        : '—';

    echo '</td>';

    echo '</tr>';


    /*
     * Fecha de envío.
     */
    echo '<tr>';

    echo '<th scope="row">';
    echo '<strong>';
    echo esc_html__(
        'Fecha de envío',
        'quantia-hub'
    );
    echo '</strong>';
    echo '</th>';

    echo '<td>';

    echo $submitted
        ? esc_html( $submitted )
        : '—';

    echo '</td>';

    echo '</tr>';


    /*
     * Estado del correo.
     */
    echo '<tr>';

    echo '<th scope="row">';
    echo '<strong>';
    echo esc_html__(
        'Estado del correo',
        'quantia-hub'
    );
    echo '</strong>';
    echo '</th>';

    echo '<td>';

    echo '<strong style="color:'
        . esc_attr( $mail_status_color )
        . ';">';

    echo esc_html( $mail_status_label );

    echo '</strong>';

    echo '</td>';

    echo '</tr>';


    echo '</tbody>';
    echo '</table>';


    /**
     * Mensaje enviado por el cliente.
     */
    echo '<h3 style="margin-top:20px;">';

    echo esc_html__(
        'Mensaje',
        'quantia-hub'
    );

    echo '</h3>';


    echo '<div style="
        background:#f6f7f7;
        padding:16px;
        border-radius:6px;
        white-space:pre-wrap;
    ">';

    echo esc_html(
        $post->post_content
    );

    echo '</div>';


    /**
     * Botón para responder al cliente.
     */
    if ( $email ) {

        echo '<p style="margin-top:20px;">';

        echo '<a
            class="button button-primary"
            href="mailto:'
            . esc_attr( $email )
            . '"
        >';

        echo esc_html__(
            'Responder por email',
            'quantia-hub'
        );

        echo '</a>';

        echo '</p>';
    }
}


/**
 * Oculta los campos estándar del editor,
 * porque el contacto es de solo consulta.
 */
add_action(
    'admin_head',
    function () {

        global $post_type;

        if ( 'contacto' === $post_type ) {

            echo '<style>

                #titlediv {
                    display:none !important;
                }

                #postdivrich {
                    display:none !important;
                }

            </style>';
        }
    }
);


/**
 * Cambia "Editar" por "Ver" en la lista
 * de contactos.
 */
add_filter(
    'post_row_actions',
    'quantia_contact_row_actions',
    10,
    2
);

function quantia_contact_row_actions(
    $actions,
    $post
): array {

    if (
        'contacto' === $post->post_type &&
        isset( $actions['edit'] )
    ) {

        $actions['edit'] = str_replace(
            __( 'Edit', 'quantia-hub' ),
            __( 'Ver', 'quantia-hub' ),
            $actions['edit']
        );

        $actions['edit'] = str_replace(
            'Editar',
            'Ver',
            $actions['edit']
        );

        $actions['edit'] = str_replace(
            'Edit',
            'Ver',
            $actions['edit']
        );
    }

    return $actions;
}