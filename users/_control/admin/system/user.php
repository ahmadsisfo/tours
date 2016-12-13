<?php
class Cadminsystemuser extends Controller {
	private $error = array();
	
	public function index() {
		$this ->load->model('admin/system/user');
		$data = $this->bahasa->loadAll('admin/system/user');
		$this ->document->setTitle($data['heading_title']);
		$this ->getList($data);
	}
	
	public function add() {
		$data = $this->bahasa->loadAll('admin/system/user');
		$this-> document->setTitle($data['heading_title']);
		$this-> load->model('admin/system/user');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			$this->Madminsystemuser->addUser($this->request->post);
			$this->session->data['success'] = $data['text_success'];
			$url  = '';
			$url .= isset($this->request->get['sort']) 	?'&sort=' . $this->request->get['sort']:'';
			$url .= isset($this->request->get['order'])	?'&order='. $this->request->get['order']:'';
			$url .= isset($this->request->get['page'])	?'&page=' . $this->request->get['page']:'';
			if (isset($this->request->get['ajax_mode'])) {
				$this->getList($data);
			}
			$this->response->redirect($this->url->link('admin/system/user', 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}
		$this->getForm($data);
	}
	
	public function edit() {
		$data = $this->bahasa->loadAll('admin/system/user');
		$this-> document->setTitle($data['heading_title']);
		$this-> load->model('admin/system/user');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			$this->Madminsystemuser->editUser($this->request->get['user_id'], $this->request->post);
			$this->session->data['success'] = $data['text_success'];
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
			$url .= isset($this->request->get['page'])?'&page=' . $this->request->get['page']:'';
			if (isset($this->request->get['ajax_mode'])) {
				$this->getList($data);
			}
			$this->response->redirect($this->url->link('admin/system/user', 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}
		$this->getForm($data);
	}
	
	public function delete() {
		$data = $this->bahasa->loadAll('admin/system/user');
		$this-> document->setTitle($data['heading_title']);
		$this-> load->model('admin/system/user');

		if (isset($this->request->post['selected']) && $this->validateDelete($data)) {
			foreach ($this->request->post['selected'] as $user_id) {
				$this->Madminsystemuser->deleteUser($user_id);
			}
			$this->session->data['success'] = $data['text_success'];
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
			$url .= isset($this->request->get['page'])?'&page=' . $this->request->get['page']:'';
			if (isset($this->request->get['ajax_mode'])) {
				$this->getList($data);
			}
			$this->response->redirect($this->url->link('admin/system/user', 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		} else {
			$data['error_checked'] = 'Pilih Item yang akan dihapus terlebih dahulu';
		}
		$this->getList($data);
	}
	
	protected function validateForm($data) {
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/system/user')) 
			$this->error['warning'] = $data['error_permission'];
		if ((strlen($this->request->post['username']) < 3) || (strlen($this->request->post['username']) > 20)) 
			$this->error['username'] = $data['error_username'];
		
		$user_info = $this->Madminsystemuser->getUserByUsername($this->request->post['username']);
		if (!isset($this->request->get['user_id'])) {
			if ($user_info) {
				$this->error['warning'] = $data['error_exists'];
			}
		} else {
			if ($user_info && ($this->request->get['user_id'] != $user_info['user_id'])) {
				$this->error['warning'] = $data['error_exists'];
			}
		}

		if ((strlen(trim($this->request->post['firstname'])) < 1) || (strlen(trim($this->request->post['firstname'])) > 32)) 
			$this->error['firstname'] = $data['error_firstname'];
		if ((strlen(trim($this->request->post['lastname'])) < 1) || (strlen(trim($this->request->post['lastname'])) > 32)) 
			$this->error['lastname'] = $data['error_lastname'];

		if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
			if ((strlen($this->request->post['password']) < 4) || (strlen($this->request->post['password']) > 20)) {
				$this->error['password'] = $data['error_password'];
			}
			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $data['error_confirm'];
			}
		}

		return !$this->error;
	}
	
	protected function validateDelete($data) {
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/system/user')) {
			$this->error['warning'] = $data['error_permission'];
		}

		foreach ($this->request->post['selected'] as $user_id) {
			if ($this->Muser->getId() == $user_id) {
				$this->error['warning'] = $data['error_account'];
			}
		}

		return !$this->error;
	}
	
	protected function getList($data) {
		$sign	= 'sign=' . $this->session->data['sign'];
		$sort  	= isset($this->request->get['sort'])  ? $this->request->get['sort']: 'nama';
		$order  = isset($this->request->get['order']) ? $this->request->get['order'] :  'ASC';
		$page   = isset($this->request->get['page'])  ? $this->request->get['page']  : 	    1;
		$limit 	= $this->config->get('limit_list');
		
		//=================== mempertahankan URL ================
		$url 	= '';
		$url   .= isset($this->request->get['sort'])  ? '&sort='  . $this->request->get['sort']:'';
		$url   .= isset($this->request->get['order']) ? '&order=' . $this->request->get['order'] :'';
		$url   .= isset($this->request->get['page'])  ? '&page='  . $this->request->get['page']  :'';

		$breads = array(
			'home' 	 => 'admin/home/dashboard',
			'user list' => 'admin/system/user'
 		);
		foreach($breads as $key => $value) {	
			$data['breadcrumbs'][] = array(
				'text' => $key,
				'href' => $this->url->link($value, $sign, 'SSL')
			);
		}
		$data['insert'] = $this->url->link('admin/system/user/add'   , $sign. $url, 'SSL');
		$data['delete'] = $this->url->link('admin/system/user/delete', $sign. $url, 'SSL');
		
		$filter 	= array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		
		$user_total = $this->Madminsystemuser->getTotalUsers();
		$results 	= $this->Madminsystemuser->getUsers($filter);
		
		$data['users']  = array();
		foreach ($results as $result) {
			$data['users'][] = array(
				'user_id'    => $result['user_id'],
				'username'   => $result['username'],
				'status'     => ($result['status'] ? $data['text_enabled'] : $data['text_disabled']),
				'date_added' => date($data['date_format_long'], strtotime($result['date_added'])),
				'edit'       => $this->url->link('admin/system/user/edit', $sign. '&user_id=' . $result['user_id'] . $url, 'SSL')
			);
		}
		
		$data['error_warning'] = isset($this->error['warning'])? $this->error['warning']:'';
		$data['success'] 	   = isset($this->session->data['success'])? $this->session->data['success']:'';
		$data['selected'] 	   = isset($this->request->post['selected'])?(array)$this->request->post['selected']:array();
		if (isset($this->session->data['success']))	unset($this->session->data['success']);
		//=================== header Table ==============
		
		$url  = '';
		$url .= ($order == 'ASC')? '&order=DESC' : '&order=ASC';
		$url .= isset($this->request->get['page'])? '&page=' . $this->request->get['page']:'';
		
		$data['sort_username'] 	= $this->url->link('admin/system/user', $sign. '&sort=username' 	. $url, 'SSL');
		$data['sort_status']   	= $this->url->link('admin/system/user', $sign. '&sort=status' 	. $url, 'SSL');
		$data['sort_date_added']= $this->url->link('admin/system/user', $sign. '&sort=date_added' . $url, 'SSL');
		
		//=================== pagination ================
		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort='  . $this->request->get['sort']:'';
		$url .= isset($this->request->get['order'])?'&order=' . $this->request->get['order']:'';
		
		$pagination 		= new Pagination();
		$pagination->total 	= $user_total;
		$pagination->page 	= $page;
		$pagination->limit 	= $limit;
		$pagination->url 	= $this->url->link('admin/system/user', $sign. $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] 	= sprintf($data['text_pagination'], ($user_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($user_total - $limit)) ? $user_total : ((($page - 1) * $limit) + $limit), $user_total, ceil($user_total / $limit));
		$data['sort'] 		= $sort;
		$data['order'] 		= $order;
		
		if (isset($this->request->get['ajax_mode'])) {
			$this->ajax('admin/system/user_list', $data);
		}
		
		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view('admin/system/user_list', $data);
		$this->response->setOutput($output);
	}
	
	protected function getForm($data) {
		$data['text_form'] 		=!isset($this->request->get['user_id']) ? $data['text_add'] : $data['text_edit'];
		$data['error_warning'] 	= isset($this->error['warning'])? $this->error['warning']:'';
		$data['error_username']	= isset($this->error['username'])?$this->error['username']:'';
		$data['error_password']	= isset($this->error['password'])?$this->error['password']:'';
		$data['error_confirm'] 	= isset($this->error['confirm'])? $this->error['confirm']:'';
		$data['error_firstname']= isset($this->error['firstname'])?$this->error['firstname']:'';
		$data['error_lastname'] = isset($this->error['lastname'])?$this->error['lastname']:'';
		
		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
		$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
		$url .= isset($this->request->get['page'])?'&page=' . $this->request->get['page']:'';
		
		$sign   = 'sign=' . $this->session->data['sign'];
		$breads = array(
			'home' 	   			=> 'admin/home/dashboard',
			'user' 	   			=> 'admin/system/user',
			$data['text_form']  => '',
			
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

		$data['action'] = !isset($this->request->get['user_id'])?
			$this->url->link('admin/system/user/add' , $sign. $url, 'SSL'):
			$this->url->link('admin/system/user/edit', $sign. '&user_id=' . $this->request->get['user_id'] . $url, 'SSL');

		$data['cancel'] = $this->url->link('admin/system/user', $sign. $url, 'SSL');
		
		$user_info = '';
		if (isset($this->request->get['user_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$user_info = $this->Madminsystemuser->getUser($this->request->get['user_id']);
		}

		$array_value = array(
			'username' 		=> '',
			'user_group_id'	=> '',
			'firstname'		=> '',
			'lastname'		=> '',
			'image'			=> '',
			'email'			=> '',
			'status'		=> 0,
		);
		$data = array_merge($data, $this->value->set($this->request->post,$user_info,$array_value));
		
		$this->load->model('admin/system/user_group');

		$data['user_groups'] = $this->Madminsystemusergroup->getUserGroups();
		$data['password']	 = isset($this->request->post['password'])? $this->request->post['password']:'';
		$data['confirm'] 	 = isset($this->request->post['confirm']) ? $this->request->post['confirm']:'';

		$this->load->model('admin/tools/image');

		if (isset($this->request->post['image']) && is_file(DI_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->Madmintoolsimage->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($user_info) && $user_info['image'] && is_file(DI_IMAGE . $user_info['image'])) {
			$data['thumb'] = $this->Madmintoolsimage->resize($user_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);
		}
		
		$data['placeholder'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);
		
		if (isset($this->request->get['ajax_mode'])) {
			$this->ajax('admin/system/user_form', $data);
		}
		
		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view('admin/system/user_form', $data);
		$this->response->setOutput($output);
	}
	
	protected function ajax($view, $data){
		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['client'] = $this->request->server['HTTPS']? 	HTTPS_CLIENT :  HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['header'] = ''; 		
		$data['menu']   = '';
		$data['footer'] = '';
		$this->load->helper('preg');  
		$preg  			= new Preg();
		$this->response->setOutput($this->load->view($view, $data), $preg->preg, $preg->rplc);
		$this->response->output();
		exit();
	}
}
