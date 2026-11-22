(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var cards = document.querySelectorAll('[data-image-key]');
        if (!cards.length || typeof wp === 'undefined' || !wp.media) {
            return;
        }

        cards.forEach(function (card) {
            var idInput  = card.querySelector('[data-image-id]');
            var urlInput = card.querySelector('[data-image-url]');
            var preview  = card.querySelector('[data-image-preview]');
            var pick     = card.querySelector('[data-image-pick]');
            var clear    = card.querySelector('[data-image-clear]');
            var reset    = card.querySelector('[data-image-reset]');
            var status   = card.querySelector('[data-image-status]');
            var frame    = null;

            function setPreview(src) {
                if (!preview) { return; }
                preview.innerHTML = '';
                if (src) {
                    var img = document.createElement('img');
                    img.src = src;
                    img.alt = '';
                    img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block';
                    preview.appendChild(img);
                } else {
                    var span = document.createElement('span');
                    span.style.cssText = 'color:#8c8f94;font-size:13px';
                    span.textContent = 'Sin imagen';
                    preview.appendChild(span);
                }
            }

            if (pick) {
                pick.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (!frame) {
                        frame = wp.media({
                            title: 'Seleccionar imagen',
                            button: { text: 'Usar esta imagen' },
                            library: { type: 'image' },
                            multiple: false
                        });
                        frame.on('select', function () {
                            var att = frame.state().get('selection').first().toJSON();
                            if (idInput)  { idInput.value = att.id || ''; }
                            if (urlInput) { urlInput.value = ''; }
                            var src = att.sizes && att.sizes.medium ? att.sizes.medium.url : att.url;
                            setPreview(src);
                            if (status) { status.textContent = 'Biblioteca de WordPress'; }
                        });
                    }
                    frame.open();
                });
            }

            if (clear) {
                clear.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (idInput)  { idInput.value = ''; }
                    if (urlInput) { urlInput.value = ''; }
                    setPreview('');
                    if (status) { status.textContent = ''; }
                });
            }

            if (reset) {
                reset.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (idInput)  { idInput.value = ''; }
                    if (urlInput) { urlInput.value = ''; }
                    setPreview('');
                    if (status) { status.textContent = 'Se usará la imagen predeterminada al guardar.'; }
                });
            }

            if (urlInput) {
                urlInput.addEventListener('change', function () {
                    var v = urlInput.value.trim();
                    if (v) {
                        if (idInput) { idInput.value = ''; }
                        setPreview(v);
                        if (status) { status.textContent = 'URL externa'; }
                    } else {
                        setPreview('');
                        if (status) { status.textContent = ''; }
                    }
                });
            }
        });
    });
})();