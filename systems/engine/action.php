<?php
class Action {
	private $file;
	private $class;
	private $method;
	private $args = array();
	
	public function __construct($route, $args = array()) {
		$path  = '';
		$parts = explode('/', str_replace('../', '', (string)$route));
		$temu  = false;
		foreach ($parts as $part) {
			$path .= $part;
			if(is_dir(DI_USERS. '_control/'. $path)) {
				$path .= '/';
				array_shift($parts);
				continue;
			} //else trigger_error('Way of Action dari direktory '.DI_USERS. '_control/'. $path.' tidak ditemukan');
				
			$file = DI_USERS. '_control/'. str_replace(array('../', '..\\', '..'),'',$path). '.php';
			if(is_file($file)) {
				$this->file  = $file;
				$this->class = 'C'. preg_replace('/[^a-zA-Z0-9]/', '', $path);
				array_shift($parts);
				$temu = true;
				break;
			} 
		}
		if(!$temu && !defined('INSTALL')) {
			$file = DI_USERS. '_control/404.php';
			if(is_file($file)) {
				$this->file   = $file;
				$this->class  = 'C'. preg_replace('/[^a-zA-Z0-9]/', '', 'public404');
				$this->method = 'index';
			} else {
				exit('404. Way of Access Not Allowed');
			}
		}else{
			if($args) $this->args = $args;
			$method = array_shift($parts);
			$method? $this->method = $method : $this->method = 'index';
		}
	}
	
	public function execute($registry) {
		if(substr($this->method, 0, 2) == '__') return false;
		if(is_file($this->file)) {
			include_once($this->file);
			$class = $this->class;
			$control = new $class($registry);
			if(is_callable(array($control,$this->method))){
				return call_user_func(array($control,$this->method), $this->args);
			} else
				return false;
		} else  return false;
	}
}