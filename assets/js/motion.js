/**
 * Quantia Hub motion primitives.
 *
 * Helpers de movimiento del tema. Los textos visibles al usuario se resuelven
 * desde QuantiaConfig.i18n para responder al idioma del dispositivo. Los
 * valores de respaldo están en español.
 */

const prefersReducedMotion = () =>
    window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

const qhI18n = window.QuantiaConfig?.i18n || {};
const qhTr = ( key, fallback ) => qhI18n[ key ] || fallback;

/* ---------- Parallax ---------- */
function initParallax() {
    const nodes = Array.from( document.querySelectorAll( '[data-parallax]' ) );
    if ( ! nodes.length ) return;

    const items = nodes.map( ( el ) => ( {
        el,
        speed: parseFloat( el.dataset.parallaxSpeed || '0.2' ),
        rotate: parseFloat( el.dataset.parallaxRotate || '0' ),
    } ) );

    const update = () => {
        if ( prefersReducedMotion() ) {
            items.forEach( ( i ) => { i.el.style.transform = ''; } );
            return;
        }
        const vh = window.innerHeight;
        items.forEach( ( i ) => {
            const rect = i.el.getBoundingClientRect();
            const center = rect.top + rect.height / 2;
            const progress = ( center - vh / 2 ) / vh;
            const distance = i.speed * 160;
            const y = -progress * distance;
            const r = i.rotate ? -progress * i.rotate * 2 : 0;
            i.el.style.transform = `translate3d(0, ${ y.toFixed( 2 ) }px, 0)` + ( r ? ` rotate(${ r.toFixed( 2 ) }deg)` : '' );
            i.el.style.willChange = 'transform';
        } );
    };

    let ticking = false;
    const onScroll = () => {
        if ( ticking ) return;
        ticking = true;
        requestAnimationFrame( () => {
            update();
            ticking = false;
        } );
    };

    window.addEventListener( 'scroll', onScroll, { passive: true } );
    window.addEventListener( 'resize', update );
    update();
}

/* ---------- Reveal frame ---------- */
function initRevealFrames() {
    const nodes = Array.from( document.querySelectorAll( '[data-reveal-frame]' ) );
    if ( ! nodes.length ) return;

    const items = nodes.map( ( el ) => ( { el, child: el.firstElementChild } ) );

    const update = () => {
        const vh = window.innerHeight;
        items.forEach( ( i ) => {
            const target = i.child || i.el;
            const rect = target.getBoundingClientRect();
            const start = vh;
            const end = vh * 0.15;
            const raw = ( start - rect.top ) / ( start - end );
            const progress = Math.max( 0, Math.min( 1, raw ) );
            if ( prefersReducedMotion() ) {
                target.style.transform = '';
                target.style.borderRadius = '';
                return;
            }
            const scale = 0.88 + 0.12 * progress;
            const radius = 56 * ( 1 - progress );
            target.style.transformOrigin = 'top';
            target.style.transform = `scale(${ scale.toFixed( 4 )})`;
            target.style.borderRadius = `${ radius.toFixed( 2 ) }px`;
            target.style.overflow = 'hidden';
        } );
    };

    let ticking = false;
    const onScroll = () => {
        if ( ticking ) return;
        ticking = true;
        requestAnimationFrame( () => {
            update();
            ticking = false;
        } );
    };

    window.addEventListener( 'scroll', onScroll, { passive: true } );
    window.addEventListener( 'resize', update );
    update();
}

/* ---------- Marquee ---------- */
function initMarquees() {
    const nodes = Array.from( document.querySelectorAll( '[data-marquee]' ) );
    if ( ! nodes.length ) return;

    const items = nodes.map( ( el ) => {
        const p = el.querySelector( 'p' );
        return {
            el,
            p,
            distance: parseFloat( el.dataset.marqueeDistance || '30' ),
            reverse: 'true' === el.dataset.marqueeReverse,
        };
    } ).filter( ( i ) => i.p );

    if ( ! items.length ) return;

    const update = () => {
        if ( prefersReducedMotion() ) {
            items.forEach( ( i ) => { i.p.style.transform = ''; } );
            return;
        }
        const vh = window.innerHeight;
        items.forEach( ( i ) => {
            const rect = i.el.getBoundingClientRect();
            const center = rect.top + rect.height / 2;
            const raw = 1 - center / vh;
            const progress = Math.max( 0, Math.min( 1, raw ) );

            const from = i.reverse ? -i.distance : 0;
            const to   = i.reverse ? 0 : -i.distance;
            const x    = from + ( to - from ) * progress;

            i.p.style.transform = `translate3d(${ x.toFixed( 2 ) }%, 0, 0)`;
            i.p.style.willChange = 'transform';
        } );
    };

    let ticking = false;
    const onScroll = () => {
        if ( ticking ) return;
        ticking = true;
        requestAnimationFrame( () => {
            update();
            ticking = false;
        } );
    };

    window.addEventListener( 'scroll', onScroll, { passive: true } );
    window.addEventListener( 'resize', update );
    update();
}

/* ---------- Scroll reveal text ---------- */
function initScrollRevealText() {
    const nodes = Array.from( document.querySelectorAll( '[data-scroll-reveal-words], [data-scroll-reveal-text]' ) );
    if ( ! nodes.length ) return;

    const parseHighlight = ( raw ) => {
        if ( ! raw ) return [];
        const delimiter = raw.includes( '|' ) ? '|' : ',';
        return raw.split( delimiter ).map( ( s ) => s.trim().toLowerCase() ).filter( Boolean );
    };

    nodes.forEach( ( node ) => {
        if ( node.hasAttribute( 'data-scroll-reveal-text' ) && ! node.querySelector( '[data-word]' ) ) {
            const text = node.dataset.text || node.textContent.trim();
            const highlight = parseHighlight( node.dataset.highlight || '' );
            const words = text.split( /\s+/ );
            node.innerHTML = '';
            words.forEach( ( word ) => {
                const span = document.createElement( 'span' );
                span.setAttribute( 'data-word', '' );
                span.className = ( highlight.includes( word.toLowerCase() ) ? 'text-qh ' : '' ) + 'mr-[0.25em] inline-block';
                span.style.opacity = '0.15';
                span.textContent = word;
                node.appendChild( span );
            } );
        }
    } );

    const update = () => {
        const vh = window.innerHeight;
        nodes.forEach( ( node ) => {
            const words = node.querySelectorAll( '[data-word]' );
            if ( ! words.length ) return;
            const rect = node.getBoundingClientRect();
            const start = vh * 0.85;
            const end = vh * 0.45;
            const raw = ( start - rect.top ) / ( start - end );
            const progress = Math.max( 0, Math.min( 1, raw ) );
            const count = Math.round( progress * words.length );
            words.forEach( ( word, i ) => {
                word.style.opacity = i < count ? '1' : '0.15';
            } );
        } );
    };

    let ticking = false;
    const onScroll = () => {
        if ( ticking ) return;
        ticking = true;
        requestAnimationFrame( () => {
            update();
            ticking = false;
        } );
    };

    window.addEventListener( 'scroll', onScroll, { passive: true } );
    window.addEventListener( 'resize', update );
    update();
}

