<?php
class Cadminlogin extends Controller {
	private $error = array();
	
	public function index() {		
		//if (!$this->request->server['HTTPS']) exit("404. Way of Access Not Allowed");
		
		if (isset($this->request->get['ajax_mode'])) {$this->ajax();exit();}
		
		$this->load->model('user');
		
		if ($this->Muser->isLogged() && isset($this->request->get['sign']) && ($this->request->get['sign'] == $this->session->data['sign'])) {
			$this->response->redirect($this->url->link('admin/home/dashboard', 'sign=' . $this->session->data['sign'], 'SSL'));
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->session->data['sign'] = md5(mt_rand());
			
			if(isset($this->request->post['redirect'])) $redirect = $this->request->post['redirect']; 
			
			if (isset($redirect) && (strpos($redirect, HTTP_SERVER) === 0 || strpos($redirect, HTTPS_SERVER) === 0 )) 
				$url = $this->request->post['redirect'].   '&sign=' . $this->session->data['sign'];
			else 
				$url = $this->url->link('admin/home/dashboard', 'sign=' . $this->session->data['sign'], 'SSL');
			
			$this->response->redirect($url);
		}
		
		$url = new Url(HTTP_SERVER, HTTPS_SERVER); 
		$data['forgotten']     	= $url->link('admin/forgotten', '','SSL');
		$data['url_action']     = $url->link('admin/login', '','SSL');
		$data['url_home']     	= $url->link('admin/login');
		
		$data['client'] 		= $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['url']    		= new Url(HTTP_SERVER, HTTPS_SERVER); 
		
		$data = array_merge($data,$this->bahasa->loadAll('default'));
		isset($this->error['warning'])?	$data['error_login'] = $this->error['warning']: $data['error_login'] = '';
		
		$output = $this->load->view('admin/login', $data);
		
		$this->response->setOutput($output);
		
	}
	
	protected function validate() {
		$this->bahasa->load('admin/login');
		
		$login_confirmation = $this->Muser->login($this->request->post['username'], $this->request->post['password']);
		if (!isset($this->request->post['username']) || !isset($this->request->post['password']) || !$login_confirmation) {
			$this->error['warning'] = $this->bahasa->get('error_login');
		}
		return !$this->error;
	}
	
	public function ajax() {
		$this->load->model('user');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['sign'] = md5(mt_rand());	
			$out 				= array();
			
			$out['user_id']		= $this->session->data['user_id'];
			$out['username']	= $this->session->data['username'];
			$out['sign']		= $this->session->data['sign'];
			$out['permissions']	= $this->session->data['permissions'];
			$out['sign_status']	= true;
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($out));
			$this->response->output();
		} else {
			echo false;
		}
	}
	
	public function check() {
		$this->load->model('user');
		if ($this->Muser->isLogged() && isset($this->request->get['sign']) && ($this->request->get['sign'] == $this->session->data['sign'])) {
			echo true;
		} else {
			echo false;
		}
	}
}

