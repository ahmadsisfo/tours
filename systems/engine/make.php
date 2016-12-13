<?php
class Make {
	private $directory = '';
	
	public function __construct($directory = '') {
		$this->directory = $directory;
	}
	
	public function script($dir, $contents, $replace = array(), $preg = array()){
		if(is_array($replace)) {
			foreach($replace as $key => $value) {	
				$contents = str_replace($key,$value,$contents);
			}
		}
		if(is_array($preg)) {
			foreach($preg as $key => $value) {	
				$contents = preg_replace($key,$value,$contents);
			}
		}
		if(!$dir) exit('parameter script yang akan dibuat tidak ada.');
		$dir = $this->directory . $dir ;
		
		$dirname  = dirname($dir);
		if (!is_dir($dirname))
		{
			mkdir($dirname, 0755, true);
		}
		$file = fopen($dir, "w");
		if ( !$file ) {
			die('fopen failed');
		}
		$c = fwrite($file, $contents);
		fclose($file);
		//echo $c, ' bytes written';
    }
	
	public function change($contents, $replace = array(), $preg = array()) {
		if(is_array($replace)) {
			foreach($replace as $key => $value) {	
				$contents = str_replace($key,$value,$contents);
			}
		}
		if(is_array($preg)) {
			foreach($preg as $key => $value) {	
				$contents = preg_replace($key,$value,$contents);
			}
		}
		return $contents;
	}
}