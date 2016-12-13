<?php 
//error_reporting(E_ALL);
define('BISMILLAH', 'FENGINE Version 1.0.0');
define('INSTALL', true);

define('HTTP_SERVER'	,'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTPS_SERVER'	,'https://'. $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_RNF'		,'http://' . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');
define('HTTPS_RNF'		,'https://'. $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\') . '/');

// DIRECTORY
define('DI_'		, str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/');
define('DI_ASSETS'		, str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/assets/');
define('DI_APP'		, str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/users/');
define('DI_SYSTEMS'	, str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/systems/');
define('DI_USERS'	, str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/install/');
define('DI_BAHASA'  , str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/install/bahasa/');
define('DI_CLIENT'  , str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/assets/client/');
define('DI_IMAGE'	, str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/assets/image/');

define('TPL_TYPE'	,'');

require_once(DI_SYSTEMS . 'system.php');

$registry 	= 	new Registry();
$loader 	= 	new Loader($registry);
$registry	->	set('load'	, $loader);
$url 		= 	new Url(HTTP_SERVER, HTTPS_SERVER);
$registry	->	set('url', $url);
$request	=	new Request();
$registry	->	set('request', $request);
$response 	= 	new Response();
$response	->	addHeader('Content-Type: text/html; charset=utf-8');
$registry	->	set('response', $response);
$registry	->	set('session', new Session());
$bahasa 	= 	new Bahasa();
$bahasa		->	load('default');
$registry	->	set('bahasa'	, $bahasa);
$registry	->	set('document', new Document());
$registry	->	set('make', new Make(DI_CLIENT));
$registry	->	set('value'	, new Valueform());
//$registry	->	set('user', new User($registry));
$frontage 	= 	new Frontage($registry);


// Upgrade
$upgrade = false;
if (file_exists(DI_USERS.'config.php')) {
	if (filesize(DI_USERS.'config.php') > 0) {
		$upgrade = true;
		$lines = file(DI_USERS . 'config.php');
		foreach ($lines as $line) {
			if (strpos(strtoupper($line), 'DB_') !== false) {
				echo eval($line);		
			}
		}
	}
} 

// Router
if (isset($request->get['way'])) {
	$action = new Action($request->get['way']);
} 
/*else if ($upgrade) {
	header('Location : ../', true, 302);
} */
else {
	$action = new Action('step_1');
}

$frontage	->	dispatch($action, '');
$response	->	output();
