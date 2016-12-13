<?php
class Cadminobjectattributegroup extends Controller {
	private $error = array();
	private $tablename  = 'attribute_group';
    private $directory  = 'object/attribute_group';
	private $classmodel = "Madminobjectattributegroup";
	
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
		if ((strlen($this->request->post['name']) < 3) || (strlen($this->request->post['name']) > 20)) 
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
		$functotal	= "getTotalattribute_groups";
		$funcselect	= "getattribute_groups";
		$fieldname  = array("attribute_group_id", "name", "type", "edit");
		include('_getlist.php');
	}
	
	protected function getForm($data) {
		$classmodel = $this->classmodel;
		$funcselect	= "getattribute_group";
		$array_value = array(
			'attribute_group_id'   => '','name'		    => '',
			'type'		=> '',
		);
		$data['error_name']	= isset($this->error['name'])?$this->error['name']:'';
		
		include('_getform.php');
	}
}
