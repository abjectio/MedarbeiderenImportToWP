<?php 
	define('REFRESH_LOG_CMD', 'cat -n /tmp/import_events.log');
  
	$getLog = $_GET["getlog"];
	if($getLog=='true'){echo refreshLog();}

/*
 * refreshLog()
 * Using tail to grab the last output from
 * the import and displays it on the right column.
 */
function refreshLog() {		
	$output = shell_exec(constant('REFRESH_LOG_CMD'));
	$output = '<code>' . str_replace(PHP_EOL,"<br>",$output) . '</code>';
	return $output;
}
?>
