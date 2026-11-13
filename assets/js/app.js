/**
 * Quantia Hub — main JS.
 * Vanilla, sin dependencias. Motion helpers se añaden en Bloques siguientes.
 */

const $  = ( sel, ctx = document ) => ctx.querySelector( sel );
const $$ = ( sel, ctx = document ) => Array.from( ctx.querySelectorAll( sel ) );

/* ---------- Mobile menu ---------- */
function initMobileMenu() {
    const openBtn  = $( '[data-quantia-mobile-open]' );
    const closeBtn = $( '[data-quantia-mobile-close]' );
    const panel    = $( '[data-quantia-mobile-panel]' );
    if ( ! openBtn || ! panel ) return;

    const open = () => {
        panel.hidden = false;
        openBtn.setAttribute( 'aria-expanded', 'true' );
        document.body.style.overflow = 'hidden';
    };
    const close = () => {
        panel.hidden = true;
        openBtn.setAttribute( 'aria-expanded', 'false' );
        document.body.style.overflow = '';
    };

    openBtn.addEventListener( 'click', open );
    closeBtn?.addEventListener( 'click', close );
    document.addEventListener( 'keydown', ( e ) => {
        if ( e.key === 'Escape' && ! panel.hidden ) close();
    } );
}

/* ---------- Products dropdown ---------- */
function initProductDropdown() {
    const wrapper = $( '[data-quantia-dropdown]' );
    if ( ! wrapper ) return;

    const trigger = $( '[data-quantia-dropdown-trigger]', wrapper );
    const panel   = $( '[data-quantia-dropdown-panel]', wrapper );
    const icon    = $( '[data-quantia-dropdown-icon]', wrapper );
    if ( ! trigger || ! panel ) return;

    const toggle = ( open ) => {
        panel.hidden = ! open;
        trigger.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
        if ( icon ) icon.style.transform = open ? 'rotate(180deg)' : '';
    };

    trigger.addEventListener( 'click', ( e ) => {
        e.stopPropagation();
        toggle( panel.hidden );
    } );

    document.addEventListener( 'click', ( e ) => {
        if ( ! wrapper.contains( e.target ) ) toggle( false );
    } );

    document.addEventListener( 'keydown', ( e ) => {
        if ( e.key === 'Escape' ) toggle( false );
    } );
}

/* ---------- Analytics stub ---------- */
function initAnalytics() {
    window.dataLayer = window.dataLayer || [];

    const push = ( event, payload = {} ) => {
        window.dataLayer.push( {
            event,
            page: window.location.pathname,
            timestamp: new Date().toISOString(),
            ...payload,
        } );
    };

    push( 'page_view', { label: document.title } );

        $$( '[data-quantia-app-download]' ).forEach( ( el ) => {
        el.addEventListener( 'click', () => {
            push( 'app_download_click', {
                product:  el.dataset.product  || 'general',
                location: el.dataset.location || 'hero',
            } );
        } );
    } );

    $$( '[data-quantia-whatsapp]' ).forEach( ( el ) => {
        el.addEventListener( 'click', () => {
            push( 'whatsapp_click', {
                product:  el.dataset.product  || 'general',
                location: el.dataset.location || 'support',
            } );
        } );
    } );

    $$( '[data-quantia-fee-sheet]' ).forEach( ( el ) => {
        el.addEventListener( 'click', () => {
            push( 'fee_sheet_download', { location: 'fees' } );
        } );
    } );
}

/* ---------- Boot ---------- */
function boot() {
    initMobileMenu();
    initProductDropdown();
    initAnalytics();
}

if ( document.readyState === 'loading' ) {
    document.addEventListener( 'DOMContentLoaded', boot );
} else {
    boot();
}

