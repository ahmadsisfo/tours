<?php
class Cache {
	private $cache;

	public function __construct($driver, $expire = 3600) {
		$class = 'Cache\\' . $driver;

		if (class_exists($class)) {
			$this->cache = new $class($expire);
		} else {
			exit('Error: Tidak dapat meload driver ' . $driver . ' cache!');
		}
	}

	public function get($key) {
		return $this->cache->get($key);
	}

	public function set($key, $value, $noEx=false) {
		return $this->cache->set($key, $value, $noEx);
	}

	public function delete($key) {
		return $this->cache->delete($key);
	}
}
