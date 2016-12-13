<?php
class Config {
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

	public function load($filename) {
		$file = DI_SYSTEMS. 'config/'. $filename . '.php';
		if (file_exists($file)) {
			$_ = array();
			require($file);
			$this->data = array_merge($this->data, $_);
		} else exit('Tidak dapat meload Config'. $filename . '!');
	}
}