/* ---------- Slide in ---------- */
function initSlideIn() {
    const nodes = Array.from( document.querySelectorAll( '[data-slide]' ) );
    if ( ! nodes.length ) return;

    const items = nodes.map( ( el ) => ( {
        el,
        from: parseFloat( el.dataset.slideFrom || '80' ),
    } ) );

    const update = () => {
        const vh = window.innerHeight;
        items.forEach( ( i ) => {
            if ( prefersReducedMotion() ) {
                i.el.style.transform = '';
                i.el.style.opacity = '';
                return;
            }
            const rect = i.el.getBoundingClientRect();
            const start = vh;
            const end = vh * 0.4;
            const raw = ( start - rect.top ) / ( start - end );
            const progress = Math.max( 0, Math.min( 1, raw ) );
            const x = i.from * ( 1 - progress );
            i.el.style.transform = `translate3d(${ x.toFixed( 2 ) }px, 0, 0)`;
            i.el.style.opacity = ( 0.15 + 0.85 * progress ).toFixed( 3 );
        } );
    };

    let ticking = false;
    const onScroll = () => {
        if ( ticking ) return;
        ticking = true;
        requestAnimationFrame( () => {
            update();
            ticking = false;
        } );
    };

    window.addEventListener( 'scroll', onScroll, { passive: true } );
    window.addEventListener( 'resize', update );
    update();
}

/* ---------- Steps fill ---------- */
function initStepsFill() {
    const list = document.querySelector( '[data-steps-list]' );
    const fill = document.querySelector( '[data-steps-fill]' );
    if ( ! list || ! fill ) return;

    const update = () => {
        const rect = list.getBoundingClientRect();
        const vh = window.innerHeight;
        const start = vh * 0.65;
        const end = vh * 0.55;
        const raw = ( start - rect.top ) / ( rect.height + start - end );
        const progress = Math.max( 0, Math.min( 1, raw ) );
        fill.style.transform = `scaleY(${ progress.toFixed( 3 )})`;
    };

    window.addEventListener( 'scroll', update, { passive: true } );
    window.addEventListener( 'resize', update );
    update();
}

/* ---------- Demo tabs ---------- */
function initDemoTabs() {
    const container = document.querySelector( '[data-demo-tabs]' );
    if ( ! container ) return;
    const buttons = Array.from( container.querySelectorAll( '[data-demo-tab]' ) );
    const phone = document.querySelector( '[data-demo-phone]' );
    if ( ! buttons.length || ! phone ) return;

    const setActive = ( key ) => {
        buttons.forEach( ( btn ) => {
            const isActive = btn.dataset.demoTab === key;
            btn.setAttribute( 'aria-pressed', isActive ? 'true' : 'false' );

            const bar   = btn.querySelector( '[data-tab-bar]' );
            const title = btn.querySelector( '[data-tab-title]' );
            const desc  = btn.querySelector( '[data-tab-desc]' );

            if ( bar ) {
                bar.className = 'mt-4 h-0.5 shrink-0 transition-all duration-200 ' + ( isActive ? 'w-10 bg-qh' : 'w-4 bg-ink/20' );
            }
            if ( title ) {
                title.className = 'block font-display text-2xl font-bold tracking-[-0.02em] transition-colors sm:text-3xl ' + ( isActive ? 'text-ink' : 'text-ink/30 group-hover:text-ink/60' );
            }
            if ( desc ) {
                desc.classList.toggle( 'hidden', ! isActive );
            }
        } );
    };

    buttons.forEach( ( btn ) => {
        btn.addEventListener( 'click', () => setActive( btn.dataset.demoTab ) );
    } );
}

/* ---------- Pointer parallax ---------- */
function initPointerParallax() {
    if ( prefersReducedMotion() ) return;
    if ( window.matchMedia( '(pointer: coarse)' ).matches ) return;

    const roots = Array.from( document.querySelectorAll( '[data-pointer-parallax]' ) );
    if ( ! roots.length ) return;

    roots.forEach( ( root ) => {
        const layers = Array.from( root.querySelectorAll( '[data-pointer-layer]' ) );
        if ( ! layers.length ) return;

        const state = { x: 0, y: 0, tx: 0, ty: 0 };
        let raf = null;

        const render = () => {
            state.x += ( state.tx - state.x ) * 0.12;
            state.y += ( state.ty - state.y ) * 0.12;

            const closeEnough = Math.abs( state.tx - state.x ) < 0.001 && Math.abs( state.ty - state.y ) < 0.001;

            layers.forEach( ( layer ) => {
                const fx  = parseFloat( layer.dataset.pointerX || '0' );
                const fy  = parseFloat( layer.dataset.pointerY || '0' );
                const rot = parseFloat( layer.dataset.pointerRotate || '0' );

                const dx = state.x * fx;
                const dy = state.y * fy;
                const r  = state.x * rot;

                let transform = `translate3d(${ dx.toFixed( 2 ) }px, ${ dy.toFixed( 2 ) }px, 0)`;
                if ( rot ) transform += ` rotate(${ r.toFixed( 2 ) }deg)`;

                layer.style.transform = transform;
                layer.style.willChange = 'transform';
            } );

            if ( ! closeEnough ) {
                raf = requestAnimationFrame( render );
            } else {
                raf = null;
            }
        };

        const kick = () => {
            if ( raf ) return;
            raf = requestAnimationFrame( render );
        };

        root.addEventListener( 'pointermove', ( e ) => {
            if ( e.pointerType !== 'mouse' ) return;
            const rect = root.getBoundingClientRect();
            state.tx = ( e.clientX - rect.left ) / rect.width - 0.5;
            state.ty = ( e.clientY - rect.top ) / rect.height - 0.5;
            kick();
        } );

        root.addEventListener( 'pointerleave', () => {
            state.tx = 0;
            state.ty = 0;
            kick();
        } );
    } );
}

