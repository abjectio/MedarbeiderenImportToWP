<?php
/**
 * Medarbeideren export
 *
 */

function showHTML() {

	if ( !current_user_can('import') )
		wp_die(__('You do not have sufficient permissions of this site.'));

	//Get the chosen events
	$chosen = $_REQUEST['chosen'];
	$refresh_btn = $_REQUEST['refresh-log-btn'];
	$import_btn = $_REQUEST['start-import-btn'];
	$title = __('Medarbeideren import');
	?>
		<h1><?php echo esc_html( $title ); ?></h1>

		<hr>
		<p>Funksjonen nedenfor eksekverer en import rutine på tjeneren. Den vil hente data fra Medarbeideren og importere disse inn i wordpress instansen.</p>
		<p>Når importen er startet vil du ikke få noe øyeblikkelig tilbakemelding om den er ferdig eller fortsatt kjørende, da eksekveringen går på tjeneren uavhengig av denne siden (en prosess i bakgrunnen startes på tjeneren).</p>
		<p>For å ha en noenlunde feeling på hvor prosessen er kan du trykke på <b>Refresh Log</b> knappen nedenfor, som vil vise de siste 20 linjene fra import loggen.</p>
		<p style="color:red;">NB: Ikke eksekver mange importer samtidig, dette er ennå ikke testet! Ha tålmodighet og se om importen er ferdig først.</p>

		<hr>

		<form method="post" action="admin.php?page=medarbeideren-import">
			<label >Velg gruppe for import : </label>
			 <select name="chosen">
			  <option value="gudstjenester" <?php echo ($chosen=='gudstjenester' ? 'selected' : '') ?>>Gudstjenester</option>
			  <option value="jesha" <?php echo ($chosen=='jesha' ? 'selected' : '') ?>>Jesha</option>
			  <option value="esc" <?php echo ($chosen=='esc' ? 'selected' : '') ?>>ESC (Ungdom)</option>
			  <option value="konfirmant" <?php echo ($chosen=='konfirmant' ? 'selected' : '') ?>>Konfirmant</option>
			  <option value="tabago" <?php echo ($chosen=='tabago' ? 'selected' : '') ?>>Tabago</option>
			</select>
			
			<p>
			  <button name="start-import-btn" class="button-primary">Start Import</button>
			</p>
			<hr>
		</form>

			<p>Oppfrisker loggen med et utdrag fra importen (resultat vises nedenfor knappen).</p>
			<p>Når importen er ferdig vil du se teksten <b>[END IMPORT]</b> i loggen.</p>
			<form action="admin.php?page=medarbeideren-import" method="post">
				<button name="refresh-log-btn" class="button-secondary">Refresh Log</button>
			</form>	
			
			<hr><p>OUTPUT:</p>
			
			<?php 
			
				//Check what action to perform - refresh or the import?
				if(isset($chosen) && isset($import_btn)) {importEvents($chosen);};
				if(isset($refresh_btn)){refreshLog();};	
}


//FUNCTIONS
function importEvents($chosen) {
	
	$import_py_dir = '/home/abjectio/koding/wp-labora-py/';
	$import_py = $import_py_dir . 'import_events.py';
	
	if(isset($chosen)){
				
		$cmdline = $import_py . ' ' . $import_py_dir . 'import_events_' . $chosen . '.cfg';
		echo '<b>Eksekverer importeringer av ' . $chosen . '</b>';
		//echo '</br>Command (runs on server) => ' . $cmdline;
		$result = shell_exec($cmdline . ' >> /dev/null &');
		refreshLog();		
	}	
}

//Refresh the Log
function refreshLog() {	
	
	$output = shell_exec('tail -20 /tmp/import_events.log');
	$output = str_replace("INFO","</br>INFO",$output);	
	echo $output;
}
?>
