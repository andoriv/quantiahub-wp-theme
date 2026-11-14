<?php
/**
 * Datos del Centro legal.
 *
 * Normaliza los documentos del CPT `documento_legal` al formato que consumen
 * las plantillas, expone accessors, define el contenido de respaldo y siembra
 * los documentos base al activar el tema.
 *
 * @package QuantiaHub
 */

defined( 'ABSPATH' ) || exit;

/**
 * Normaliza un WP_Post de tipo documento_legal.
 *
 * @param WP_Post $post Documento.
 * @return array
 */
function quantia_get_legal_document( WP_Post $post ): array {
    $summary  = '';
    $updated  = '';
    $sections = array();

    // Intentamos leer con ACF primero
    if ( function_exists( 'get_field' ) ) {
        $summary  = (string) get_field( 'legal_summary', $post->ID );
        $updated  = (string) get_field( 'legal_updated', $post->ID );
        $sections = (array) get_field( 'legal_sections', $post->ID );
    }

    // Si ACF falló o no está activo, leemos directo de los metadatos de WP
    if ( empty( $sections ) ) {
        $summary  = (string) get_post_meta( $post->ID, 'legal_summary', true );
        $updated  = (string) get_post_meta( $post->ID, 'legal_updated', true );
        $sections = (array) get_post_meta( $post->ID, 'legal_sections', true );
    }

    return array(
        'ID'       => $post->ID,
        'slug'     => $post->post_name,
        'title'    => get_the_title( $post ),
        'summary'  => $summary,
        'updated'  => $updated,
        'sections' => quantia_normalize_legal_sections( $sections ),
    );
}

/**
 * Convierte el repeater de secciones al formato consumido por las vistas.
 *
 * @param array $rows Filas del repeater.
 * @return array
 */
function quantia_normalize_legal_sections( array $rows ): array {
    $sections = array();

    foreach ( $rows as $row ) {
        $heading = isset( $row['heading'] ) ? (string) $row['heading'] : '';
        if ( '' === $heading ) {
            continue;
        }
        $sections[] = array(
            'heading' => $heading,
            'blocks'  => quantia_normalize_legal_blocks( isset( $row['blocks'] ) && is_array( $row['blocks'] ) ? $row['blocks'] : array() ),
        );
    }

    return $sections;
}

/**
 * Convierte el flexible content de ACF en un array plano de bloques.
 *
 * @param array $rows Filas del flexible content.
 * @return array
 */
function quantia_normalize_legal_blocks( array $rows ): array {
    $blocks = array();

    foreach ( $rows as $row ) {
        $layout = isset( $row['acf_fc_layout'] ) ? $row['acf_fc_layout'] : '';

        switch ( $layout ) {
            case 'p':
                $blocks[] = array( 'type' => 'p', 'text' => (string) ( $row['text'] ?? '' ) );
                break;

            case 'list':
                $items = array();
                foreach ( $row['items'] ?? array() as $item ) {
                    $text = isset( $item['text'] ) ? (string) $item['text'] : '';
                    if ( '' !== $text ) {
                        $items[] = $text;
                    }
                }
                $blocks[] = array(
                    'type'    => 'list',
                    'ordered' => ! empty( $row['ordered'] ),
                    'items'   => $items,
                );
                break;

            case 'defs':
                $items = array();
                foreach ( $row['items'] ?? array() as $item ) {
                    $term = isset( $item['term'] ) ? (string) $item['term'] : '';
                    $text = isset( $item['text'] ) ? (string) $item['text'] : '';
                    if ( '' === $term && '' === $text ) {
                        continue;
                    }
                    $items[] = array( 'term' => $term, 'text' => $text );
                }
                $blocks[] = array( 'type' => 'defs', 'items' => $items );
                break;

            case 'contact':
                $items = array();
                foreach ( $row['items'] ?? array() as $item ) {
                    $label = isset( $item['label'] ) ? (string) $item['label'] : '';
                    if ( '' === $label ) {
                        continue;
                    }
                    $items[] = array(
                        'label' => $label,
                        'value' => (string) ( $item['value'] ?? '' ),
                        'href'  => (string) ( $item['href'] ?? '' ),
                    );
                }
                $blocks[] = array( 'type' => 'contact', 'items' => $items );
                break;

            case 'fee_tables':
                $blocks[] = array( 'type' => 'feeTables' );
                break;

            case 'ref':
                $blocks[] = array(
                    'type'   => 'ref',
                    'text'   => (string) ( $row['text'] ?? '' ),
                    'label'  => (string) ( $row['label'] ?? '' ),
                    'target' => (string) ( $row['target'] ?? '' ),
                );
                break;
        }
    }

    return $blocks;
}

/**
 * Devuelve todos los documentos legales publicados, ordenados por menu_order.
 *
 * @return array
 */
function quantia_get_all_legal_documents(): array {
    $query = new WP_Query( array(
        'post_type'      => 'documento_legal',
        'post_status'    => 'publish',
        'posts_per_page' => 50,
        'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
    ) );

    $docs = array();
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $docs[] = quantia_get_legal_document( get_post() );
        }
        wp_reset_postdata();
    }

    return $docs;
}

/**
 * Contenido de respaldo del Centro legal.
 *
 * Portado desde legalTerms.ts, legalPrivacy.ts, legalClaims.ts y legal.ts
 * del proyecto React original. El contenido de los documentos legales es
 * responsabilidad del equipo legal de MMG Bank.
 *
 * @return array
 */
function quantia_fallback_legal_documents(): array {
    return array(
        'terminos-legales' => array(
            'title'    => __( 'Términos legales', 'quantia-hub' ),
            'summary'  => __( 'Términos y Condiciones de Quantia: las reglas de la cuenta, sus productos y servicios.', 'quantia-hub' ),
            'updated'  => __( 'Octubre 2025', 'quantia-hub' ),
            'sections' => quantia_legal_terms_sections(),
        ),
        'aviso-de-privacidad' => array(
            'title'    => __( 'Aviso de Privacidad', 'quantia-hub' ),
            'summary'  => __( 'Para qué solicitamos tu información personal, cómo la protegemos y qué derechos puedes ejercer sobre ella.', 'quantia-hub' ),
            'updated'  => __( 'Octubre 2025', 'quantia-hub' ),
            'sections' => quantia_legal_privacy_sections(),
        ),
        'aclaraciones-y-reclamaciones' => array(
            'title'    => __( 'Aclaraciones y reclamaciones', 'quantia-hub' ),
            'summary'  => __( 'El procedimiento a seguir si tienes una queja, reclamo o disconformidad con el servicio bancario de MMG Bank.', 'quantia-hub' ),
            'updated'  => __( 'Octubre 2025', 'quantia-hub' ),
            'sections' => quantia_legal_claims_sections(),
        ),
        'costos-y-comisiones' => array(
            'title'    => __( 'Costos y comisiones', 'quantia-hub' ),
            'summary'  => __( 'Tarifario (Anexo I): todos los cargos y comisiones de Quantia en una tabla simple.', 'quantia-hub' ),
            'updated'  => __( 'Octubre 2025', 'quantia-hub' ),
            'sections' => quantia_legal_fees_sections(),
        ),
    );
}

/* =========================================================
 * Términos legales — portado de legalTerms.ts
 * ========================================================= */