/* ---------- Fee tabs ---------- */
function initFeeTabs() {
    const tabs = Array.from( document.querySelectorAll( '[data-fee-tab]' ) );
    if ( ! tabs.length ) return;

    const panels  = Array.from( document.querySelectorAll( '[data-fee-schedule-panel]' ) );
    const countEl = document.querySelector( '[data-fee-count]' );
    const descEl  = document.querySelector( '[data-fee-description]' );

    const tabBase = 'relative flex-1 whitespace-nowrap rounded-full px-5 py-3 text-sm font-semibold transition-colors sm:flex-none sm:px-7 ';

    const setActive = ( id ) => {
        tabs.forEach( ( tab ) => {
            const isActive = tab.dataset.feeTab === id;
            tab.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
            tab.className = tabBase + ( isActive ? 'bg-ink text-white' : 'text-ink/60 hover:text-ink' );
        } );

        panels.forEach( ( panel ) => {
            panel.classList.toggle( 'hidden', panel.dataset.feeSchedulePanel !== id );
        } );

        const panel = panels.find( ( p ) => p.dataset.feeSchedulePanel === id );
        if ( ! panel ) return;

        if ( countEl ) {
            countEl.innerHTML = '<span class="text-ink">' + panel.dataset.feeScheduleRows +
                '</span> ' + qhTr( 'conceptsIn', 'conceptos en el tarifario de' ) + ' ' + panel.dataset.feeScheduleShortLabel +
                '. <span class="text-qh">' + panel.dataset.feeScheduleFree + ' ' + qhTr( 'noCost', 'sin costo' ) + '.</span>';
        }
        if ( descEl ) {
            descEl.textContent = panel.dataset.feeScheduleDescription;
        }
    };

    tabs.forEach( ( tab ) => {
        tab.addEventListener( 'click', () => setActive( tab.dataset.feeTab ) );
    } );
}

/* ---------- Fee scroll spy ---------- */
function initFeeScrollSpy() {
    const links = Array.from( document.querySelectorAll( '[data-fee-nav]' ) )
        .map( ( a ) => ( { el: a, id: a.dataset.feeNav, target: document.getElementById( a.dataset.feeNav ) } ) )
        .filter( ( l ) => l.target );

    if ( ! links.length ) return;

    const base = 'block whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition-colors lg:-ml-px lg:rounded-none lg:border-l-2 lg:bg-transparent lg:py-2.5 lg:pl-5 lg:font-display lg:text-lg ';

    const setActive = ( id ) => {
        links.forEach( ( l ) => {
            const isActive = l.id === id;
            l.el.className = base + ( isActive
                ? 'bg-ink text-white lg:border-qh lg:text-ink'
                : 'bg-mist text-ink/55 hover:text-ink lg:border-transparent lg:text-ink/35' );
        } );
    };

    const update = () => {
        const sorted = links.slice().sort( ( a, b ) =>
            a.target.getBoundingClientRect().top - b.target.getBoundingClientRect().top
        );
        const visible = sorted.filter( ( l ) => {
            const rect = l.target.getBoundingClientRect();
            return rect.top < window.innerHeight * 0.65 && rect.bottom > 0;
        } );
        if ( visible.length ) setActive( visible[0].id );
    };

    let ticking = false;
    const onScroll = () => {
        if ( ticking ) return;
        ticking = true;
        requestAnimationFrame( () => {
            update();
            ticking = false;
        } );
    };

    window.addEventListener( 'scroll', onScroll, { passive: true } );
    window.addEventListener( 'resize', update );
    update();
}

/* ---------- Profile explorer ---------- */
function initProfileExplorer() {
    const roots = Array.from( document.querySelectorAll( '[data-profile-explorer]' ) );
    if ( ! roots.length ) return;

    roots.forEach( ( root ) => {
        const tabs   = Array.from( root.querySelectorAll( '[data-profile-tab]' ) );
        const panels = Array.from( root.querySelectorAll( '[data-profile-panel]' ) );
        if ( ! tabs.length || ! panels.length ) return;

        const setActive = ( key ) => {
            tabs.forEach( ( tab ) => {
                const isActive = tab.dataset.profileTab === key;
                tab.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );

                const title = tab.querySelector( '[data-title]' );
                if ( title ) {
                    title.className = 'font-display text-3xl font-bold tracking-[-0.03em] transition-colors sm:text-4xl ' +
                        ( isActive ? 'text-ink' : 'text-ink/25 group-hover:text-ink/55' );
                }

                const level = parseInt( tab.dataset.profileLevel || '0', 10 );
                const bars  = Array.from( tab.querySelectorAll( '[data-bar]' ) );
                bars.forEach( ( bar, i ) => {
                    const on = ( i + 1 ) <= level;
                    bar.className = 'h-4 w-1.5 rounded-full transition-colors duration-200 ' +
                        ( on
                            ? ( isActive ? 'bg-qh' : 'bg-ink/25' )
                            : 'bg-ink/10' );
                } );
            } );

            panels.forEach( ( panel ) => {
                panel.classList.toggle( 'hidden', panel.dataset.profilePanel !== key );
            } );
        };

        tabs.forEach( ( tab ) => {
            tab.addEventListener( 'click', () => setActive( tab.dataset.profileTab ) );
        } );
    } );
}

/* ---------- Asset groups ---------- */
function initAssetGroups() {
    const groups = Array.from( document.querySelectorAll( '[data-asset-group]' ) );
    if ( ! groups.length ) return;

    groups.forEach( ( group ) => {
        const toggle  = group.querySelector( '[data-asset-toggle]' );
        const funds   = group.querySelector( '[data-asset-funds]' );
        const chevron = group.querySelector( '[data-asset-chevron]' );
        if ( ! toggle || ! funds ) return;

        toggle.addEventListener( 'click', () => {
            const isOpen = ! funds.hidden;
            funds.hidden = isOpen;
            toggle.setAttribute( 'aria-expanded', isOpen ? 'false' : 'true' );
            if ( chevron ) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        } );
    } );
}

