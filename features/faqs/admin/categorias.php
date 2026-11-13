<?php
/**
 * FAQ categories administration.
 *
 * This module renders the category management screen and handles the create,
 * update, toggle and delete operations. Deletion uses the standard WordPress
 * behavior with a JavaScript confirmation. Access is gated through the
 * manage_options capability.
 *
 * Administración de categorías de FAQ.
 *
 * Este módulo renderiza la pantalla de gestión de categorías y maneja las
 * operaciones de creación, actualización, visibilidad y eliminación. La
 * eliminación usa el comportamiento estándar de WordPress con una confirmación
 * de JavaScript. El acceso está limitado por la capability manage_options.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_render_faqs_categorias(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $base_url = admin_url( 'admin.php?page=quantia-faqs-categorias' );

    $edit_id = isset( $_GET['edit'] ) ? (int) $_GET['edit'] : 0;
    $editing = null;

    if ( $edit_id ) {
        $term = get_term( $edit_id, 'categoria_faq' );
        if ( $term && ! is_wp_error( $term ) ) {
            $editing = $term;
        }
    }

    $terms = get_terms( array( 'taxonomy' => 'categoria_faq', 'hide_empty' => false ) );
    if ( is_wp_error( $terms ) ) {
        $terms = array();
    }

    $saved   = isset( $_GET['saved'] )   && '1' === $_GET['saved'];
    $deleted = isset( $_GET['deleted'] ) && '1' === $_GET['deleted'];
    $toggled = isset( $_GET['toggled'] ) && '1' === $_GET['toggled'];
    $error   = isset( $_GET['error'] )   ? sanitize_text_field( wp_unslash( $_GET['error'] ) ) : '';

    $total_cats  = count( $terms );
    $hidden_cats = 0;
    foreach ( $terms as $t ) {
        if ( ! quantia_faq_category_is_visible( (int) $t->term_id ) ) {
            $hidden_cats++;
        }
    }
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e( 'FAQ categories', 'quantia-hub' ); ?></h1>
        <hr class="wp-header-end">

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Category saved.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>
        <?php if ( $deleted ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Category deleted.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>
        <?php if ( $toggled ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Visibility updated.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>
        <?php if ( 'name' === $error ) : ?>
            <div class="notice notice-error"><p><?php esc_html_e( 'The category name is mandatory.', 'quantia-hub' ); ?></p></div>
        <?php elseif ( 'exists' === $error ) : ?>
            <div class="notice notice-error"><p><?php esc_html_e( 'A category with that name already exists.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>

        <p style="margin:12px 0;color:#50575e">
            <?php
            printf(
                /* translators: %d: total categories */
                esc_html__( '%d categories.', 'quantia-hub' ),
                (int) $total_cats
            );
            ?>
            <?php if ( $hidden_cats > 0 ) : ?>
                <span style="color:#996800">
                    <?php
                    printf(
                        /* translators: %d: hidden categories */
                        esc_html__( '%d hidden, their questions are not shown.', 'quantia-hub' ),
                        (int) $hidden_cats
                    );
                    ?>
                </span>
            <?php endif; ?>
        </p>

        <div style="display:grid;grid-template-columns:1fr 1.6fr;gap:30px;margin-top:20px;align-items:start">

            <div class="card" style="margin:0;max-width:none">
                <h2><?php echo $editing ? esc_html__( 'Edit category', 'quantia-hub' ) : esc_html__( 'Add category', 'quantia-hub' ); ?></h2>

                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <?php wp_nonce_field( 'quantia_faq_cat_save', 'quantia_faq_cat_nonce' ); ?>
                    <input type="hidden" name="action" value="quantia_faq_cat_save">
                    <input type="hidden" name="cat_id" value="<?php echo $editing ? (int) $editing->term_id : 0; ?>">

                    <table class="form-table" role="presentation">
                        <tr>
                            <th scope="row">
                                <label for="cat_name">
                                    <?php esc_html_e( 'Name', 'quantia-hub' ); ?> <span style="color:#d63638">*</span>
                                </label>
                            </th>
                            <td>
                                <input type="text" id="cat_name" name="cat_name" class="regular-text"
                                       value="<?php echo $editing ? esc_attr( $editing->name ) : ''; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="cat_slug"><?php esc_html_e( 'Slug', 'quantia-hub' ); ?></label></th>
                            <td>
                                <input type="text" id="cat_slug" name="cat_slug" class="regular-text"
                                       value="<?php echo $editing ? esc_attr( $editing->slug ) : ''; ?>"
                                       placeholder="<?php esc_attr_e( 'Generated automatically when left empty', 'quantia-hub' ); ?>">
                            </td>
                        </tr>
                    </table>

                    <p>
                        <button type="submit" class="button button-primary">
                            <?php echo $editing ? esc_html__( 'Save changes', 'quantia-hub' ) : esc_html__( 'Add category', 'quantia-hub' ); ?>
                        </button>
                        <?php if ( $editing ) : ?>
                            <a href="<?php echo esc_url( $base_url ); ?>" class="button"><?php esc_html_e( 'Cancel', 'quantia-hub' ); ?></a>
                        <?php endif; ?>
                    </p>
                </form>
            </div>

            <div>
                <h2><?php esc_html_e( 'Existing categories', 'quantia-hub' ); ?></h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Name', 'quantia-hub' ); ?></th>
                            <th style="width:150px"><?php esc_html_e( 'Slug', 'quantia-hub' ); ?></th>
                            <th style="width:80px"><?php esc_html_e( 'Questions', 'quantia-hub' ); ?></th>
                            <th style="width:100px"><?php esc_html_e( 'Visibility', 'quantia-hub' ); ?></th>
                            <th style="width:200px"><?php esc_html_e( 'Actions', 'quantia-hub' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( empty( $terms ) ) : ?>
                            <tr><td colspan="5"><?php esc_html_e( 'No categories yet.', 'quantia-hub' ); ?></td></tr>
                        <?php else : ?>
                            <?php foreach ( $terms as $term ) :
                                $term_id    = (int) $term->term_id;
                                $is_visible = quantia_faq_category_is_visible( $term_id );

                                $edit_url   = add_query_arg( 'edit', $term_id, $base_url );
                                $delete_url = wp_nonce_url(
                                    add_query_arg( array( 'action' => 'quantia_faq_cat_delete', 'id' => $term_id ), admin_url( 'admin-post.php' ) ),
                                    'quantia_faq_cat_delete_' . $term_id
                                );
                                $toggle_url = wp_nonce_url(
                                    add_query_arg( array( 'action' => 'quantia_faq_cat_toggle', 'id' => $term_id ), admin_url( 'admin-post.php' ) ),
                                    'quantia_faq_cat_toggle_' . $term_id
                                );
                            ?>
                                <tr>
                                    <td><strong><?php echo esc_html( $term->name ); ?></strong></td>
                                    <td><code><?php echo esc_html( $term->slug ); ?></code></td>
                                    <td><?php echo (int) $term->count; ?></td>
                                    <td>
                                        <?php if ( $is_visible ) : ?>
                                            <span style="color:#00a32a;font-weight:600"><?php esc_html_e( 'Visible', 'quantia-hub' ); ?></span>
                                        <?php else : ?>
                                            <span style="color:#d63638;font-weight:600"><?php esc_html_e( 'Hidden', 'quantia-hub' ); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit', 'quantia-hub' ); ?></a> |
                                        <a href="<?php echo esc_url( $toggle_url ); ?>" style="<?php echo $is_visible ? 'color:#996800' : 'color:#00a32a'; ?>">
                                            <?php echo $is_visible ? esc_html__( 'Hide', 'quantia-hub' ) : esc_html__( 'Show', 'quantia-hub' ); ?>
                                        </a> |
                                        <a href="<?php echo esc_url( $delete_url ); ?>" style="color:#b32d2e"
                                           onclick="return confirm('<?php echo esc_js( __( 'Delete this category? Its questions will remain without category and will not be shown on the site.', 'quantia-hub' ) ); ?>')">
                                            <?php esc_html_e( 'Delete', 'quantia-hub' ); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
}

