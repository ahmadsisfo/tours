<?php
class Cadminhomelogout extends Controller {
	public function index() {
		$this->load->model('user');
		$this->Muser->logout();
		unset($this->session->data['sign']);
		$this->response->redirect($this->url->link('admin/login', '', 'SSL'));
	}
}