/* ---------- Product FAQ ---------- */
function initProductFaq() {
    const roots = Array.from( document.querySelectorAll( '[data-product-faq]' ) );
    if ( ! roots.length ) return;

    roots.forEach( ( root ) => {
        const toggles = Array.from( root.querySelectorAll( '[data-faq-toggle]' ) );
        toggles.forEach( ( toggle ) => {
            toggle.addEventListener( 'click', () => {
                const li = toggle.closest( 'li' );
                if ( ! li ) return;
                const answer  = li.querySelector( '[data-faq-answer]' );
                const chevron = toggle.querySelector( '[data-faq-chevron]' );
                if ( ! answer ) return;

                const isOpen = ! answer.hidden;
                answer.hidden = isOpen;
                toggle.setAttribute( 'aria-expanded', isOpen ? 'false' : 'true' );
                if ( chevron ) chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
            } );
        } );
    } );
}

/* ---------- Demo iframe ---------- */
function initDemoIframe() {
    const iframes = Array.from( document.querySelectorAll( 'iframe[title*="Demo interactiva"], iframe[title*="interactive app demo"]' ) );
    if ( ! iframes.length ) return;

    iframes.forEach( ( iframe ) => {
        const wrapper = iframe.parentElement;
        if ( ! wrapper ) return;

        const loader = document.createElement( 'div' );
        loader.className = 'absolute inset-0 flex flex-col items-center justify-center gap-3 bg-white text-ink/50';
        loader.innerHTML = '<svg class="h-6 w-6 animate-spin text-qh" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg><p class="text-sm">' + qhTr( 'loading', 'Cargando' ) + '</p>';
        wrapper.appendChild( loader );

        iframe.style.opacity = '0';
        iframe.style.transition = 'opacity 250ms ease';

        iframe.addEventListener( 'load', () => {
            loader.remove();
            iframe.style.opacity = '1';
        }, { once: true } );
    } );
}

/* ---------- Compound calculator ---------- */
function initCompoundCalculator() {
    const roots = Array.from( document.querySelectorAll( '[data-compound-calculator]' ) );
    if ( ! roots.length ) return;

    const localeCode = window.QuantiaConfig?.locale || 'es_PA';
    const normalizedLocale = localeCode.replace( '_', '-' );

    const formatCurrency = ( value ) =>
        new Intl.NumberFormat( normalizedLocale, {
            style: 'currency',
            currency: 'USD',
            maximumFractionDigits: 0,
        } ).format( value );

    const simulate = ( initial, monthly, months, annualRate ) => {
        const monthlyRate = annualRate / 12;
        const points = [];
        let saldo = initial;
        let aportado = initial;
        points.push( { month: 0, aportado, saldo } );

        for ( let m = 1; m <= months; m++ ) {
            saldo = saldo * ( 1 + monthlyRate ) + monthly;
            aportado += monthly;
            points.push( {
                month: m,
                aportado: Math.round( aportado ),
                saldo: Math.round( saldo ),
            } );
        }

        return {
            points,
            aportado: Math.round( aportado ),
            rendimiento: Math.round( saldo - aportado ),
            saldo: Math.round( saldo ),
        };
    };

    const renderBars = ( container, bars ) => {
        if ( ! container ) return;
        if ( ! bars.length ) { container.innerHTML = ''; return; }

        const max = Math.max( 1, ...bars.map( ( b ) => b.saldo ) );
        const labelEvery = bars.length > 20 ? 10 : bars.length > 10 ? 5 : 1;
        const chartHeight = window.matchMedia( '(min-width: 640px)' ).matches ? 240 : 200;

        const barHtml = bars.map( ( bar ) => {
            const total = Math.max( 0, Math.min( 1, bar.saldo / max ) );
            const barHeight = Math.max( 6, total * chartHeight );
            const contributionShare = bar.saldo > 0
                ? Math.min( 1, Math.max( 0, bar.aportado / bar.saldo ) )
                : 1;
            const contributionHeight = contributionShare * barHeight;
            const growthHeight = barHeight - contributionHeight;

            return `
                <div style="flex: 1 1 0%; min-width: 0; height: ${ chartHeight }px; display: flex; flex-direction: column; justify-content: flex-end;">
                    <div style="width: 100%; height: ${ barHeight.toFixed( 2 ) }px; display: flex; flex-direction: column; overflow: hidden; border-radius: 3px 3px 0 0;">
                        <div style="width: 100%; height: ${ growthHeight.toFixed( 2 ) }px; background: #6FE0AF; flex-shrink: 0;"></div>
                        <div style="width: 100%; height: ${ contributionHeight.toFixed( 2 ) }px; background: rgba(255, 255, 255, 0.28); flex-shrink: 0;"></div>
                    </div>
                </div>
            `;
        } ).join( '' );

        const labelHtml = bars.map( ( bar ) => {
            const show = bar.year === 1 || bar.year % labelEvery === 0;
            return `<span style="flex: 1 1 0%; text-align: center; min-width: 0;">${ show ? bar.year : '' }</span>`;
        } ).join( '' );

        container.innerHTML = `
            <div aria-hidden="true">
                <div style="display: flex; align-items: flex-end; gap: 3px; height: ${ chartHeight }px;">${ barHtml }</div>
                <div class="mt-2 flex gap-[3px] text-[10px] text-white/40 sm:text-xs">${ labelHtml }</div>
            </div>
        `;
    };

    roots.forEach( ( root ) => {
        const form       = root.querySelector( '[data-compound-form]' );
        const inputInit  = root.querySelector( '[data-compound-initial]' );
        const inputMo    = root.querySelector( '[data-compound-monthly]' );
        const inputYears = root.querySelector( '[data-compound-years]' );
        const rates      = Array.from( root.querySelectorAll( '[data-compound-rate]' ) );

        const outYears       = root.querySelectorAll( '[data-compound-years-out]' );
        const outTotal       = root.querySelector( '[data-compound-total]' );
        const outContributed = root.querySelector( '[data-compound-contributed]' );
        const outInterest    = root.querySelector( '[data-compound-interest]' );
        const outSimple      = root.querySelector( '[data-compound-simple]' );
        const outAdvantage   = root.querySelector( '[data-compound-advantage]' );
        const barsContainer  = root.querySelector( '[data-compound-bars]' );

        if ( ! inputInit || ! inputMo || ! inputYears || ! rates.length ) return;

        if ( form ) {
            form.addEventListener( 'submit', ( e ) => e.preventDefault() );
        }

        const getCheckedRate = () => {
            const checked = rates.find( ( r ) => r.checked );
            return checked ? parseFloat( checked.dataset.rate || '0.07' ) : 0.07;
        };

        const syncRateStyles = () => {
            rates.forEach( ( r ) => {
                const label = r.closest( '[data-compound-rate-label]' );
                if ( ! label ) return;
                const kind = label.querySelector( '[data-compound-rate-kind]' );
                if ( r.checked ) {
                    label.classList.remove( 'text-white', 'ring-white/15', 'hover:bg-white/[0.06]' );
                    label.classList.add( 'bg-qh-300', 'text-ink', 'ring-qh-300' );
                    if ( kind ) {
                        kind.classList.remove( 'text-white/45' );
                        kind.classList.add( 'text-ink/60' );
                    }
                } else {
                    label.classList.remove( 'bg-qh-300', 'text-ink', 'ring-qh-300' );
                    label.classList.add( 'text-white', 'ring-white/15', 'hover:bg-white/[0.06]' );
                    if ( kind ) {
                        kind.classList.remove( 'text-ink/60' );
                        kind.classList.add( 'text-white/45' );
                    }
                }
            } );
        };

        const render = () => {
            const initial = Math.max( 0, Number( inputInit.value ) || 0 );
            const monthly = Math.max( 0, Number( inputMo.value ) || 0 );
            const years   = Math.max( 1, Math.min( 40, Number( inputYears.value ) || 1 ) );
            const rate    = getCheckedRate();

            const months = years * 12;
            const sim = simulate( initial, monthly, months, rate );

            const simpleInterest =
                initial * rate * years +
                ( monthly * rate * ( months * ( months - 1 ) / 2 ) ) / 12;
            const simpleTotal = Math.round( sim.aportado + simpleInterest );
            const advantage   = Math.max( 0, sim.saldo - simpleTotal );

            const yearWord = years === 1
                ? qhTr( 'year', 'año' )
                : qhTr( 'years', 'años' );
            const yearsLabel = years + ' ' + yearWord;
            outYears.forEach( ( el ) => { el.textContent = yearsLabel; } );

            if ( outTotal )       outTotal.textContent       = formatCurrency( sim.saldo );
            if ( outContributed ) outContributed.textContent = formatCurrency( sim.aportado );
            if ( outInterest )    outInterest.textContent    = '+' + formatCurrency( sim.rendimiento );
            if ( outSimple )      outSimple.textContent      = formatCurrency( simpleTotal );
            if ( outAdvantage )   outAdvantage.textContent   = formatCurrency( advantage );

            const bars = sim.points
                .filter( ( p ) => p.month > 0 && p.month % 12 === 0 )
                .map( ( p ) => ( {
                    year: p.month / 12,
                    aportado: p.aportado,
                    saldo: p.saldo,
                } ) );

            renderBars( barsContainer, bars );
            syncRateStyles();
        };

        [ inputInit, inputMo, inputYears ].forEach( ( el ) => {
            el.addEventListener( 'input', render );
            el.addEventListener( 'change', render );
        } );

        rates.forEach( ( r ) => r.addEventListener( 'change', render ) );

        render();
    } );
}

