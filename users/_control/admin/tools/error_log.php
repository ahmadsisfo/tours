<?php
class Cadmintoolserrorlog extends Controller {
	private $error = array();
	
	public function index() {		
		
		$this->document->setTitle('APP DISHUB');
		$data['title'] 			= $this->document->getTitle();
		$data['client'] 		= HTTPS_CLIENT;
		$data['url']    		= new Url(HTTP_SERVER, HTTPS_SERVER); 
		
		$data = array_merge($data,$this->bahasa->loadAll('default'));
		$data = array_merge($data,$this->bahasa->loadAll('admin/tools/error_log'));
		
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
		
		$data['clear'] = $this->url->link('admin/tools/error_log/clear', 'sign=' . $this->session->data['sign'], 'SSL');
		$data['log']   = '';
		$file = DI_LOGS . $this->config->get('error_log_file');

		if (file_exists($file)) {
			$size = filesize($file);
			if ($size >= 5242880) {
				$suffix = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
				$i = 0;
				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}
				$data['error_warning'] = sprintf($this->bahasa->get('error_warning'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
			} else {
				$data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
			}
		}
		
		$breads = array(
			'home' 		=> 'admin/home/dashboard',
			'error log' => 'admin/tools/error_log'
 		);
		foreach($breads as $key => $value) {	
			$data['breadcrumbs'][] = array(
				'text' => $key,
				'href' => $this->url->link($value, 'sign=' . $this->session->data['sign'], 'SSL')
			);
		}
		
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['client'] 	= $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['menu']   = $this->load->view('admin/home/menu'  , $data);
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		
		
		$output = $this->load->view('admin/tools/error_log', $data);
		if (isset($this->request->get['ajax_mode'])) {
			$this->ajax('admin/tools/error_log', $data);
		}
		//$this->make->script('_system/model/home/dashboard.js', $this->load->script('script/dashboard.js'));
		$this->response->setOutput($output);
		
	}
	
	public function clear() {
		$data = $this->bahasa->load('admin/tools/error_log');
		$this->load->model('user');
		if  (!$this->Muser->hasPermission('modify', 'admin/tools/error_log')) {
			$this->session->data['error']   = $this->bahasa->get('error_permission');
		} else {
			$file = DI_LOGS . $this->config->get('error_log_file');
			$handle = fopen($file, 'w+');
			fclose($handle);
			$this->session->data['success'] = $this->bahasa->get('text_success');
		}
		if (isset($this->request->get['ajax_mode'])) {
			$this->index();
		}
		$this->response->redirect($this->url->link('admin/tools/error_log', 'sign=' . $this->session->data['sign'], 'SSL'));
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

