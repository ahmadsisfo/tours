<?php
class C'adminsystembaru' extends Controller {
	private $error 	= array();
	private $table 	= 'baru';
    private $direc 	= 'admin/system/baru';
	private $model  = 'adminsystembaru';
	private $id		= 'dua';
	private $fields	= array('satu','dua');
	private $fieldlist = array('satu','dua');
	
	public function index() {
		$this->document->setTitle($this->table." list");
		$this ->getList();
	}
	
	public function add() {
		$table	=  	$this->table;
		$direc	=  	$this->direc;
		$model	=	$this->model;
		$id		= 	$this->id;

		$data	= $this->bahasa->loadAll($direc);
		
		$this->document->setTitle("add ".$table);
		$this->load->model($direc);
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			$funcadd = 'add'.$table;
			$this->$model->$funcadd($this->request->post);
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']	:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']	:'';
			$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']	:'';
			$this->session->data['success'] = $data['text_success'];			
			$this->response->redirect($this->url->link($direc, 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}
		$this->getForm($data);
	}
	
	public function edit() {
		$table	=  	$this->table;
		$direc	=  	$this->direc;
		$model	=	$this->model;
		$id		= 	$this->id;
		
		$data	= $this->bahasa->loadAll($direc);
		
		$this->document->setTitle("edit ".$table);
		$this->load->model($direc);
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			$funcedit = 'edit'.$table;
			$this->$model->$funcedit($this->request->get[$id], $this->request->post);
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']	:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']	:'';
			$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']	:'';
			$this->session->data['success'] = $data['text_success'];
			$this->response->redirect($this->url->link($direc, 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}
		$this->getForm($data);
	}
	