/* ---------- Support FAQ ---------- */
function initSupportFaq() {
    const root = document.querySelector( '[data-support-faq]' );
    if ( ! root ) return;

    const allFaqs = JSON.parse( root.dataset.supportAll || '[]' );
    if ( ! allFaqs.length ) return;

    const listEl  = root.querySelector( '[data-support-faq-list]' );
    const titleEl = root.querySelector( '[data-support-faq-title]' );
    const countEl = root.querySelector( '[data-support-faq-count]' );
    const tabs    = Array.from( root.querySelectorAll( '[data-support-faq-tab]' ) );
    const search  = document.querySelector( '[data-support-query]' );

    let activeCategory = 'todos';
    let query = '';
    let openItem = allFaqs[0]?.question || null;

    const normalize = ( str ) => str
        .toLowerCase()
        .normalize( 'NFD' )
        .replace( /[\u0300-\u036f]/g, '' );

    const renderTabs = () => {
        tabs.forEach( ( tab ) => {
            const isActive = tab.dataset.supportFaqTab === activeCategory;
            tab.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
            tab.className = 'whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition-colors ' +
                ( isActive ? 'bg-ink text-white' : 'bg-mist text-ink/60 hover:text-ink' );
        } );
    };

    const render = () => {
        const q = normalize( query.trim() );
        const results = allFaqs.filter( ( faq ) => {
            if ( activeCategory !== 'todos' && faq.category !== activeCategory ) return false;
            if ( ! q ) return true;
            return normalize( faq.question + ' ' + faq.answer ).includes( q );
        } );

        if ( titleEl ) titleEl.textContent = query ? ( qhTr( 'resultsFor', 'Resultados para' ) + ' "' + query + '"' ) : qhTr( 'faqTitle', 'Preguntas frecuentes' );
        if ( countEl ) {
            const word = results.length === 1 ? qhTr( 'answer', 'respuesta' ) : qhTr( 'answers', 'respuestas' );
            countEl.textContent = results.length + ' ' + word;
        }

        if ( ! results.length ) {
            listEl.innerHTML = `
                <div class="mt-10 flex flex-col items-start gap-4 border-t border-ink/10 pt-10">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8 text-ink/30" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="m13.41 6.59-2.83 2.83"/><path d="m8.76 11.24 2.83-2.83"/></svg>
                    <p class="font-display text-xl font-bold text-ink">${ qhTr( 'noResults', 'No encontramos una respuesta para eso.' ) }</p>
                    <p class="text-sm text-ink/60">${ qhTr( 'noResultsHelp', 'Escríbenos y una persona de nuestro equipo te ayuda directamente.' ) }</p>
                    <a href="${ window.QuantiaConfig?.homeUrl || '/' }soporte/#contacto"
                        class="inline-flex items-center gap-2 rounded-full bg-qh px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-qh-600">
                        ${ qhTr( 'writeToTeam', 'Escribir al equipo' ) }
                    </a>
                </div>
            `;
            return;
        }

        listEl.innerHTML = results.map( ( faq ) => {
            const isOpen = openItem === faq.question;
            const showCategory = activeCategory === 'todos';
            return `
                <li class="border-b border-ink/10">
                    <button type="button" data-faq-q="${ encodeURIComponent( faq.question ) }"
                        aria-expanded="${ isOpen ? 'true' : 'false' }"
                        class="flex w-full items-start justify-between gap-6 py-5 text-left">
                        <span>
                            ${ showCategory ? `<span class="block text-xs font-semibold text-qh-700">${ faq.category_name }</span>` : '' }
                            <span class="mt-0.5 block font-display text-lg font-semibold leading-snug text-ink sm:text-xl">${ faq.question }</span>
                        </span>
                        <span class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full transition-[transform,background-color,color] duration-200 ${ isOpen ? 'rotate-45 bg-qh text-white' : 'bg-mist text-ink' }">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        </span>
                    </button>
                    ${ isOpen ? `<div class="overflow-hidden"><p class="max-w-2xl pb-5 text-base leading-relaxed text-ink/65">${ faq.answer }</p></div>` : '' }
                </li>
            `;
        } ).join( '' );

        listEl.querySelectorAll( '[data-faq-q]' ).forEach( ( btn ) => {
            btn.addEventListener( 'click', () => {
                const q = decodeURIComponent( btn.dataset.faqQ );
                openItem = openItem === q ? null : q;
                render();
            } );
        } );

        renderTabs();
    };

    tabs.forEach( ( tab ) => {
        tab.addEventListener( 'click', () => {
            activeCategory = tab.dataset.supportFaqTab;
            render();
        } );
    } );

    if ( search ) {
        search.addEventListener( 'input', () => {
            query = search.value;
            if ( query ) activeCategory = 'todos';
            render();
        } );
    }

    document.querySelectorAll( '[data-support-topic]' ).forEach( ( btn ) => {
        btn.addEventListener( 'click', () => {
            activeCategory = btn.dataset.supportTopic;
            query = '';
            if ( search ) search.value = '';
            render();
            document.getElementById( 'respuestas' )?.scrollIntoView( { behavior: 'smooth' } );
        } );
    } );

    render();
}

