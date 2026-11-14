<?php
defined( 'ABSPATH' ) || exit;

$inquiry_types = array( 'Cuenta corriente', 'Kuara y transferencias', 'Tarjeta Visa Débito', 'Inversiones', 'Jubilación', 'Cuenta de planilla', 'Otro' );
$nonce         = wp_create_nonce( 'quantia_contact' );
$ajax_url      = admin_url( 'admin-ajax.php' );
?>

<form data-contact-form action="<?php echo esc_url( $ajax_url ); ?>" method="post" novalidate
    class="border-t-2 border-ink pt-8" aria-labelledby="contacto-titulo"
    data-ajax-url="<?php echo esc_url( $ajax_url ); ?>"
    data-nonce="<?php echo esc_attr( $nonce ); ?>">

    <div data-contact-form-inner>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

            <div>
                <label for="contact-name" class="block text-xs font-semibold text-ink">Nombre <span class="text-qh">*</span></label>
                <input id="contact-name" name="name" type="text" required
                    class="mt-2 w-full rounded-xl border border-ink/15 bg-white px-4 py-3 text-sm text-ink outline-none transition-colors focus:border-qh">
            </div>

            <div>
                <label for="contact-email" class="block text-xs font-semibold text-ink">Email <span class="text-qh">*</span></label>
                <input id="contact-email" name="email" type="email" required
                    class="mt-2 w-full rounded-xl border border-ink/15 bg-white px-4 py-3 text-sm text-ink outline-none transition-colors focus:border-qh">
            </div>

            <div>
                <label for="contact-company" class="block text-xs font-semibold text-ink">Empresa</label>
                <input id="contact-company" name="company" type="text"
                    class="mt-2 w-full rounded-xl border border-ink/15 bg-white px-4 py-3 text-sm text-ink outline-none transition-colors focus:border-qh">
            </div>

            <div>
                <label for="contact-role" class="block text-xs font-semibold text-ink">Cargo en la empresa</label>
                <input id="contact-role" name="role" type="text"
                    class="mt-2 w-full rounded-xl border border-ink/15 bg-white px-4 py-3 text-sm text-ink outline-none transition-colors focus:border-qh">
            </div>

            <div>
                <label for="contact-phone" class="block text-xs font-semibold text-ink">Teléfono <span class="text-qh">*</span></label>
                <input id="contact-phone" name="phone" type="tel" required
                    class="mt-2 w-full rounded-xl border border-ink/15 bg-white px-4 py-3 text-sm text-ink outline-none transition-colors focus:border-qh">
            </div>

            <div>
                <label for="contact-new" class="block text-xs font-semibold text-ink">¿Es usted nuevo cliente? <span class="text-qh">*</span></label>
                <select id="contact-new" name="newClient" required
                    class="mt-2 w-full rounded-xl border border-ink/15 bg-white px-4 py-3 text-sm text-ink outline-none transition-colors focus:border-qh">
                    <option value="">Seleccionar</option>
                    <option value="Sí">Sí</option>
                    <option value="No">No</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label for="contact-type" class="block text-xs font-semibold text-ink">Tipo de consulta <span class="text-qh">*</span></label>
                <select id="contact-type" name="inquiryType" required
                    class="mt-2 w-full rounded-xl border border-ink/15 bg-white px-4 py-3 text-sm text-ink outline-none transition-colors focus:border-qh">
                    <option value="">Seleccionar</option>
                    <?php foreach ( $inquiry_types as $type ) : ?>
                        <option value="<?php echo esc_attr( $type ); ?>"><?php echo esc_html( $type ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label for="contact-message" class="block text-xs font-semibold text-ink">Mensaje <span class="text-qh">*</span></label>
                <textarea id="contact-message" name="message" rows="5" required minlength="10"
                    placeholder="Cuéntanos en qué podemos ayudarte"
                    class="mt-2 w-full rounded-xl border border-ink/15 bg-white px-4 py-3 text-sm text-ink outline-none transition-colors placeholder:text-ink/35 focus:border-qh"></textarea>
            </div>
        </div>

        <p data-contact-error hidden class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></p>

        <div class="mt-6 flex flex-wrap items-center gap-4">
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-full bg-qh px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-qh-600 disabled:cursor-not-allowed disabled:opacity-60">
                <?php echo quantia_icon( 'send', 'h-4 w-4' ); ?>
                Enviar consulta
            </button>
            <p class="text-xs leading-relaxed text-ink/50">
                Al enviar aceptas el tratamiento de tus datos conforme al aviso de privacidad.
            </p>
        </div>
    </div>

    <div data-contact-success hidden class="rounded-2xl border border-qh/30 bg-qh-50 p-8">
        <?php echo quantia_icon( 'check-circle', 'h-6 w-6 text-qh' ); ?>
        <h3 class="mt-4 font-display text-xl font-semibold text-ink">Consulta enviada</h3>
        <p class="mt-2 max-w-md text-sm leading-relaxed text-ink/70">
            Gracias por escribirnos. Un ejecutivo de Quantia te contactará en horario de atención.
            Si tu caso es urgente, escríbenos por WhatsApp.
        </p>
        <button type="button" data-contact-reset
            class="mt-6 rounded-full border border-ink/15 bg-white px-6 py-2.5 text-sm font-semibold text-ink transition-colors hover:border-ink/40">
            Enviar otra consulta
        </button>
    </div>
</form>