	public function delete() {
		$table	=  	$this->table;
		$direc	=  	$this->direc;
		$model	=	$this->model;
		$id		= 	$this->id;

		$data = $this->bahasa->loadAll($direc);
		
		$this-> load->model($direc);

		if (isset($this->request->post['selected']) && $this->validateDelete($data)) {
			$funcdelete = 'delete'.$table;
			foreach ($this->request->post['selected'] as $tableid) {
				$this->$model->$funcdelete($tableid);
			}
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']	:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']	:'';
			$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']	:'';
			$this->session->data['success'] = $data['text_success'];			
			$this->response->redirect($this->url->link($direc, 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		} else {
			$data['error_checked'] = 'Pilih Item yang akan dihapus terlebih dahulu';
		}
		$this->getList($data);
	}
	
	protected function validateForm($data) {
		
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', $this->direc)) 
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
		if (!$this->Muser->hasPermission('modify', $this->direc)) {
			$this->error['warning'] = $data['error_permission'];
		}
		return !$this->error;
	}
	
	protected function getList() {
		
		$table	=  	$this->table;
		$direc	=  	$this->direc;
		$model	=	$this->model;
		$fields	=  	$this->fields;
		$id		= 	$this->id;
		
		$functotal	= "gettotal".$table."s";
		$funcselect	= "get".$table."s";
		
		$data 	= 	$this->bahasa->loadAll($direc);

		$this->load->model($direc);

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
			$table.' list' => $direc
		);
		
		foreach($breads as $key => $value) {	
			$data['breadcrumbs'][] = array(
				'text' => $key,
				'href' => $this->url->link($value, $sign, 'SSL')
			);
		}
		
		$data['insert'] = $this->url->link($direc.'/add'   , $sign. $url, 'SSL');
		$data['delete'] = $this->url->link($direc.'/delete', $sign. $url, 'SSL');

		$filter 	= array(
			'sort'  =>  $sort,
			'order' =>  $order,
			'start' => ($page - 1) * $limit,
			'limit' =>  $limit
		);

		$total		= $this->$model->$functotal();
		$results	= $this->$model->$funcselect($filter);

		$data[$table.'s']  = array();
		foreach ($results as $result) {
			$arrend = array();
			foreach ($fields as $field){
				if($field == "status"){
					$arrfield = array(
						'status'     => ($result['status'] ? $data['text_enabled'] : $data['text_disabled'])
					);
				} else if ($field == "edit") {
					$arrfield  = array(
						'edit' => $this->url->link($direc.'/edit', $sign. '&'.$id.'=' . $result[$id] . $url, 'SSL')
					);
				} else {
					$arrfield = array($field => $result[$field]);
				}
				$arrend = array_merge($arrend,$arrfield);
			}
			$data[$table.'s'][] = $arrend;
		}

		$data['error_warning'] = isset($this->error['warning'])			? $this->error['warning']:'';
		$data['success'] 	   = isset($this->session->data['success'])	? $this->session->data['success']:'';
		$data['selected'] 	   = isset($this->request->post['selected'])?(array)$this->request->post['selected']:array();
		
		if (isset($this->session->data['success']))	unset($this->session->data['success']);
		
		//=================== header Table ==============

		$url  =  '';
		$url .= ($order == 'ASC')? '&order=DESC' : '&order=ASC';
		$url .=  isset($this->request->get['page'])? '&page=' . $this->request->get['page']:'';

		foreach ($fields as $field){
			if($field != "edit") {
				$data['sort_'.$field] 	= $this->url->link($direc, $sign. '&sort='.$field . $url, 'SSL');
			}
		}

		//=================== pagination ================
		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort='  . $this->request->get['sort']:'';
		$url .= isset($this->request->get['order'])?'&order=' . $this->request->get['order']:'';

		$pagination 		= new Pagination();
		$pagination->total 	= $total;
		$pagination->page 	= $page;
		$pagination->limit 	= $limit;
		$pagination->url 	= $this->url->link($direc, $sign. $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] 	= sprintf($data['text_pagination'], ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));
		$data['sort'] 		= $sort;
		$data['order'] 		= $order;


		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view($direc.'_list', $data);
		$this->response->setOutput($output);
	}
	
	protected function getForm($data) {
		
		$table	=  	$this->table;
		$direc	=  	$this->direc;
		$model	=	$this->model;
		$fields	=  	$this->fields;
		$id		= 	$this->id;
		
		$funcselect	= "get".$table;
		
		$defvalue = array(
			'category_id'=> '',  'sort_order'=>'', 'attribute_group_id'=>'',
		);
		$data['error_name']	= isset($this->error['categoryname'])?$this->error['categoryname']:'';
		
		/*$this->load->model('admin/object/attribute_group');
		$data['attribute_groups'] = $this->Madminobjectattributegroup->getattribute_groups();
		$this->load->model('admin/object/attribute');
		$data['attribute_rec'] = isset($this->request->get['category_id'])?$this->Madminobjectattribute->getattribute($this->request->get['category_id']):array();
		$this->load->model('admin/object/category');
		$data['categorys'] = $this->Madminobjectcategory->getcategorys();
		*/
		
		$info = '';
		if (isset($this->request->get[$id]) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$info = $this->$model->$funcselect($this->request->get[$id]);
		}

		$data 	=  array_merge($data, $this->value->set($this->request->post,$info,$defvalue));

		$sign   = 'sign=' . $this->session->data['sign'];

		$data['text_form'] 			=!isset($this->request->get[$id]) ? $data['text_add'] : $data['text_edit'];
		$data['error_warning'] 		= isset($this->error['warning'])? $this->error['warning']:'';

		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']	:'';
		$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']	:'';
		$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']	:'';

		$data['action'] = !isset($this->request->get[$id])?
			$this->url->link($direc.'/add' , $sign. $url, 'SSL'):
			$this->url->link($direc.'/edit', $sign. '&'.$id.'=' . $this->request->get[$id] . $url, 'SSL');
			
		$data['cancel'] = $this->url->link($direc, $sign. $url, 'SSL');

		$data['password'] = isset($this->request->post['password'])? $this->request->post['password']:'';
		$data['confirm']  = isset($this->request->post['confirm']) ? $this->request->post['confirm']:'';

		$breads = array(
		    'home' 	   			=> 'admin/home/dashboard',
			 $table 	   		=>  $direc,
			 $data['text_form'] => '',
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
		} elseif (isset($info['image']) &&!empty($info) && $info['image'] && is_file(DI_IMAGE . $info['image'])) {
			$data['thumb'] = $this->Madmintoolsimage->resize($info['image'], 100, 100);
		} elseif (isset($info[0]['image']) &&!empty($info) && $info[0]['image'] && is_file(DI_IMAGE . $info[0]['image'])) {
			$data['thumb'] = $this->Madmintoolsimage->resize($info[0]['image'], 100, 100);	
		} else {
			$data['thumb'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);

		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view($direc.'_form', $data);
		$this->response->setOutput($output);
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
