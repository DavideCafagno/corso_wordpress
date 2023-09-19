<?php

/*
 POSSIBILI SHORTCODES
[persone id_organizzazione='8888']
 */

wp_enqueue_style( 'style_tabella_front_dt',
	plugins_url( '/acerra-persone/includes/jquery.dataTables.min.css' ) );

wp_enqueue_style( 'style_tabella_front_ap',
	plugins_url( '/acerra-persone/includes/persone-tables.css' ) );

wp_register_script( 'jqueryPersone',
	plugins_url( '/acerra-persone/includes/jquery.dataTables.min.js' ),
	array( 'jquery' ) );
wp_enqueue_script( 'jqueryPersone' );

wp_register_script( 'scPersone',
	plugins_url( 'shortcode.js', __FILE__ ), array( 'jquery' ) );
wp_enqueue_script( 'scPersone' );

$url = add_query_arg( array(), plugin_dir_url( __FILE__ ) . "service_wrap_frontend.php" );

wp_localize_script( 'scPersone',
	'ajax_Persone',
	array( 'serviceWrapFrontendUrl' => $url, ) );

$idOrganizzazione = $atts["id_organizzazione"];
?>
    <div class="container container_form_ricerca">
        <form id="form_ricerca">
            <div class="row">
				<?php echo getFiltri() ?>
            </div>

            <input id='id_organizzazione' type='hidden' value='<?php echo $idOrganizzazione ?>'>

            <div class="row"></div>
            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="col-4" id="div-btn-reset">
                    <button class="sbt-reset"
                            id="btnReset"
                            type="reset"
                            title="Resetta i filtri"
                            aria-label="Resetta i filtri">Reset
                    </button>
                </div>
                <div class="col-4">&nbsp;</div>
                <div class="col-4" id="div-btn-cerca">
                    <button class="sbt-cerca"
                            id="btnCerca"
                            type="button"
                            title="Effettua la ricerca"
                            aria-label="Effettua la ricerca">Cerca
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="containerTabella" class="mt-5">
        <table id="tablePersona" class="display" style="width:100%">
            <thead>
            <tr>
                <th>Nominativo</th>
                <th>Ruolo</th>
                <th>Organizzazione di riferimento</th>
                <th>Responsabile di</th>

                <th>Dettagli</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

<?php

function getFiltri(): string {
	/*
	filtri
	nominativo testo
	ruolo testo
	*/

	return "
<div class='col-md-4 fieldFormRicerca'>
    <label class='form_ricerca'
           for='nominativo'
           style='width: auto;'>Nominativo</label>
    <input id='nominativo'
           name='nominativo'
           class='libero'
           type='text'
           style='width: 100%'
           autocomplete='off'>
</div>

<div class='col-md-4 fieldFormRicerca'>
    <label class='form_ricerca'
           for='ruolo'
           style='width: auto;'>Ruolo</label>
    <input id='ruolo'
           name='ruolo'
           class='libero'
           type='text'
           style='width: 100%'
           autocomplete='off'>
</div>
";
}