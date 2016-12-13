<?php
class Cadminobjectcategory extends Controller {
	private $error = array();
	private $tablename  = 'category';
    private $directory  = 'object/category';
	private $classmodel = "Madminobjectcategory";
	
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
			$this->error['categoryname'] = $data['error_categoryname'];
		
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
		$functotal	= "getTotalcategorys";
		$funcselect	= "getCategorys";
		$fieldname  = array("category_id", "name", "image", "parent", "status", "edit");
		include('_getlist.php');
	}
	
	protected function getForm($data) {
		$classmodel = $this->classmodel;
		$funcselect	= "getCategory";
		$array_value = array(
			'category_id'   => '','name'		    => '',
			'parent_id'	    => '','image'			=> '',
			'status'		=> 0,
		);
		$data['error_categoryname']	= isset($this->error['categoryname'])?$this->error['categoryname']:'';
		$data['parents'] 			= $this->$classmodel->getparents();
		
		include('_getform.php');
	}
}
