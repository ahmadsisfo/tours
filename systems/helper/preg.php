<?php
class Preg {
	public $preg = array();
	public $rplc = array();
	
	public function __construct() {
		$this->preg = array(
			'#(<a href=".*?).*?way=(.*?)&.*?(&.*?" .*?>.*?</a>)#' => '$1#$2$3',
			'#(<a href=".*?).*?way=(.*?)&.*?(&.*?".*?>.*?</a>)#' => '$1#$2$3',			
			'#(<a href=".*?).*?way=(.*?)&.*?(" .*?>.*?</a>)#' => '$1#$2$3', 
			'#(<a href=".*?).*?way=(.*?)&.*?(">.*?</a>)#' => '$1#$2$3', 
			'#(<form action=".*?).*?way=(.*?)&.*?(&.*?")#' => '$1#$2$3',
			'#(<form action=".*?).*?way=(.*?)&.*?(")#' => '$1#$2$3',
		);
		$this->rplc = array(
			'https://'=>'http://',
		);
	}
}