function quantia_legal_terms_sections(): array {
    return array(
        array(
            'heading' => __( '1. Definiciones', 'quantia-hub' ),
            'blocks'  => array(
                array(
                    'type'  => 'defs',
                    'items' => array(
                        array( 'term' => __( 'Cajero Automático', 'quantia-hub' ), 'text' => __( 'Equipo dispensador y/o recibidor de dinero integrado y/o afiliado a la Red Clave y/o a cualesquiera otras redes a las que la Red Clave esté integrada o afiliada, que permite la realización de una o más Transacciones Electrónicas.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Cliente', 'quantia-hub' ), 'text' => __( 'Persona natural que adquiere uno o más de los servicios o productos a que se refiere este Contrato, que de acuerdo con los registros de MMG haya sido autorizada para girar órdenes de pago e instrucciones contra la Cuenta de Quantia y/o hacer uso de los productos y servicios que hubiesen sido contratados por el Cliente. Toda referencia al término Cliente se entenderá hecha a las "Personas Autorizadas".', 'quantia-hub' ) ),
                        array( 'term' => __( 'Código QR', 'quantia-hub' ), 'text' => __( 'Módulo para almacenar información en una matriz de puntos o en un código de barras bidimensional.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Debida Diligencia', 'quantia-hub' ), 'text' => __( 'El proceso de identificación del Cliente y sus fuentes de ingreso, llevado a cabo por MMG, en atención a lo establecido en el Acuerdo No. 10-2015 expedido por la Superintendencia de Bancos de Panamá, por el cual se dictan pautas para la prevención del uso indebido de los servicios bancarios y fiduciarios (incluyendo sus posteriores reformas) y demás disposiciones legales que se refieran al tema.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Día Hábil', 'quantia-hub' ), 'text' => __( 'Todo día que no sea sábado, domingo, Día de Fiesta o Duelo Nacional o feriado municipal en el Distrito de Panamá.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Dólares o "$"', 'quantia-hub' ), 'text' => __( 'La moneda de curso legal de los Estados Unidos de América.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Empleador', 'quantia-hub' ), 'text' => __( 'Empresa que adquiere y contrata los servicios de MMG Bank Corporation para el pago de su planilla laboral mediante el acreditamiento de las prestaciones laborales a que tenga derecho cada trabajador, a través de la plataforma digital de Quantia.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Estado de Cuenta', 'quantia-hub' ), 'text' => __( 'Reporte de movimiento de la Cuenta Quantia durante un determinado período de tiempo, preparado por MMG, el cual contendrá como mínimo la siguiente información: saldo inicial y final de la cuenta al primer y al último día del período a que se refiere el Estado de Cuenta; débitos y créditos realizados a la Cuenta Quantia, con sus respectivos montos y fechas en que se efectuaron durante el período a que se refiere el Estado de Cuenta.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Horario de Oficina y Atención al Público', 'quantia-hub' ), 'text' => __( 'El horario fijado de tiempo en tiempo por MMG y dado a conocer al público en general mediante avisos colocados permanentemente en las instalaciones y/o en la página o sitio de internet de MMG.', 'quantia-hub' ) ),
                        array( 'term' => __( 'MMG', 'quantia-hub' ), 'text' => __( 'MMG Bank Corporation, sociedad anónima organizada bajo las leyes de Panamá, inscrita a la Ficha 421669, Documento 380693, de la Sección Mercantil del Registro Público de Panamá, titular de una Licencia General otorgada por la Superintendencia de Bancos de Panamá y de una licencia de Casa de Valores otorgada por la Superintendencia del Mercado de Valores de Panamá.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Transacciones vía Código QR', 'quantia-hub' ), 'text' => __( 'Transacciones a través de un código QR que es generado por el punto de venta o aplicación móvil y que se escanea desde la billetera electrónica.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Partes', 'quantia-hub' ), 'text' => __( 'Se refiere a MMG y el Cliente en conjunto.', 'quantia-hub' ) ),
                        array( 'term' => __( 'PIN ("Personal Identification Number", por sus siglas en inglés)', 'quantia-hub' ), 'text' => __( 'Número de identificación personal que corresponde al código secreto que debe ser usado para poder realizar Transacciones Electrónicas, usar la Tarjeta de Débito, o hacer uso de cualquier otro producto o servicio contratado por el Cliente que requiera un PIN.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Quantia o billetera electrónica', 'quantia-hub' ), 'text' => __( 'Es una plataforma digital que integra funcionalidades de ahorro, inversión, planificación financiera y pensiones voluntarias. Incluye apertura de cuentas digitales, transferencias ACH y P2P, pagos a comercios y servicios, gestión de portafolios de inversión, afiliación y administración de fondos de pensión voluntarios, y acceso a contenido educativo financiero.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Q15', 'quantia-hub' ), 'text' => __( 'Servicio de valor agregado ofrecido que permite a los empleados de las empresas que hayan contratado el servicio de pago de planilla por medio de Quantia, recibir un anticipo del cobro de su salario.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Red Clave', 'quantia-hub' ), 'text' => __( 'Sistema de telecomunicación y plataforma electrónica administrado por la empresa TELERED (incluyendo cualquier sucesor o cesionario de ésta), al que MMG está integrado, por medio del cual el Cliente puede efectuar Transacciones Electrónicas.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Kuara', 'quantia-hub' ), 'text' => __( 'Sistema de telecomunicación y plataforma electrónica administrado por la empresa Innovación y Pagos, S.A. (incluyendo cualquier sucesor o cesionario de ésta), al que MMG está integrado, por medio del cual el Cliente puede efectuar Transacciones Electrónicas.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Tarjeta Débito', 'quantia-hub' ), 'text' => __( 'Tarjeta emitida por MMG a solicitud del Cliente, la cual le permite realizar Transacciones Electrónicas.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Transacciones Electrónicas', 'quantia-hub' ), 'text' => __( '1) Jubilación de dinero a través de Cajeros Automáticos; 2) Pagos por medio de Terminales de Punto de Venta o Cajeros Automáticos, a consecuencia de la adquisición o compra de bienes y servicios; 3) Donaciones por medio de Cajeros Automáticos o Terminales de Punto de Venta; 4) Consultas de saldos por medio de la Red Clave; 5) Cualquier otra operación o transacción que en el futuro sea autorizada u ofrecida por MMG a través de la Red Clave y/o Kuara; 6) Cualquiera de las transacciones antes indicadas por medio de otras redes o sistemas electrónicos, propios o de terceros que en el futuro MMG ponga a disposición del Cliente.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Terminales de Punto de Venta', 'quantia-hub' ), 'text' => __( 'Establecimientos comerciales, industriales, profesionales y de cualquier otra naturaleza, sean públicos, privados o mixtos, que estén afiliados o integrados a la Red Clave para aceptar pagos por medio de Tarjetas Débito y/o Tarjetas de Crédito.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Usuario', 'quantia-hub' ), 'text' => __( 'Persona Autorizada para hacer uso de la aplicación Quantia.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Quantia AFP', 'quantia-hub' ), 'text' => __( 'Quantia AFP Corp, sociedad constituida bajo las leyes de la República de Panamá, inscrita al Folio 155655851, Sección Mercantil del Registro Público de Panamá, con la licencia de Administradora de Inversiones de Fondos de Jubilación y Pensiones desde 2018.', 'quantia-hub' ) ),
                    ),
                ),
            ),
        ),
        array(
            'heading' => __( '2. Aceptación del Servicio', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Al registrarse y utilizar la plataforma, el Cliente acepta los presentes términos y condiciones, así como las políticas internas de MMG Bank Corporation y la normativa vigente de la República de Panamá.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '3. Descripción General del Servicio', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Quantia es una plataforma digital de MMG Bank Corporation que integra funcionalidades de ahorro, inversión, planificación financiera y pensiones voluntarias. Incluye apertura de cuentas digitales, cuentas para recepción del pago de planillas, transferencias entre cuentas bancarias mantenidas en MMG o terceros, transferencias ACH mediante la Red Clave y P2P, pagos a servicios y comercios afiliados, gestión de portafolios de inversión, afiliación y administración de fondos de pensión voluntarios (Quantia AFP), y acceso a contenido educativo financiero.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'El Cliente podrá mantener una cuenta individual o una cuenta para la recepción del pago de planillas y realizar las siguientes operaciones, a través de su plataforma:', 'quantia-hub' ) ),
                array(
                    'type'  => 'list',
                    'items' => array(
                        __( 'Consultar saldos y transacciones.', 'quantia-hub' ),
                        __( 'Enviar y recibir avisos y comunicaciones a/de MMG.', 'quantia-hub' ),
                        __( 'Pagos a servicios y comercios afiliados.', 'quantia-hub' ),
                        __( 'Otras operaciones que de tiempo en tiempo sean ofrecidas por MMG.', 'quantia-hub' ),
                    ),
                ),
                array( 'type' => 'p', 'text' => __( 'MMG tendrá en cualquier momento el derecho a eliminar, suspender y reactivar cualquiera de las operaciones antes descritas, así como a incluir operaciones adicionales.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'MMG prestará al Cliente el servicio por medio de la red pública de Internet, con sujeción a los requisitos y procedimientos aquí establecidos, a las leyes y reglamentos que adopten las autoridades de la República de Panamá.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Todas las operaciones que afecten saldos de la Cuenta Quantia del Cliente serán reflejadas en los respectivos Estados de Cuenta los cuales podrán ser consultados en la plataforma, de acuerdo con los términos y condiciones aplicables a dicha cuenta.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'La remisión o puesta a disposición del Estado de Cuenta al Cliente se hará mediante los canales de comunicación de la plataforma de Quantia. Todo Estado de Cuenta se considerará correcto y aceptado por el Cliente si dentro de los treinta (30) días calendario siguientes a la fecha de la ejecución de la transacción, MMG no hubiese recibido reclamo alguno respecto al mismo, salvo que se trate de errores matemáticos.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '4. Depósitos', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Todo depósito que se pretenda realizar a la Cuenta Quantia está sujeto al cumplimiento de las siguientes condiciones:', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'a) Podrá hacerse bajo cualquiera de las siguientes formas: (i) a través de medios electrónicos autorizados por MMG y contratados por el Cliente; (ii) mediante transferencia bancaria local; o (iii) por cualquier otro medio que en el futuro sea establecido y ofrecido por MMG.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'b) MMG también tendrá derecho a rechazar cualquier depósito, sin incurrir en responsabilidad alguna por tal hecho, en cualquiera de los siguientes casos: (i) si los instrumentos negociables a ser depositados provienen de: (1) persona originaria, residente o establecida en algún país con el cual la República de Panamá tenga prohibido realizar transacciones o negocios o recibir bienes, ya sea por decisión propia, de algún organismo internacional de Derecho Público del cual Panamá fuese parte o de algún organismo público de investigación de cualquier país; o (2) persona que aparezca en alguna base de datos o lista elaborada por entidades de Derecho Público o Privado o por organismos públicos de investigación de cualquier país sobre personas vinculadas con posibles actividades o conductas ilícitas; (ii) si a juicio de MMG, los dineros o documentos negociables a ser depositados provienen de actividades ilícitas.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '5. Jubilacións y Órdenes de Pago', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Toda solicitud de jubilación de fondos y/u orden de pago contra la Cuenta Quantia estará sujeta al cumplimiento de las siguientes condiciones:', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'a) Debe hacerse por cualquiera de los siguientes medios: (i) transacciones electrónicas, a través del uso de la Tarjeta de Débito; (ii) instrucciones impartidas por medio de la plataforma de Quantia. Queda expresamente aceptado por el Cliente que la Cuenta Quantia no permitirá el jubilación de fondos y/u órdenes de pago mediante cheques.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'b) La Cuenta Quantia deberá tener fondos de disponibilidad inmediata y en cantidad suficiente para cubrir los jubilacións u órdenes de pago solicitados, los cuales deberán estar libres de gravámenes, medidas cautelares y/o restricciones legales o contractuales.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'c) MMG se reserva el derecho a rechazar cualquier solicitud de jubilación u orden de pago o transferencia, sin incurrir en responsabilidad alguna, en cualquiera de los siguientes casos:', 'quantia-hub' ) ),
                array(
                    'type'  => 'list',
                    'items' => array(
                        __( '(i) incumplimiento de cualquiera de las condiciones antes mencionadas;', 'quantia-hub' ),
                        __( '(ii) que a juicio de MMG existan órdenes u instrucciones contradictorias por parte del Cliente;', 'quantia-hub' ),
                        __( '(iii) que la persona a cuyo favor se debe hacer el pago (1) sea originaria, residente o establecida en algún país con el cual la República de Panamá tenga prohibido realizar transacciones o negocios o recibir bienes, ya sea por decisión propia o adoptada por algún organismo internacional de Derecho Público del que Panamá fuese parte o algún organismo público de investigación de cualquier país; o (2) aparezca en alguna base de datos o lista elaborada por entidades de Derecho Público o Privado o por organismos públicos de investigación de cualquier país sobre personas vinculadas con posibles actividades o conductas ilícitas;', 'quantia-hub' ),
                        __( '(iv) que a juicio de MMG, los dineros o fondos a ser transferidos o retirados tengan como fin actividades ilícitas.', 'quantia-hub' ),
                    ),
                ),
            ),
        ),
        array(
            'heading' => __( '6. Inversiones', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'MMG prestará el servicio de cuenta de inversión bajo las siguientes condiciones:', 'quantia-hub' ) ),
                array(
                    'type'  => 'list',
                    'items' => array(
                        __( 'La apertura, administración y operación de la cuenta estarán sujetas a las políticas internas y a la normativa aplicable vigente en la República de Panamá.', 'quantia-hub' ),
                        __( 'El Cliente deberá cumplir con todos los requisitos de debida diligencia establecidos por MMG, así como mantener actualizada su información personal y financiera.', 'quantia-hub' ),
                        __( 'Todas las operaciones y solicitudes estarán condicionadas a la disponibilidad de fondos suficientes y a la ausencia de restricciones legales o contractuales sobre los mismos.', 'quantia-hub' ),
                        __( 'La ejecución de órdenes de inversión estará supeditada a la validación previa por parte de MMG y podrá ser rechazada en caso de incumplimiento de las condiciones establecidas, instrucciones contradictorias, restricciones regulatorias o sospecha de actividades ilícitas.', 'quantia-hub' ),
                        __( 'Las órdenes de compra y venta son solicitadas por el Cliente y ejecutadas exclusivamente por el personal autorizado de MMG.', 'quantia-hub' ),
                        __( 'Las operaciones de compra se efectúan según el monto designado, mientras que las ventas se procesan sobre la cantidad de acciones especificadas.', 'quantia-hub' ),
                        __( 'El Cliente tiene posibilidad de personalizar el portafolio y establecer aportes recurrentes.', 'quantia-hub' ),
                        __( 'Las comisiones se configuran por rangos y se aplican conforme al instrumento operado.', 'quantia-hub' ),
                    ),
                ),
                array( 'type' => 'p', 'text' => __( 'MMG dispondrá de un plazo máximo de siete (7) días calendario, contados a partir de la recepción y validación de la orden de compra, para ejecutar la adquisición de acciones o participaciones en fondos de inversión solicitadas por el Cliente. MMG no será responsable por las variaciones en el valor de las acciones o participaciones de los fondos que ocurran durante dicho plazo, eximiendo expresamente a MMG de cualquier reclamación derivada de cambios en el precio de mercado entre la fecha de solicitud y la fecha de ejecución de la orden.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'En caso de que la ejecución de la orden de compra se vea impedida, retrasada o dificultada por causas de fuerza mayor, caso fortuito, suspensión de operaciones del mercado, restricciones regulatorias, fallos tecnológicos, interrupciones en los servicios de terceros, o cualquier otra circunstancia fuera del control razonable de MMG, el plazo de ejecución se extenderá por el tiempo que persista la causa que impida la ejecución.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'MMG notificará al Cliente sobre la existencia de la causa de fuerza mayor y la reanudación de la ejecución se realizará en la primera oportunidad disponible, sin que ello implique responsabilidad alguna para MMG por las variaciones en el valor de los instrumentos durante dicho periodo.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '7. Fondo de Pensión Voluntario', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'El Cliente tiene la posibilidad de afiliarse a Quantia AFP y programar aportes mensuales automáticos. Las modificaciones en la distribución del portafolio se encuentran restringidas a un máximo de tres veces por año. Los jubilacións requieren validación y aprobación por parte de MMG. Asimismo, se permite consultar el rendimiento y el historial de movimientos.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Asimismo, el Cliente otorga su consentimiento previo, expreso e informado para que MMG comparta con Quantia AFP la información personal, financiera y transaccional que sea estrictamente necesaria para fines relacionados con la afiliación, administración y manejo de su jubilación privada, conforme a la normativa vigente de la República de Panamá.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'De acuerdo con el Artículo 5 de la Ley 10 de 1993, los planes privados de ahorro para la jubilación requieren un mínimo de diez (10) años de cotización para permitir jubilacións voluntarios, o cinco (5) años en casos de ingreso o cumplimiento de los 55 años de edad. No obstante, se autorizan jubilacións anticipados sin penalización en situaciones excepcionales como fallecimiento, incapacidad, emergencias médicas o graves situaciones financieras.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '8. Programa de Referidos', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'El Cliente puede participar mediante códigos únicos. La plataforma registra y monitorea el desempeño del programa.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '9. Asignación de Credenciales de Uso', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Una vez que MMG apruebe la solicitud del Cliente para acceder al servicio de Quantia, MMG proporcionará al Cliente las siguientes credenciales: (a) una contraseña temporal ("password") y (b) un identificador de usuario. Estas credenciales permitirán al Cliente utilizar el servicio.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'El Cliente reconoce que dichas credenciales constituyen un mecanismo de seguridad diseñado para proteger sus intereses, evitando el uso no autorizado o indebido de su billetera electrónica por parte de terceros.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '10. Vulnerabilidad de Internet y Exoneración de Responsabilidad', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'El Cliente reconoce que todos los sistemas y redes de telecomunicación públicas y abiertas como Internet así como los sistemas tecnológicos y programas de computadora ("firewalls"), incluidos los propios y de terceros usados por MMG para prestar el servicio, son vulnerables y susceptibles de ser atacados y penetrados por terceras personas de forma ilegal y sin autorización, por lo cual estas personas pudiesen contaminar, apropiarse y hacer uso indebido de la información almacenada en tales sistemas tecnológicos, incluida información relacionada con la(s) Cuenta(s) Quantia y las credenciales del Cliente.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'MMG declara, y así lo reconoce el Cliente, que no puede garantizar ni asegurar que las redes de telecomunicación usadas para prestar el servicio, sus sistemas tecnológicos y sus programas de protección estarán libres del riesgo antes descrito, y en consecuencia el Cliente acuerda que MMG queda relevado de toda responsabilidad por cualquier daño o perjuicio que pudiese sufrir el Cliente en caso de ocurrir dicho riesgo.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '11. Disponibilidad del Servicio', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'MMG procurará que la plataforma Quantia esté disponible de forma ininterrumpida, sujeto al buen y normal funcionamiento de la red abierta de Internet, las redes de transmisión de los servicios de telecomunicación, los servicios de electricidad, los procesos de corrida de los sistemas de MMG y/o los sistemas tecnológicos usados por MMG o de terceros contratados para la prestación del servicio y a la ausencia de alguno de los eventos de suspensión del servicio a que se refiere la cláusula siguiente.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Las operaciones realizadas por medio del servicio serán procesadas en un plazo de dos (2) días hábiles siguientes al día en que fueron realizadas.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'El Cliente deberá asegurarse de que las operaciones que involucren pagos y/o transferencias sean realizadas con la antelación debida, a fin de que los pagos o transferencias se reciban dentro del plazo que el Cliente haya estipulado con el beneficiario de tales pagos.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '12. Suspensión del Servicio', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'MMG se reserva el derecho a suspender la prestación del servicio, sin necesidad de dar aviso previo al Cliente y sin incurrir en responsabilidad alguna, en caso de presentarse cualquiera de los siguientes eventos:', 'quantia-hub' ) ),
                array(
                    'type'  => 'list',
                    'items' => array(
                        __( 'Por razón de fallas o daños en: (i) los sistemas y/o redes de telecomunicación, propios o de terceros, (ii) los servicios públicos de electricidad, (iii) los sistemas tecnológicos utilizados por MMG para la prestación de los servicios.', 'quantia-hub' ),
                        __( 'Por casos fortuitos o hechos de fuerza mayor.', 'quantia-hub' ),
                        __( 'Por trabajos de mantenimiento y reparación requeridos a causa de daños o actos ocurridos de forma imprevista.', 'quantia-hub' ),
                        __( 'Por cualesquiera de las causas de suspensión o terminación del contrato señaladas en las secciones I y II aplicables a la Cuenta Quantia.', 'quantia-hub' ),
                        __( 'Cuando MMG tenga sospecha de que el PIN y/o el número de usuario se están usando de forma no autorizada o en violación a lo dispuesto en este contrato.', 'quantia-hub' ),
                        __( 'Cuando MMG lo considere conveniente para proteger y mantener la integridad de las redes, equipos o sistemas usados por MMG para prestar el servicio.', 'quantia-hub' ),
                        __( 'Por insuficiencia de fondos en la Cuenta Quantia del Cliente.', 'quantia-hub' ),
                        __( 'Cuando tenga una sospecha razonable o presuma que la Cuenta Quantia está siendo utilizada para actividades ilícitas, operaciones no autorizadas, fraude, lavado de activos, financiamiento del terrorismo, evasión de sanciones u otros fines contrarios a la ley, la regulación aplicable o sus políticas internas.', 'quantia-hub' ),
                    ),
                ),
                array( 'type' => 'p', 'text' => __( 'Siempre que sea posible, MMG procurará notificar anticipadamente al Cliente sobre la suspensión del servicio.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '13. Cuenta Inactiva', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'En caso de que la Cuenta Quantia se mantenga inactiva, esta será cerrada y los dineros depositados en ella serán traspasados al Banco Nacional de Panamá o a quien la Superintendencia de Bancos de Panamá indique, de acuerdo con las reglas establecidas de tiempo en tiempo por dicha entidad o por la ley aplicable a tales casos. Para los fines de esta cláusula, se entiende que una cuenta está inactiva cuando el Cliente no hubiese realizado movimientos de dinero, ya sea por medio de jubilacións o depósitos en la misma, y el Cliente no hubiese respondido a los avisos enviados por MMG a la dirección suministrada por el Cliente, informando tal circunstancia, o el Cliente no se hubiese podido localizar físicamente o vía telefónica. Los movimientos hechos a la Cuenta Quantia por razón de intereses generados y/o los débitos hechos por MMG para el pago de tarifas o comisiones debidas por el Cliente no se reputarán como movimientos de la Cuenta Quantia. MMG tendrá derecho a establecer y cobrar comisiones sobre aquellas cuentas que no presenten movimientos.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '14. Obligaciones del Cliente', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'En adición a las demás obligaciones establecidas en otras secciones del Contrato, el Cliente se obliga a:', 'quantia-hub' ) ),
                array(
                    'type'  => 'list',
                    'items' => array(
                        __( 'Notificar de inmediato a MMG sobre: (i) cualquier situación o hecho que implique el uso indebido de la Cuenta Quantia por personas no autorizadas o que pueda afectar negativamente a ésta; (ii) el hurto, robo o pérdida de Tarjeta Débito tan pronto tenga conocimiento de tal hecho; (iii) cualquier cambio en cuanto a su dirección registrada.', 'quantia-hub' ),
                        __( 'No girar instrucciones ni órdenes de pago contra la Cuenta Quantia sin asegurarse previamente que ésta tenga fondos de disponibilidad inmediata y en cantidad suficiente para cumplir tales órdenes.', 'quantia-hub' ),
                        __( 'Aceptar plenamente la responsabilidad por todas las operaciones realizadas a través de Quantia utilizando sus credenciales.', 'quantia-hub' ),
                        __( 'Utilizar la Cuenta Quantia sólo para actividades lícitas.', 'quantia-hub' ),
                        __( 'No permitir que la Cuenta Quantia sea utilizada por terceras personas distintas al Cliente.', 'quantia-hub' ),
                        __( 'Suministrar a MMG la información y documentación que sea requerida por éste en cumplimiento de sus políticas internas y/o disposiciones legales, a fin de que en todo momento la misma se mantenga actualizada.', 'quantia-hub' ),
                        __( 'Observar las medidas de seguridad recomendadas por MMG y tomar las precauciones debidas con el fin de proteger y preservar la confidencialidad de las claves y los códigos de identificación asignados por MMG para realizar operaciones.', 'quantia-hub' ),
                        __( 'Notificar dentro de los plazos estipulados cualquier error o discrepancia que pudiese presentarse en los Estados de Cuenta o demás documentos en que se informe sobre el estado o situación de la Cuenta Quantia.', 'quantia-hub' ),
                        __( 'Cumplir con las obligaciones fiscales que le sean aplicables de acuerdo con las leyes de su país de origen, residencia o domicilio, incluyendo la obligación de declarar o reportar la existencia de la Cuenta Quantia y/o los dineros acreditados en ésta.', 'quantia-hub' ),
                    ),
                ),
                array( 'type' => 'p', 'text' => __( 'El incumplimiento de cualquiera obligación dará a MMG, sin incurrir en responsabilidad alguna, el derecho a terminar el contrato o suspender sus efectos o los servicios.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'El Cliente será responsable de forma exclusiva de los daños y perjuicios que puedan resultar por el incumplimiento de sus obligaciones.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '14. Duración y Terminación del Contrato', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Los servicios y productos bancarios a que se refiere este Contrato serán por tiempo indefinido, salvo que MMG y el Cliente hubiesen pactado otra cosa. Cualquiera de las Partes tendrá derecho a dar por terminado el contrato en cualquier momento, sin necesidad de explicar las razones de tal decisión, pero la terminación y/o el jubilación de los saldos mantenidos en la Cuenta Quantia estará condicionada a lo siguiente:', 'quantia-hub' ) ),
                array(
                    'type'  => 'list',
                    'items' => array(
                        __( 'Si la Cuenta Quantia o los dineros depositados en ésta son objeto de alguna medida cautelar o restrictiva, legal o contractual, la entrega tendrá lugar cuando tales medidas hubiesen sido levantadas y MMG haya recibido la respectiva resolución judicial por la cual se ordena levantar la medida cautelar.', 'quantia-hub' ),
                        __( 'Una vez terminado el contrato y siempre que la Cuenta Quantia o los fondos en ella depositados no se encuentren limitados por los supuestos indicados, los saldos mantenidos en ésta serán puestos a disposición del Cliente o de quien éste designe, previa deducción de cualquier suma adeudada por el Cliente a MMG. A partir de la fecha de terminación, el saldo de la Cuenta Quantia no producirá intereses.', 'quantia-hub' ),
                    ),
                ),
            ),
        ),
        array(
            'heading' => __( '15. Servicios Adicionales de Valor Agregado', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( '15.1 Emisión y Uso de Tarjeta Débito', 'quantia-hub' ) ),
                array(
                    'type'  => 'defs',
                    'items' => array(
                        array( 'term' => __( 'Descripción del Servicio', 'quantia-hub' ), 'text' => __( 'El uso de la Tarjeta Débito constituye un servicio en favor del Cliente el cual le permite efectuar Transacciones Electrónicas, con sujeción a los términos y condiciones establecidos en esta Sección y en la Sección I de este Contrato. Este servicio requiere que el Cliente sea titular de una Cuenta Quantia, contra la cual se harán los cargos derivados de las transacciones realizadas por medio de la Tarjeta Débito. Este servicio está disponible para cuentas individuales.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Propiedad de la Tarjeta Débito', 'quantia-hub' ), 'text' => __( 'La Tarjeta Débito es intransferible y de propiedad de MMG.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Asignación del PIN', 'quantia-hub' ), 'text' => __( 'Para usar la Tarjeta Débito el Cliente requiere de un PIN, el cual será proporcionado por MMG, para uso exclusivo del Cliente. El Cliente podrá solicitar el cambio de su PIN, mediante nota suscrita dirigida a MMG, para lo cual deberá seguir el procedimiento que MMG tenga establecido. El Cliente se obliga a (i) mantener la reserva y confidencialidad del PIN y a no revelarlo bajo ninguna forma ni a permitir el uso de la Tarjeta Débito y/o del PIN a terceros, y (ii) observar las sugerencias e instrucciones que MMG expida en cuanto al uso y manejo de la Tarjeta Débito con el propósito de evitar su uso indebido.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Límite de montos por transacción', 'quantia-hub' ), 'text' => __( 'MMG tendrá derecho en cualquier momento a limitar el monto de las transacciones que podrán realizarse a través de la Tarjeta Débito.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Responsabilidad del Cliente', 'quantia-hub' ), 'text' => __( 'Queda acordado que todas las Transacciones Electrónicas efectuadas mediante el uso de la Tarjeta Débito se entienden realizadas por el Cliente. Por lo tanto, el Cliente asume la exclusiva y total responsabilidad de los actos y las operaciones resultantes de tales Transacciones Electrónicas, de conformidad con lo establecido en este contrato, salvo que se compruebe que la Transacción Electrónica no fue realizada por el Cliente.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Débito a la Cuenta', 'quantia-hub' ), 'text' => __( 'Las Transacciones Electrónicas que impliquen el jubilación de fondos estarán sujetas a lo siguiente: a) que la Cuenta Quantia mantenga fondos de disponibilidad inmediata y suficientes para cubrir la cantidad que se desee retirar; b) que la Cuenta Quantia y/o los fondos depositados estén libres de medidas cautelares y/o restricciones legales o contractuales. Las transacciones de jubilación de fondos con la Tarjeta Débito se procesarán de forma inmediata.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Comprobante de transacción', 'quantia-hub' ), 'text' => __( 'El Cliente reconoce que los recibos y comprobantes en los cuales consten jubilacións de efectivo o pagos efectuados por medio de la Tarjeta Débito, aunque no tengan su firma, incluyendo cualquier registro, dispositivo u otro medio mecánico o electrónico, constituyen plena prueba de la Transacción Electrónica realizada por medio de la Tarjeta Débito, salvo que se compruebe que la Transacción Electrónica no fue realizada o autorizada por el Cliente. Los recibos, comprobantes o constancias tendrán el mismo valor probatorio aun cuando en éstos, sus datos o los de la Tarjeta Débito resulten ilegibles. El Cliente acepta que la falta de su firma en los comprobantes y recibos de pago en los Terminales de Punto de Venta no lo liberará de su responsabilidad siempre que la transacción se hubiese realizado a través de o mediante su Tarjeta de Débito, salvo que se compruebe que la Transacción Electrónica no fue realizada o autorizada por el Cliente.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Acceso a Cajeros Automáticos y Terminales de Punto de Venta', 'quantia-hub' ), 'text' => __( 'MMG procurará que el Cliente tenga acceso ininterrumpido a Cajeros Automáticos, sean o no de propiedad de MMG, excepto cuando la disponibilidad del servicio se vea interrumpida por los casos descritos en la cláusula diez (10). En el caso de los Terminales de Puntos de Venta, el Cliente tendrá acceso a éstos de acuerdo con el horario de los establecimientos.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Suspensión del Servicio', 'quantia-hub' ), 'text' => __( 'MMG tendrá en todo momento derecho a suspender la prestación de los servicios, sin incurrir en responsabilidad alguna y sin necesidad de dar aviso previo al Cliente, en cualquiera de los siguientes casos: (i) insuficiencia de fondos en la Cuenta Quantia; (ii) trabajos de mantenimiento y reparación de los Cajeros Automáticos y/o la Red Clave, incluyendo las redes a las cuales la Red Clave esté integrada; (iii) cuando MMG tenga sospecha de que la Tarjeta Débito o el PIN se está utilizando de forma no autorizada o en violación a lo dispuesto en el presente contrato; (iv) cuando MMG lo considere conveniente para proteger y mantener la integridad de los servicios; (v) por cualesquiera de las causas de suspensión o terminación indicadas en la cláusula trece (13) de la Sección 1; (vi) por casos fortuitos o actos de fuerza mayor; (vii) por razón de fallas o daños en: (a) los sistemas en la Red Clave o las redes a las cuales esté integrada, (b) los servicios públicos de electricidad y telecomunicación, o (c) los sistemas tecnológicos utilizados por MMG para la prestación del servicio. Siempre que sea posible, MMG procurará notificar anticipadamente al Cliente sobre la suspensión del servicio.', 'quantia-hub' ) ),
                    ),
                ),
                array( 'type' => 'p', 'text' => __( 'Exoneración de responsabilidad de MMG. MMG no será responsable por cualquier daño o perjuicio que se pudiese causar al ocurrir cualquiera de los siguientes eventos:', 'quantia-hub' ) ),
                array(
                    'type'  => 'list',
                    'items' => array(
                        __( 'Falta de fluido eléctrico, fallas mecánicas o casos fortuitos o de fuerza mayor, que afecten el funcionamiento de cualquiera de los Cajeros Automáticos y/o acceso a Terminales de Punto de Venta, la Red Clave o las redes a las que esté integrada, los servicios públicos de telecomunicación y/o de suministro de electricidad.', 'quantia-hub' ),
                        __( 'Por hechos que constituyan caso fortuito o fuerza mayor, o por causa atribuible directa o indirectamente a alguna disputa o circunstancia fuera del control de MMG, que imposibilite o impida a MMG prestar el servicio o cumplir con las obligaciones derivadas de esta Sección.', 'quantia-hub' ),
                        __( 'En caso de que alguno de los establecimientos comerciales afiliados a la Red decida no aceptar la Tarjeta Débito o se niegue a facilitar los servicios al Cliente.', 'quantia-hub' ),
                        __( 'Reclamos derivados, directa e indirectamente, por la cantidad, precio, defectos o calidad de los bienes o servicios que adquiera el Cliente a través de la Tarjeta Débito. En estos casos, cualquier disputa o reclamo deberá ser solucionada directamente por el Cliente y el proveedor del bien o servicio.', 'quantia-hub' ),
                        __( 'Por fraude o uso indebido o no autorizado de la Tarjeta Débito y/o del PIN.', 'quantia-hub' ),
                        __( 'La ocurrencia de cualquiera de los eventos de suspensión del servicio.', 'quantia-hub' ),
                    ),
                ),
                array(
                    'type'  => 'defs',
                    'items' => array(
                        array( 'term' => __( 'Pérdida o hurto de la Tarjeta Débito', 'quantia-hub' ), 'text' => __( 'En caso de pérdida o hurto de la Tarjeta Débito, el Cliente se obliga a notificar de tal hecho a MMG. La notificación podrá hacerse por escrito o vía telefónica. Mientras MMG no haya recibido tal notificación, el Cliente será responsable por todas las Transacciones Electrónicas realizadas con la Tarjeta Débito y asumirá los perjuicios que puedan resultar de ese hecho. (i) En caso de que la notificación se haga vía telefónica, el Cliente será responsable por las Transacciones Electrónicas realizadas por medio de la Tarjeta Débito hasta dos (2) horas después de recibida la notificación; (ii) en caso de que la notificación se haga por escrito a MMG dentro del Horario de Oficina y en Día Hábil, el Cliente será responsable por las Transacciones realizadas hasta dos (2) horas después de recibida la notificación; (iii) en caso de que la notificación por escrito se reciba fuera del Horario de Oficina o en día que no sea hábil, el Cliente será responsable por las Transacciones Electrónicas realizadas hasta las seis (6) primeras horas del Día Hábil siguiente. Para reportar por vía telefónica la pérdida o hurto de la Tarjeta Débito, el Cliente deberá llamar al número que será proporcionado por MMG al momento que se le hace entrega de la Tarjeta Débito o a cualquier otro número que, de tiempo en tiempo, le sea suministrado por MMG.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Cargos', 'quantia-hub' ), 'text' => __( 'MMG tendrá derecho a establecer cargos y comisiones por la emisión y uso de la Tarjeta Débito, los cuales serán pagados por el Cliente. Los cargos y comisiones podrán ser ajustados, de tiempo en tiempo, por MMG. Los cargos y comisiones actualmente cobrados se indican en el Tarifario (Anexo I). El pago de los cargos se hará por medio de débitos a la Cuenta Quantia del Cliente, y en caso de que ésta no tenga fondos suficientes, MMG queda autorizado para hacer los débitos contra la Cuenta del Cliente, en cuanto ésta reciba depósitos y tenga fondos disponibles.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Autorización para divulgar información del Cliente', 'quantia-hub' ), 'text' => __( 'El Cliente por este medio autoriza a MMG a suministrar cualquier información relacionada con el Cliente y su Cuenta Quantia que pudiese ser requerida por el operador de la Red Clave o de cualquier otra red a la cual la Red Clave esté afiliada o integrada. MMG declara que, si la información compartida contiene datos personales, los datos compartidos serán sólo los necesarios para la actualización y gestión de la cuenta Quantia.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Duración del servicio', 'quantia-hub' ), 'text' => __( 'Este servicio se pacta por término indefinido, pero cualquiera de las partes podrá darlo por concluido en cualquier momento, dando aviso por escrito a la otra. Este contrato también quedará terminado de forma automática y sin necesidad de aviso previo, al terminar el contrato entre el Cliente y MMG aplicable a la Cuenta Quantia o en caso de que el Cliente no acepte los cambios a los términos y condiciones aplicables a este servicio. Al producirse la terminación, el Cliente devolverá de inmediato a MMG la Tarjeta Débito de propiedad de MMG.', 'quantia-hub' ) ),
                    ),
                ),
                array( 'type' => 'p', 'text' => __( '15.2 Servicio Q15', 'quantia-hub' ) ),
                array(
                    'type'  => 'defs',
                    'items' => array(
                        array( 'term' => __( 'Descripción del servicio', 'quantia-hub' ), 'text' => __( 'El servicio de Q15 permite que las empresas autoricen un anticipo a los empleados permanentes que reciban el pago de sus planillas a través de Quantia.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Condiciones generales', 'quantia-hub' ), 'text' => __( 'El Cliente podrá acceder a este servicio cuando su Empleador contrate el servicio de pago de planilla de Quantia y cumpla con todas las obligaciones establecidas en el Contrato de Planilla. Cuando el Cliente realice la solicitud de anticipo de quincena por medio del servicio Q15 quedará a discreción de MMG autorizar el desembolso del anticipo.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Cargos', 'quantia-hub' ), 'text' => __( 'Aprobado el anticipo, MMG cobrará una comisión del 5% sobre el total de cada solicitud de anticipo. El Cliente podrá realizar pagos por adelantado y en caso de que este solicite una renovación del anticipo previo a la fecha de cobro, se cobrará una comisión del 5% sobre la comisión y el monto total adeudado. Posterior a la primera renovación el monto adeudado será automáticamente debitado en la siguiente quincena. MMG podrá aceptar y cancelar la solicitud de renovaciones a su discreción.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Duración del servicio', 'quantia-hub' ), 'text' => __( 'Este servicio se pacta por término indefinido, pero cualquiera de las partes podrá darlo por concluido en cualquier momento. En caso de que el empleador rescinda el servicio y/o el contrato laboral del Cliente y este adeude pagos por la solicitud de anticipos, los pagos pendientes se deducirán automáticamente del monto de su liquidación.', 'quantia-hub' ) ),
                    ),
                ),
            ),
        ),
        array(
            'heading' => __( '16. Comisiones, Cargos y Tarifas', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'MMG tendrá derecho a cobrar comisiones, cargos y tarifas por la prestación de los servicios y productos bancarios, las cuales serán pagadas por el Cliente y cuyo monto será fijado, de tiempo en tiempo, por MMG. Los montos se darán a conocer al público en general en el Tarifario (Anexo I) mediante avisos colocados permanentemente en las instalaciones y/o en la página o en los canales de comunicación de la plataforma de Quantia. MMG podrá, de tiempo en tiempo, agregar comisiones y tarifas.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '17. Modificación de Términos y Condiciones', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'MMG podrá de tiempo en tiempo adicionar o modificar los términos y condiciones de este Contrato. Las modificaciones y adiciones entrarán a regir a los treinta (30) días calendario siguientes, contados a partir de la fecha de entrega del respectivo aviso o comunicación hecha bajo cualquiera de las formas señaladas en el numeral 3 de esta Sección. Toda adición o modificación a los términos y condiciones que resulte en virtud de la adopción de una norma legal entrará a regir a partir de la fecha que se establezca en la respectiva norma legal, sin necesidad de aviso previo al Cliente.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '18. Avisos y Comunicaciones', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Los avisos y comunicaciones se considerarán entregados a la parte a quien se le remite: (a) a los diez (10) días calendario contados a partir de la fecha en que se puso en el correo, si por correo ordinario o especial; (b) si es mediante aviso publicado en un periódico de circulación nacional, a los dos (2) días siguientes, contados a partir de la última publicación; (c) si es por correo electrónico, a partir de la fecha y hora de envío que aparezca en el mensaje, siempre que sea un día hábil. De no serlo, se entenderá entregado al día hábil siguiente.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Los avisos y comunicaciones se enviarán a las direcciones (física o postal) o dirección de correo electrónico, según sea el caso, suministradas por cada una de las Partes.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '19. Declaraciones y Representaciones', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'El Cliente declara y reconoce lo siguiente:', 'quantia-hub' ) ),
                array(
                    'type'  => 'list',
                    'items' => array(
                        __( '(a) Que, al momento de la celebración de este contrato, está en plena capacidad de sus facultades y no existe impedimento alguno que le impida su celebración.', 'quantia-hub' ),
                        __( '(b) Que toda la información y documentación proporcionada a MMG para la celebración de este contrato es verdadera.', 'quantia-hub' ),
                        __( '(c) Una vez que suscriba este contrato y sea aprobado por MMG, quedará obligado a cumplir con los términos y condiciones aquí establecidos.', 'quantia-hub' ),
                    ),
                ),
            ),
        ),
        array(
            'heading' => __( '20. Discrepancias', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'En caso de existir alguna discrepancia entre las condiciones generales y las condiciones especiales contenidas en este Contrato prevalecerán las condiciones especiales.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '21. Ley Aplicable', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Este Contrato se regirá de acuerdo con sus términos y condiciones y en todo aquello que no esté contemplado en éstos, se regirá de forma supletoria e interpretará de acuerdo con las leyes de Panamá.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '22. Resolución de Conflictos', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Cualquier controversia o reclamación que se origine de la interpretación, cumplimiento o incumplimiento de este Contrato será resuelta ante los tribunales competentes de la ciudad de Panamá, sin perjuicio de que el Cliente decida presentar cualquier reclamo ante el sistema de atención de reclamos de MMG, o someterlo al procedimiento administrativo ante la Superintendencia de Bancos establecido en el Acuerdo 3-2008, o que el Cliente y MMG acuerden someter el conflicto a arbitraje.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '23. Uso de Datos Personales', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'En virtud de lo dispuesto en la legislación vigente de Protección de datos personales, salvo en los casos expresamente autorizados por la ley y/o directamente derivados de la relación contractual entre el cliente y MMG, MMG no podrá compartir información que contenga datos personales sin autorización previa, informada y expresa del titular de los datos personales, salvo que la parte receptora o solicitante de dicha información presente evidencia de haber sido expresamente autorizada para requerir dicha información a MMG. La autorización a que se hace referencia deberá indicar los datos personales que son requeridos y la finalidad del requerimiento, en cuyo caso MMG quedará liberado de toda responsabilidad por el uso y tratamiento que de esos datos haga quien reciba los datos personales requeridos.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '24. Entrada en Vigor y Condiciones Precedentes', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'La vigencia y entrada en vigor efectiva de este Contrato, así como la apertura, habilitación operativa y disponibilidad de la Cuenta Quantia, la prestación de cualquier servicio o producto bancario y su continuidad, estarán expresamente condicionadas a que el Cliente complete satisfactoriamente el proceso de vinculación, identificación, verificación y Debida Diligencia requerido por MMG, de conformidad con sus políticas internas y la normativa aplicable, y a que realice el primer aporte de fondos a la Cuenta Quantia cuando dicho aporte sea requerido por MMG para la activación de la cuenta, producto o servicio correspondiente. En consecuencia, la aceptación de este Contrato por parte del Cliente no producirá por sí sola la vigencia efectiva del mismo ni obligará a MMG a abrir, activar, mantener o prestar servicios asociados a la Cuenta Quantia hasta tanto se cumplan, a satisfacción de MMG, las siguientes condiciones precedentes:', 'quantia-hub' ) ),
                array(
                    'type'    => 'list',
                    'ordered' => true,
                    'items'   => array(
                        __( 'Que se aporte y suministre de forma completa a MMG toda la documentación e información requerida por éste, salvo que MMG hubiese otorgado una dispensa al solicitante para aportar parte de la documentación o información en fecha posterior.', 'quantia-hub' ),
                        __( 'Que el Cliente complete satisfactoriamente el proceso de vinculación, identificación, verificación y Debida Diligencia requerido por MMG, y que MMG lo encuentre satisfactorio conforme a sus políticas internas y a la normativa aplicable.', 'quantia-hub' ),
                        __( 'Que MMG haya aprobado la celebración del contrato y notificado tal hecho al Cliente.', 'quantia-hub' ),
                        __( 'Que el Cliente realice el primer aporte de fondos a la Cuenta Quantia, en el monto, forma y oportunidad que establezca MMG.', 'quantia-hub' ),
                        __( 'Que el Cliente se mantenga al día en el cumplimiento de las obligaciones adquiridas por medio de este Contrato.', 'quantia-hub' ),
                    ),
                ),
                array( 'type' => 'p', 'text' => __( 'En caso de que MMG hubiese otorgado al Cliente una dispensa a que se refiere el punto (i) anterior y éste no hubiese aportado la documentación o información dentro del plazo establecido, MMG tendrá derecho a terminar el contrato o suspender los efectos de éste, sin responsabilidad alguna para MMG.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'La aceptación de los términos y condiciones de este documento por el solicitante NO implica la aprobación automática del servicio o producto solicitado, la apertura efectiva de la Cuenta Quantia, la habilitación operativa de la cuenta ni la obligación de MMG de prestar los servicios solicitados. La vigencia efectiva de este Contrato y la activación de la relación contractual quedarán condicionadas al cumplimiento satisfactorio de las condiciones precedentes establecidas en esta cláusula, incluyendo, sin limitación, la culminación satisfactoria del proceso de vinculación y Debida Diligencia del Cliente, la aprobación interna correspondiente por parte de MMG y, cuando aplique, la recepción del primer aporte de fondos a la Cuenta Quantia.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '25. Autorización para Verificar Historial de Crédito – APC', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Por este medio confirmo que he sido informado de forma clara del uso que MMG Bank Corporation, en adelante el BANCO, hará de los datos que he compartido con ellos y expresamente autorizo al BANCO que de conformidad con lo expresado en el artículo 24 y demás disposiciones aplicables de la Ley 24 de 22 de mayo de 2002, solicite, consulte, recopile informaciones relacionadas con obligaciones o transacciones crediticias que mantengo o pudiera mantener con cualquier agente económico de la localidad, sobre mi historial de crédito y relaciones con acreedores, incluyendo información de instituciones gubernamentales relacionadas con las obligaciones o transacciones crediticias antes referidas, siempre dentro de los parámetros requeridos para la relación comercial/contractual que estamos realizando y mientras esta relación comercial/contractual se mantenga vigente.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Del mismo modo autorizo al BANCO a que:', 'quantia-hub' ) ),
                array(
                    'type'  => 'list',
                    'items' => array(
                        __( 'Intercambie y transmita a cualquier agencia de información de datos, bancos o agentes económicos, información relacionada con obligaciones o transacciones crediticias que mantengo o pudiera mantener con el BANCO, incluyendo información de instituciones gubernamentales relacionadas con las obligaciones o transacciones crediticias antes referidas, siempre que dichas agencias de información de datos, bancos o agentes económicos hayan sido expresamente autorizadas por nosotros para solicitar y obtener dicha información.', 'quantia-hub' ),
                        __( 'Intercambie y transmita a APC información relacionada con obligaciones o transacciones crediticias que mantengo o pudiera mantener con el BANCO, incluyendo información de instituciones gubernamentales relacionadas con las obligaciones o transacciones crediticias antes referidas, para que otros bancos o agentes económicos que hayan sido expresamente autorizados por nosotros para solicitar y obtener dicha información puedan obtenerla de APC. APC no podrá realizar ningún otro tratamiento a la información así recibida, salvo que haya sido previa y expresamente autorizada por nosotros para tal fin.', 'quantia-hub' ),
                    ),
                ),
                array( 'type' => 'p', 'text' => __( 'En base a lo establecido en la Ley 81 de 2019, para cualquier otro tratamiento no autorizado expresamente en este documento, incluyendo la transferencia de información a sus compañías afiliadas, subsidiarias, cesionarios y/o sucesoras, a sus empleados, ejecutivos, directores, dignatarios o apoderados, deberá ser previa y expresamente autorizado por nosotros, salvo que se trate de una compañía que, por una operación de cesión, administración o compra de cartera adquiera los derechos de mi crédito, a la que esta autorización será automáticamente transferida en los términos y las condiciones en que ha sido autorizado el BANCO.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Confirmo que he tenido acceso a la Política de Protección de Datos Personales y que he sido informado del nombre y forma de contactar a la persona designada como Responsable de Protección de Datos Personales en caso de que quiera ejercer alguno de los derechos ARCOP, incluyendo la revocación de la autorización a cualquier tratamiento expresamente autorizado en este documento.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'QUEDA ENTENDIDO QUE EL BANCO SUMINISTRARÁ A REQUERIMIENTO DEL INTERESADO TODA LA INFORMACIÓN CREDITICIA RECOPILADA CON BASE EN LA PRESENTE AUTORIZACIÓN.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '26. Declaración Jurada de Cumplimiento de Obligaciones Tributarias', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'A fin de cumplir con las normativas aplicables sobre prevención del uso indebido de los servicios bancarios y fiduciarios, y el cumplimiento de las obligaciones tributarias, por este medio declaro que los flujos de ingreso y salida correspondientes a las cuentas bancarias que mantengo en MMG Bank, sus subsidiarias o afiliadas, cumplen y cumplirán con las obligaciones tributarias vigentes en el(los) país(es) en que mantengo mi residencia fiscal.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( '27. Descargo de Responsabilidad y Advertencia de Riesgo', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Al operar en Quantia, la plataforma de MMG, usted acepta que todas las transacciones son bajo su propio riesgo. MMG solo intermedia y no se responsabiliza por la validez, calidad, rentabilidad, condición o autenticidad de los instrumentos financieros negociados.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Usted reconoce que es su responsabilidad llevar a cabo las verificaciones necesarias respecto a los instrumentos financieros, incluyendo, pero sin limitarse a, la evaluación de riesgos, el cumplimiento de la normativa aplicable y la verificación de la validez de cada transacción. De igual manera, MMG no asume responsabilidad alguna por daños, pérdidas o reclamaciones resultantes de la compra, venta u otras operaciones vinculadas a dichos instrumentos.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Advertencia de riesgo: La adquisición y enajenación de instrumentos financieros conlleva riesgos que deben ser evaluados detenidamente. Se recomienda consultar a un asesor financiero independiente antes de proceder con cualquier operación.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Al seleccionar la casilla de aceptación de este descargo de responsabilidad, usted manifiesta haber leído y comprendido los términos y condiciones aquí establecidos, y se compromete a eximir a MMG de toda responsabilidad derivada de las transacciones efectuadas mediante esta plataforma.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( 'Anexo I · Tarifario', 'quantia-hub' ),
            'blocks'  => array(
                array(
                    'type'   => 'ref',
                    'text'   => __( 'Las tarifas para Clientes Persona Natural y para Clientes Corporativos (Cuentas de Planilla) forman parte de estos términos.', 'quantia-hub' ),
                    'target' => 'costos-y-comisiones',
                    'label'  => __( 'Ver tarifario en Costos y comisiones', 'quantia-hub' ),
                ),
            ),
        ),
    );
}

/* =========================================================
 * Aviso de Privacidad — portado de legalPrivacy.ts
 * ========================================================= */

function quantia_legal_privacy_sections(): array {
    return array(
        array(
            'heading' => __( 'Aviso de privacidad', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'MMG Bank Corporation y subsidiarias, en adelante MMG Bank, en cumplimiento de lo dispuesto en el Régimen de Protección de Datos Personales que comprenden a la Ley No. 81 de 2019, el Decreto Ejecutivo No. 285 de 2021 y el Acuerdo No. 1-2022 de la Superintendencia de Bancos de Panamá, ha desarrollado este Aviso de Privacidad para informar a los titulares de los datos personales, los fines para los cuales es solicitada su información personal, el interés legítimo y los tratamientos que realizamos a dichos datos, las medidas de protección que utilizamos para garantizar su confidencialidad, integridad y disponibilidad, y los derechos que pueden ejercer los titulares de los datos y/o sus representantes debidamente autorizados.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'MMG Bank expresa su interés en la protección de la intimidad y de la privacidad de sus colaboradores y clientes y toda aquella persona cuyos datos hayan sido suministrados directa o indirectamente a MMG Bank durante el desarrollo de sus actividades comerciales, en particular durante las actividades de tratamiento a las que dichos datos son sometidos y las cuales se realizan en respeto de los principios de acceso, legalidad, equidad, confidencialidad, seguridad y lealtad.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'MMG Bank manifiesta por este medio que toda la información personal que se encuentra en nuestras bases de datos ha sido obtenida de forma lícita en el desarrollo de nuestra actividad comercial, que su recopilación se ha hecho y se hará siempre en atención al interés legítimo y a criterios de cumplimiento normativo y que de forma periódica se realiza una verificación de las autorizaciones de tratamiento que hemos recibido de los propietarios de dicha información.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Para mayor información acerca del tratamiento y de los derechos que puede hacer valer, usted puede acceder al aviso de privacidad integral.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( 'Responsable de los datos personales', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'MMG Bank es el responsable del tratamiento de Datos Personales. Sus oficinas principales se encuentran ubicadas en Ave. Paseo del Mar, Costa del Este, MMG Tower, Piso 22, Ciudad Panamá, República de Panamá. Para temas relacionados con el ejercicio de los Derechos ARCOP, escribir a pdp@mmgbank.com o acceder a los formularios que están disponibles en la página www.mmgbank.com/pdp.', 'quantia-hub' ) ),
                array(
                    'type'  => 'contact',
                    'items' => array(
                        array( 'label' => __( 'Teléfono', 'quantia-hub' ), 'value' => '265-7600', 'href' => 'tel:+5072657600' ),
                        array( 'label' => __( 'Derechos ARCOP', 'quantia-hub' ), 'value' => 'pdp@mmgbank.com', 'href' => 'mailto:pdp@mmgbank.com' ),
                        array( 'label' => __( 'Formularios', 'quantia-hub' ), 'value' => 'www.mmgbank.com/pdp', 'href' => 'https://www.mmgbank.com/pdp' ),
                    ),
                ),
            ),
        ),
        array(
            'heading' => __( 'Derechos de los titulares de los datos personales', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Los titulares de los Datos Personales son las personas naturales cuyos Datos Personales sean objeto de algún tratamiento por parte de MMG Bank. En virtud de lo establecido por la normatividad vigente en materia de protección de datos, a los titulares de los Datos Personales se les reconocen los llamados derechos ARCOP, los cuales los pueden ejercer en cualquier momento:', 'quantia-hub' ) ),
                array(
                    'type'  => 'defs',
                    'items' => array(
                        array( 'term' => __( 'Derecho de Acceso', 'quantia-hub' ), 'text' => __( 'Permite al titular obtener sus Datos Personales que se encuentren almacenados o sujetos a tratamiento en bases de datos de instituciones públicas o privadas, además de conocer el origen y la finalidad para los cuales han sido recabados.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Derecho de Rectificación', 'quantia-hub' ), 'text' => __( 'Permite al titular solicitar la corrección de sus Datos Personales que sean incorrectos, irrelevantes, incompletos, desfasados, inexactos, falsos o impertinentes.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Derecho de Cancelación', 'quantia-hub' ), 'text' => __( 'Permite al titular solicitar la eliminación de sus Datos Personales incorrectos, irrelevantes, incompletos, desfasados, inexactos, falsos o impertinentes.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Derecho de Oposición', 'quantia-hub' ), 'text' => __( 'Permite al titular, por motivos fundados y legítimos relacionados con una situación en particular, negarse a proporcionar sus Datos Personales o a que sean objeto de determinado tratamiento, así como a revocar su consentimiento.', 'quantia-hub' ) ),
                        array( 'term' => __( 'Derecho de Portabilidad', 'quantia-hub' ), 'text' => __( 'Derecho a obtener una copia de los Datos Personales de manera estructurada, en un formato genérico y de uso común, que permita ser operado por distintos sistemas y/o transmitirlos a otro responsable.', 'quantia-hub' ) ),
                    ),
                ),
            ),
        ),
        array(
            'heading' => __( 'Formularios derechos ARCOP', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'MMG Bank garantiza al titular de los datos personales el ejercicio de los Derechos ARCOP, a fin de que, con previa acreditación de su identidad, legitimidad y sin costo alguno, tenga completo acceso a sus Datos Personales. Para facilitar el acceso, MMG Bank ha puesto a disposición en esta sección formularios de solicitud para cada derecho, los cuales deberán ser enviados a la siguiente dirección pdp@mmgbank.com para el trámite correspondiente.', 'quantia-hub' ) ),
                array(
                    'type'  => 'list',
                    'items' => array(
                        __( 'Formulario de Solicitud de Derechos de Acceso', 'quantia-hub' ),
                        __( 'Formulario de Solicitud de Derechos de Rectificación', 'quantia-hub' ),
                        __( 'Formulario de Solicitud de Derechos de Cancelación', 'quantia-hub' ),
                        __( 'Formulario de Solicitud de Derechos de Oposición', 'quantia-hub' ),
                        __( 'Formulario de Solicitud de Derechos de Portabilidad', 'quantia-hub' ),
                    ),
                ),
            ),
        ),
        array(
            'heading' => __( 'Oficial de PDP', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Puede contactar con el Oficial de Protección de Datos Personales para hacerle llegar sus sugerencias, consultas, dudas o reclamaciones a través de esta dirección:', 'quantia-hub' ) ),
                array(
                    'type'  => 'contact',
                    'items' => array(
                        array( 'label' => __( 'Correo-e', 'quantia-hub' ), 'value' => 'pdp@mmgbank.com', 'href' => 'mailto:pdp@mmgbank.com' ),
                        array( 'label' => __( 'Oficina', 'quantia-hub' ), 'value' => 'MMG Tower, Piso 22, Ave. Paseo del Mar, Costa del Este, Ciudad Panamá, República de Panamá.' ),
                    ),
                ),
            ),
        ),
        array(
            'heading' => __( 'Derecho a recurrir ante la Superintendencia de Bancos de Panamá', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'En cumplimiento del artículo 11 y 27 del Acuerdo No. 1-2022, MMG Bank hace del conocimiento de los titulares de datos personales que en caso de que el banco no atienda sus solicitudes de ejercicio de los derechos ARCOP o cuando se encuentren disconformes con la decisión adoptada por el banco con relación a sus solicitudes, el titular de los datos personales podrá interponer un reclamo ante la Superintendencia de Bancos. Para tales fines, el titular tendrá un plazo de 30 días calendario, los cuales empezarán a contarse a partir de la fecha en que obtuvo respuesta formal por parte del banco o cuando el banco no haya cumplido con resolver la solicitud en el plazo correspondiente.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( 'Uso de Cookies en nuestro sitio web', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Nuestro sitio web, www.mmgbank.com, utiliza el servicio Google Analytics para la medición de la información de tráfico del sitio web anónimamente.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Nuestro sitio web recoge información anónima, es decir, que no identifica ni es susceptible de identificar al usuario de la sesión, a través de cookies. También se emplea el uso de cookies por medio de la herramienta Google Analytics con el propósito de adquirir conocimiento sobre el tráfico de los visitantes de nuestro sitio web para luego desarrollar mejoras y medir su eficacia.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Si el usuario no desea utilizar cookies, puede modificar o inhabilitar su uso a través de la configuración de su navegador. En el caso de los navegadores, se pueden realizar ajustes para eliminar, permitir o bloquear todas las cookies o emplear el modo de incógnito que no guarda el historial, ni almacena cookies en su dispositivo.', 'quantia-hub' ) ),
            ),
        ),
        array(
            'heading' => __( 'Vigencia del Aviso de privacidad', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'Este Aviso de Privacidad entró en vigor a partir de la fecha de su publicación y sustituye y deja sin efecto cualquier otra disposición organizacional que le sea contraria o que haya sido emitida con anterioridad. Toda información no contemplada en la presente política será tratada de la forma como lo establece el Acuerdo 001-2022 de la Superintendencia de Bancos de Panamá y la Ley 81 de 2019.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'La actualización de este Aviso de Privacidad dependerá de las instrucciones y lineamientos que adopte MMG Bank, así como de las normativas emitidas por la Superintendencia de Bancos de Panamá y la Superintendencia del Mercado de Valores de Panamá, según sea el caso, y en su defecto, por cambios sustanciales en la normativa vigente en la República de Panamá.', 'quantia-hub' ) ),
                array( 'type' => 'p', 'text' => __( 'Fecha de actualización: Octubre 2025.', 'quantia-hub' ) ),
            ),
        ),
    );
}

