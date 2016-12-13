<?php
class Cadminhomedashboard extends Controller {
	
	public function index() {		
		
		$this->load->bahasa('default');
		$this->getList();
		
	}
	
	protected function getList(){
		
		$data = $this->bahasa->loadAll('default');
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->bahasa->get('text_home'),
			'href' => $this->url->link('admin/home/dashboard', 'sign=' . $this->session->data['sign'], 'SSL')
		);
		
		$data['participants'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('limit_list'),
			'limit' => $this->config->get('limit_list')
		);

		$this->load->model('admin/home/dashboard');
		$participant_total = $this->Madminhomedashboard->getTotalParticipants();

		$results = $this->Madminhomedashboard->getParticipants($filter_data);

		foreach ($results as $result) {
			$data['users'][] = array(
				
				'user_id'  		=> $result['user_id'],
				'user_group_id' => $result['user_group_id'],
				'name'  		=> $result['firstname'].' '.$result['lastname'],
				'username'  	=> $result['username'],
				'email' 	    => $result['email'],
				'phone' 	    => $result['phone'],
				'ip' 	        => $result['ip'],
				'date_added' 	=> $result['date_added'],
				//'edit'      => $this->url->link('admin/home/dashboard/edit', 'sign=' . $this->session->data['sign'] . '&mail_id=' . $result['mail_receive_id'] . $url, 'SSL')
			);
		}

		//exit(json_encode($data['participants']));

		if (isset($this->error['warning'])) {
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_date_added'] = $this->url->link('admin/home/dashboard', 'sign=' . $this->session->data['sign'] . '&sort=date_added' . $url, 'SSL');
		$data['sort_name'] = $this->url->link('admin/home/dashboard', 'sign=' . $this->session->data['sign'] . '&sort=name' . $url, 'SSL');
		$data['sort_email'] = $this->url->link('admin/home/dashboard', 'sign=' . $this->session->data['sign'] . '&sort=email' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $participant_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('limit_list');
		$pagination->url = $this->url->link('admin/home/dashboard', 'sign=' . $this->session->data['sign'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->bahasa->get('text_pagination'), ($participant_total) ? (($page - 1) * $this->config->get('limit_list')) + 1 : 0, ((($page - 1) * $this->config->get('limit_list')) > ($participant_total - $this->config->get('limit_list'))) ? $participant_total : ((($page - 1) * $this->config->get('limit_list')) + $this->config->get('limit_list')), $participant_total, ceil($participant_total / $this->config->get('limit_list')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['delete'] = '';
		
		$data['client'] 	= $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['url']    	= new Url(HTTP_SERVER, HTTPS_SERVER); 
		$data['sign']		= 'sign=' . $this->session->data['sign'];
		$data['header'] 	= $this->load->control('admin/home/header', $data);
		$data['footer'] 	= $this->load->view('admin/home/footer', $data);

		$this->response->setOutput($this->load->view('admin/home/dashboard', $data));
		
	}
	
}



