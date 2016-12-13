<?php
class Cadminsystemsetting extends Controller {
	private $error = array();
	private $tablename  = 'setting';
    private $directory  = 'system/setting';
	private $classmodel = "Madminsystemsetting";
	
	public function index() {
		$this ->getList();
	}
	
	public function add() {
		$tablename  = $this->tablename;
		$directory  = $this->directory;
		$classmodel = $this->classmodel;

		$this-> load->model('admin/'.$directory);

		$this-> document->setTitle($tablename);
		$data = $this->bahasa->loadAll('admin/'.$directory);
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			$addfunction = 'add'.$tablename;
			$this->$classmodel->$addfunction($this->request->post);
			$this->session->data['success'] = $data['text_success'];
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
			$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']:'';
			
			$this->response->redirect($this->url->link('admin/'.$directory, 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}
		$this->getForm($data);
	}
	
	public function edit() {
		$tablename  = $this->tablename;
		$directory  = $this->directory;
		$classmodel = $this->classmodel;

		$data = $this->bahasa->loadAll('admin/'.$directory);
		$this-> document->setTitle($tablename);
		$this-> load->model('admin/'.$directory);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			$editfunction = 'edit'.$tablename;
			$this->$classmodel->$editfunction($this->request->get[$tablename.'_id'], $this->request->post);
			$this->session->data['success'] = $data['text_success'];
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
			$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']:'';
			
			$this->response->redirect($this->url->link('admin/'.$directory, 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}
		$this->getForm($data);
	}
	
	public function delete() {
		$tablename  = $this->tablename;
		$directory  = $this->directory;
		$classmodel = $this->classmodel;


		$data = $this->bahasa->loadAll('admin/'.$directory);
		$this-> document->setTitle($tablename);
		$this-> load->model('admin/'.$directory);

		if (isset($this->request->post['selected']) && $this->validateDelete($data)) {
			$deletefunction = 'delete'.$tablename;
			foreach ($this->request->post['selected'] as $tablename_id) {
				$this->$classmodel->$deletefunction($tablename_id);
			}
			$this->session->data['success'] = $data['text_success'];
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
			$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']:'';
			
			$this->response->redirect($this->url->link('admin/'.$directory, 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		} else {
			$data['error_checked'] = 'Pilih Item yang akan dihapus terlebih dahulu';
		}
		$this->getList($data);

	}
	
	protected function validateForm($data) {
		$directory  = $this->directory;
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/'.$directory)) 
			$this->error['warning'] = $data['error_permission'];
		
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
		$functotal	= "getTotalsettings";
		$funcselect	= "getsettings";
		$fieldname  = array('setting_id', 'app_id', 'sgroup', 'skey', 'svalue',	'serialized', "edit");
						
		$tablename  = $this->tablename;
		$directory  = $this->directory;
		$classmodel = $this->classmodel;

		$data = $this->bahasa->loadAll('admin/'.$directory);

		$this ->load->model('admin/'.$directory);

		$sign	= 'sign=' . $this->session->data['sign'];
		$sort  	= isset($this->request->get['sort'])  ? $this->request->get['sort']  : 'name';
		$order  = isset($this->request->get['order']) ? $this->request->get['order'] :  'ASC';
		$page   = isset($this->request->get['page'])  ? $this->request->get['page']  : 	    1;
		$limit 	= $this->config->get('limit_list');

		//=================== mempertahankan URL ================
		$url 	= '';
		$url   .= isset($this->request->get['sort'])  ? '&sort='  . $this->request->get['sort']  :'';
		$url   .= isset($this->request->get['order']) ? '&order=' . $this->request->get['order'] :'';
		$url   .= isset($this->request->get['page'])  ? '&page='  . $this->request->get['page']  :'';

		$breads = array(
			'home' 	 => 'admin/home/dashboard',
			$tablename.' list' => 'admin/'.$directory
		);
		foreach($breads as $key => $value) {	
			$data['breadcrumbs'][] = array(
				'text' => $key,
				'href' => $this->url->link($value, $sign, 'SSL')
			);
		}
		$data['insert'] = $this->url->link('admin/'.$directory.'/add'   , $sign. $url, 'SSL');
		$data['delete'] = $this->url->link('admin/'.$directory.'/delete', $sign. $url, 'SSL');

		$filter 	= array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

		$tablename_total = $this->$classmodel->$functotal();
		$results 	     = $this->$classmodel->$funcselect($filter);


		$data[$tablename.'s']  = array();
		foreach ($results as $result) {
			$arrend = array();
			$fieldedit    = array(
				'edit'       => $this->url->link('admin/'.$directory.'/edit', $sign. '&'.$tablename.'_id=' . $result[$tablename.'_id'] . $url, 'SSL')
			);
			foreach ($fieldname as $field){
				if($field == "status"){
					$arrfield = array(
						'status'     => ($result['status'] ? $data['text_enabled'] : $data['text_disabled'])
					);
				} else if ($field == "edit") {
					$arrfield = array(
						'edit'       => $this->url->link('admin/'.$directory.'/edit', $sign. '&'.$tablename.'_id=' . $result[$tablename.'_id'] . $url, 'SSL')
					);
				} else {
					$arrfield = array($field => $result[$field]);
				}
				$arrend = array_merge($arrend,$arrfield);
				
			}
			$data[$tablename.'s'][] = $arrend;
			/*$data[$tablename.'s'][] = array(
				'category_id'=> $result['category_id'],
				'name'   	 => $result['name'],
				'image' 	 => $result['image'],
				'parent' 	 => $result['parent_id'],
				'status'     => ($result['status'] ? $data['text_enabled'] : $data['text_disabled']),
				'edit'       => $this->url->link('admin/object/category/edit', $sign. '&category_id=' . $result['category_id'] . $url, 'SSL')
			);*/
		}

		//echo json_encode($data[$tablename.'s']);

		$data['error_warning'] = isset($this->error['warning'])? $this->error['warning']:'';
		$data['success'] 	   = isset($this->session->data['success'])? $this->session->data['success']:'';
		$data['selected'] 	   = isset($this->request->post['selected'])?(array)$this->request->post['selected']:array();
		if (isset($this->session->data['success']))	unset($this->session->data['success']);
		//=================== header Table ==============

		$url  = '';
		$url .= ($order == 'ASC')? '&order=DESC' : '&order=ASC';
		$url .= isset($this->request->get['page'])? '&page=' . $this->request->get['page']:'';

		foreach ($fieldname as $field){
			if($field != "edit") {
				$data['sort_'.$field] 	= $this->url->link('admin/'.$directory, $sign. '&sort='.$field . $url, 'SSL');
			}
		}

		//=================== pagination ================
		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort='  . $this->request->get['sort']:'';
		$url .= isset($this->request->get['order'])?'&order=' . $this->request->get['order']:'';

		$pagination 		= new Pagination();
		$pagination->total 	= $tablename_total;
		$pagination->page 	= $page;
		$pagination->limit 	= $limit;
		$pagination->url 	= $this->url->link('admin/'.$directory, $sign. $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] 	= sprintf($data['text_pagination'], ($tablename_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($tablename_total - $limit)) ? $tablename_total : ((($page - 1) * $limit) + $limit), $tablename_total, ceil($tablename_total / $limit));
		$data['sort'] 		= $sort;
		$data['order'] 		= $order;


		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view('admin/'.$directory.'_list', $data);
		$this->response->setOutput($output);
	}
	
	protected function getForm($data) {
		$classmodel = $this->classmodel;
		$funcselect	= "getsetting";
		$array_value =  array(
			'setting_id'=>'', 'app_id'=>'', 'sgroup'=>'', 'skey'=>'', 
			'svalue'=>'',	'serialized'=>'');
		
		$tablename  = $this->tablename;
		$directory  = $this->directory;
		$classmodel = $this->classmodel;

		$tablename_info = '';
		if (isset($this->request->get[''.$tablename.'_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tablename_info = $this->$classmodel->$funcselect($this->request->get[$tablename.'_id']);
		}

		$data = array_merge($data, $this->value->set($this->request->post,$tablename_info,$array_value));

		$sign   	= 'sign=' . $this->session->data['sign'];

		$data['text_form'] 			=!isset($this->request->get[$tablename.'_id']) ? $data['text_add'] : $data['text_edit'];
		$data['error_warning'] 		= isset($this->error['warning'])? $this->error['warning']:'';


		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
		$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
		$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']:'';

		$data['action'] = !isset($this->request->get[$tablename.'_id'])?
			$this->url->link('admin/'.$directory.'/add' , $sign. $url, 'SSL'):
			$this->url->link('admin/'.$directory.'/edit', $sign. '&'.$tablename.'_id=' . $this->request->get[''.$tablename.'_id'] . $url, 'SSL');

		$data['cancel'] = $this->url->link('admin/'.$directory.'', $sign. $url, 'SSL');

		$data['password']	 = isset($this->request->post['password'])? $this->request->post['password']:'';
		$data['confirm'] 	 = isset($this->request->post['confirm']) ? $this->request->post['confirm']:'';

		$breads = array(
			'home' 	   			    => 'admin/home/dashboard',
			$tablename 	   			=> 'admin/'.$directory.'',
			$data['text_form']      => '',
			
		);
		foreach($breads as $key => $value) {	
			if($value == ''||$value == '#') {
				$data['breadcrumbs'][] = array('text' => $key,'href' => '');
			} else {
				$data['breadcrumbs'][] = array(
					'text' => $key,
					'href' => $this->url->link($value, $sign, 'SSL')
				);
			}
		}


		$this->load->model('admin/tools/image');

		if (isset($this->request->post['image']) && is_file(DI_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->Madmintoolsimage->resize($this->request->post['image'], 100, 100);
		} elseif (isset($tablename_info['image']) &&!empty($tablename_info) && $tablename_info['image'] && is_file(DI_IMAGE . $tablename_info['image'])) {
			$data['thumb'] = $this->Madmintoolsimage->resize($tablename_info['image'], 100, 100);
		} elseif (isset($tablename_info[0]['image']) &&!empty($tablename_info) && $tablename_info[0]['image'] && is_file(DI_IMAGE . $tablename_info[0]['image'])) {
			$data['thumb'] = $this->Madmintoolsimage->resize($tablename_info[0]['image'], 100, 100);	
		} else {
			$data['thumb'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);

		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view('admin/'.$directory.'_form', $data);
		$this->response->setOutput($output);
	}
}