/* =========================================================
 * Aclaraciones y reclamaciones — portado de legalClaims.ts
 * ========================================================= */

function quantia_legal_claims_sections(): array {
    return array(
        array(
            'heading' => __( 'Cómo presentar tu queja', 'quantia-hub' ),
            'blocks'  => array(
                array( 'type' => 'p', 'text' => __( 'De existir alguna queja, reclamo o disconformidad con el servicio bancario prestado por MMG Bank a cualquiera de sus clientes, el procedimiento será el siguiente:', 'quantia-hub' ) ),
                array(
                    'type'  => 'contact',
                    'items' => array(
                        array( 'label' => __( 'Correo de reclamos', 'quantia-hub' ), 'value' => 'reclamos@mmgbank.com', 'href' => 'mailto:reclamos@mmgbank.com' ),
                    ),
                ),
            ),
        ),
        array(
            'heading' => __( 'Procedimiento', 'quantia-hub' ),
            'blocks'  => array(
                array(
                    'type'    => 'list',
                    'ordered' => true,
                    'items'   => array(
                        __( 'El cliente deberá emitir su queja por escrito a las oficinas de MMG Bank, mediante nota o correo electrónico reclamos@mmgbank.com.', 'quantia-hub' ),
                        __( 'En el caso de que el reclamo se haya presentado mediante nota, al cliente se le expedirá una constancia escrita sobre la presentación de su reclamo. Para los reclamos presentados por correo electrónico, se le remitirá al cliente esta constancia mediante un correo electrónico.', 'quantia-hub' ),
                        __( 'En caso de que el cliente dirija la queja por escrito directamente al Oficial encargado de su cuenta, este Oficial deberá remitir la misma al Ejecutivo de Reclamos, quien realizará la debida investigación y dará respuesta formal al cliente.', 'quantia-hub' ),
                        __( 'El Ejecutivo de Reclamos deberá realizar las investigaciones pertinentes a cada reclamo en particular e informar a la Gerencia la causal del reclamo del cliente.', 'quantia-hub' ),
                        __( 'El Ejecutivo de Reclamos tendrá un período de 30 días para atender, conocer y dar respuesta en forma personalizada y por escrito al cliente, mediante nota o correo electrónico (del cual se recibió la queja), a las reclamaciones que presenten los clientes bancarios.', 'quantia-hub' ),
                        __( 'En los casos en que el cliente bancario se mantenga insatisfecho con la respuesta proporcionada por el Banco a su queja, el cliente bancario tendrá un período de cinco (5) días hábiles para exponer sus razones por escrito.', 'quantia-hub' ),
                        __( 'A su vez el Banco contará con cinco (5) días hábiles para considerar lo indicado y dar respuesta al cliente por escrito.', 'quantia-hub' ),
                        __( 'El Ejecutivo de Reclamos llevará un expediente con el registro de cada una de las reclamaciones interpuestas por los clientes, el cual debe contener todas las incidencias que se relacionen a dicho reclamo, así como el resultado de su gestión. Estos registros se mantendrán en la oficina principal del sistema y por un término de cinco (5) años. Igualmente se deben mantener a disposición de la Superintendencia de Bancos.', 'quantia-hub' ),
                    ),
                ),
            ),
        ),
    );
}

