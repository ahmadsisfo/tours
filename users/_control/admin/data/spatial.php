<?php
class Cadmindataspatial extends Controller {
	private $error = array();
	private $tablename  = 'spatial';
    private $directory  = 'data/spatial';
	private $classmodel = "Madmindataspatial";
	
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
			$url .= isset($this->request->get['filter_name'])   ?'&filter_name=' . $this->request->get['filter_name']:'';
			$url .= isset($this->request->get['filter_address'])?'&filter_address=' . $this->request->get['filter_address']:'';
			$url .= isset($this->request->get['filter_filter']) ?'&filter_filter=' . $this->request->get['filter_filter']:'';
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
			if(isset($id_edit)){$tablename = $id_edit;}
			$this->$classmodel->$editfunction($this->request->get['gid'], $this->request->post);
			$this->session->data['success'] = $data['text_success'];
			$url  = '';
			$url .= isset($this->request->get['filter_name'])   ?'&filter_name=' . $this->request->get['filter_name']:'';
			$url .= isset($this->request->get['filter_address'])?'&filter_address=' . $this->request->get['filter_address']:'';
			$url .= isset($this->request->get['filter_filter']) ?'&filter_filter=' . $this->request->get['filter_filter']:'';
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
			$url .= isset($this->request->get['filter_name'])   ?'&filter_name=' . $this->request->get['filter_name']:'';
			$url .= isset($this->request->get['filter_address'])?'&filter_address=' . $this->request->get['filter_address']:'';
			$url .= isset($this->request->get['filter_filter']) ?'&filter_filter=' . $this->request->get['filter_filter']:'';
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
		if (!$this->Muser->hasPermission('modify', 'admin/data/spatial')) {
			$this->error['warning'] = $data['error_permission'];
		}
		return !$this->error;
	}
	
	protected function getList() {
		$id_edit = "gid";
		$functotal	= "getTotalspatials";
		$funcselect	= "getspatials";
		$fieldname  = array("gid", "image", "name", "address", "category", "date_added", "filter", "edit");
		
		
		$tablename  = $this->tablename;
		$directory  = $this->directory;
		$classmodel = $this->classmodel;

		$data = $this->bahasa->loadAll('admin/'.$directory);

		$this ->load->model('admin/'.$directory);

		$sign	= 'sign=' . $this->session->data['sign'];
		$sort  	= isset($this->request->get['sort'])  ? $this->request->get['sort']  : 'name';
		$order  = isset($this->request->get['order']) ? $this->request->get['order'] :  'ASC';
		$page   = isset($this->request->get['page'])  ? $this->request->get['page']  : 	    1;
		$data['filter_name']     = isset($this->request->get['filter_name'])  ? $this->request->get['filter_name']  : '';
		$data['filter_address']  = isset($this->request->get['filter_address'])  ? $this->request->get['filter_address']  : '';
		$data['filter_filter'] = isset($this->request->get['filter_category'])  ? $this->request->get['filter_filter']  : '';
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
		
		
		$data['refresh'] = $this->url->link('admin/'.$directory.'/'   , $sign. $url, 'SSL');
		$data['insert']  = $this->url->link('admin/'.$directory.'/add'   , $sign. $url, 'SSL');
		$data['delete']  = $this->url->link('admin/'.$directory.'/delete', $sign. $url, 'SSL');

		$filter 	= array(
			'filter_name'	      => $data['filter_name'],
			'filter_address'	  => $data['filter_address'],
			'filter_filter'	      => $data['filter_filter'],
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		
		$tablename_total = $this->$classmodel->$functotal();
		$results 	     = $this->$classmodel->$funcselect($filter);

		$this ->load->model('admin/tools/image');
		$data[$tablename.'s']  = array();
		foreach ($results as $result) {
			if (is_file(DI_IMAGE . $result['image'])) {
				$image = $this->Madmintoolsimage->resize($result['image'], 40, 40);
			} else {
				$image = '';
			}
			$arrend = array();
			foreach ($fieldname as $field){
				if($field == "status"){
					$arrfield = array(
						'status'     => ($result['status'] ? $data['text_enabled'] : $data['text_disabled'])
					);
				} else if($field == "image") {
					$arrfield = array($field => $image);
				} else if ($field == "edit") {
					if(isset($id_edit)){
						$tablename = $id_edit;
					}
					$arrfield = array(
						'edit'       => $this->url->link('admin/'.$directory.'/edit', $sign. '&'.$tablename.'=' . $result[$tablename.''] . $url, 'SSL')
					);
				} else {
					$arrfield = array($field => $result[$field]);
				}
				$arrend = array_merge($arrend,$arrfield);
				
			}
			$tablename =  $this->tablename;
			$data[$tablename.'s'][] = $arrend;
			
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
		$data['num_start']  = ($page - 1) * $limit;
		
		$data['pagination'] = $pagination->render();
		$data['results'] 	= sprintf($data['text_pagination'], ($tablename_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($tablename_total - $limit)) ? $tablename_total : ((($page - 1) * $limit) + $limit), $tablename_total, ceil($tablename_total / $limit));
		$data['sort'] 		= $sort;
		$data['order'] 		= $order;


		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['image']  = $this->request->server['HTTPS']? HTTPS_ASSETS."image/":HTTP_ASSETS."image/";
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view('admin/'.$directory.'_list', $data);
		$this->response->setOutput($output);
	}
	
	protected function getForm($data) {
		$id_edit = "gid";
		$classmodel = $this->classmodel;
		$funcselect	= "getspatial";
		$array_value = array(
			'gid'  => '', 'name'=>'', 'category_id'=>'',
			'address'=> '', 'description'=>'', 'image'=>'', 'thegeom'=>''
		);
		$data['error_name']	= isset($this->error['categoryname'])?$this->error['categoryname']:'';
		
				
		$tablename  = $this->tablename;
		$directory  = $this->directory;
		$classmodel = $this->classmodel;

		if(isset($id_edit)){$tablename  = $id_edit;}
		$tablename_info = '';
		if (isset($this->request->get[''.$tablename.'']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$spatial_general = $this->$classmodel->$funcselect($this->request->get[$tablename.'']);
			$tablename_info = $spatial_general;
			
		}
		
		$data = array_merge($data, $this->value->set($this->request->post,$tablename_info,$array_value));
		
		
		$tablename  = $this->tablename;
		
		$this->load->model('admin/object/category');
		$data['categorys'] = $this->Madminobjectcategory->getcategorys();

		if (isset($this->request->post['spatial_filter'])) {
			$filters = $this->request->post['spatial_filter'];
		} elseif (isset($this->request->get['gid'])) {
			$filters = $this->Madmindataspatial->getSpatialFilters($this->request->get['gid']);
		} else {
			$filters = array();
		}
		
		$this->load->model('admin/object/filter');
		$data['spatial_filters'] = array();
		foreach ($filters as $filter_id) {
			$filter_info = $this->Madminobjectfilter->getFilter($filter_id);
			
			if ($filter_info) {
				$filter_info = $filter_info[0];
				$data['spatial_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['filter_group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}
		
		if (isset($this->request->post['spatial_related'])) {
			$spatials = $this->request->post['spatial_related'];
		} elseif (isset($this->request->get['gid'])) {
			$spatials = $this->Madmindataspatial->getSpatialRelated($this->request->get['gid']);
		} else {
			$spatials = array();
		}
		
		$data['spatial_relateds'] = array();
		foreach ($spatials as $gid) {
			
			if ($gid) {
				$data['spatial_relateds'][] = array(
					'related_gid' 		=> $gid['related_gid'],
					'name'       => $gid['name']
				);
			}
		}
		
		if (isset($this->request->post['attribute'])) {
			$attributes = $this->request->post['attribute'];
		} elseif (isset($this->request->get['gid'])) {
			$attributes = $this->Madmindataspatial->getSpatialAttribute($this->request->get['gid']);
		} else {
			$attributes = array();
		}
		
		$data['attributes'] = array();
		foreach ($attributes as $gid) {
			
			if ($gid) {
				$data['attributes'][] = array(
					'id'	 => $gid['id'],
					'name'   => $gid['name'],
					'type'   => $gid['type'],
					'value'   => $gid['value'],
				);
			}
		}
		
		
		if (isset($this->request->post['spatial_special'])) {
			$spatial_specials = $this->request->post['spatial_special'];
		} elseif (isset($this->request->get['gid'])) {
			$spatial_specials = $this->Madmindataspatial->getspatialSpecials($this->request->get['gid']);
		} else {
			$spatial_specials = array();
		}

		$data['spatial_specials'] = array();

		foreach ($spatial_specials as $spatial_special) {
			$data['spatial_specials'][] = array(
				'priority'          => $spatial_special['priority'],
				'description'             => $spatial_special['description'],
				'price'             => $spatial_special['price'],
				'date_start'        => ($spatial_special['date_start'] != '0000-00-00') ? $spatial_special['date_start'] : '',
				'date_end'          => ($spatial_special['date_end'] != '0000-00-00') ? $spatial_special['date_end'] :  ''
			);
		}
		
		
		if (isset($this->request->post['spatial_comment'])) {
			$spatial_comments = $this->request->post['spatial_comment'];
		} elseif (isset($this->request->get['gid'])) {
			$spatial_comments = $this->Madmindataspatial->getspatialcomments($this->request->get['gid']);
		} else {
			$spatial_comments = array();
		}

		$data['spatial_comments'] = array();

		foreach ($spatial_comments as $spatial_comment) {
			$data['spatial_comments'][] = array(
				'user_id'   => $spatial_comment['user_id'],
				'username'   => $spatial_comment['username'],
				'comment'    => $spatial_comment['comment'],
				'status'     => $spatial_comment['status'],
				'time'       => $spatial_comment['time'],
				'date'       => ($spatial_comment['date_added'] != '0000-00-00') ? $spatial_comment['date_added'] : '',
			);
		}
		
		//echo json_encode($data);
		
		$sign   	= 'sign=' . $this->session->data['sign'];

		$data['text_form'] 			=!isset($this->request->get[$tablename]) ? $data['text_add'] : $data['text_edit'];
		$data['error_warning'] 		= isset($this->error['warning'])? $this->error['warning']:'';

		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
		$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
		$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']:'';

		if(isset($id_edit)){$tablename = $id_edit;}
		$data['action'] = !isset($this->request->get[$tablename])?
			$this->url->link('admin/'.$directory.'/add' , $sign. $url, 'SSL'):
			$this->url->link('admin/'.$directory.'/edit', $sign. '&'.$tablename.'=' . $this->request->get[''.$tablename.''] . $url, 'SSL');
		$tablename  = $this->tablename;
			
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
		
		// Images
		if (isset($this->request->post['spatial_image'])) {
			$spatial_images = $this->request->post['spatial_image'];
		} elseif (isset($this->request->get['gid'])) {
			$spatial_images = $this->Madmindataspatial->getSpatialImages($this->request->get['gid']);
		} else {
			$spatial_images = array();
		}

		$data['spatial_images'] = array();

		foreach ($spatial_images as $spatial_image) {
			if (is_file(DI_IMAGE . $spatial_image['image'])) {
				$image = $spatial_image['image'];
				$thumb = $spatial_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['spatial_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->Madmintoolsimage->resize($thumb, 100, 100),
				'name' => $spatial_image['name'],
				'description' => $spatial_image['description'],
				'sort_order' => $spatial_image['sort_order']
			);
		}
		
		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view('admin/'.$directory.'_form', $data);
		$this->response->setOutput($output);
	}
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('admin/data/spatial');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$spatials = $this->Madmindataspatial->getSpatials($filter_data);

			foreach ($spatials as $spatial) {
				$json[] = array(
					'gid' => $spatial['gid'],
					'name'      => strip_tags(html_entity_decode($spatial['name'], ENT_QUOTES, 'UTF-8'))
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
	
	public function migrasi() {
		$this->load->model('admin/data/spatial');
		$spatials = $this->Madmindataspatial->migrasi();
	}
}
