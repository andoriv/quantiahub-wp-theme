<?php
/**
 * Pantalla de configuración en wp-admin.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'quantia_visibility_admin_menu', 20 );
function quantia_visibility_admin_menu(): void {
    $parent = 'quantia-settings';
    global $admin_page_hooks;
    if ( ! isset( $admin_page_hooks[ $parent ] ) ) {
        $parent = 'options-general.php';
    }

    add_submenu_page(
        $parent,
        __( 'Visibilidad de secciones', 'quantia-hub' ),
        __( 'Visibilidad', 'quantia-hub' ),
        'manage_options',
        'quantia-visibility',
        'quantia_visibility_render_page'
    );
}

add_action( 'admin_init', 'quantia_visibility_register_settings' );
function quantia_visibility_register_settings(): void {
    register_setting(
        'quantia_visibility_group',
        QUANTIA_VISIBILITY_OPTION,
        array(
            'type'              => 'array',
            'description'       => __( 'Claves de elementos ocultos del sitio público.', 'quantia-hub' ),
            'sanitize_callback' => 'quantia_visibility_sanitize',
            'default'           => array(),
            'show_in_rest'      => false,
        )
    );
}

/**
 * Sanitiza la lista de claves ocultas contra el registro.
 *
 * @param mixed $input Valor recibido de options.php.
 * @return string[]
 */
function quantia_visibility_sanitize( $input ): array {
    if ( ! is_array( $input ) ) {
        return array();
    }
    $valid = array_keys( quantia_visibility_registry() );
    $clean = array();
    foreach ( $input as $key ) {
        if ( is_string( $key ) && in_array( $key, $valid, true ) ) {
            $clean[] = $key;
        }
    }
    return array_values( array_unique( $clean ) );
}

function quantia_visibility_render_page(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'No tienes permisos para acceder a esta página.', 'quantia-hub' ) );
    }

    $registry = quantia_visibility_registry();
    $hidden   = quantia_hidden_sections();

    $groups = array();
    foreach ( $registry as $key => $item ) {
        $groups[ $item['group'] ][ $key ] = $item;
    }

    $all_hidden = count( $registry ) > 0 && count( $hidden ) === count( $registry );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Visibilidad de secciones', 'quantia-hub' ); ?></h1>

        <p class="description" style="max-width:820px">
            <?php esc_html_e( 'Marca los elementos que quieras ocultar al público. Las páginas y productos ocultos devuelven 404, desaparecen de los menús de navegación y se excluyen de los sitemaps. Las secciones ocultas dejan de renderizarse en la plantilla y desaparecen de los menús que apunten a su ancla. El contenido no se borra: el editor puede seguir trabajándolo desde wp-admin.', 'quantia-hub' ); ?>
        </p>

        <?php settings_errors( 'quantia_visibility_group' ); ?>

        <?php if ( $all_hidden ) : ?>
            <div class="notice notice-warning" style="margin:16px 0">
                <p>
                    <strong><?php esc_html_e( 'Atención:', 'quantia-hub' ); ?></strong>
                    <?php esc_html_e( 'Todos los elementos están ocultos. Esto afecta gravemente la navegación del sitio.', 'quantia-hub' ); ?>
                </p>
            </div>
        <?php endif; ?>

        <form method="post" action="options.php" novalidate>
            <?php settings_fields( 'quantia_visibility_group' ); ?>

            <input type="hidden" name="<?php echo esc_attr( QUANTIA_VISIBILITY_OPTION ); ?>" value="" />

            <?php foreach ( $groups as $group_label => $items ) : ?>
                <h2 style="margin-top:28px"><?php echo esc_html( $group_label ); ?></h2>
                <table class="form-table" role="presentation">
                    <tbody>
                    <?php foreach ( $items as $key => $item ) :
                        $is_hidden = in_array( $key, $hidden, true );
                        $field_id  = 'quantia-vis-' . sanitize_html_class( $key );
                        $desc_id   = $field_id . '-desc';
                        ?>
                        <tr>
                            <th scope="row">
                                <?php echo esc_html( $item['label'] ); ?>
                            </th>
                            <td>
                                <label for="<?php echo esc_attr( $field_id ); ?>">
                                    <input
                                        type="checkbox"
                                        id="<?php echo esc_attr( $field_id ); ?>"
                                        name="<?php echo esc_attr( QUANTIA_VISIBILITY_OPTION ); ?>[]"
                                        value="<?php echo esc_attr( $key ); ?>"
                                        aria-describedby="<?php echo esc_attr( $desc_id ); ?>"
                                        <?php checked( $is_hidden ); ?>
                                    />
                                    <?php esc_html_e( 'Ocultar al público', 'quantia-hub' ); ?>
                                </label>

                                <p id="<?php echo esc_attr( $desc_id ); ?>" class="description">
                                    <?php echo esc_html( $item['description'] ); ?>
                                    <?php if ( 'page' === $item['type'] || 'producto' === $item['type'] ) : ?>
                                        &mdash;
                                        <?php
                                        printf(
                                            esc_html__( 'Ruta: /%s/', 'quantia-hub' ),
                                            esc_html( $item['slug'] )
                                        );
                                        ?>
                                    <?php endif; ?>
                                </p>

                                <?php if ( $is_hidden ) : ?>
                                    <p class="description" style="color:#b32d2e">
                                        <?php esc_html_e( 'Actualmente oculto al público.', 'quantia-hub' ); ?>
                                    </p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>

            <?php submit_button( __( 'Guardar cambios', 'quantia-hub' ) ); ?>
        </form>
    </div>
    <?php
}

add_filter( 'display_post_states', 'quantia_visibility_post_state', 10, 2 );
function quantia_visibility_post_state( $states, $post ) {
    if ( ! $post instanceof WP_Post ) {
        return $states;
    }
    if ( ! in_array( $post->post_type, array( 'page', 'producto' ), true ) ) {
        return $states;
    }
    if ( quantia_visibility_hidden_key( $post->post_name, $post->post_type ) ) {
        $states['quantia_hidden'] = __( 'Oculta al público', 'quantia-hub' );
    }
    return $states;
}