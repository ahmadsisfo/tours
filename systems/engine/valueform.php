<?php
class Valueform {
	private $data 	= array();
	
	public function __construct() {

	}
	
	public function set($item_post = '',$item_info = '',$args = array()) {
		
		foreach($args as $key => $value){
			if (isset($item_post[$key])) {
				$this->data[$key] = $item_post[$key];
			} elseif (!empty($item_info)) {
				if(isset($item_info[$key])){
					$this->data[$key] = $item_info[$key];
				} else {
					//echo json_encode($item_info);
					if(isset($item_info[0][$key])){
						$this->data[$key] = $item_info[0][$key];
					}  else if(isset($item_info[$key])&&($item_info[$key] == null)){
						$this->data[$key] = $item_info[$key];
					} else {
						$this->data[$key] = '';
					}
				}
			} else {
				$this->data[$key] = $value;
			}
		}
		return $this->data;
	}
	
	public function def($item_post = '',$item_info = '',$args = array()){
		if(isset($item_post)&&$item_post!=null){
			$post_key = array();
			foreach($item_post as $key => $value){
				$post_key[] = $key;
			}
			$i = 0;
			//exit(json_encode($item_post));
			foreach($args as $key){
				if(in_array($key, $post_key)){
					$this->data[$key] = $item_post[$key];
				} else {
					//$this->data[$key] = 0;
				}
				$i++;
			}
		} else {
			foreach($args as $key){
				if (!empty($item_info)) {
					if(isset($item_info[$key])){
						$this->data[$key] = $item_info[$key];
					} else {
						//echo json_encode($item_info);
						if(isset($item_info[0][$key])){
							$this->data[$key] = $item_info[0][$key];
						} else if(isset($item_info[$key])&&($item_info[$key] == null)){
							$this->data[$key] = $item_info[$key];
						} else {
							$this->data[$key] = '';
						}
					}
				} else {
					$this->data[$key] = '';
				}
			}
		}
		//exit(json_encode($this->data));
		return $this->data;
	}
	
	public function get($key) {
		isset($this->data[$key])? $result = $this->data[$key]: $result = $key;
		return $result;
	}
	
	
}