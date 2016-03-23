<?php

/*
 * showHTML - displays the plugin within the admin menu  
 */

function showHTML() {

  if ( !current_user_can('import') )
		wp_die(__('You do not have sufficient permissions of this site.'));

	//Get the chosen events
	$chosen = $_REQUEST['chosen'];
	$import_btn = $_REQUEST['start-import-btn'];

	//Styles and scripts (Bootstrap)
	wp_register_style( 'medimp_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' );
	wp_register_style( 'medimp_bootstraptheme', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css' );
	wp_register_script( 'medimp_jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js' );
	wp_register_script( 'medimp_bootstrap_js', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js' );
	
	wp_enqueue_style( 'medimp_bootstrap');
	wp_enqueue_style( 'medimp_bootstraptheme');
	wp_enqueue_script( 'medimp_jquery');
	wp_enqueue_script( 'medimp_bootstrap_js');

	?>

	</br>
	
	<!-- One Form -->
	<form role="form" method="post" action="admin.php?page=medarbeideren-import">

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
					<div class="form-group">
							<label for="chosen">Velg gruppe for import:</label>
							<select class="form-control" id="chosen" name="chosen">
								<option value="gudstjenester" <?php echo ($chosen=='gudstjenester' ? 'selected' : '') ?>>Gudstjenester</option>
								<option value="jesha" <?php echo ($chosen=='jesha' ? 'selected' : '') ?>>Jesha</option>
								<option value="esc" <?php echo ($chosen=='esc' ? 'selected' : '') ?>>ESC (Ungdom)</option>
								<option value="konfirmant" <?php echo ($chosen=='konfirmant' ? 'selected' : '') ?>>Konfirmant</option>
								<option value="tabago" <?php echo ($chosen=='tabago' ? 'selected' : '') ?>>Tabago</option>
						  </select>
						</div> <!-- Form Group -->
						<button type="submit" id="start-import-btn" name="start-import-btn" class="btn btn-danger" >Start import</button>

				</div> <!-- Panel body end -->
			</div> <!-- Panel IMPORT END -->				
				
		</div> <!-- LEFT COLUMN END -->
		
		<!-- RIGHT COLUMN -->
		<div class="col-sm-8">
			
			<!-- Panel LOG OUTPUT -->
			<div class="panel panel-primary">
				<div class="panel-heading">Server logg</div>
				<div class="panel-body">
					<div id="loggen"></div>					
					<?php
					//Check what action to perform - refresh or the import?
					if(isset($chosen) && isset($import_btn)) {importEvents($chosen);};
					?>
				</div> <!-- Panel body end -->
				</div> <!-- Panel LOG OUTPUT END -->
				
		</div> <!-- RIGHT COLUMN END -->
	</div> <!-- GRID WITH TWO COLUMNS END -->


	</form> <!-- Everything in one form -->

<body onLoad="replaceLog();startRefresh()"/>
<script>

//intervalID for refresh - disable it when log is finished
intervalID = null;

function startRefresh() {	
	intervalID = setInterval(replaceLog, 1000);
}

function stopRefresh() {

	$('#start-import-btn').attr("disabled", false);
	$('#chosen').attr("disabled", false);
	clearInterval(intervalID);

}	

function replaceLog() {
		$('#start-import-btn').attr("disabled", true);
		$('#chosen').attr("disabled", true);
		$.get("../wp-content/plugins/MedarbeiderenImportToWP/server-log.php?getlog=true", function(data, status){
			$('#loggen').html(data);
			if (data.search("END IMPORT")>0) { stopRefresh(); };
    });
}

</script>	

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
		echo $alertmsg;
		$result = shell_exec($cmdline . ' >> /dev/null &');
	}	
}
?>
