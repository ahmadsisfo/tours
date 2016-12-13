<?php
class Cadminforgotten extends Controller {
	private $error = array();
	
	public function index() {
		//if (!$this->request->server['HTTPS']) exit("404. Way of Access Not Allowed");
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if($this->sendMail()){
				$this->response->redirect($this->url->link('admin/login', '', 'SSL'));
			}
		}
	
		$url = new Url(HTTP_SERVER, HTTPS_SERVER); 
		$data['url_login']     	= $url->link('admin/login', '','SSL');
		$data['url_action']     = $url->link('admin/forgotten', '','SSL');
		$data['url_home']     	= $url->link('admin/login');
		
		$data['client'] 		= $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['url']    		= new Url(HTTP_SERVER, HTTPS_SERVER); 
		isset($this->error['warning'])?	$data['error_warning'] = $this->error['warning']: $data['error_warning'] = '';
		
		$data = array_merge($data,$this->bahasa->loadAll('default'));
		$this->response->setOutput($this->load->view('admin/forgotten', $data));
	}
	
	protected function validate() {
		$this->load->model('admin/user');
		
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->bahasa->get('p');
		} else if (!$this->Madminuser->getTotalUsersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->bahasa->get('p');
		}
		return !$this->error;
	}
	
	private function sendmail() {
		$this->bahasa->load('admin/forgotten');
		$this->load->model('admin/user');
		
		$code     = sha1(uniqid(mt_rand(), true));
		
		$this->Madminuser->editCode($this->request->post['email'], $code);
		
		$subject  = sprintf($this->bahasa->get('p'), 'Your APP');
		$message  = $this->bahasa->get('b');
		$message .= $this->url->link('home/reset', 'code='. $code, 'SSL');
		$message .= sprintf($this->bahasa->get('i'), $this->request->server['REMOTE_ADDR']);
		
		$mail  = new Mail();
		$mail -> setTo($this->request->post['email']);
		$mail -> setFrom('ahmadsisfo1@gmail.com');
		$mail -> setSender('Rahmat Nurfajri');
		$mail -> setSubject($subject);
		$mail -> setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail -> send();
		
		$this -> session->data['mail_success'] = $this->bahasa->get('e');
		return true;
	}
}

