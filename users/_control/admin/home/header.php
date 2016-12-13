<?php
class Cadminhomeheader extends Controller {
	
	public function index() {		
		$this->load->model('user');
		
		if (!$this->Muser->isLogged() || !isset($this->request->get['sign']) || ($this->request->get['sign'] != $this->session->data['sign'])) {
			$this->response->redirect($this->url->link('admin/login'));
		}
	
		$data['title'] 		= $this->document->getTitle();
		$data['description']= $this->document->getDescription();
		$data['keywords'] 	= $this->document->getKeywords();
		$data['links'] 		= $this->document->getLinks();
		$data['styles'] 	= $this->document->getStyles();
		$data['scripts'] 	= $this->document->getScripts();
		$data['lang'] 		= $this->bahasa->get('code');
		$data['direction'] 	= $this->bahasa->get('direction');
		
		
		if (!isset($this->request->get['sign']) || !isset($this->session->data['sign']) && ($this->request->get['sign'] != $this->session->data['sign'])) {
			$data['logged'] = '';

			$data['home'] = $this->url->link('admin/home/dashboard', '', 'SSL');
		} else {
			$data['logged'] = true;

			$data['home'] = $this->url->link('admin/home/dashboard', 'sign=' . $this->session->data['sign'], 'SSL');
			$data['logout'] = $this->url->link('admin/home/logout', 'sign=' . $this->session->data['sign'], 'SSL');
		}
		
		if ($this->request->server['HTTPS']) {
			$data['client'] = HTTPS_CLIENT;
			$data['assets'] = HTTPS_ASSETS;
		} else {
			$data['client'] = HTTP_CLIENT;
			$data['assets'] = HTTP_ASSETS;
		}
		
		$data['username']   = $this->session->data['username'];
		$data['usergroup']  = $this->session->data['usergroup'];
		$data['userimage']  = $data['assets'].'image/'.$this->session->data['userimage'];
		$data['client'] 	= $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['url']    	= new Url(HTTP_SERVER, HTTPS_SERVER); 
		$data['sign']		= 'sign=' . $this->session->data['sign'];
		$data['menu']		= $this->load->view('admin/home/menu', $data);
		
		$data = array_merge($data,$this->bahasa->loadAll('admin/home/header'));
		
		return $this->load->view('admin/home/header', $data);
	}
}

