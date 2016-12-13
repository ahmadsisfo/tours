<?php
class Url {
	private $domain;
	private $rewrite = array();
	private $ssl;

	public function __construct($domain, $ssl = '') {
		$this->domain = $domain;
		$this->ssl = $ssl;
	}

	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false) {
		!$secure? $url = $this->domain:	$url = $this->ssl;
		//=========================
		$url .= '?way=' . $route;
		//=========================
		if ($args) {
			//$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			$url .= str_replace('&', '&', '&' . ltrim($args, '&'));
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}
}