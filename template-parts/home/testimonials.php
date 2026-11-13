<?php
defined( 'ABSPATH' ) || exit;

$testimonials = quantia_fallback_testimonials();
$marquee_text = __( 'Confianza · Constancia · Tranquilidad · ', 'quantia-hub' );
$marquee_x3   = trim( str_repeat( $marquee_text, 3 ) );
?>

<div class="bg-ink" data-reveal-frame>
    <section aria-labelledby="testimonios-title" class="w-full overflow-hidden bg-white py-20 md:py-32">
        <div data-marquee data-marquee-distance="30" aria-hidden="true"
            class="pointer-events-none select-none overflow-hidden">
            <p class="whitespace-nowrap font-display font-extrabold leading-none tracking-[-0.04em] text-[clamp(4rem,10vw,9rem)] text-ink/[0.04]">
                <?php echo esc_html( $marquee_x3 ); ?>
            </p>
        </div>

        <div class="mx-auto -mt-8 max-w-6xl px-5 sm:px-6">
            <div data-parallax data-parallax-speed="0.15">
                <h2 id="testimonios-title"
                    class="font-display text-4xl font-extrabold leading-[1.02] tracking-[-0.035em] text-ink sm:text-5xl">
                    <?php esc_html_e( 'Lo dicen quienes', 'quantia-hub' ); ?><br>
                    <span class="text-qh"><?php esc_html_e( 'ya empezaron.', 'quantia-hub' ); ?></span>
                </h2>
            </div>

            <ul class="mt-16 md:mt-24">
                <?php foreach ( $testimonials as $i => $t ) : ?>
                    <?php $reversed = $i % 2 === 1; ?>
                    <li class="grid grid-cols-1 items-center gap-10 border-t border-ink/10 py-14 md:grid-cols-[minmax(0,5fr)_minmax(0,7fr)] md:gap-16 md:py-20">

                        <div data-parallax data-parallax-speed="<?php echo $reversed ? '0.3' : '0.18'; ?>"
                            class="relative mx-auto w-full max-w-[340px] <?php echo $reversed ? 'md:order-last md:ml-auto md:mr-0' : 'md:ml-0'; ?>">

                            <div class="aspect-[4/5] w-full overflow-hidden rounded-[2rem] bg-mist">
                                <?php if ( ! empty( $t['photo'] ) ) : ?>
                                    <?php echo quantia_image( $t['photo'], 'quantia-testimonial', array( 'alt' => $t['name'] . ', ' . $t['detail'], 'class' => 'h-full w-full object-cover', 'loading' => 'lazy' ) ); // phpcs:ignore ?>
                                <?php else : ?>
                                    <div class="flex h-full w-full items-center justify-center font-display text-6xl font-extrabold text-qh-300">
                                        <?php echo esc_html( mb_substr( $t['name'], 0, 1 ) ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <span class="absolute -bottom-4 rounded-full bg-ink px-4 py-2 text-sm font-semibold text-white <?php echo $reversed ? '-left-3' : '-right-3'; ?>">
                                <?php echo esc_html( $t['product'] ); ?>
                            </span>
                        </div>

                        <figure>
                            <span aria-hidden="true" class="block font-display text-[6rem] font-extrabold leading-[0.6] text-qh">&ldquo;</span>

                            <?php
                            $words     = preg_split( '/\s+/', $t['quote'] );
                            $highlight = array_map( 'strtolower', $t['highlight'] );
                            ?>
                            <blockquote data-scroll-reveal-words
                                class="mt-4 font-display text-[clamp(1.75rem,3.6vw,3rem)] font-bold leading-[1.15] tracking-[-0.03em] text-ink">
                                <?php foreach ( $words as $word ) : ?>
                                    <span data-word
                                        class="<?php echo in_array( strtolower( $word ), $highlight, true ) ? 'text-qh' : ''; ?> mr-[0.25em] inline-block"
                                        style="opacity:0.15">
                                        <?php echo esc_html( $word ); ?>
                                    </span>
                                <?php endforeach; ?>
                            </blockquote>

                            <figcaption data-slide data-slide-from="<?php echo $reversed ? '-60' : '60'; ?>"
                                class="mt-8 flex items-baseline gap-3">
                                <span class="font-display text-xl font-bold text-ink"><?php echo esc_html( $t['name'] ); ?></span>
                                <span class="text-sm text-ink/55"><?php echo esc_html( $t['detail'] ); ?></span>
                            </figcaption>
                        </figure>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p class="text-xs text-ink/40">
                <?php esc_html_e( 'Testimonios ilustrativos. Las experiencias individuales pueden variar.', 'quantia-hub' ); ?>
            </p>
        </div>
    </section>
</div>