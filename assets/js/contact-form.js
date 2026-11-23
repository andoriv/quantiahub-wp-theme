document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll(
        '[data-contact-form]'
    );

    forms.forEach(function (form) {
        const formInner = form.querySelector(
            '[data-contact-form-inner]'
        );

        const successBox = form.querySelector(
            '[data-contact-success]'
        );

        const errorBox = form.querySelector(
            '[data-contact-error]'
        );

        const resetButton = form.querySelector(
            '[data-contact-reset]'
        );

        const submitButton = form.querySelector(
            'button[type="submit"]'
        );

        const submitText = form.querySelector(
            '[data-contact-submit-text]'
        );

        if (
            !formInner ||
            !successBox ||
            !errorBox ||
            !submitButton
        ) {
            console.error(
                'Quantia Contact: la estructura del formulario está incompleta.'
            );

            return;
        }

        const originalSubmitText = submitText
            ? submitText.textContent
            : 'Enviar consulta';

        function showError(message) {
            errorBox.textContent =
                message ||
                'No fue posible enviar la consulta.';

            errorBox.hidden = false;
            errorBox.setAttribute('tabindex', '-1');
            errorBox.focus();
        }

        function clearError() {
            errorBox.textContent = '';
            errorBox.hidden = true;
            errorBox.removeAttribute('tabindex');
        }

        function resetTurnstile() {
            if (
                window.turnstile &&
                typeof window.turnstile.reset === 'function'
            ) {
                try {
                    window.turnstile.reset();
                } catch (error) {
                    console.warn(
                        'Quantia Contact: no se pudo reiniciar Turnstile.',
                        error
                    );
                }
            }
        }

        function updateStartedTime() {
            const startedInput = form.querySelector(
                'input[name="form_started_at"]'
            );

            if (startedInput) {
                startedInput.value = Math.floor(
                    Date.now() / 1000
                ).toString();
            }
        }

        function setLoading(isLoading) {
            submitButton.disabled = isLoading;

            if (isLoading) {
                submitButton.setAttribute(
                    'aria-busy',
                    'true'
                );

                if (submitText) {
                    submitText.textContent = 'Enviando...';
                }

                return;
            }

            submitButton.removeAttribute('aria-busy');

            if (submitText) {
                submitText.textContent =
                    originalSubmitText;
            }
        }

        /*
         * Actualizar el time-trap después de cargar la página.
         * Evita problemas si el HTML proviene del caché.
         */
        updateStartedTime();

        form.addEventListener(
            'submit',
            async function (event) {
                event.preventDefault();
                clearError();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const ajaxUrl =
                    form.dataset.ajaxUrl ||
                    form.getAttribute('action');

                if (!ajaxUrl) {
                    showError(
                        'No se encontró la dirección de envío.'
                    );

                    return;
                }

                const turnstileResponse =
                    form.querySelector(
                        'input[name="cf-turnstile-response"]'
                    );

                if (
                    !turnstileResponse ||
                    !turnstileResponse.value.trim()
                ) {
                    showError(
                        'Completa la validación de seguridad antes de enviar.'
                    );

                    return;
                }

                const formData = new FormData(form);

                /*
                 * Garantizar que WordPress conozca la acción AJAX.
                 */
                formData.set(
                    'action',
                    'quantia_contact'
                );

                /*
                 * Garantizar que el nonce se envíe.
                 */
                const nonceInput = form.querySelector(
                    'input[name="nonce"]'
                );

                const nonce =
                    nonceInput?.value ||
                    form.dataset.nonce ||
                    '';

                if (!nonce) {
                    showError(
                        'No se encontró el código de seguridad. Recarga la página.'
                    );

                    return;
                }

                formData.set('nonce', nonce);

                setLoading(true);

                try {
                    const response = await fetch(
                        ajaxUrl,
                        {
                            method: 'POST',
                            body: formData,
                            credentials: 'same-origin',
                            headers: {
                                'X-Requested-With':
                                    'XMLHttpRequest'
                            }
                        }
                    );

                    const responseText =
                        await response.text();

                    let result;

                    try {
                        result = JSON.parse(
                            responseText
                        );
                    } catch (jsonError) {
                        console.error(
                            'Quantia Contact: respuesta no JSON:',
                            responseText
                        );

                        throw new Error(
                            'El servidor devolvió una respuesta inesperada.'
                        );
                    }

                    if (
                        !response.ok ||
                        !result.success
                    ) {
                        const message =
                            result?.data?.message ||
                            'No fue posible enviar la consulta.';

                        throw new Error(message);
                    }

                    form.reset();

                    formInner.hidden = true;
                    successBox.hidden = false;
                    successBox.setAttribute(
                        'tabindex',
                        '-1'
                    );

                    successBox.focus();

                    resetTurnstile();
                    updateStartedTime();
                } catch (error) {
                    console.error(
                        'Quantia Contact: error de envío:',
                        error
                    );

                    showError(
                        error.message ||
                        'Ocurrió un error inesperado.'
                    );

                    resetTurnstile();
                    updateStartedTime();
                } finally {
                    setLoading(false);
                }
            }
        );

        if (resetButton) {
            resetButton.addEventListener(
                'click',
                function () {
                    form.reset();
                    clearError();

                    successBox.hidden = true;
                    formInner.hidden = false;

                    resetTurnstile();
                    updateStartedTime();

                    const firstInput =
                        form.querySelector(
                            'input[name="firstName"]'
                        );

                    if (firstInput) {
                        firstInput.focus();
                    }
                }
            );
        }
    });
});