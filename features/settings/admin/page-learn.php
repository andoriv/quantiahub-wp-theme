<?php
/**
 * Página de configuración de los conceptos del Aprende.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

function quantia_settings_page_learn(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'No tienes permiso para acceder a esta página.', 'quantia-hub' ) );
    }

    $rows  = quantia_settings_rows( 'learn_concepts' );
    $saved = isset( $_GET['saved'] ) && '1' === $_GET['saved'];

    $visual_options = array(
        'riesgo'             => __( 'Riesgo, líneas', 'quantia-hub' ),
        'diversificacion'    => __( 'Diversificación, dona', 'quantia-hub' ),
        'interes-compuesto'  => __( 'Interés compuesto, barras', 'quantia-hub' ),
        'ahorro-primero'     => __( 'Ahorro primero, barras horizontales', 'quantia-hub' ),
        'ahorro-vs-inversion'=> __( 'Ahorro versus inversión, líneas', 'quantia-hub' ),
    );
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Conceptos del Aprende', 'quantia-hub' ); ?></h1>

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Cambios guardados.', 'quantia-hub' ); ?></p></div>
        <?php endif; ?>

        <p style="max-width:820px;color:#50575e">
            <?php esc_html_e( 'Cada fila corresponde a una tarjeta de la sección Conceptos de la página Aprende. El identificador se usa como ancla de navegación. La etiqueta corta se usa en el menú superior de conceptos. Los puntos se escriben uno por línea.', 'quantia-hub' ); ?>
        </p>

        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <?php wp_nonce_field( 'quantia_settings_learn_concepts' ); ?>
            <input type="hidden" name="action" value="quantia_settings_save_learn_concepts">

            <div id="quantia-learn-rows">
                <?php foreach ( $rows as $i => $row ) :
                    $points_text = is_array( $row['points'] ?? null ) ? implode( "\n", $row['points'] ) : '';
                ?>
                    <div class="card" style="max-width:none;margin-bottom:16px;padding:16px">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                            <strong style="font-size:14px">
                                <?php
                                printf(
                                    /* translators: %d: número de fila */
                                    esc_html__( 'Concepto %d', 'quantia-hub' ),
                                    (int) ( $i + 1 )
                                );
                                ?>
                            </strong>
                            <button type="button" class="button quantia-remove-concept"><?php esc_html_e( 'Eliminar', 'quantia-hub' ); ?></button>
                        </div>

                        <table class="form-table">
                            <tr>
                                <th style="width:180px"><label><?php esc_html_e( 'Identificador', 'quantia-hub' ); ?></label></th>
                                <td>
                                    <input type="text" name="rows[<?php echo (int) $i; ?>][concept_id]" value="<?php echo esc_attr( $row['concept_id'] ?? '' ); ?>" class="regular-text" required>
                                    <p class="description"><?php esc_html_e( 'Minúsculas, sin espacios. Se usa como ancla (#riesgo, #diversificacion).', 'quantia-hub' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Etiqueta corta', 'quantia-hub' ); ?></label></th>
                                <td><input type="text" name="rows[<?php echo (int) $i; ?>][label]" value="<?php echo esc_attr( $row['label'] ?? '' ); ?>" class="regular-text" required></td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Eyebrow', 'quantia-hub' ); ?></label></th>
                                <td><input type="text" name="rows[<?php echo (int) $i; ?>][eyebrow]" value="<?php echo esc_attr( $row['eyebrow'] ?? '' ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Fundamentos, Estrategia, Hábito', 'quantia-hub' ); ?>"></td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Título', 'quantia-hub' ); ?></label></th>
                                <td><input type="text" name="rows[<?php echo (int) $i; ?>][title]" value="<?php echo esc_attr( $row['title'] ?? '' ); ?>" class="large-text" required></td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Descripción', 'quantia-hub' ); ?></label></th>
                                <td><textarea name="rows[<?php echo (int) $i; ?>][description]" rows="3" class="large-text" required><?php echo esc_textarea( $row['description'] ?? '' ); ?></textarea></td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Visual', 'quantia-hub' ); ?></label></th>
                                <td>
                                    <select name="rows[<?php echo (int) $i; ?>][visual]">
                                        <?php foreach ( $visual_options as $key => $label ) : ?>
                                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $row['visual'] ?? 'riesgo', $key ); ?>>
                                                <?php echo esc_html( $label ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th><label><?php esc_html_e( 'Puntos', 'quantia-hub' ); ?></label></th>
                                <td>
                                    <textarea name="rows[<?php echo (int) $i; ?>][points_text]" rows="5" class="large-text" placeholder="<?php esc_attr_e( 'Un punto por línea', 'quantia-hub' ); ?>"><?php echo esc_textarea( $points_text ); ?></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>

            <p><button type="button" class="button" id="quantia-add-concept"><?php esc_html_e( 'Añadir concepto', 'quantia-hub' ); ?></button></p>
            <p class="submit"><button type="submit" class="button button-primary"><?php esc_html_e( 'Guardar cambios', 'quantia-hub' ); ?></button></p>
        </form>
    </div>
    <script>
    document.getElementById('quantia-add-concept').addEventListener('click', function () {
        var wrap = document.getElementById('quantia-learn-rows');
        var i = wrap.children.length;
        var card = document.createElement('div');
        card.className = 'card';
        card.style.cssText = 'max-width:none;margin-bottom:16px;padding:16px';
        card.innerHTML = '<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">'
            + '<strong style="font-size:14px">Concepto ' + (i + 1) + '</strong>'
            + '<button type="button" class="button quantia-remove-concept">Eliminar</button></div>'
            + '<table class="form-table">'
            + '<tr><th style="width:180px">Identificador</th><td><input type="text" name="rows[' + i + '][concept_id]" class="regular-text" required></td></tr>'
            + '<tr><th>Etiqueta corta</th><td><input type="text" name="rows[' + i + '][label]" class="regular-text" required></td></tr>'
            + '<tr><th>Eyebrow</th><td><input type="text" name="rows[' + i + '][eyebrow]" class="regular-text"></td></tr>'
            + '<tr><th>Título</th><td><input type="text" name="rows[' + i + '][title]" class="large-text" required></td></tr>'
            + '<tr><th>Descripción</th><td><textarea name="rows[' + i + '][description]" rows="3" class="large-text" required></textarea></td></tr>'
            + '<tr><th>Visual</th><td><select name="rows[' + i + '][visual]">'
            + '<option value="riesgo">Riesgo, líneas</option>'
            + '<option value="diversificacion">Diversificación, dona</option>'
            + '<option value="interes-compuesto">Interés compuesto, barras</option>'
            + '<option value="ahorro-primero">Ahorro primero, barras horizontales</option>'
            + '<option value="ahorro-vs-inversion">Ahorro versus inversión, líneas</option>'
            + '</select></td></tr>'
            + '<tr><th>Puntos</th><td><textarea name="rows[' + i + '][points_text]" rows="5" class="large-text" placeholder="Un punto por línea"></textarea></td></tr>'
            + '</table>';
        wrap.appendChild(card);
    });
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('quantia-remove-concept')) {
            e.target.closest('.card').remove();
        }
    });
    </script>
    <?php
}