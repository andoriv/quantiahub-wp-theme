<?php
/**
 * FAQ administration form.
 *
 * This module renders the create and edit form for a single FAQ. The category
 * field is mandatory because the frontend ignores any question without a
 * category. The location field maps the question to the help center or to a
 * specific product page. Access is gated through manage_options.
 *
 * Formulario de administración de FAQ.
 *
 * Este módulo renderiza el formulario de creación y edición de una pregunta.
 * El campo de categoría es obligatorio porque el frontend ignora cualquier
 * pregunta sin categoría. El campo de ubicación mapea la pregunta al centro de
 * ayuda o a la página de un producto específico. El acceso está limitado por
 * manage_options.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_render_faq_form( string $action ): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have permission to access this page.', 'quantia-hub' ) );
    }

    $id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;

    if ( 'edit' === $action && ! $id ) {
        echo '<div class="wrap"><p>' . esc_html__( 'Invalid ID.', 'quantia-hub' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=quantia-faqs' ) ) . '">' . esc_html__( 'Back', 'quantia-hub' ) . '</a></p></div>';
        return;
    }

    $question = '';
    $answer   = '';
    $category = '';
    $product  = '';
    $visible  = true;
    $order    = 0;

    if ( 'edit' === $action && $id ) {
        $post = get_post( $id );
        if ( ! $post || 'faq' !== $post->post_type ) {
            echo '<div class="wrap"><p>' . esc_html__( 'Question not found.', 'quantia-hub' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=quantia-faqs' ) ) . '">' . esc_html__( 'Back', 'quantia-hub' ) . '</a></p></div>';
            return;
        }
        $question = $post->post_title;
        $answer   = $post->post_content;
        $product  = get_post_meta( $id, 'faq_product', true );
        $visible  = '0' !== get_post_meta( $id, 'faq_visible', true );
        $order    = (int) $post->menu_order;

        $terms = get_the_terms( $id, 'categoria_faq' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $category = $terms[0]->name;
        }
    }

    $existing_terms = get_terms( array( 'taxonomy' => 'categoria_faq', 'hide_empty' => false ) );
    $existing_names = array();
    if ( ! is_wp_error( $existing_terms ) ) {
        foreach ( $existing_terms as $t ) {
            $existing_names[] = $t->name;
        }
    }

    $product_labels   = quantia_faq_product_labels();
    $back_url         = admin_url( 'admin.php?page=quantia-faqs' );
    $categories_url   = admin_url( 'admin.php?page=quantia-faqs-categorias' );
    $has_any_category = ! empty( $existing_names );
    ?>
    <div class="wrap">
        <h1><?php echo 'new' === $action ? esc_html__( 'Add question', 'quantia-hub' ) : esc_html__( 'Edit question', 'quantia-hub' ); ?></h1>
        <a href="<?php echo esc_url( $back_url ); ?>">&larr; <?php esc_html_e( 'Back to list', 'quantia-hub' ); ?></a>

        <?php if ( isset( $_GET['error'] ) && 'required' === $_GET['error'] ) : ?>
            <div class="notice notice-error">
                <p><?php esc_html_e( 'Question, answer and category are mandatory.', 'quantia-hub' ); ?></p>
            </div>
        <?php endif; ?>

        <?php if ( ! $has_any_category ) : ?>
            <div class="notice notice-warning">
                <p>
                    <strong><?php esc_html_e( 'No categories created yet.', 'quantia-hub' ); ?></strong>
                    <a href="<?php echo esc_url( $categories_url ); ?>"><?php esc_html_e( 'Create a category', 'quantia-hub' ); ?></a>
                </p>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="max-width:820px;margin-top:20px">
            <?php wp_nonce_field( 'quantia_faq_save', 'quantia_faq_nonce' ); ?>
            <input type="hidden" name="action" value="quantia_faq_save">
            <input type="hidden" name="faq_id" value="<?php echo (int) $id; ?>">

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="faq_question">
                            <?php esc_html_e( 'Question', 'quantia-hub' ); ?> <span style="color:#d63638">*</span>
                        </label>
                    </th>
                    <td>
                        <input type="text" id="faq_question" name="faq_question" class="regular-text"
                               value="<?php echo esc_attr( $question ); ?>" required maxlength="300">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="faq_answer">
                            <?php esc_html_e( 'Answer', 'quantia-hub' ); ?> <span style="color:#d63638">*</span>
                        </label>
                    </th>
                    <td>
                        <textarea id="faq_answer" name="faq_answer" rows="6" class="large-text" required><?php echo esc_textarea( $answer ); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="faq_category">
                            <?php esc_html_e( 'Category', 'quantia-hub' ); ?> <span style="color:#d63638">*</span>
                        </label>
                    </th>
                    <td>
                        <select id="faq_category" name="faq_category" required style="min-width:280px" <?php disabled( ! $has_any_category ); ?>>
                            <option value="">
                                <?php echo $has_any_category ? esc_html__( 'Select a category', 'quantia-hub' ) : esc_html__( 'Create a category first', 'quantia-hub' ); ?>
                            </option>
                            <?php foreach ( $existing_names as $name ) : ?>
                                <option value="<?php echo esc_attr( $name ); ?>" <?php selected( $category, $name ); ?>>
                                    <?php echo esc_html( $name ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Location', 'quantia-hub' ); ?></th>
                    <td>
                        <select id="faq_product" name="faq_product">
                            <?php foreach ( $product_labels as $key => $label ) : ?>
                                <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $product, $key ); ?>>
                                    <?php echo esc_html( $label ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Visible', 'quantia-hub' ); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="faq_visible" value="1" <?php checked( $visible ); ?>>
                            <?php esc_html_e( 'Show on the site', 'quantia-hub' ); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Order', 'quantia-hub' ); ?></th>
                    <td>
                        <input type="number" id="faq_order" name="faq_order" class="small-text"
                               value="<?php echo (int) $order; ?>" min="0" step="1">
                    </td>
                </tr>
            </table>

            <p class="submit">
                <button type="submit" class="button button-primary" <?php disabled( ! $has_any_category ); ?>>
                    <?php echo 'new' === $action ? esc_html__( 'Add question', 'quantia-hub' ) : esc_html__( 'Save changes', 'quantia-hub' ); ?>
                </button>
                <a href="<?php echo esc_url( $back_url ); ?>" class="button"><?php esc_html_e( 'Cancel', 'quantia-hub' ); ?></a>
            </p>
        </form>
    </div>
    <?php
}