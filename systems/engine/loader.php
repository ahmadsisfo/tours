<?php
class Loader {
	private $registry;
	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function model($model) {
		$file  = DI_USERS. '_model/' .$model.'.php';
		$class = 'M'. preg_replace('/[^a-zA-Z0-9]/', '', $model);
		if(file_exists($file)) {
			include_once($file);
			$modelclass = new $class($this->registry);
			if(is_callable(array($modelclass,'index'))){
				$modelclass->index();
			}
			$this->registry->set('M' . preg_replace('/[^a-zA-Z0-9]/', '', $model), $modelclass);
			//return call_user_func(array($class,'index'));
		} else exit('Tidak dapat meload Model '.$file.' !');	
	}
	
	public function view($view, $data = array()) {
		$view .= TPL_TYPE;
		$file = DI_USERS. '_view/' .$view ;
		if(file_exists($file)){
			extract($data);
			ob_start();
			require($file);
			$result = ob_get_contents();
			ob_end_clean();
			return $result;
		} else exit('Gagal mengambil VIEW page '.$view. ' !');
	}
	
	public function control($route, $args = array()) {
		$action = new Action($route, $args);
		return $action->execute($this->registry);
	}
	
	public function script($script, $data = array()) {
		$file = DI_USERS. '_view/' .$script ;
		if(file_exists($file)){
			extract($data);
			ob_start();
			require($file);
			$result = ob_get_contents();
			ob_end_clean();
			return $result;
		} else exit('Gagal mengambil script page '.$script. ' !');
	}
	
	public function bahasa($bahasa) {
		return $this->registry->get('bahasa')->load($bahasa);
	}
	
	public function library($library) {
		$file = DI_SYSTEMS. 'library/'. $library . '.php';
		if(file_exists($file)) {
			include_once($file);
		} else exit('Tidak dapat meload Library '.$file. ' !');
	}
	
	public function helper($helper) {
		$file = DI_SYSTEMS . 'helper/' .$helper .'.php';
		if (file_exists($file)) {
			require_once($file);
		} else exit('Tidak bisa meload file helper '.$file.' !');
	}
}