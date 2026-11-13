<?php
/**
 * Role recovery script.
 *
 * This script restores the administrator role and reassigns any user account
 * that was left with a broken role after the cleanup of the custom permission
 * roles. It must be accessed from the admin area while logged in. The script
 * runs once on admin_init when the quantia_fix_roles query parameter is
 * present, writes a report to the page and exits. Delete it after use.
 *
 * Script de recuperación de roles.
 *
 * Este script restaura el rol de administrador y reasigna cualquier cuenta de
 * usuario que haya quedado con un rol roto tras la limpieza de los roles
 * personalizados de permisos. Debe accederse desde el área de administración
 * estando autenticado. El script se ejecuta una vez en admin_init cuando el
 * parámetro de consulta quantia_fix_roles está presente, escribe un reporte en
 * la página y termina. Elimínalo después de usarlo.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', function () {
    if ( ! isset( $_GET['quantia_fix_roles'] ) || '1' !== $_GET['quantia_fix_roles'] ) {
        return;
    }

    $report = array(
        'administrator_role_recreated' => false,
        'administrator_caps_added'     => 0,
        'current_user_fixed'           => '',
        'other_users_fixed'            => array(),
    );

    if ( ! get_role( 'administrator' ) ) {
        add_role( 'administrator', 'Administrator', array() );
        $report['administrator_role_recreated'] = true;
    }

    $administrator = get_role( 'administrator' );

    if ( $administrator ) {
        $standard_caps = array(
            'switch_themes', 'edit_themes', 'activate_plugins', 'edit_plugins',
            'edit_users', 'edit_files', 'manage_options', 'moderate_comments',
            'manage_categories', 'manage_links', 'upload_files', 'import',
            'unfiltered_html', 'edit_posts', 'edit_others_posts',
            'edit_published_posts', 'publish_posts', 'edit_pages', 'read',
            'level_10', 'level_9', 'level_8', 'level_7', 'level_6', 'level_5',
            'level_4', 'level_3', 'level_2', 'level_1', 'level_0',
            'edit_others_pages', 'edit_published_pages', 'publish_pages',
            'delete_pages', 'delete_others_pages', 'delete_published_pages',
            'delete_posts', 'delete_others_posts', 'delete_published_posts',
            'delete_private_posts', 'edit_private_posts', 'read_private_posts',
            'delete_private_pages', 'edit_private_pages', 'read_private_pages',
            'delete_users', 'create_users', 'unfiltered_upload', 'edit_dashboard',
            'update_plugins', 'delete_plugins', 'install_plugins', 'update_themes',
            'install_themes', 'update_core', 'list_users', 'remove_users',
            'promote_users', 'edit_theme_options', 'delete_themes', 'export',
        );
        foreach ( $standard_caps as $cap ) {
            if ( ! $administrator->has_cap( $cap ) ) {
                $administrator->add_cap( $cap );
                $report['administrator_caps_added']++;
            }
        }
    }

    $current_user = wp_get_current_user();
    if ( $current_user && $current_user->exists() ) {
        $current_roles = (array) $current_user->roles;
        if ( ! in_array( 'administrator', $current_roles, true ) ) {
            $current_user->set_role( 'administrator' );
            $report['current_user_fixed'] = $current_user->user_login;
        }
    }

    $all_users = get_users();
    foreach ( $all_users as $u ) {
        $roles = (array) $u->roles;
        $has_broken_role = false;
        foreach ( $roles as $role_slug ) {
            if ( 0 === strpos( $role_slug, 'qh_' ) && ! get_role( $role_slug ) ) {
                $has_broken_role = true;
                break;
            }
        }
        if ( $has_broken_role ) {
            $u->set_role( 'administrator' );
            $report['other_users_fixed'][] = $u->user_login;
        }
    }

    // Clear the user cache for the current session so the caps are fresh.
    clean_user_cache( $current_user->ID );
    wp_set_current_user( $current_user->ID );

    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Role recovery</title>';
    echo '<style>body{font-family:system-ui,sans-serif;max-width:720px;margin:40px auto;padding:0 20px;line-height:1.6}';
    echo '.notice{padding:20px;border-left:4px solid #46b450;background:#f0fff4;border-radius:4px}';
    echo 'h2{margin-top:0;color:#00a32a}ul{padding-left:20px}';
    echo '.button{display:inline-block;padding:10px 20px;background:#2271b1;color:#fff;text-decoration:none;border-radius:4px;margin-top:16px;margin-right:8px}';
    echo '</style></head><body>';

    echo '<div class="notice">';
    echo '<h2>Role recovery completed</h2>';
    echo '<ul>';
    echo '<li>Administrator role recreated: <strong>' . ( $report['administrator_role_recreated'] ? 'yes' : 'no, it was present' ) . '</strong></li>';
    echo '<li>Standard capabilities added: <strong>' . (int) $report['administrator_caps_added'] . '</strong></li>';
    if ( $report['current_user_fixed'] ) {
        echo '<li>Current user reassigned to administrator: <strong>' . esc_html( $report['current_user_fixed'] ) . '</strong></li>';
    } else {
        echo '<li>Current user was already an administrator.</li>';
    }
    if ( ! empty( $report['other_users_fixed'] ) ) {
        echo '<li>Other users with broken roles reassigned to administrator: <strong>' . esc_html( implode( ', ', $report['other_users_fixed'] ) ) . '</strong></li>';
    }
    echo '</ul>';
    echo '<p>You can now access the FAQ administration.</p>';
    echo '<p><a href="' . esc_url( admin_url( 'admin.php?page=quantia-faqs' ) ) . '" class="button">Go to FAQ</a>';
    echo '<a href="' . esc_url( admin_url() ) . '" class="button">Go to dashboard</a></p>';
    echo '<p style="color:#666;font-size:14px"><strong>Important:</strong> delete <code>fix-roles.php</code> from the server now that the repair is done.</p>';
    echo '</div>';

    echo '</body></html>';
    exit;
} );