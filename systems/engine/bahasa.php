<?php
class Bahasa {
	private $default = 'indonesia';
	private $directory;
	private $data = array();
	
	public function __construct($directory = '') {
		$this->directory = $directory;
	}
	
	public function get($key) {
		isset($this->data[$key])? $result = $this->data[$key]: $result = $key;
		return $result;
	}
	
	public function load($filename) {
		$_ = array();
		$file = DI_BAHASA. $this->default. '/' .$filename. '.php';
		if	(file_exists($file)) require($file);
		else trigger_error($file.' tidak ditemukan'); 
		if($this->directory) {	
			$file = DI_BAHASA. $this->directory. '/' .$filename. '.php';
			if(file_exists($file)) require($file);
			else trigger_error($file.' tidak ditemukan'); 
		}
		$this->data = array_merge($this->data, $_);
		return $this->data;
	}
	
	public function loadAll($filename) {
		$_ = array();
		$file = DI_BAHASA. $this->default. '/' .$filename. '.php';
		if	(file_exists($file)) require($file);
		else trigger_error($file.' tidak ditemukan');
		$file = DI_BAHASA. $this->default. '/default.php';
		if	(file_exists($file)) require($file);
		if($this->directory) {	
			$file = DI_BAHASA. $this->directory. '/' .$filename. '.php';
			if(file_exists($file)) require($file);
			else trigger_error($file.' tidak ditemukan'); 
		}
		$this->data = $_;
		return $this->data;
	}
}