/* ---------- Contact form ---------- */
function initContactForm() {
    const form = document.querySelector( '[data-contact-form]' );
    if ( ! form ) return;

    const inner   = form.querySelector( '[data-contact-form-inner]' );
    const success = form.querySelector( '[data-contact-success]' );
    const errorEl = form.querySelector( '[data-contact-error]' );
    const resetBtn = form.querySelector( '[data-contact-reset]' );
    const submitBtn = form.querySelector( 'button[type="submit"]' );

    form.addEventListener( 'submit', async ( event ) => {
        event.preventDefault();
        if ( errorEl ) errorEl.hidden = true;

        const fields = new FormData( form );
        fields.append( 'action', 'quantia_contact' );
        fields.append( 'nonce', form.dataset.nonce );

        const name = ( fields.get( 'name' ) || '' ).toString().trim();
        const email = ( fields.get( 'email' ) || '' ).toString().trim();
        const phone = ( fields.get( 'phone' ) || '' ).toString().trim();
        const newClient = ( fields.get( 'newClient' ) || '' ).toString().trim();
        const inquiryType = ( fields.get( 'inquiryType' ) || '' ).toString().trim();
        const message = ( fields.get( 'message' ) || '' ).toString().trim();

        const problems = [];
        if ( ! name ) problems.push( 'name' );
        if ( ! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test( email ) ) problems.push( 'email' );
        if ( ! phone ) problems.push( 'phone' );
        if ( ! newClient ) problems.push( 'new_client' );
        if ( ! inquiryType ) problems.push( 'inquiry_type' );
        if ( message.length < 10 ) problems.push( 'message' );

        if ( problems.length ) {
            if ( errorEl ) {
                errorEl.textContent = qhTr( 'error', 'Revisa los campos obligatorios.' );
                errorEl.hidden = false;
            }
            return;
        }

        if ( submitBtn ) {
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = qhTr( 'loading', 'Cargando' );
        }

        try {
            const response = await fetch( form.dataset.ajaxUrl || form.action, {
                method: 'POST',
                body: fields,
                credentials: 'same-origin',
            } );

            const raw = await response.text();

            if ( raw === '0' || raw === '-1' ) {
                if ( errorEl ) {
                    errorEl.textContent = qhTr( 'error', 'Ocurrió un error. Intenta de nuevo.' );
                    errorEl.hidden = false;
                }
                return;
            }

            let data;
            try {
                data = JSON.parse( raw );
            } catch ( parseError ) {
                if ( errorEl ) {
                    errorEl.textContent = qhTr( 'error', 'Respuesta inválida del servidor.' );
                    errorEl.hidden = false;
                }
                return;
            }

            if ( data.success ) {
                form.reset();
                if ( inner ) inner.hidden = true;
                if ( success ) success.hidden = false;
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push( { event: 'cta_click', label: 'formulario_contacto:' + inquiryType, page: window.location.pathname } );
            } else {
                if ( errorEl ) {
                    errorEl.textContent = data?.data?.message || qhTr( 'error', 'No pudimos enviar tu consulta.' );
                    errorEl.hidden = false;
                }
            }
        } catch ( err ) {
            if ( errorEl ) {
                errorEl.textContent = qhTr( 'error', 'Error de red.' );
                errorEl.hidden = false;
            }
        } finally {
            if ( submitBtn ) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.dataset.originalText || qhTr( 'send', 'Enviar' );
            }
        }
    } );

    if ( resetBtn ) {
        resetBtn.addEventListener( 'click', () => {
            if ( inner ) inner.hidden = false;
            if ( success ) success.hidden = true;
        } );
    }
}