/* =========================================================
 * Costos y comisiones — portado de legal.ts
 * ========================================================= */

function quantia_legal_fees_sections(): array {
    $blocks = array(
        array( 'type' => 'feeTables' ),
    );

    $fee_text_blocks = array();
    if ( function_exists( 'quantia_fallback_fee_legal_text' ) ) {
        foreach ( quantia_fallback_fee_legal_text() as $text ) {
            $fee_text_blocks[] = array( 'type' => 'p', 'text' => $text );
        }
    }

    return array(
        array(
            'heading' => __( 'Tarifario', 'quantia-hub' ),
            'blocks'  => $blocks,
        ),
        array(
            'heading' => __( 'Impuestos y condiciones', 'quantia-hub' ),
            'blocks'  => $fee_text_blocks,
        ),
    );
}

/* =========================================================
 * Seeder
 * ========================================================= */

/**
 * Siembra los documentos legales base al activar el tema.
 *
 * Idempotente: no hace nada si ya existe al menos un documento con alguno
 * de los slugs esperados.
 */
function quantia_seed_legal_documents(): void {
    if ( ! current_user_can( 'manage_options' ) && ! doing_action( 'after_switch_theme' ) ) {
        return;
    }

    $existing = get_posts( array(
        'post_type'      => 'documento_legal',
        'posts_per_page' => 1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ) );

    if ( ! empty( $existing ) ) {
        return;
    }

    $order = 0;

    foreach ( quantia_fallback_legal_documents() as $slug => $doc ) {
        $post_id = wp_insert_post( array(
            'post_type'    => 'documento_legal',
            'post_status'  => 'publish',
            'post_title'   => $doc['title'],
            'post_name'    => $slug,
            'post_content' => '',
            'menu_order'   => $order++,
        ), true );

        if ( is_wp_error( $post_id ) || ! $post_id ) {
            continue;
        }

        $sections_acf = quantia_legal_sections_to_acf( $doc['sections'] );

        if ( function_exists( 'update_field' ) ) {
            update_field( 'legal_summary', $doc['summary'], $post_id );
            update_field( 'legal_updated', $doc['updated'], $post_id );
            update_field( 'legal_sections', $sections_acf, $post_id );
        } else {
            update_post_meta( $post_id, 'legal_summary', $doc['summary'] );
            update_post_meta( $post_id, 'legal_updated', $doc['updated'] );
            update_post_meta( $post_id, 'legal_sections', $sections_acf );
        }
    }

    flush_rewrite_rules();
}
add_action( 'admin_init', 'quantia_seed_legal_documents' );
add_action( 'after_switch_theme', 'quantia_seed_legal_documents' );