add_action( 'admin_post_quantia_faq_cat_save', 'quantia_handle_faq_cat_save' );
function quantia_handle_faq_cat_save(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    if ( ! isset( $_POST['quantia_faq_cat_nonce'] ) || ! wp_verify_nonce( $_POST['quantia_faq_cat_nonce'], 'quantia_faq_cat_save' ) ) {
        wp_die( __( 'Invalid security token.', 'quantia-hub' ) );
    }

    $id = isset( $_POST['cat_id'] ) ? (int) $_POST['cat_id'] : 0;

    $name = isset( $_POST['cat_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_name'] ) ) : '';
    $slug = isset( $_POST['cat_slug'] ) ? sanitize_title( wp_unslash( $_POST['cat_slug'] ) ) : '';

    if ( '' === $name ) {
        wp_safe_redirect( admin_url( 'admin.php?page=quantia-faqs-categorias&error=name' ) );
        exit;
    }

    $base_url = admin_url( 'admin.php?page=quantia-faqs-categorias' );

    if ( $id ) {
        $existing = get_term_by( 'name', $name, 'categoria_faq' );
        if ( $existing && (int) $existing->term_id !== $id ) {
            wp_safe_redirect( add_query_arg( 'error', 'exists', $base_url ) );
            exit;
        }
        $args = array( 'name' => $name );
        if ( $slug ) {
            $args['slug'] = $slug;
        }
        $result = wp_update_term( $id, 'categoria_faq', $args );
    } else {
        if ( term_exists( $name, 'categoria_faq' ) ) {
            wp_safe_redirect( add_query_arg( 'error', 'exists', $base_url ) );
            exit;
        }
        $args = array();
        if ( $slug ) {
            $args['slug'] = $slug;
        }
        $result = wp_insert_term( $name, 'categoria_faq', $args );
    }

    if ( is_wp_error( $result ) ) {
        wp_safe_redirect( add_query_arg( 'error', 'exists', $base_url ) );
        exit;
    }

    if ( ! $id && isset( $result['term_id'] ) ) {
        update_term_meta( (int) $result['term_id'], QUANTIA_FAQ_CAT_VISIBLE_META, '1' );
    }

    wp_safe_redirect( admin_url( 'admin.php?page=quantia-faqs-categorias&saved=1' ) );
    exit;
}

