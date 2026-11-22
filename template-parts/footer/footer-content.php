<?php
/**
 * Contenido del Footer principal.
 * 
 * Contexto de Arquitectura:
 * - Incluido por: footer.php.
 * - Contiene la navegación secundaria, redes sociales, reguladores y legales.
 * - Dependencias visuales: quantia_icon() para SVGs.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;
?>

<footer class="w-full overflow-hidden bg-ink pb-12 pt-20 text-white">
    <div class="mx-auto max-w-6xl px-5 sm:px-6">

        <div class="flex flex-wrap items-center justify-between gap-6 border-b border-white/10 pb-5">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Quantia Hub" class="block transition-opacity hover:opacity-80">
                <img src="<?php echo esc_url( quantia_managed_image_src( 'brand_logo' ) ); ?>" alt="Quantia" class="h-8 w-auto brightness-0 invert">
            </a>
            
            <div class="flex items-center gap-4">
                <a href="https://www.instagram.com/quantiahub/" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-white/70 transition-colors hover:border-qh hover:text-qh-300">
                    <?php echo quantia_icon( 'instagram', 'h-4 w-4' ); ?>
                </a>
                <a href="https://www.facebook.com/p/Quantia-Hub-61554626611782/" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-white/70 transition-colors hover:border-qh hover:text-qh-300">
                    <?php echo quantia_icon( 'facebook', 'h-4 w-4' ); ?>
                </a>
                <a href="https://www.linkedin.com/company/quantia-hub/home/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-white/70 transition-colors hover:border-qh hover:text-qh-300">
                    <?php echo quantia_icon( 'linkedin', 'h-4 w-4' ); ?>
                </a>
                <a href="https://www.youtube.com/@QuantiaHub" target="_blank" rel="noopener noreferrer" aria-label="YouTube" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-white/70 transition-colors hover:border-qh hover:text-qh-300">
                    <?php echo quantia_icon( 'youtube', 'h-4 w-4' ); ?>
                </a>
                <a href="#top" aria-label="Volver arriba" class="ml-2 flex h-10 w-10 items-center justify-center rounded-full bg-qh text-white transition-colors hover:bg-qh-600">
                    <?php echo quantia_icon( 'arrow-up', 'h-4 w-4' ); ?>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 py-16 md:gap-10 lg:gap-12">
            
            <nav aria-labelledby="footer-products">
                <h3 id="footer-products" class="text-xs font-semibold uppercase tracking-wider text-qh-300">Productos</h3>
                <ul class="mt-6 space-y-4">
                    <li><a href="<?php echo esc_url( home_url( '/productos/inversiones' ) ); ?>" class="text-sm text-white/65 transition-colors hover:text-white">Inversiones</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/productos/alcancias' ) ); ?>" class="text-sm text-white/65 transition-colors hover:text-white">Alcancías</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/productos/jubilación' ) ); ?>" class="text-sm text-white/65 transition-colors hover:text-white">Retiro</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/productos/tarjeta' ) ); ?>" class="text-sm text-white/65 transition-colors hover:text-white">Tarjetas</a></li>
                </ul>
            </nav>

            <nav aria-labelledby="footer-site">
                <h3 id="footer-site" class="text-xs font-semibold uppercase tracking-wider text-qh-300">Sitio</h3>
                <ul class="mt-6 space-y-4">
                    <li><a href="<?php echo esc_url( home_url( '/tarifas' ) ); ?>" class="text-sm text-white/65 transition-colors hover:text-white">Tarifas</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/aprende' ) ); ?>" class="text-sm text-white/65 transition-colors hover:text-white">Aprende</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/soporte' ) ); ?>" class="text-sm text-white/65 transition-colors hover:text-white">Soporte</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/nosotros' ) ); ?>" class="text-sm text-white/65 transition-colors hover:text-white">Nosotros</a></li>
                </ul>
            </nav>

            <nav aria-labelledby="footer-legal">
                <h3 id="footer-legal" class="text-xs font-semibold uppercase tracking-wider text-qh-300">Legal</h3>
                <ul class="mt-6 space-y-4">
                    <li><a href="<?php echo esc_url( home_url( '/legal/terminos-legales' ) ); ?>" class="text-sm text-white/65 underline-offset-4 transition-colors hover:text-white hover:underline">Términos legales</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/legal/aviso-de-privacidad' ) ); ?>" class="text-sm text-white/65 underline-offset-4 transition-colors hover:text-white hover:underline">Aviso de Privacidad</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/legal/aclaraciones-y-reclamaciones' ) ); ?>" class="text-sm text-white/65 underline-offset-4 transition-colors hover:text-white hover:underline">Aclaraciones y reclamaciones</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/legal/costos-y-comisiones' ) ); ?>" class="text-sm text-white/65 underline-offset-4 transition-colors hover:text-white hover:underline">Costos y comisiones</a></li>
                </ul>
            </nav>

            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-qh-300">Contacto</h3>
                <ul class="mt-6 space-y-4 text-sm text-white/65">
                    <li>
                        <a href="https://wa.me/50764777460" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 transition-colors hover:text-white">
                            <?php echo quantia_icon( 'message-circle', 'h-4 w-4 shrink-0' ); ?> <span class="break-all">+507 6477-7460</span>
                        </a>
                    </li>
                    <li>
                        <a href="tel:+5072087024" class="inline-flex items-center gap-2 transition-colors hover:text-white">
                            <?php echo quantia_icon( 'phone', 'h-4 w-4 shrink-0' ); ?> <span class="break-all">+507 208-7024</span>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:quantia@mmgbank.com" class="inline-flex items-center gap-2 transition-colors hover:text-white">
                            <?php echo quantia_icon( 'mail', 'h-4 w-4 shrink-0' ); ?> <span class="break-all">quantia@mmgbank.com</span>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:reclamos@mmgbank.com" class="transition-colors hover:text-white">Atención de reclamos</a>
                    </li>
                </ul>
            </div>
        </div>

        <section aria-labelledby="footer-trust" class="rounded-3xl bg-white/[0.04] p-6 ring-1 ring-white/10 sm:p-8">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 lg:gap-6">
                
                <div>
                    <h3 id="footer-trust" class="mb-4 flex items-center gap-2 text-[11px] font-semibold uppercase tracking-wider text-white/55">
                        <?php echo quantia_icon( 'shield-check', 'h-3.5 w-3.5 text-qh-300' ); ?> Regulado por
                    </h3>
                    <div class="flex flex-row flex-nowrap gap-3 sm:gap-4">
                        <span title="Superintendencia de Bancos de Panamá" class="flex items-center gap-3 rounded-xl bg-white/[0.06] p-2 pr-4 ring-1 ring-white/15 transition-colors hover:bg-white/[0.09]">
                            <span class="flex h-16 w-20 shrink-0 items-center justify-center rounded-lg bg-white p-1.5">
                                <img src="<?php echo esc_url( quantia_managed_image_src( 'regulator_sbp' ) ); ?>" alt="Logo SBP" class="h-full w-full object-contain">
                            </span>
                            <span class="whitespace-nowrap text-xs font-medium leading-tight text-white/80">Superintendencia de Bancos</span>
                        </span>
                        <span title="Superintendencia del Mercado de Valores" class="flex items-center gap-3 rounded-xl bg-white/[0.06] p-2 pr-4 ring-1 ring-white/15 transition-colors hover:bg-white/[0.09]">
                            <span class="flex h-16 w-20 shrink-0 items-center justify-center rounded-lg bg-white p-1.5">
                                <img src="<?php echo esc_url( quantia_managed_image_src( 'regulator_smv' ) ); ?>" alt="Logo SMV" class="h-full w-full object-contain">
                            </span>
                            <span class="whitespace-nowrap text-xs font-medium leading-tight text-white/80">Superintendencia de Valores</span>
                        </span>
                    </div>
                </div>

                <div>
                    <p class="mb-4 text-[11px] font-semibold uppercase tracking-wider text-white/55">Participante</p>
                    <div class="flex flex-row gap-4">
                        <span title="Latinex" class="flex items-center gap-3 rounded-xl bg-white/[0.06] p-2 pr-4 ring-1 ring-white/15 transition-colors hover:bg-white/[0.09]">
                            <span class="flex h-16 w-20 shrink-0 items-center justify-center rounded-lg bg-white p-1.5">
                                <img src="<?php echo esc_url( quantia_managed_image_src( 'regulator_latinex' ) ); ?>" alt="Logo Latinex" class="h-full w-full object-contain">
                            </span>
                            <span class="whitespace-nowrap text-xs font-medium leading-tight text-white/80">Latinex</span>
                        </span>
                    </div>
                </div>

                <div class="shrink-0 flex items-center">
                    <a href="<?php echo esc_url( home_url( '/legal' ) ); ?>" class="group inline-flex items-center justify-center gap-1.5 rounded-full bg-qh px-5 py-2.5 text-xs font-semibold text-white transition-colors hover:bg-qh-600">
                        Centro legal
                        <?php echo quantia_icon( 'arrow-right', 'h-3 w-3 transition-transform group-hover:translate-x-0.5' ); ?>
                    </a>
                </div>

            </div>
        </section>

        <div class="mt-12 flex flex-col gap-5 border-t border-white/10 pt-10 lg:flex-row lg:items-center lg:justify-between">
            <p class="shrink-0 text-xs text-white/45">© 2026 MMG Bank Corporation. Todos los derechos reservados.</p>
        </div>

        <p class="mt-6 max-w-4xl text-[11px] leading-relaxed text-white/45">
            Quantia Hub es la aplicación financiera de MMG Bank Corporation, entidad regulada en Panamá. La información de este sitio es de carácter informativo y no constituye una oferta ni una recomendación de inversión. Consulta tarifas vigentes y documentos regulatorios antes de contratar. Operación exclusiva para Panamá. 
            <a href="https://www.mmgbank.com" target="_blank" rel="noopener noreferrer" class="text-white/65 underline underline-offset-4 hover:text-white">Conoce MMG Bank</a>
        </p>

    </div>
</footer>