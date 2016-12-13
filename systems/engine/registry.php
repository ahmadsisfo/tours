<?php
class Registry {
	private $data = array();
	
	public function get($key) {
		isset($this->data[$key]) ? $result = $this->data[$key] : $result = null;
		return $result;
	}
	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function has($key) {
		return isset($this->data[$key]);
	}
}