/**
 * Convierte el array normalizado de secciones al formato ACF para guardar.
 *
 * @param array $sections Secciones normalizadas.
 * @return array
 */
function quantia_legal_sections_to_acf( array $sections ): array {
    $rows = array();
    foreach ( $sections as $section ) {
        $blocks_acf = array();
        foreach ( $section['blocks'] ?? array() as $block ) {
            $blocks_acf[] = quantia_legal_block_to_acf( $block );
        }
        $rows[] = array(
            'heading' => $section['heading'] ?? '',
            'blocks'  => $blocks_acf,
        );
    }
    return $rows;
}

/**
 * Convierte un bloque normalizado al formato ACF.
 *
 * @param array $block Bloque.
 * @return array
 */
function quantia_legal_block_to_acf( array $block ): array {
    $type = $block['type'] ?? '';

    switch ( $type ) {
        case 'p':
            return array( 'acf_fc_layout' => 'p', 'text' => $block['text'] ?? '' );

        case 'list':
            $items = array();
            foreach ( $block['items'] ?? array() as $text ) {
                $items[] = array( 'text' => $text );
            }
            return array( 'acf_fc_layout' => 'list', 'ordered' => ! empty( $block['ordered'] ), 'items' => $items );

        case 'defs':
            $items = array();
            foreach ( $block['items'] ?? array() as $item ) {
                $items[] = array( 'term' => $item['term'] ?? '', 'text' => $item['text'] ?? '' );
            }
            return array( 'acf_fc_layout' => 'defs', 'items' => $items );

        case 'contact':
            $items = array();
            foreach ( $block['items'] ?? array() as $item ) {
                $items[] = array(
                    'label' => $item['label'] ?? '',
                    'value' => $item['value'] ?? '',
                    'href'  => $item['href'] ?? '',
                );
            }
            return array( 'acf_fc_layout' => 'contact', 'items' => $items );

        case 'feeTables':
            return array( 'acf_fc_layout' => 'fee_tables' );

        case 'ref':
            return array(
                'acf_fc_layout' => 'ref',
                'text'          => $block['text'] ?? '',
                'label'         => $block['label'] ?? '',
                'target'        => $block['target'] ?? '',
            );
    }

    return array( 'acf_fc_layout' => 'p', 'text' => '' );
}