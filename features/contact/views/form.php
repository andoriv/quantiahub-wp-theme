<?php
/**
 * Formulario de contacto.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

$nonce    = wp_create_nonce( 'quantia_contact' );
$ajax_url = admin_url( 'admin-ajax.php' );

$is_turnstile = (
    function_exists( 'quantia_turnstile_is_configured' ) &&
    quantia_turnstile_is_configured()
);

$site_key = $is_turnstile
    ? quantia_turnstile_site_key()
    : '';
?>

<?php if ( ! $is_turnstile ) : ?>
    <div
        class="rounded-2xl border border-amber-200 bg-amber-50
               p-5 text-sm text-amber-900"
        role="alert"
    >
        <strong>
            <?php esc_html_e(
                'Formulario no disponible temporalmente.',
                'quantia-hub'
            ); ?>
        </strong>

        <br>

        <?php esc_html_e(
            'Escríbenos por WhatsApp o correo mientras lo reactivamos.',
            'quantia-hub'
        ); ?>
    </div>

    <?php return; ?>
<?php endif; ?>

<form
    data-contact-form
    action="<?php echo esc_url( $ajax_url ); ?>"
    method="post"
    novalidate
    class="border-t-2 border-ink pt-8"
    aria-labelledby="contacto-titulo"
    data-ajax-url="<?php echo esc_url( $ajax_url ); ?>"
    data-nonce="<?php echo esc_attr( $nonce ); ?>"
>
    <!-- Acción requerida por admin-ajax.php -->
    <input
        type="hidden"
        name="action"
        value="quantia_contact"
    >

    <!-- Nonce requerido por check_ajax_referer() -->
    <input
        type="hidden"
        name="nonce"
        value="<?php echo esc_attr( $nonce ); ?>"
    >

    <!-- Tiempo inicial para el time-trap -->
    <input
        type="hidden"
        name="form_started_at"
        value="<?php echo esc_attr( (string) time() ); ?>"
    >

    <div data-contact-form-inner>

        <!-- Honeypot contra bots -->
        <div
            aria-hidden="true"
            style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;"
        >
            <label for="contact-website">
                Website
            </label>

            <input
                id="contact-website"
                name="website"
                type="text"
                tabindex="-1"
                autocomplete="off"
            >
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

            <div>
                <label
                    for="contact-first-name"
                    class="block text-xs font-semibold text-ink"
                >
                    <?php esc_html_e(
                        'Nombre',
                        'quantia-hub'
                    ); ?>

                    <span class="text-qh">*</span>
                </label>

                <input
                    id="contact-first-name"
                    name="firstName"
                    type="text"
                    required
                    minlength="2"
                    maxlength="80"
                    autocomplete="given-name"
                    class="mt-2 w-full rounded-xl border border-ink/15
                           bg-white px-4 py-3 text-sm text-ink outline-none
                           transition-colors focus:border-qh"
                >
            </div>

            <div>
                <label
                    for="contact-last-name"
                    class="block text-xs font-semibold text-ink"
                >
                    <?php esc_html_e(
                        'Apellido',
                        'quantia-hub'
                    ); ?>

                    <span class="text-qh">*</span>
                </label>

                <input
                    id="contact-last-name"
                    name="lastName"
                    type="text"
                    required
                    minlength="2"
                    maxlength="80"
                    autocomplete="family-name"
                    class="mt-2 w-full rounded-xl border border-ink/15
                           bg-white px-4 py-3 text-sm text-ink outline-none
                           transition-colors focus:border-qh"
                >
            </div>

            <div class="sm:col-span-2">
                <label
                    for="contact-email"
                    class="block text-xs font-semibold text-ink"
                >
                    <?php esc_html_e(
                        'Correo electrónico',
                        'quantia-hub'
                    ); ?>

                    <span class="text-qh">*</span>
                </label>

                <input
                    id="contact-email"
                    name="email"
                    type="email"
                    required
                    maxlength="180"
                    autocomplete="email"
                    class="mt-2 w-full rounded-xl border border-ink/15
                           bg-white px-4 py-3 text-sm text-ink outline-none
                           transition-colors focus:border-qh"
                >
            </div>

            <div class="sm:col-span-2">
                <label
                    for="contact-phone"
                    class="block text-xs font-semibold text-ink"
                >
                    <?php esc_html_e(
                        'Teléfono',
                        'quantia-hub'
                    ); ?>

                    <span class="text-qh">*</span>
                </label>

                <input
                    id="contact-phone"
                    name="phone"
                    type="tel"
                    required
                    minlength="7"
                    maxlength="25"
                    autocomplete="tel"
                    inputmode="tel"
                    pattern="[0-9\s\-\+\(\)\.]{7,25}"
                    class="mt-2 w-full rounded-xl border border-ink/15
                           bg-white px-4 py-3 text-sm text-ink outline-none
                           transition-colors focus:border-qh"
                >
            </div>

            <div class="sm:col-span-2">
                <label
                    for="contact-message"
                    class="block text-xs font-semibold text-ink"
                >
                    <?php esc_html_e(
                        'Comentarios',
                        'quantia-hub'
                    ); ?>

                    <span class="text-qh">*</span>
                </label>

                <textarea
                    id="contact-message"
                    name="message"
                    rows="5"
                    required
                    minlength="10"
                    maxlength="3000"
                    placeholder="<?php esc_attr_e(
                        'Cuéntanos en qué podemos ayudarte',
                        'quantia-hub'
                    ); ?>"
                    class="mt-2 w-full rounded-xl border border-ink/15
                           bg-white px-4 py-3 text-sm text-ink outline-none
                           transition-colors placeholder:text-ink/35
                           focus:border-qh"
                ></textarea>
            </div>

        </div>

        <div class="mt-6">
            <div
                class="cf-turnstile"
                data-sitekey="<?php echo esc_attr( $site_key ); ?>"
                data-theme="light"
                data-language="es"
                data-size="normal"
            ></div>
        </div>

        <p
            data-contact-error
            hidden
            role="alert"
            aria-live="assertive"
            class="mt-4 rounded-xl border border-red-200 bg-red-50
                   px-4 py-3 text-sm text-red-700"
        ></p>

        <div class="mt-6 flex flex-wrap items-center gap-4">
            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-full
                       bg-qh px-6 py-3 text-sm font-semibold text-white
                       transition-colors hover:bg-qh-600
                       disabled:cursor-not-allowed disabled:opacity-60"
            >
                <?php echo quantia_icon(
                    'send',
                    'h-4 w-4'
                ); ?>

                <span data-contact-submit-text>
                    <?php esc_html_e(
                        'Enviar consulta',
                        'quantia-hub'
                    ); ?>
                </span>
            </button>

            <p class="text-xs leading-relaxed text-ink/50">
                <?php esc_html_e(
                    'Al enviar aceptas el tratamiento de tus datos conforme al aviso de privacidad.',
                    'quantia-hub'
                ); ?>
            </p>
        </div>
    </div>

    <div
        data-contact-success
        hidden
        role="status"
        aria-live="polite"
        class="rounded-2xl border border-qh/30 bg-qh-50 p-8"
    >
        <?php echo quantia_icon(
            'check-circle',
            'h-6 w-6 text-qh'
        ); ?>

        <h3 class="mt-4 font-display text-xl font-semibold text-ink">
            <?php esc_html_e(
                'Consulta enviada',
                'quantia-hub'
            ); ?>
        </h3>

        <p class="mt-2 max-w-md text-sm leading-relaxed text-ink/70">
            <?php esc_html_e(
                'Gracias por escribirnos. Un ejecutivo de Quantia te contactará en horario de atención. Si tu caso es urgente, escríbenos por WhatsApp.',
                'quantia-hub'
            ); ?>
        </p>

        <button
            type="button"
            data-contact-reset
            class="mt-6 rounded-full border border-ink/15 bg-white
                   px-6 py-2.5 text-sm font-semibold text-ink
                   transition-colors hover:border-ink/40"
        >
            <?php esc_html_e(
                'Enviar otra consulta',
                'quantia-hub'
            ); ?>
        </button>
    </div>
</form>