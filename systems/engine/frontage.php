<?php
class Frontage {
	private $registry;
	private $preAction = array();
	private $error;
	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function addPreAction($preAction) {
		$this->preAction[] = $preAction;
	}
	
	public function dispatch($action, $error) {
		$this->error = $error;
		if($this->preAction){
			foreach ($this->preAction as $preAction) {
				$result = $this->execute($preAction);
				if($result) {$action = $result; break;}
				while ($action) {
					$action = $this->execute($action);
				}
			}
		} else $action = $this->execute($action);
	}
	
	private function execute($action) {
		$result = $action->execute($this->registry);
		if(is_object($result)) $action = $result;
		else if($result === false) {
			$action = $this->error;
			$this->error = '';
		} else $action = false;
		return $action;
	}
}