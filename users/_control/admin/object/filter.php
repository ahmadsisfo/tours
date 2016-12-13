<?php
class Cadminobjectfilter extends Controller {
	private $error = array();
	private $tablename  = 'filter';
    private $directory  = 'object/filter';
	private $classmodel = "Madminobjectfilter";
	
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
		$functotal	= "getTotalfilters";
		$funcselect	= "getfilters";
		$fieldname  = array("filter_id", "name", "sort_order", "filter_group", "edit");
		include('_getlist.php');
	}
	
	protected function getForm($data) {
		$classmodel = $this->classmodel;
		$funcselect	= "getfilter";
		$array_value = array(
			'filter_id'=> '', 'name'=>'',
			'sort_order'=>'', 'filter_group_id'=>'',
		);
		$data['error_name']	= isset($this->error['categoryname'])?$this->error['categoryname']:'';
		$this->load->model('admin/object/filter_group');
		$data['filter_groups'] = $this->Madminobjectfiltergroup->getfilter_groups();
		
		include('_getform.php');
	}
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('admin/object/filter');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$filters = $this->Madminobjectfilter->getFilters($filter_data);

			foreach ($filters as $filter) {
				$json[] = array(
					'filter_id' => $filter['filter_id'],
					'name'      => strip_tags(html_entity_decode($filter['filter_group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8'))
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
