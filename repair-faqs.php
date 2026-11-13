<?php
/**
 * One time FAQ repair.
 *
 * This file registers a repair routine that assigns the correct FAQ category
 * to questions that were imported before the categoria_faq taxonomy was
 * registered. It runs only when the quantia_repair_faqs query parameter is
 * present on an admin request and the current user has the manage_options
 * capability. The routine is idempotent and can be removed after the first
 * successful run.
 *
 * Reparación puntual de FAQ.
 *
 * Este archivo registra una rutina de reparación que asigna la categoría
 * correcta a las preguntas que fueron importadas antes de que la taxonomía
 * categoria_faq estuviera registrada. Se ejecuta solo cuando el parámetro de
 * consulta quantia_repair_faqs está presente en una petición del admin y el
 * usuario actual tiene la capability manage_options. La rutina es idempotente
 * y puede eliminarse después de la primera ejecución exitosa.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', function () {
    if ( ! isset( $_GET['quantia_repair_faqs'] ) || '1' !== $_GET['quantia_repair_faqs'] ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sin permisos.' );
    }

    if ( ! taxonomy_exists( 'categoria_faq' ) ) {
        wp_die( 'La taxonomía categoria_faq no está registrada. Verifica que features/faqs/cpt.php se carga desde el bootstrap.' );
    }

    $map = array(
        '¿Cómo puedo recibir fondos en mi cuenta Quantia Hub?' => 'Cuenta corriente',
        '¿Cómo abro una cuenta en Quantia Hub?' => 'Cuenta corriente',
        '¿Cuál es el monto máximo permitido en mi cuenta?' => 'Cuenta corriente',
        '¿Cómo puedo aumentar el límite de mi cuenta?' => 'Cuenta corriente',
        '¿Existe un monto mínimo para mantener mi cuenta activa?' => 'Cuenta corriente',
        '¿Qué es Kuara?' => 'Kuara y transferencias',
        '¿Cómo transfiero dinero a otros clientes de Quantia Hub?' => 'Kuara y transferencias',
        '¿Qué tipo de transacciones puedo realizar?' => 'Kuara y transferencias',
        '¿Qué hago si cambio de equipo o número de celular?' => 'Dispositivos y seguridad',
        '¿Qué hago si cambio de equipo (celular) o el número de celular?' => 'Dispositivos y seguridad',
        '¿Cómo funciona la tasa de interés en Quantia Hub?' => 'Intereses y actualización de datos',
        '¿Dónde puedo actualizar mis datos personales?' => 'Intereses y actualización de datos',
        '¿Cómo solicito una carta de referencia bancaria?' => 'Cartas, movimientos y tarjetas',
        '¿Cómo puedo ver mis transacciones?' => 'Cartas, movimientos y tarjetas',
        '¿Cómo solicito la tarjeta Visa Débito?' => 'Tarjeta Visa Débito',
        '¿Cómo vinculo y activo mi tarjeta?' => 'Tarjeta Visa Débito',
        '¿Puedo tener tarjetas digitales o usarlas en Wallet?' => 'Tarjeta Visa Débito',
        '¿Por qué algunas transacciones aparecen en tránsito?' => 'Tarjeta Visa Débito',
        '¿Cómo bloqueo mi tarjeta?' => 'Tarjeta Visa Débito',
        '¿En qué países puedo usar la tarjeta?' => 'Tarjeta Visa Débito',
        '¿Por qué la tarjeta es sin nombre?' => 'Tarjeta Visa Débito',
        '¿Por qué es importante contar con un seguro de fraude?' => 'Tarjeta Visa Débito',
        '¿Qué tipo de transacciones puedo hacer con la tarjeta?' => 'Tarjeta Visa Débito',
        '¿Cuál es el monto mínimo para abrir un portafolio de inversión?' => 'Inversiones',
        '¿Cuándo y cómo puedo retirar el dinero invertido?' => 'Inversiones',
        '¿Qué pasa con los dividendos de mis inversiones?' => 'Inversiones',
        '¿En qué puedo invertir?' => 'Inversiones',
        '¿Cuánto tiempo tarda en procesarse mi compra o venta?' => 'Inversiones',
    );

    $updated = 0;
    $skipped = 0;
    $errors  = array();

    foreach ( $map as $question => $category_name ) {
        $posts = get_posts( array(
            'post_type'      => 'faq',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'title'          => $question,
        ) );

        if ( empty( $posts ) ) {
            $skipped++;
            continue;
        }

        $post_id = (int) $posts[0];

        $term = get_term_by( 'name', $category_name, 'categoria_faq' );
        if ( ! $term || is_wp_error( $term ) ) {
            $new = wp_insert_term( $category_name, 'categoria_faq' );
            if ( is_wp_error( $new ) || empty( $new['term_id'] ) ) {
                $errors[] = sprintf( 'No se pudo crear la categoría "%s" para la pregunta "%s"', $category_name, $question );
                $skipped++;
                continue;
            }
            $term_id = (int) $new['term_id'];
            update_term_meta( $term_id, QUANTIA_FAQ_CAT_VISIBLE_META, '1' );
        } else {
            $term_id = (int) $term->term_id;
        }

        $result = wp_set_object_terms( $post_id, array( $term_id ), 'categoria_faq' );
        if ( is_wp_error( $result ) ) {
            $errors[] = sprintf( 'No se pudo asignar la categoría "%s" a la pregunta "%s": %s', $category_name, $question, $result->get_error_message() );
            $skipped++;
            continue;
        }

        $updated++;
    }

    echo '<div class="notice notice-success" style="padding:16px;margin:16px 0">';
    echo '<h2 style="margin:0 0 8px">Reparación completada</h2>';
    echo '<p><strong>' . (int) $updated . '</strong> preguntas actualizadas con categoría.</p>';
    echo '<p><strong>' . (int) $skipped . '</strong> preguntas omitidas.</p>';

    if ( ! empty( $errors ) ) {
        echo '<h3>Errores</h3>';
        echo '<ul style="list-style:disc;padding-left:20px">';
        foreach ( $errors as $error ) {
            echo '<li>' . esc_html( $error ) . '</li>';
        }
        echo '</ul>';
    }

    echo '<p><a href="' . esc_url( admin_url( 'admin.php?page=quantia-faqs' ) ) . '" class="button button-primary">Ir al listado de preguntas</a></p>';
    echo '</div>';
    exit;
} );