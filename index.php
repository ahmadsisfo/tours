<?php
//header('Access-Control-Allow-Origin: *');
define('BISMILLAH', 'FENGINE Version 1.0.0');

if (is_file('users/config.php')){
	if (filesize('users/config.php') > 0) require_once('users/config.php'); 
	else exit(header('Location : install/', true, 302));
} else exit("file users/config.php tidak ditemukan");	

require_once(DI_SYSTEMS. 'system.php'); 
$registry 	= 	new Registry();
$loader 	= 	new Loader($registry);
$registry	->	set('load'	, $loader);
$config 	= 	new Config();
$registry	->	set('config'	, $config);
$db 		= 	new _DB(DB_DRIVER, DB_HOST, DB_USER, DB_PASS, DB_DBASE);
$registry	->	set('db', $db);
$url 		= 	new Url(HTTP_SERVER, HTTPS_SERVER);
$registry	->	set('url', $url);
$log 		= 	new Log('error.log');
$registry	->	set('log', $log);
$loader		->	helper('error_handler');
$request	=	new Request();
$registry	->	set('request', $request);
$response 	= 	new Response();
$response	->	addHeader('Content-Type: text/html; charset=utf-8');
$registry	->	set('response', $response);
$cache		=   new Cache('file');
$registry   ->  set('cache', $cache);
$registry	->	set('session', new Session());
$bahasa 	= 	new Bahasa();
$bahasa		->	load('default');
$registry	->	set('bahasa'	, $bahasa);
$registry	->	set('document', new Document());
$registry	->	set('tracker', new Tracker());
$registry	->	set('make', new Make());
$registry	->	set('value'	, new Valueform());
//$registry	->	set('user', new User($registry));
$frontage 	= 	new Frontage($registry);

$query 		=   $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE app_id = '0'");

foreach ($query->rows as $setting) {
	!$setting['serialized']?
	 $config->set($setting['skey'], $setting['svalue']):
	 $config->set($setting['skey'], unserialize($setting['svalue']));
	 
	 
}


if (isset($request->get['way'])) 
	$action = 	new Action($request->get['way']);
else {
	$action = 	new Action('admin/login');
}

$frontage	->	dispatch($action, '');
$response	->	output();
