<?php
class Cadminhomereset extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('user');
		if ($this->Muser->isLogged() && isset($this->request->get['sign']) && ($this->request->get['token'] == $this->session->data['sign'])) {
			$this->response->redirect($this->url->link('home/dashboard', '', 'SSL'));
		}

		if (!$this->config->get('password_reset')) {
			$this->response->redirect($this->url->link('public/login', '', 'SSL'));
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->model('public/user');

		$user_info = $this->Mpublicuser->getUserByCode($code);

		if ($user_info) {
			$data = $this->bahasa->loadAll('home/reset');

			$this->document->setTitle($data['heading_title']);

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$this->Mpublicuser->editPassword($user_info['user_id'], $this->request->post['password']);

				$this->session->data['success'] = $this->language->get('text_success');

				$this->response->redirect($this->url->link('admin/login', '', 'SSL'));
			}


			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('common/reset', '', 'SSL')
			);

			if (isset($this->error['password'])) {
				$data['error_password'] = $this->error['password'];
			} else {
				$data['error_password'] = '';
			}

			if (isset($this->error['confirm'])) {
				$data['error_confirm'] = $this->error['confirm'];
			} else {
				$data['error_confirm'] = '';
			}

			$data['action'] = $this->url->link('home/reset', 'code=' . $code, 'SSL');

			$data['cancel'] = $this->url->link('public/login', '', 'SSL');

			if (isset($this->request->post['password'])) {
				$data['password'] = $this->request->post['password'];
			} else {
				$data['password'] = '';
			}

			if (isset($this->request->post['confirm'])) {
				$data['confirm'] = $this->request->post['confirm'];
			} else {
				$data['confirm'] = '';
			}

			$data['header'] = $this->load->controller('common/header');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('common/reset.tpl', $data));
		} else {
			$this->load->model('system/setting');

			$this->Msystemsetting->editSettingValue('config', 'config_password', '0');

			return new Action('common/login');
		}
	}
	
	protected function validate() {
		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}

		return !$this->error;
	}
}