<?php
class Request {
	public $get 	= array();
	public $post 	= array();
	public $cookie 	= array();
	public $files 	= array();
	public $server 	= array();

	public function __construct() {
		$this->get 		= $this->filter($_GET);
		$this->post 	= $this->filter($_POST);
		$this->request 	= $this->filter($_REQUEST);
		$this->cookie 	= $this->filter($_COOKIE);
		$this->files 	= $this->filter($_FILES);
		$this->server 	= $this->filter($_SERVER);
		
		/*$i=1; $text = '<table/>';
		foreach($this->server as $key => $value){
			$text .= "<tr><td>" .$i ."</td><td>". $key ." </td><td> : ". $value ."</td></tr>";
			$i++;
		}
		define('SERVER_INFO',$text); 
		*/
	}
    
	public function filter($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);
				$data[$this->filter($key)] = $this->filter($value);
			}
		} else {
			$data = htmlspecialchars(trim($data), ENT_COMPAT, 'UTF-8');
		}
		return $data;
	}
}