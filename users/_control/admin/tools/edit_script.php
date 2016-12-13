<?php
class Cadmintoolseditscript extends Controller {
	private $error = array();
	private $curfolder = "users/";
	
	public function index() {		
		
		$data['title'] 			= $this->document->getTitle();
		$data['client'] 		= HTTPS_CLIENT;
		$data['url']    		= new Url(HTTP_SERVER, HTTPS_SERVER); 
		
		$data = array_merge($data,$this->bahasa->loadAll('default'));
		$data = array_merge($data,$this->bahasa->loadAll('admin/tools/edit_script'));
		
		$this->getForm($data);
		
	}
	
	public function edit() {
		$data = $this->bahasa->loadAll('default');
		$data = array_merge($data,$this->bahasa->loadAll('admin/tools/edit_script'));
		

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			$rplc = array(
				"&lt;" => "<", "&quot;"=>'"', "&gt;"=>">", "&amp;lt;"=>"<", "&amp;gt;"=>"<"
			);
			$file = fopen(DI_ . $this->curfolder . $this->request->post['scriptname'], 'w');
			fwrite($file, $this->make->change($this->request->post['scripteditor'],$rplc));
			fclose($file);
			$this->session->data['success'] = $data['text_success'];
		}
		$this->getForm($data);
	}
	
	private function getForm($data){
		$data['header'] = $this->load->control('admin/home/header'); 		
		
		
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
		
		
		$this->load->model('admin/tools/image');
		//exit($this->request->post['image']);
		if (isset($this->request->post['image'])&&$this->request->post['image']!=null) {
			$exte = pathinfo($this->request->post['image'], PATHINFO_EXTENSION);
			$data['thumb'] = $this->Madmintoolsimage->resize("manager/icon/".$exte.".jpg", 100, 100);
		} else {
			$data['thumb'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);
		}
		
		if (isset($this->request->post['scriptname'])) {
			$data['scriptname'] = $this->request->post['scriptname'];
		} else {
			$data['scriptname'] = "";
		}
		
		if (isset($this->request->post['scripteditor'])) {
			$data['scripteditor'] = $this->request->post['scripteditor'];
		} else {
			$data['scripteditor'] = "";
		}
		$data['placeholder'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);
		$sign   = 'sign=' . $this->session->data['sign'];
		$data['action'] = $this->url->link('admin/tools/edit_script/edit', $sign, 'SSL');

		
		$breads = array(
			'home' 		=> 'admin/home/dashboard',
			'edit script' => 'admin/tools/edit_script'
 		);
		foreach($breads as $key => $value) {	
			$data['breadcrumbs'][] = array(
				'text' => $key,
				'href' => $this->url->link($value, 'sign=' . $this->session->data['sign'], 'SSL')
			);
		}
		
		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['client'] 	= $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['menu']   = $this->load->view('admin/home/menu'  , $data);
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		
		
		$output = $this->load->view('admin/tools/edit_script', $data);
		$this->response->setOutput($output);
	}
	
	protected function validateForm($data) {
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/tools/edit_script')) 
			$this->error['warning'] = $data['error_permission'];

		if ((strlen(trim($this->request->post['scriptname'])) < 3) || (strlen(trim($this->request->post['scriptname'])) > 32)) 
			$this->error['warning'] = $data['error_scriptnot'];
		

		return !$this->error;
	}
	
	public function getscript(){
		$data['log']   = '';
		$file = DI_ .$this->curfolder. $this->request->get['target'];

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
		echo $data['log'];
	}
	
}