add_action( 'admin_post_quantia_faq_cat_toggle', 'quantia_handle_faq_cat_toggle' );
function quantia_handle_faq_cat_toggle(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    $id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
    if ( ! $id ) {
        wp_die( __( 'Invalid ID.', 'quantia-hub' ) );
    }
    check_admin_referer( 'quantia_faq_cat_toggle_' . $id );

    $current = get_term_meta( $id, QUANTIA_FAQ_CAT_VISIBLE_META, true );
    update_term_meta( $id, QUANTIA_FAQ_CAT_VISIBLE_META, ( '0' === $current ) ? '1' : '0' );

    wp_safe_redirect( admin_url( 'admin.php?page=quantia-faqs-categorias&toggled=1' ) );
    exit;
}

add_action( 'admin_post_quantia_faq_cat_delete', 'quantia_handle_faq_cat_delete' );
function quantia_handle_faq_cat_delete(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to perform this action.', 'quantia-hub' ) );
    }
    $id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
    if ( ! $id ) {
        wp_die( __( 'Invalid ID.', 'quantia-hub' ) );
    }
    check_admin_referer( 'quantia_faq_cat_delete_' . $id );

    wp_delete_term( $id, 'categoria_faq' );

    wp_safe_redirect( admin_url( 'admin.php?page=quantia-faqs-categorias&deleted=1' ) );
    exit;
}