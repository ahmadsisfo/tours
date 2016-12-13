<?php
class Cadminobjectattribute extends Controller {
	private $error = array();
	private $tablename  = 'attribute';
    private $directory  = 'object/attribute';
	private $classmodel = "Madminobjectattribute";
	
	public function index() {
		$this ->getList();
	}
	
	public function add() {
		include('_add.php');
	}
	
	public function edit() {
		$id_edit = "category";
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
		/*if ((strlen($this->request->post['name']) < 3) || (strlen($this->request->post['name']) > 20)) 
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
		$id_edit = "category";
		$functotal	= "getTotalattributes";
		$funcselect	= "getattributes";
		$fieldname  = array("category_id", "name", "attribute", "edit");
		include('_getlist.php');
	}
	
	protected function getForm($data) {
		$id_edit = "category";
		$classmodel = $this->classmodel;
		$funcselect	= "getattribute";
		$array_value = array(
			'category_id'=> '',  
			'sort_order'=>'', 'attribute_group_id'=>'',
		);
		$data['error_name']	= isset($this->error['categoryname'])?$this->error['categoryname']:'';
		$this->load->model('admin/object/attribute_group');
		$data['attribute_groups'] = $this->Madminobjectattributegroup->getattribute_groups();
		$this->load->model('admin/object/attribute');
		$data['attribute_rec'] = isset($this->request->get['category_id'])?$this->Madminobjectattribute->getattribute($this->request->get['category_id']):array();
		$this->load->model('admin/object/category');
		$data['categorys'] = $this->Madminobjectcategory->getcategorys();
		include('_getform.php');
	}
	
	public function autoform() {
		$json = array();

		if (isset($this->request->get['category_id'])) {
			$this->load->model('admin/object/attribute');

			$filter_data = array(
				'category_id' => $this->request->get['category_id'],
				'sort'        => 'category_id',
				'order'       => 'ASC',
			);

			$results = $this->Madminobjectattribute->getattributesform($filter_data['category_id']);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'attribute'   => $result['attribute'],
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
