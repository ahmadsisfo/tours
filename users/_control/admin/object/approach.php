<?php
class Cadminobjectapproach extends Controller {
	private $error = array();
	private $tablename  = 'approach';
    private $directory  = 'object/approach';
	private $classmodel = "Madminobjectapproach";
	
	public function index() {
		$this ->getList();
	}
	
	public function add() {
		include('_add.php');
	}
	
	public function edit() {
		include('_edit.php');
	}
	
	public function delete() {
		include('_delete.php');
	}
	
	protected function validateForm($data) {
		$directory  = $this->directory;
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/'.$directory)) 
			$this->error['warning'] = $data['error_permission'];
		/*if ((strlen($this->request->post['keyword']) < 3)) 
			$this->error['name'] = $data['error_name'];
		
		/*$category_info = $this->Madminobjectcategory->getcategorybyname($this->request->post['name']);
		if (!isset($this->request->get['category_id'])) {
			if ($category_info) {
				$this->error['warning'] = $data['error_exists'];
			}
		} else {
			if ($category_info && ($this->request->get['category_id'] != $category_info['category_id'])) {
				$this->error['warning'] = $data['error_exists'];
			}
		}*/
		return !$this->error;
	}
	
	protected function validateDelete($data) {
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/object/category')) {
			$this->error['warning'] = $data['error_permission'];
		}
		return !$this->error;
	}
	
	protected function getList() {
		$functotal	= "getTotalapproachs";
		$funcselect	= "getapproachs";
		$fieldname  = array("approach_id", "keyword", "edit");
		include('_getlist.php');
	}
	
	protected function getForm($data) {
		$classmodel = $this->classmodel;
		$funcselect	= "getapproach";
		$array_value = array(
			'approach_id'=> '', 'keyword'=>'',
		);
		$data['error_name']	= isset($this->error['categoryname'])?$this->error['categoryname']:'';
		
		include('_getform.php');
	}
}
