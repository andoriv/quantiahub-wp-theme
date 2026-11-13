<?php
/**
 * Permissions cleanup.
 *
 * This script removes the custom roles and capabilities that belonged to the
 * permissions module. It runs only when the quantia_cleanup_permissions query
 * parameter is present on an admin request and the current user has the
 * manage_options capability. It is idempotent, so it can be executed multiple
 * times without side effects. Delete the file after the first successful run.
 *
 * Limpieza de permisos.
 *
 * Este script elimina los roles y capabilities personalizados que pertenecían
 * al módulo de permisos. Se ejecuta solo cuando el parámetro de consulta
 * quantia_cleanup_permissions está presente en una petición del admin y el
 * usuario actual tiene la capability manage_options. Es idempotente, por lo
 * que puede ejecutarse varias veces sin efectos secundarios. Elimina el
 * archivo después de la primera ejecución exitosa.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', function () {
    if ( ! isset( $_GET['quantia_cleanup_permissions'] ) || '1' !== $_GET['quantia_cleanup_permissions'] ) {
        return;
    }
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sin permisos.' );
    }

    $custom_roles = array( 'qh_admin', 'qh_rb', 'qh_dt' );
    $removed_roles = array();
    foreach ( $custom_roles as $role ) {
        if ( get_role( $role ) ) {
            remove_role( $role );
            $removed_roles[] = $role;
        }
    }

    $administrator = get_role( 'administrator' );
    $removed_caps = 0;

    if ( $administrator ) {
        $all_caps = array_keys( $administrator->capabilities );
        foreach ( $all_caps as $cap ) {
            if ( 0 === strpos( $cap, 'qh_' ) ) {
                $administrator->remove_cap( $cap );
                $removed_caps++;
            }
        }
    }

    delete_option( 'quantia_roles_seeded' );
    delete_option( 'quantia_roles_caps_hash' );
    delete_option( 'quantia_deletion_log' );

    echo '<div class="notice notice-success" style="padding:16px;margin:16px 0">';
    echo '<h2 style="margin:0 0 8px">Limpieza completada</h2>';
    echo '<p><strong>Roles eliminados:</strong> ' . ( empty( $removed_roles ) ? 'ninguno' : esc_html( implode( ', ', $removed_roles ) ) ) . '</p>';
    echo '<p><strong>Capabilities eliminadas del administrator:</strong> ' . (int) $removed_caps . '</p>';
    echo '<p><strong>Opciones eliminadas:</strong> quantia_roles_seeded, quantia_roles_caps_hash, quantia_deletion_log</p>';
    echo '<p><a href="' . esc_url( admin_url( 'admin.php?page=quantia-faqs' ) ) . '" class="button button-primary">Ir al listado de preguntas</a></p>';
    echo '</div>';
    exit;
} );