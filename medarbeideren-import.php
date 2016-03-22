<?php

/*
 * showHTML - displays the plugin within the admin menu  
 */
function showHTML() {

  if ( !current_user_can('import') )
		wp_die(__('You do not have sufficient permissions of this site.'));

	//Get the chosen events
	$chosen = $_REQUEST['chosen'];
	$refresh_btn = $_REQUEST['refresh-log-btn'];
	$import_btn = $_REQUEST['start-import-btn'];

	//Styles and scripts (Bootstrap)
	wp_register_style( 'medimp_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' );
	wp_register_style( 'medimp_bootstraptheme', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css' );
	//wp_register_script( 'medimp_jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js' );
	//wp_register_script( 'medimp_bootstrap_js', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js' );
	
	wp_enqueue_style( 'medimp_bootstrap');
	wp_enqueue_style( 'medimp_bootstraptheme');
	//wp_enqueue_script( 'medimp_jquery');
	//wp_enqueue_script( 'medimp_bootstrap_js');
	
	?>

	</br>
	<!-- GRID WITH TWO COLUMNS -->
	<div class="row">
		
		<!-- LEFT COLUMN -->
		<div class="col-sm-4">
			<!-- Panel INFO -->
			<div class="panel panel-info">
				<div class="panel-heading">Medarbeideren import - informasjon</div>
				<div class="panel-body">
					<p>Funksjonen nedenfor eksekverer en import rutine på tjeneren. Den vil hente data fra Medarbeideren og importere disse inn i wordpress instansen.</p>
					<p>Når importen er startet vil du ikke få noe øyeblikkelig tilbakemelding om den er ferdig eller fortsatt kjørende, da eksekveringen går på tjeneren uavhengig av denne siden (en prosess i bakgrunnen startes på tjeneren).</p>
					<p>For å ha en noenlunde feeling på hvor prosessen er kan du trykke på <b>Refresh Log</b> knappen nedenfor, som vil vise de siste 20 linjene fra import loggen.</p>
					<p style="color:red;">NB: Ikke eksekver mange importer samtidig, dette er ennå ikke testet! Ha tålmodighet og se om importen er ferdig først.</p>
				</div> <!-- Panel body end -->
			</div> <!-- Panel INFO END -->
		
		
			<!-- Panel IMPORT -->
			<div class="panel panel-default">
				<div class="panel-heading">Import</div>
				<div class="panel-body">
					<form method="post" action="admin.php?page=medarbeideren-import">
						<label >Velg gruppe for import : </label>
						<select name="chosen">
							<option value="gudstjenester" <?php echo ($chosen=='gudstjenester' ? 'selected' : '') ?>>Gudstjenester</option>
							<option value="jesha" <?php echo ($chosen=='jesha' ? 'selected' : '') ?>>Jesha</option>
							<option value="esc" <?php echo ($chosen=='esc' ? 'selected' : '') ?>>ESC (Ungdom)</option>
							<option value="konfirmant" <?php echo ($chosen=='konfirmant' ? 'selected' : '') ?>>Konfirmant</option>
							<option value="tabago" <?php echo ($chosen=='tabago' ? 'selected' : '') ?>>Tabago</option>
						</select></br>
						<button type="submit" name="start-import-btn" class="btn btn-danger">Start import</button>
					</form>
				</div> <!-- Panel body end -->
			</div> <!-- Panel IMPORT END -->
				
			<!-- Panel REFRESH LOG -->
			<div class="panel panel-default">
				<div class="panel-heading">Les fra server loggen</div>
				<div class="panel-body">
					<p>Oppfrisker loggen med et utdrag fra importen (resultat vises nedenfor knappen).</p>
					<p>Når importen er ferdig vil du se teksten <b>[END IMPORT]</b> i loggen.</p>
					
				<form action="admin.php?page=medarbeideren-import" method="post">
					<button name="refresh-log-btn" class="btn btn-primary">Refresh Log</button>
				</form>
				</div> <!-- Panel body end -->
			</div> <!-- Panel REFRESH LOG END -->
				
		</div> <!-- LEFT COLUMN END -->
		
		<!-- RIGHT COLUMN -->
		<div class="col-sm-8">
			
			<!-- Panel LOG OUTPUT -->
			<div class="panel panel-primary">
				<div class="panel-heading">Server logg</div>
				<div class="panel-body">
					<?php
					//Check what action to perform - refresh or the import?
					if(isset($chosen) && isset($import_btn)) {importEvents($chosen);};
					if(isset($refresh_btn)){refreshLog();};
					?>
				</div> <!-- Panel body end -->
				</div> <!-- Panel LOG OUTPUT END -->
				
		</div> <!-- RIGHT COLUMN END -->
	</div> <!-- GRID WITH TWO COLUMNS END -->	
<?php	
}


/*
 * importEvents($chosen)
 * - Executes the import on server as a background task.
 * - Using the python script and chosen group to import
 */
function importEvents($chosen) {
		
	if(isset($chosen)){
		$cmdline = constant('IMPORT_PY') . ' ' . constant('IMPORT_PY_DIR') . constant('IMPORT_PY_EVENTS'). $chosen . '.cfg';
		echo '<b>Eksekverer importeringer av ' . $chosen . '</b>';
		//echo '</br>Command (runs on server) => ' . $cmdline;
		$result = shell_exec($cmdline . ' >> /dev/null &');
		refreshLog();		
	}	
}

/*
 * refreshLog()
 * Using tail to grab the last output from
 * the import and displays it on the right column.
 */
function refreshLog() {	
	
	$output = shell_exec(constant('REFRESH_LOG_CMD'));
	$output = '<code>' . $output;
	$output = str_replace(PHP_EOL,"</br>",$output);	
	$output = $output . '</code>';
	echo $output;
}
?>
