<?php
function error_handler($errno, $errstr, $errfile, $errline) {
	global $log, $config;

	// error suppressed with @
	if (error_reporting() === 0) {
		return false;
	}

	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	//if ($config->get('config_error_display')) {
		echo '<b>' . $error . '</b>: ' . $errstr . ' di <b>' . $errfile . '</b> pada baris ke <b>' . $errline . '</b><br/>';
	//}

	//if ($config->get('config_error_log')) {
		$log->write('PHP ' . $error . ':  ' . $errstr . ' di ' . $errfile . ' pada baris ke ' . $errline);
	//}

	return true;
}

set_error_handler('error_handler');
