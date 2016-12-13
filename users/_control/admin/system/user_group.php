<?php 
class Cadminsystemusergroup extends Controller {
	private $error = array();
	
	public function index() {
		$data = $this->bahasa->loadAll('admin/system/user_group');
		$this-> document->setTitle($data['heading_title']);
		$this-> load->model('admin/system/user_group');
		$this-> getList($data);
	}
	
	public function add() {
		$data = $this->bahasa->loadAll('admin/system/user_group');
		$this-> document->setTitle($data['heading_title']);
		$this->load->model('admin/system/user_group');
		
		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			$this->Madminsystemusergroup->addUserGroup($this->request->post);
			$this->session->data['success'] = $data['text_success'];
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
			$url .= isset($this->request->get['page'])?'&page=' . $this->request->get['page']:'';
			if (isset($this->request->get['ajax_mode'])) {
				$this->getList($data);
			}
			$this->response->redirect($this->url->link('admin/system/user_group', 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		} 
		$this->getForm($data);
	}
	
	public function edit() {
		$data = $this->bahasa->loadAll('admin/system/user_group');
		$this ->document->setTitle($data['heading_title']);
		$this ->load->model('admin/system/user_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			$this->Madminsystemusergroup->editUserGroup($this->request->get['user_group_id'], $this->request->post);
			$this->session->data['success'] = $data['text_success'];
			$url = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
			$url .= isset($this->request->get['page'])?'&page=' . $this->request->get['page']:'';
			if (isset($this->request->get['ajax_mode'])) {
				$this->getList($data);
			}
			$this->response->redirect($this->url->link('admin/system/user_group', 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}
		$this->getForm($data);
	}
	
	public function delete() {
		$data = $this->bahasa->loadAll('admin/system/user_group');
		$this ->document->setTitle($data['heading_title']);
		$this->load->model('admin/system/user_group');

		if (isset($this->request->post['selected']) && $this->validateDelete($data)) {
			foreach ($this->request->post['selected'] as $user_group_id) {
				$this->Madminsystemusergroup->deleteUserGroup($user_group_id);
			}
			$this->session->data['success'] = $data['text_success'];
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
			$url .= isset($this->request->get['page'])?'&page=' . $this->request->get['page']:'';
			if (isset($this->request->get['ajax_mode'])) {
				$this->getList($data);
			}
			$this->response->redirect($this->url->link('admin/system/user_group', 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		} else {
			$data['error_checked'] = 'Pilih Item yang akan dihapus terlebih dahulu';
		}
		$this->getList($data);
	}
	
	protected function validateForm($data) {
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/system/user_group')||(isset($this->request->get['user_group_id'])&&$this->request->get['user_group_id']!=1)) {
			$this->error['warning'] = $data['error_permission'];
		}
		if ((strlen($this->request->post['name']) < 3) || (strlen($this->request->post['name']) > 20)) {
			$this->error['name'] = $data['error_name'];
		}
		
		return !$this->error;
	}
	
	protected function validateDelete($data) {
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/system/user_group')||$this->request->get['user_group_id']!=1) {
			$this->error['warning'] = $data['error_permission'];
		}
		$this->load->model('admin/system/user');
		foreach ($this->request->post['selected'] as $user_group_id) {
			$user_total = $this->Msystemuser->getTotalUsersByGroupId($user_group_id);
			if ($user_total) 
				$this->error['warning'] = sprintf($data['error_user'], $user_total);
		}
		return !$this->error;
	}
	
	protected function getList($data) {
		$sign	= 'sign=' . $this->session->data['sign'];
		$sort  	= isset($this->request->get['sort'])  ? $this->request->get['sort']: 'nama';
		$order  = isset($this->request->get['order']) ? $this->request->get['order'] :  'ASC';
		$page   = isset($this->request->get['page'])  ? $this->request->get['page']  : 	    1;
		$limit 	= $this->config->get('limit_list');
		$url 	= '';
		$url   .= isset($this->request->get['sort'])  ? '&sort='  . $this->request->get['sort']:'';
		$url   .= isset($this->request->get['order']) ? '&order=' . $this->request->get['order'] :'';
		$url   .= isset($this->request->get['page'])  ? '&page='  . $this->request->get['page']  :'';

		$breads = array(
			'home' 	 => 'admin/home/dashboard',
			'user group' => 'admin/system/user_group'
 		);
		foreach($breads as $key => $value) {	
			$data['breadcrumbs'][] = array(
				'text' => $key,
				'href' => $this->url->link($value, $sign, 'SSL')
			);
		}
		$data['insert'] = $this->url->link('admin/system/user_group/add'   , $sign.$url,'SSL');
		$data['delete'] = $this->url->link('admin/system/user_group/delete', $sign.$url,'SSL');
			
		$tablefilter = array(
			'sort'	=>  $sort,
			'order'	=>  $order,
			'start'	=> ($page - 1)*$limit,
			'limit' =>	$limit
		); 
		
		$ugTotal  = $this->Madminsystemusergroup->getTotalUserGroups();
		$results  = $this->Madminsystemusergroup->getUserGroups($tablefilter);
		
		$data['user_groups'] = array();
		foreach ($results as $result) {
			$data['user_groups'][] = array(
				'user_group_id'	=> $result['user_group_id'],
				'name'			=> $result['name'],
				'edit'			=> $this->url->link('admin/system/user_group/edit', $sign.'&user_group_id='.$result['user_group_id'],'SSL')
			);
 		}
		
		$data['error_warning'] = isset($this->error['warning'])? $this->error['warning']:'';
		$data['success'] 	   = isset($this->session->data['success'])? $this->session->data['success']:'';
		$data['selected'] 	   = isset($this->request->post['selected'])?(array)$this->request->post['selected']:array();
		if (isset($this->session->data['success']))	unset($this->session->data['success']);
		$url  = '';
		$url .= ($order == 'ASC')? '&order=DESC' : '&order=ASC';
		$url .= isset($this->request->get['page'])? '&page=' . $this->request->get['page']:'';
		
		$data['sort_name'] = $this->url->link('admin/system/user_group', $sign. '&sort=name'. $url, 'SSL');
		
		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort='  . $this->request->get['sort']:'';
		$url .= isset($this->request->get['order'])?'&order=' . $this->request->get['order']:'';
		
		$pagin			= new Pagination();
		$pagin->total 	= $ugTotal;
		$pagin->page  	= $page;
		$pagin->limit	= $limit;
		$pagin->url		= $this->url->link('admin/system/user_group', $sign. $url . '&page={page}', 'SSL');
		
		$data['pagination'] = $pagin->render();
		$data['results']= sprintf($data['text_pagination'], ($ugTotal) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($ugTotal - $limit)) ? $ugTotal : ((($page - 1) * $limit) + $limit), $ugTotal, ceil($ugTotal / $limit));
		$data['sort']  	= $sort;
		$data['order'] 	= $order;
		
		if (isset($this->request->get['ajax_mode'])) {
			$this->ajax('admin/system/user_group_list', $data);
		}
		
		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['menu']   = $this->load->view('admin/home/menu'  , $data);
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view('admin/system/user_group_list', $data);
		$this->response->setOutput($output);

	}	
	
	protected function getForm($data) {
		isset($this->error['warning'])? $data['error_warning'] = $this->error['warning']:$data['error_warning'] = '';
		isset($this->error['name'])   ? $data['error_name']    = $this->error['name']: $data['error_name'] = '';
		$data['text_form'] = !isset($this->request->get['user_group_id']) ? $data['text_add'] : $data['text_edit'];
		
		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']:'';
		$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']:'';
		$url .= isset($this->request->get['page'])?'&page=' . $this->request->get['page']:'';
		$sign   = 'sign=' . $this->session->data['sign'];
		
		$breads = array(
			'home' 	 => 'admin/home/dashboard',
			'user group' => 'admin/system/user_group',
			$data['text_form'] => ''
 		);
		foreach($breads as $key => $value) {	
			$data['breadcrumbs'][] = array(
				'text' => $key,
				'href' => $this->url->link($value, 'sign=' . $this->session->data['sign'], 'SSL')
			);
		}
		
		if (!isset($this->request->get['user_group_id'])) 
			$data['action'] = $this->url->link('admin/system/user_group/add', $sign. $url, 'SSL');
	    else 
			$data['action'] = $this->url->link('admin/system/user_group/edit',$sign. '&user_group_id=' . $this->request->get['user_group_id'] . $url, 'SSL');
		
		$data['cancel'] = $this->url->link('admin/system/user_group', $sign . $url, 'SSL');
		
		if (isset($this->request->get['user_group_id']) && $this->request->server['REQUEST_METHOD'] != 'POST') {
			$user_group_info = $this->Madminsystemusergroup->getUserGroup($this->request->get['user_group_id']);
		}
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($user_group_info)) {
			$data['name'] = $user_group_info['name'];
		} else {
			$data['name'] = '';
		}

		$ignore = array(
			'public/index',
			'public/forgotten',
			'public/footer',
			'public/menu',
			'puclic/header'
		);
		
		$data['permissions'] = array();

		$files = array_merge(glob(DI_USERS . '_control/*/*/*.php'),glob(DI_USERS . '_control/*/*.php'));
		$max_length_part = 0;
		
		foreach ($files as $file) {
			$part = explode('/', dirname($file));
			if(count($part)>$max_length_part){
				$max_length_part = count($part);
			}
			//echo $max_length_part;
			
			if(count($part) == $max_length_part){
				$permission = $part[$max_length_part-2] . '/' . $part[$max_length_part-1] . '/';
			}else if(count($part) < $max_length_part){
				$permission = $part[$max_length_part-2] . '/';
			}
			$permission .=  basename($file, '.php');

			if (!in_array($permission, $ignore)) {
				$data['permissions'][] = $permission;
			}
		}
		
		if (isset($this->request->post['permission']['access'])) {
			$data['access'] = $this->request->post['permission']['access'];
		} elseif (isset($user_group_info['permission']['access'])) {
			$data['access'] = $user_group_info['permission']['access'];
		} else {
			$data['access'] = array();
		}
		
		if (isset($this->request->post['permission']['modify'])) {
			$data['modify'] = $this->request->post['permission']['modify'];
		} elseif (isset($user_group_info['permission']['modify'])) {
			$data['modify'] = $user_group_info['permission']['modify'];
		} else {
			$data['modify'] = array();
		}
		
		if (isset($this->request->get['ajax_mode'])) {
			$this->ajax('admin/system/user_group_form', $data);
		}
		
		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER); 	
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['menu']   = $this->load->view('admin/home/menu'  , $data);
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view('admin/system/user_group_form', $data);
		$this->response->setOutput($output);
	}
	
	protected function ajax($view, $data){
		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['header'] = ''; 		
		$data['client'] = $this->request->server['HTTPS']? 	HTTPS_CLIENT :  HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['menu']   = '';
		$data['footer'] = '';
		$this->load->helper('preg');  
		$preg  			= new Preg();
		$this->response->setOutput($this->load->view($view, $data), $preg->preg, $preg->rplc);
		$this->response->output();
		exit();
	}
}