<?php
defined( 'ABSPATH' ) || exit;

$building  = 'MMG Tower, piso 22';
$name      = quantia_option( 'site_bank', 'MMG Bank Corporation' );
$address   = 'Ave. Paseo del Mar, Costa del Este';
$city      = 'Ciudad de Panamá, República de Panamá';
$lat       = 9.0092;
$lng       = -79.4668;
$maps_url  = 'https://www.google.com/maps/search/?api=1&query=MMG+Tower+Costa+del+Este+Panama';

$bbox_delta = 0.003;
$bbox = implode( ',', array(
    $lng - $bbox_delta,
    $lat - 0.002,
    $lng + $bbox_delta,
    $lat + 0.002,
) );
$osm_url = sprintf(
    'https://www.openstreetmap.org/export/embed.html?bbox=%s&layer=mapnik&marker=%s,%s',
    rawurlencode( $bbox ),
    $lat,
    $lng
);
?>

<section aria-labelledby="oficina-titulo" class="w-full overflow-hidden bg-white py-12 sm:py-16 lg:py-24">
    <div class="mx-auto grid max-w-6xl grid-cols-1 items-center gap-10 px-5 sm:px-6 lg:grid-cols-[1fr_1.4fr] lg:gap-16">

        <div data-slide data-slide-from="-60">
            <h2 id="oficina-titulo" class="text-sm font-semibold text-ink/50">Nuestras oficinas</h2>
            <address class="mt-3 not-italic">
                <span class="block font-display text-3xl font-extrabold tracking-[-0.03em] text-ink">
                    <?php echo esc_html( $building ); ?>
                </span>
                <span class="mt-2 block text-base leading-relaxed text-ink/65">
                    <?php echo esc_html( $name ); ?><br>
                    <?php echo esc_html( $address ); ?><br>
                    <?php echo esc_html( $city ); ?>
                </span>
            </address>
            <a href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener noreferrer"
                class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-qh-700 transition-colors hover:text-ink">
                Ver en Google Maps
                <?php echo quantia_icon( 'external-link', 'h-4 w-4' ); ?>
            </a>
        </div>

        <div data-parallax data-parallax-speed="0.12">
            <div class="overflow-hidden rounded-[2rem]">
                <iframe
                    src="<?php echo esc_url( $osm_url ); ?>"
                    title="Mapa con la ubicación de <?php echo esc_attr( $name ); ?>"
                    loading="lazy"
                    class="block h-[clamp(220px,40vw,300px)] w-full border-0"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</section>