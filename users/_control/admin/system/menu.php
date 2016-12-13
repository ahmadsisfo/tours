<?php
class Cadminsystemmenu extends Controller {
	private $error = array();
	private $curfolder = 'users/';
	
	public function index() {
		//$this ->load->model('admin/system/menu');
		$data = $this->bahasa->loadAll('admin/system/menu');
		$this ->document->setTitle($data['heading_title']);
		$this ->getForm($data);
	}
	
	public function edit() {
		$data = $this->bahasa->loadAll('admin/system/menu');
		$this-> document->setTitle($data['heading_title']);
		//$this-> load->model('admin/system/menu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			$rplc = array(
				"&lt;" => "<", "&quot;"=>'"', "&gt;"=>">",  
				"&amp;lt;" => "<", "&amp;quot;"=>'"', "&amp;gt;"=>">",  
				"amp;lt;" => "<", "amp;quot;"=>'"', "amp;gt;"=>">",  
				"&amp;gt;"=>">", "%20"=>" ", "%27"=>"'"
			);
			$file = fopen(DI_ . $this->curfolder . $this->config->get('menus'), 'w');
			fwrite($file, $this->make->change($this->request->post['menus'],$rplc));
			fclose($file);
			
			$this->session->data['success'] = $data['text_success'];
			$this->response->redirect($this->url->link('admin/system/menu', 'sign=' . $this->session->data['sign'], 'SSL'));
		}
		$this->getForm($data);
	}
	
	protected function validateForm($data) {
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/system/menu')) 
			$this->error['warning'] = $data['error_permission'];
		
		return !$this->error;
	}
	
	protected function getForm($data) {
		$data['text_form'] 		=!isset($this->request->get['user_id']) ? $data['text_add'] : $data['text_edit'];
		$data['error_warning'] 	= isset($this->error['warning'])? $this->error['warning']:'';
		$data['success'] 	    = isset($this->session->data['success'])? $this->session->data['success']:'';
		
		$url  = '';

		$sign   = 'sign=' . $this->session->data['sign'];
		$breads = array(
			'home' 	   			=> 'admin/home/dashboard',
			'menu' 	   			=> 'admin/system/menu',
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

		$data['action'] = $this->url->link('admin/system/menu/edit', $sign, 'SSL');
		$data['scriptname'] = $this->config->get('menus');
		
		
		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		
		
		if (isset($this->request->post['menus'])) {
			$data['menus'] = $this->request->post['menus'];
		} else if($this->config->get('menus')!=""){
			$file = DI_ . $this->curfolder . $this->config->get('menus');
			$data['menus'] = file_get_contents($file);
		} else {
			$data['menus'] = '';
		}
		$rplc = array(
			"&lt;" => "<", "&quot;"=>'"', "&gt;"=>">",  
				"&amp;lt;" => "<", "&amp;quot;"=>'"', "&amp;gt;"=>">",  
				"&amp;gt;"=>">", "%20"=>" ","%27"=>"'",'</a>' => '</a> <a data-toggle="tooltip" class="btn btn-success"><i class="fa fa-plus"></i></a> <a data-toggle="tooltip-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>'
		);
		$data['menuseditor'] = $this->make->change($this->load->view('admin/home/menu', $data),$rplc);
		//exit($data['menus']);
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view('admin/system/menu_list', $data);
		$this->response->setOutput($output);
	}
	
	
}
