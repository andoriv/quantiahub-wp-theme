<?php
/**
 * FAQ administration list.
 *
 * This module renders the FAQ list with the search field, the category filter,
 * the product filter, the visibility filter and the row actions. Every row
 * action is available to any user with the manage_options capability because
 * the FAQ administration now relies on the native WordPress capability system
 * after the permission module was removed.
 *
 * Listado de administración de FAQ.
 *
 * Este módulo renderiza el listado de FAQ con el campo de búsqueda, el filtro
 * por categoría, el filtro por producto, el filtro por visibilidad y las
 * acciones por fila. Cada acción por fila está disponible para cualquier
 * usuario con la capability manage_options porque la administración de FAQ
 * ahora depende del sistema nativo de capabilities de WordPress tras la
 * eliminación del módulo de permisos.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_render_faq_list(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $search         = isset( $_GET['s'] )       ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
    $filter_product = isset( $_GET['product'] ) ? sanitize_key( $_GET['product'] ) : '';
    $filter_cat     = isset( $_GET['cat'] )     ? sanitize_title( $_GET['cat'] ) : '';
    $filter_visible = isset( $_GET['visible'] ) ? sanitize_key( $_GET['visible'] ) : '';

    $args = array(
        'post_type'      => 'faq',
        'post_status'    => array( 'publish', 'draft' ),
        'posts_per_page' => 500,
        'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
    );

    if ( '' !== $search ) {
        $args['s'] = $search;
    }

    $meta_query = array();

    if ( $filter_product ) {
        $meta_query[] = array(
            'key'     => 'faq_product',
            'value'   => $filter_product,
            'compare' => '=',
        );
    }

    if ( '1' === $filter_visible ) {
        $meta_query[] = array(
            'relation' => 'OR',
            array( 'key' => 'faq_visible', 'value' => '0', 'compare' => '!=' ),
            array( 'key' => 'faq_visible', 'compare' => 'NOT EXISTS' ),
        );
    } elseif ( '0' === $filter_visible ) {
        $meta_query[] = array(
            'key'     => 'faq_visible',
            'value'   => '0',
            'compare' => '=',
        );
    }

    if ( ! empty( $meta_query ) ) {
        $args['meta_query'] = $meta_query;
    }

    if ( $filter_cat ) {
        if ( '__none__' === $filter_cat ) {
            $args['tax_query'] = array(
                array( 'taxonomy' => 'categoria_faq', 'operator' => 'NOT EXISTS' ),
            );
        } else {
            $args['tax_query'] = array(
                array( 'taxonomy' => 'categoria_faq', 'field' => 'slug', 'terms' => $filter_cat ),
            );
        }
    }

    $faqs           = get_posts( $args );
    $product_labels = quantia_faq_product_labels();
    $list_url       = admin_url( 'admin.php?page=quantia-faqs' );
    $new_url        = add_query_arg( 'action', 'new', $list_url );

    $terms = get_terms( array( 'taxonomy' => 'categoria_faq', 'hide_empty' => false ) );
    if ( is_wp_error( $terms ) ) {
        $terms = array();
    }

    $has_filters         = ( '' !== $search || '' !== $filter_product || '' !== $filter_cat || '' !== $filter_visible );
    $uncategorized_count = quantia_count_uncategorized_faqs();
    $uncategorized_url   = add_query_arg( 'cat', '__none__', $list_url );

    $saved   = isset( $_GET['saved'] )   && '1' === $_GET['saved'];
    $deleted = isset( $_GET['deleted'] ) && '1' === $_GET['deleted'];
    $toggled = isset( $_GET['toggled'] ) && '1' === $_GET['toggled'];
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php esc_html_e( 'Questions', 'quantia-hub' ); ?></h1>
        <a href="<?php echo esc_url( $new_url ); ?>" class="page-title-action">
            <?php esc_html_e( 'Add new', 'quantia-hub' ); ?>
        </a>
        <hr class="wp-header-end">

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Question saved.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>
        <?php if ( $deleted ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Question deleted.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>
        <?php if ( $toggled ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Visibility updated.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>

        <?php if ( $uncategorized_count > 0 ) : ?>
            <div class="notice notice-warning">
                <p>
                    <?php
                    printf(
                        /* translators: %d: count of uncategorized questions */
                        esc_html__( '%d questions without category. They will not be shown on the site.', 'quantia-hub' ),
                        (int) $uncategorized_count
                    );
                    ?>
                    <a href="<?php echo esc_url( $uncategorized_url ); ?>"><?php esc_html_e( 'View only those', 'quantia-hub' ); ?></a>
                </p>
            </div>
        <?php endif; ?>

        <form method="get" style="margin-top:16px;padding:14px 16px;background:#fff;border:1px solid #c3c4c7;border-radius:4px;">
            <input type="hidden" name="page" value="quantia-faqs">
            <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center">
                <input type="search" name="s" value="<?php echo esc_attr( $search ); ?>"
                       placeholder="<?php esc_attr_e( 'Search by question or answer', 'quantia-hub' ); ?>"
                       style="min-width:280px;height:32px;padding:0 8px">

                <select name="cat" style="min-width:180px;height:32px">
                    <option value=""><?php esc_html_e( 'All categories', 'quantia-hub' ); ?></option>
                    <?php if ( $uncategorized_count > 0 ) : ?>
                        <option value="__none__" <?php selected( $filter_cat, '__none__' ); ?>>
                            <?php
                            printf(
                                /* translators: %d: count of uncategorized questions */
                                esc_html__( 'Without category (%d)', 'quantia-hub' ),
                                (int) $uncategorized_count
                            );
                            ?>
                        </option>
                    <?php endif; ?>
                    <?php foreach ( $terms as $term ) :
                        $is_visible = quantia_faq_category_is_visible( (int) $term->term_id );
                    ?>
                        <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $filter_cat, $term->slug ); ?>>
                            <?php echo esc_html( $term->name ); ?> (<?php echo (int) $term->count; ?>)<?php echo $is_visible ? '' : ' · ' . esc_html__( 'hidden', 'quantia-hub' ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="product" style="min-width:180px;height:32px">
                    <option value=""><?php esc_html_e( 'All locations', 'quantia-hub' ); ?></option>
                    <?php foreach ( $product_labels as $key => $label ) : ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $filter_product, $key ); ?>>
                            <?php echo esc_html( $label ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="visible" style="min-width:150px;height:32px">
                    <option value=""><?php esc_html_e( 'Any visibility', 'quantia-hub' ); ?></option>
                    <option value="1" <?php selected( $filter_visible, '1' ); ?>><?php esc_html_e( 'Visible only', 'quantia-hub' ); ?></option>
                    <option value="0" <?php selected( $filter_visible, '0' ); ?>><?php esc_html_e( 'Hidden only', 'quantia-hub' ); ?></option>
                </select>

                <button type="submit" class="button button-primary"><?php esc_html_e( 'Filter', 'quantia-hub' ); ?></button>
                <?php if ( $has_filters ) : ?>
                    <a href="<?php echo esc_url( $list_url ); ?>" class="button"><?php esc_html_e( 'Clear filters', 'quantia-hub' ); ?></a>
                <?php endif; ?>
            </div>
            <p style="margin:10px 0 0;color:#666;font-size:13px">
                <?php if ( $has_filters ) : ?>
                    <?php
                    printf(
                        /* translators: %d: results count */
                        esc_html__( '%d results with current filters.', 'quantia-hub' ),
                        (int) count( $faqs )
                    );
                    ?>
                <?php else : ?>
                    <?php
                    printf(
                        /* translators: %d: total count */
                        esc_html__( '%d questions in total.', 'quantia-hub' ),
                        (int) count( $faqs )
                    );
                    ?>
                <?php endif; ?>
            </p>
        </form>

        <table class="wp-list-table widefat fixed striped" style="margin-top:12px">
            <thead>
                <tr>
                    <th style="width:60px"><?php esc_html_e( 'Order', 'quantia-hub' ); ?></th>
                    <th><?php esc_html_e( 'Question', 'quantia-hub' ); ?></th>
                    <th style="width:200px"><?php esc_html_e( 'Category', 'quantia-hub' ); ?></th>
                    <th style="width:170px"><?php esc_html_e( 'Location', 'quantia-hub' ); ?></th>
                    <th style="width:100px"><?php esc_html_e( 'Visibility', 'quantia-hub' ); ?></th>
                    <th style="width:220px"><?php esc_html_e( 'Actions', 'quantia-hub' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( empty( $faqs ) ) : ?>
                    <tr>
                        <td colspan="6">
                            <?php if ( $has_filters ) : ?>
                                <?php esc_html_e( 'No questions match the current filters.', 'quantia-hub' ); ?>
                                <a href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'Clear filters', 'quantia-hub' ); ?></a>.
                            <?php else : ?>
                                <?php esc_html_e( 'No questions yet.', 'quantia-hub' ); ?>
                                <a href="<?php echo esc_url( $new_url ); ?>"><?php esc_html_e( 'Add the first one', 'quantia-hub' ); ?></a>.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ( $faqs as $faq ) :
                        $id       = $faq->ID;
                        $product  = get_post_meta( $id, 'faq_product', true );
                        $visible  = '0' !== get_post_meta( $id, 'faq_visible', true );
                        $terms_p  = get_the_terms( $id, 'categoria_faq' );

                        $has_cat     = $terms_p && ! is_wp_error( $terms_p );
                        $cat_name    = $has_cat ? $terms_p[0]->name : '';
                        $cat_visible = $has_cat ? quantia_faq_category_is_visible( (int) $terms_p[0]->term_id ) : false;

                        $edit_url   = add_query_arg( array( 'action' => 'edit', 'id' => $id ), $list_url );
                        $delete_url = wp_nonce_url(
                            add_query_arg( array( 'action' => 'delete', 'id' => $id ), $list_url ),
                            'quantia_faq_delete_' . $id
                        );
                        $toggle_url = wp_nonce_url(
                            add_query_arg( array( 'action' => 'quantia_faq_toggle', 'id' => $id ), admin_url( 'admin-post.php' ) ),
                            'quantia_faq_toggle_' . $id
                        );

                        $product_label = $product_labels[ $product ] ?? $product_labels[''];
                    ?>
                        <tr>
                            <td><?php echo (int) $faq->menu_order; ?></td>
                            <td>
                                <strong>
                                    <a href="<?php echo esc_url( $edit_url ); ?>"><?php echo esc_html( $faq->post_title ); ?></a>
                                </strong>
                                <div style="color:#666;font-size:12px;margin-top:4px">
                                    <?php echo esc_html( wp_trim_words( $faq->post_content, 14 ) ); ?>
                                </div>
                                <?php if ( 'draft' === $faq->post_status ) : ?>
                                    <span style="color:#996800;font-weight:600"><?php esc_html_e( 'Draft', 'quantia-hub' ); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ( ! $has_cat ) : ?>
                                    <span style="color:#d63638;font-weight:600"><?php esc_html_e( 'No category', 'quantia-hub' ); ?></span>
                                    <div style="color:#d63638;font-size:11px"><?php esc_html_e( 'Not shown on the site', 'quantia-hub' ); ?></div>
                                <?php else : ?>
                                    <?php echo esc_html( $cat_name ); ?>
                                    <?php if ( ! $cat_visible ) : ?>
                                        <div style="color:#996800;font-weight:600;font-size:11px"><?php esc_html_e( 'Hidden category', 'quantia-hub' ); ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html( $product_label ); ?></td>
                            <td>
                                <?php if ( $visible ) : ?>
                                    <span style="color:#00a32a;font-weight:600"><?php esc_html_e( 'Visible', 'quantia-hub' ); ?></span>
                                <?php else : ?>
                                    <span style="color:#d63638;font-weight:600"><?php esc_html_e( 'Hidden', 'quantia-hub' ); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit', 'quantia-hub' ); ?></a> |
                                <a href="<?php echo esc_url( $toggle_url ); ?>"><?php echo $visible ? esc_html__( 'Hide', 'quantia-hub' ) : esc_html__( 'Show', 'quantia-hub' ); ?></a> |
                                <a href="<?php echo esc_url( $delete_url ); ?>" style="color:#b32d2e"
                                   onclick="return confirm('<?php echo esc_js( __( 'Delete this question permanently?', 'quantia-hub' ) ); ?>')">
                                    <?php esc_html_e( 'Delete', 'quantia-hub' ); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}