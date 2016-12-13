<?php if (!defined('BISMILLAH')) exit('No access allowed');

error_reporting(E_ALL);
if(version_compare(phpversion(),'5.3.0','<') == true ) exit('PHP versi 5.3+ required');

if(!ini_get('date.timezone')) date_default_timezone_set('Asia/Jakarta');

if(!isset($_SERVER['DOCUMENT_ROOT'])) exit('Document Root Tidak Tersedia');

if(!isset($_SERVER['REQUEST_URI'])) exit('Request URI tidak berjalan');

if(!isset($_SERVER['HTTP_HOST'])) $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');

if 	   (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) 
	$_SERVER['HTTPS'] = true;
else if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') 
	$_SERVER['HTTPS'] = true;
else 
	$_SERVER['HTTPS'] = false;

date_default_timezone_set('Asia/Jakarta');

function autoload($class) {
	//echo$class."<br/>";
	$fileautoload = DI_SYSTEMS . 'engine/' . str_replace('\\', '/', strtolower($class)) . '.php';
	if (file_exists($fileautoload)) {
		include($fileautoload);
		return true;
	} else {
		exit('autoload file pada class '.$class.' tidak bekerja');
		return false;
	}
}

spl_autoload_register('autoload');
spl_autoload_extensions('.php');
require_once(DI_SYSTEMS . 'engine/controller.php');
require_once(DI_SYSTEMS . 'engine/model.php');