/* ---------- Article filters ---------- */
function initArticleFilters() {
    const roots = Array.from( document.querySelectorAll( '[data-article-list]' ) );
    if ( ! roots.length ) return;

    roots.forEach( ( root ) => {
        const buttons = Array.from( root.querySelectorAll( '[data-article-filter]' ) );
        const items   = Array.from( root.querySelectorAll( '[data-article-item]' ) );
        if ( ! buttons.length || ! items.length ) return;

        const pillBase = 'rounded-full px-4 py-2 text-sm font-semibold transition-colors ';
        const setActive = ( filter ) => {
            buttons.forEach( ( btn ) => {
                const isActive = btn.dataset.articleFilter === filter;
                btn.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
                btn.className = pillBase + ( isActive ? 'bg-ink text-white' : 'bg-mist text-ink/60 hover:text-ink' );
            } );

            items.forEach( ( item ) => {
                const cats = ( item.dataset.articleCategories || '' ).split( ',' );
                const visible = 'Todos' === filter || cats.includes( filter );
                item.style.display = visible ? '' : 'none';
            } );
        };

        buttons.forEach( ( btn ) => {
            btn.addEventListener( 'click', () => setActive( btn.dataset.articleFilter ) );
        } );
    } );
}

/* ---------- Share buttons ---------- */
function initShareButtons() {
    const roots = Array.from( document.querySelectorAll( '[data-share-buttons]' ) );
    if ( ! roots.length ) return;

    roots.forEach( ( root ) => {
        const copyBtn   = root.querySelector( '[data-share-copy]' );
        const copyLabel = root.querySelector( '[data-share-label]' );
        const copyIcon  = root.querySelector( '[data-share-icon]' );
        const url       = root.dataset.shareUrl || window.location.href;
        if ( ! copyBtn || ! copyLabel ) return;

        copyBtn.addEventListener( 'click', async () => {
            try {
                await navigator.clipboard.writeText( url );
                copyLabel.textContent = qhTr( 'linkCopied', 'Enlace copiado' );
                if ( copyIcon ) {
                    copyIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>';
                }
                window.setTimeout( () => {
                    copyLabel.textContent = qhTr( 'copyLink', 'Copiar enlace' );
                    if ( copyIcon ) {
                        copyIcon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>';
                    }
                }, 1800 );
            } catch ( err ) {}
        } );
    } );
}

/* ---------- Reading progress ---------- */
function initReadingProgress() {
    const bar  = document.querySelector( '[data-reading-progress]' );
    const body = document.querySelector( '[data-reading-body]' );
    if ( ! bar || ! body ) return;

    const update = () => {
        const rect = body.getBoundingClientRect();
        const vh = window.innerHeight;
        const total = rect.height - vh * 0.3;
        const progress = Math.max( 0, Math.min( 1, ( vh * 0.3 - rect.top ) / total ) );
        bar.style.transform = `scaleX(${ progress.toFixed( 4 ) })`;
    };

    let ticking = false;
    const onScroll = () => {
        if ( ticking ) return;
        ticking = true;
        requestAnimationFrame( () => { update(); ticking = false; } );
    };

    window.addEventListener( 'scroll', onScroll, { passive: true } );
    window.addEventListener( 'resize', update );
    update();
}

/* ---------- FAQ tabs carousel ---------- */
function initFaqTabsCarousel() {
    const wraps = Array.from( document.querySelectorAll( '[data-faq-tabs-wrap]' ) );
    if ( ! wraps.length ) return;

    wraps.forEach( ( wrap ) => {
        const scroller = wrap.querySelector( '[data-faq-tabs]' );
        const prevBtn  = wrap.querySelector( '[data-faq-tabs-prev]' );
        const nextBtn  = wrap.querySelector( '[data-faq-tabs-next]' );
        if ( ! scroller ) return;

        const step = () => Math.max( 180, Math.floor( scroller.clientWidth * 0.7 ) );

        const update = () => {
            const maxScroll = scroller.scrollWidth - scroller.clientWidth;
            const scrollable = maxScroll > 4;
            const atStart = scroller.scrollLeft <= 2;
            const atEnd   = scroller.scrollLeft >= maxScroll - 2;
            wrap.dataset.atStart = atStart ? 'true' : 'false';
            wrap.dataset.atEnd   = atEnd   ? 'true' : 'false';
            if ( prevBtn ) prevBtn.hidden = ! scrollable || atStart;
            if ( nextBtn ) nextBtn.hidden = ! scrollable || atEnd;
        };

        if ( prevBtn ) {
            prevBtn.addEventListener( 'click', () => {
                scroller.scrollBy( { left: -step(), behavior: 'smooth' } );
            } );
        }
        if ( nextBtn ) {
            nextBtn.addEventListener( 'click', () => {
                scroller.scrollBy( { left: step(), behavior: 'smooth' } );
            } );
        }

        scroller.addEventListener( 'scroll', update, { passive: true } );
        window.addEventListener( 'resize', update );
        requestAnimationFrame( update );
    } );
}

document.addEventListener( 'quantia:faq-tabs-updated', () => {
    document.querySelectorAll( '[data-faq-tabs-wrap]' ).forEach( ( wrap ) => {
        const scroller = wrap.querySelector( '[data-faq-tabs]' );
        if ( ! scroller ) return;
        const maxScroll = scroller.scrollWidth - scroller.clientWidth;
        wrap.dataset.atStart = scroller.scrollLeft <= 2 ? 'true' : 'false';
        wrap.dataset.atEnd   = scroller.scrollLeft >= maxScroll - 2 ? 'true' : 'false';
    } );
} );

