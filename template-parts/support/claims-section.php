<?php
defined( 'ABSPATH' ) || exit;

$claims_email = quantia_option( 'contact_claims_email', 'reclamos@mmgbank.com' );
$claims_url   = quantia_option( 'contact_claims_url', 'mailto:reclamos@mmgbank.com' );
$steps        = quantia_fallback_claim_steps();
?>

<section id="reclamos" aria-labelledby="reclamos-titulo"
    class="w-full scroll-mt-20 overflow-hidden bg-mist py-14 sm:py-20 lg:py-28">
    <div class="mx-auto grid max-w-6xl grid-cols-1 gap-14 px-5 sm:px-6 lg:grid-cols-[1fr_1.3fr] lg:gap-20">

        <div data-parallax data-parallax-speed="0.2">
            <p class="text-sm font-semibold text-qh-700">Aclaraciones y reclamaciones</p>
            <h2 id="reclamos-titulo"
                class="mt-4 font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-5xl">
                ¿Algo no salió<br>
                <span class="text-qh">como esperabas?</span>
            </h2>
            <a href="<?php echo esc_url( $claims_url ); ?>"
                class="mt-8 inline-flex items-center gap-3 font-display text-xl font-bold text-ink transition-colors hover:text-qh-700 sm:text-2xl">
                <?php echo quantia_icon( 'mail', 'h-6 w-6 text-qh' ); ?>
                <?php echo esc_html( $claims_email ); ?>
            </a>
        </div>

        <div>
            <ol class="border-t border-ink/10">
                <?php foreach ( $steps as $i => $step ) : ?>
                    <li class="border-b border-ink/10">
                        <div data-slide data-slide-from="<?php echo $i % 2 === 0 ? '70' : '-70'; ?>"
                            class="flex gap-6 py-7">
                            <span class="font-display text-4xl font-extrabold leading-none tracking-[-0.04em] text-qh">
                                <?php echo (int) ( $i + 1 ); ?>
                            </span>
                            <div>
                                <h3 class="font-display text-xl font-bold tracking-[-0.02em] text-ink">
                                    <?php echo esc_html( $step['title'] ); ?>
                                </h3>
                                <p class="mt-2 text-[15px] leading-relaxed text-ink/65">
                                    <?php echo esc_html( $step['description'] ); ?>
                                </p>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
            <p class="mt-6 text-sm leading-relaxed text-ink/60">
                Si no estás conforme con la respuesta, tienes 5 días hábiles para exponer tus razones por escrito y el banco te responde en otros 5 días hábiles.
            </p>
            <div class="mt-10">
                <a href="<?php echo esc_url( home_url( '/legal/aclaraciones-y-reclamaciones/' ) ); ?>"
                    class="group inline-flex items-center gap-4 rounded-full bg-ink py-2 pl-7 pr-2 text-base font-semibold text-white shadow-[0_18px_40px_-18px_rgba(11,31,23,0.6)] transition-colors hover:bg-qh-700 sm:text-lg">
                    <span class="whitespace-nowrap">Ver procedimiento completo</span>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-qh text-white transition-transform group-hover:rotate-45 sm:h-12 sm:w-12">
                        <?php echo quantia_icon( 'arrow-up-right', 'h-5 w-5' ); ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>