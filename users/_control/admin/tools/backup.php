<?php
class Cadmintoolsbackup extends Controller {
	private $error = array();
	
	public function index() {		
	
		$data['title'] 			= $this->document->getTitle();
		$data['client'] 		= HTTPS_CLIENT;
		$data['url']    		= new Url(HTTP_SERVER, HTTPS_SERVER); 		
		$data = array_merge($data,$this->bahasa->loadAll('default'));
		$data = array_merge($data,$this->bahasa->loadAll('admin/tools/backup'));
		
		$this->load->model('admin/tools/backup');
		
		$data['tables'] = $this->Madmintoolsbackup->getTables();
		
		
		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['restore'] = $this->url->link('admin/tools/backup'       , 'sign=' . $this->session->data['sign'], 'SSL');
		$data['backup']  = $this->url->link('admin/tools/backup/backup', 'sign=' . $this->session->data['sign'], 'SSL');
	
		
		
		$breads = array(
			'home' 	 => 'admin/home/dashboard',
			'backup' => 'admin/tools/backup'
 		);
		foreach($breads as $key => $value) {	
			$data['breadcrumbs'][] = array(
				'text' => $key,
				'href' => $this->url->link($value, 'sign=' . $this->session->data['sign'], 'SSL')
			);
		}
		
		if (isset($this->request->get['ajax_mode'])) {
			$this->ajax('admin/tools/backup', $data);
		}
		
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['client'] 	= $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['menu']   = $this->load->view('admin/home/menu'  , $data);
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		//$this->make->script('_system/model/home/dashboard.js', $this->load->script('script/dashboard.js'));
		$output 		= $this->load->view('admin/tools/backup', $data);
		$this->response->setOutput($output);
		
	}
	
	public function backup() {
		$this->load->bahasa('admin/tools/backup');
		$this->load->model('user');
		if (!isset($this->request->post['backup'])) {
			$this->session->data['error'] = $this->bahasa->get('error_backup');
			if (isset($this->request->get['ajax_mode'])) {
				$this->index();
			}
			$this->response->redirect($this->url->link('admin/tools/backup', 'sign=' . $this->session->data['sign'], 'SSL'));
		} else if ($this->Muser->hasPermission('modify', 'admin/tools/backup')) {
			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . date('Y-m-d_H-i-s', time()) . '_backup.sql');
			$this->response->addheader('Content-Transfer-Encoding: binary');

			$this->load->model('admin/tools/backup');
			$this->response->setOutput($this->Madmintoolsbackup->backup($this->request->post['backup']));
		} else {
			$this->session->data['error'] = $this->bahasa->get('error_permission');
			if (isset($this->request->get['ajax_mode'])) {
				$this->index();
			}
			$this->response->redirect($this->url->link('admin/tools/backup', 'token=' . $this->session->data['token'], 'SSL'));
		}
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