/* ---------- Legal document accordion ---------- */
function initLegalDocumentAccordion() {
    const roots = Array.from( document.querySelectorAll( '[data-legal-doc]' ) );
    if ( ! roots.length ) return;

    roots.forEach( ( root ) => {
        const items        = Array.from( root.querySelectorAll( '[data-legal-section]' ) );
        if ( ! items.length ) return;
        const toggleAllBtn = root.querySelector( '[data-legal-toggle-all]' );

        const applyState = ( item, open ) => {
            const btn   = item.querySelector( '[data-legal-section-toggle]' );
            const panel = item.querySelector( '[data-legal-section-panel]' );
            const plus  = item.querySelector( '[data-legal-section-plus]' );
            if ( ! btn || ! panel ) return;

            btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
            panel.hidden = ! open;

            if ( plus ) {
                plus.classList.toggle( 'rotate-45', open );
                plus.classList.toggle( 'bg-qh', open );
                plus.classList.toggle( 'text-white', open );
                plus.classList.toggle( 'bg-mist', ! open );
                plus.classList.toggle( 'text-ink', ! open );
            }
        };

        const updateToggleAllLabel = () => {
            if ( ! toggleAllBtn ) return;
            const allOpen = items.every( ( item ) => {
                const btn = item.querySelector( '[data-legal-section-toggle]' );
                return btn && btn.getAttribute( 'aria-expanded' ) === 'true';
            });
            toggleAllBtn.textContent = allOpen
                ? qhTr( 'collapseAll', 'Contraer todo' )
                : qhTr( 'expandAll', 'Expandir todo' );
        };

        items.forEach( ( item ) => {
            const btn = item.querySelector( '[data-legal-section-toggle]' );
            if ( ! btn ) return;
            btn.addEventListener( 'click', () => {
                const isOpen = btn.getAttribute( 'aria-expanded' ) === 'true';
                applyState( item, ! isOpen );
                updateToggleAllLabel();
            } );
        } );

        if ( toggleAllBtn ) {
            toggleAllBtn.addEventListener( 'click', () => {
                const allOpen = items.every( ( item ) => {
                    const btn = item.querySelector( '[data-legal-section-toggle]' );
                    return btn && btn.getAttribute( 'aria-expanded' ) === 'true';
                } );
                items.forEach( ( item ) => applyState( item, ! allOpen ) );
                updateToggleAllLabel();
            } );
        }

        // LÓGICA NUEVA: Abrir sección si la URL tiene un hash que coincide
        const hash = window.location.hash;
        if ( hash ) {
            const targetSection = root.querySelector( hash );
            if ( targetSection && targetSection.hasAttribute( 'data-legal-section' ) ) {
                const btn = targetSection.querySelector( '[data-legal-section-toggle]' );
                if ( btn && btn.getAttribute( 'aria-expanded' ) === 'false' ) {
                    applyState( targetSection, true );
                    updateToggleAllLabel();
                    
                    // Hacemos scroll suave hacia la sección abierta después de un breve delay
                    setTimeout( () => {
                        targetSection.scrollIntoView( { behavior: 'smooth', block: 'start' } );
                    }, 100 );
                }
            }
        }
    } );
}

/* ---------- Legal page navigation (Scroll Spy & Anchor Offset) ---------- */
function initLegalPageNav() {
    const docs         = Array.from( document.querySelectorAll( '[data-legal-doc]' ) );
    const desktopLinks = Array.from( document.querySelectorAll( '[data-legal-nav]' ) );
    const mobileLinks  = Array.from( document.querySelectorAll( '[data-legal-nav-mobile]' ) );

    if ( ! docs.length ) return;

    const baseDesktop   = [ 'border-transparent', 'text-ink/50', 'hover:text-ink' ];
    const activeDesktop = [ 'border-qh', 'text-ink' ];
    const baseMobile    = [ 'bg-mist', 'text-ink/65', 'hover:text-ink' ];
    const activeMobile  = [ 'bg-ink', 'text-white' ];

    const toggle = ( el, list, on ) => {
        list.forEach( ( c ) => el.classList.toggle( c, on ) );
    };

    const setActive = ( slug ) => {
        desktopLinks.forEach( ( link ) => {
            const isActive = link.dataset.legalNav === slug;
            toggle( link, baseDesktop, ! isActive );
            toggle( link, activeDesktop, isActive );
            if ( isActive ) link.setAttribute( 'aria-current', 'true' );
            else link.removeAttribute( 'aria-current' );
        } );
        mobileLinks.forEach( ( link ) => {
            const isActive = link.dataset.legalNavMobile === slug;
            toggle( link, baseMobile, ! isActive );
            toggle( link, activeMobile, isActive );
            if ( isActive ) link.setAttribute( 'aria-current', 'true' );
            else link.removeAttribute( 'aria-current' );
        } );
    };

    // 1. SISTEMA DE SCROLL SPY (Calcula qué sección está visible)
    const handleScroll = () => {
        let currentSlug = docs[0].id;
        
        // Buscamos cuál es la sección que está más cerca del top de la pantalla (dejando espacio para el header)
        docs.forEach( ( doc ) => {
            const rect = doc.getBoundingClientRect();
            // Si la parte superior de la sección está a menos de 150px del top, es la activa
            if ( rect.top <= 150 ) {
                currentSlug = doc.id;
            }
        } );
        
        setActive( currentSlug );
    };

    let ticking = false;
    window.addEventListener( 'scroll', () => {
        if ( ! ticking ) {
            window.requestAnimationFrame( () => {
                handleScroll();
                ticking = false;
            } );
            ticking = true;
        }
    } );
    
    // Ejecutar al cargar para establecer el estado inicial
    handleScroll();

    // 2. SCROLL EXACTO CON OFFSET (Para que el header no tape el título)
    const handleAnchorClick = ( e ) => {
        e.preventDefault();
        const link = e.currentTarget;
        const targetId = link.getAttribute( 'href' ).replace( '#', '' );
        const targetEl = document.getElementById( targetId );
        
        if ( targetEl ) {
            // Calculamos la altura del header fijo (aprox 100px)
            const headerOffset = 120; 
            const elementPosition = targetEl.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.scrollY - headerOffset;
  
            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    };

    [...desktopLinks, ...mobileLinks].forEach( ( link ) => {
        link.addEventListener( 'click', handleAnchorClick );
    } );

    // Si entramos desde un enlace externo con ancla
    if ( window.location.hash ) {
        const target = document.querySelector( window.location.hash );
        if ( target ) {
            setTimeout(() => {
                const headerOffset = 120;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.scrollY - headerOffset;
                window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
            }, 100);
        }
    }
}

/* ---------- Boot ---------- */
function boot() {
    initParallax();
    initRevealFrames();
    initMarquees();
    initScrollRevealText();
    initSlideIn();
    initStepsFill();
    initPointerParallax();
    initFeeTabs();
    initFeeScrollSpy();
    initProfileExplorer();
    initAssetGroups();
    initProductFaq();
    initDemoIframe();
    initArticleFilters();
    initShareButtons();
    initReadingProgress();
    initCompoundCalculator();
    initSupportFaq();
    initContactForm();
    initFaqTabsCarousel();
    initLegalDocumentAccordion();
    initLegalPageNav();
}

if ( document.readyState === 'loading' ) {
    document.addEventListener( 'DOMContentLoaded', boot );
} else {
    boot();
}