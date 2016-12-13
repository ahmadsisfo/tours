<?php
class Cadmintoolsrestore extends Controller {
	private $error = array();
	
	public function index() {		
		
		$data['title'] 			= $this->document->getTitle();
		$data['client'] 		= HTTPS_CLIENT;
		$data['url']    		= new Url(HTTP_SERVER, HTTPS_SERVER); 		
		$data = array_merge($data,$this->bahasa->loadAll('default'));
		$data = array_merge($data,$this->bahasa->loadAll('admin/tools/restore'));
		
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
		$data['restore'] = $this->url->link('admin/tools/restore/restore'       , 'sign=' . $this->session->data['sign'], 'SSL');
		
		$breads = array(
			'home' 	  => 'admin/home/dashboard',
			'restore' => 'admin/tools/restore'
 		);
		foreach($breads as $key => $value) {	
			$data['breadcrumbs'][] = array(
				'text' => $key,
				'href' => $this->url->link($value, 'sign=' . $this->session->data['sign'], 'SSL')
			);
		}
		
		if (isset($this->request->get['ajax_mode'])) {
			$this->ajax('admin/tools/restore', $data);
		}
		
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['menu']   = $this->load->view('admin/home/menu'  , $data);
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view('admin/tools/restore', $data);
		$this->response->setOutput($output);
		
	}
		
	public function restore(){
		$data = $this->bahasa->loadAll('default');
		$data = array_merge($data,$this->bahasa->loadAll('admin/tools/restore'));
		$this->load->model('admin/tools/backup');
		$this->load->model('user');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->Muser->hasPermission('modify', 'admin/tools/restore')) {
			if (is_file($this->request->files['import']['tmp_name'])) {
				$content = file_get_contents($this->request->files['import']['tmp_name']);
			} else {
				$content = false;
			}
			
			if ($content) {
				$this->Madmintoolsbackup->restore($content);
				$this->session->data['success'] = $data['text_success'];
				if (isset($this->request->get['ajax_mode'])) {
					$this->index();
				}
				$this->response->redirect($this->url->link('admin/tools/restore', 'sign=' . $this->session->data['sign'], 'SSL'));
			} else {
				$this->error['warning'] = $data['error_empty'];
				//exit();
			}
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

