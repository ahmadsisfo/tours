<?php
class CAdminSystemmailreceive extends Controller {
	private $error = array();

	public function index() {
		$this->load->bahasa('admin/system/mailreceive');

		$this->document->setTitle($this->bahasa->get('heading_title'));

		$this->load->model('admin/system/mailreceive');

		$this->getList();
	}

	public function add() {
		$this->load->bahasa('admin/system/mailreceive');

		$this->document->setTitle($this->bahasa->get('heading_title'));

		$this->load->model('admin/system/mailreceive');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->Madminsystemmailreceive->addLayout($this->request->post);

			$this->session->data['success'] = $this->bahasa->get('text_success');

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

			$this->response->redirect($this->url->link('admin/system/mailreceive', 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->bahasa('admin/system/mailreceive');

		$this->document->setTitle($this->bahasa->get('heading_title'));

		$this->load->model('admin/system/mailreceive');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->Madminsystemmailreceive->editLayout($this->request->get['layout_id'], $this->request->post);

			$this->session->data['success'] = $this->bahasa->get('text_success');

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

			$this->response->redirect($this->url->link('admin/system/mailreceive', 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->bahasa('admin/system/mailreceive');

		$this->document->setTitle($this->bahasa->get('heading_title'));

		$this->load->model('admin/system/mailreceive');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $layout_id) {
				$this->Madminsystemmailreceive->deleteLayout($layout_id);
			}

			$this->session->data['success'] = $this->bahasa->get('text_success');

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

			$this->response->redirect($this->url->link('admin/system/mailreceive', 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		$data = array_merge($this->bahasa->loadAll('default'),$this->bahasa->loadAll('admin/system/mailreceive'));
		
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

		$data['breadcrumbs'][] = array(
			'text' => $this->bahasa->get('heading_title'),
			'href' => $this->url->link('admin/system/mailreceive', 'sign=' . $this->session->data['sign'] . $url, 'SSL')
		);
		
		$data['insert'] = $this->url->link('admin/system/mailreceive/add', 'sign=' . $this->session->data['sign'] . $url, 'SSL');
		$data['delete'] = $this->url->link('admin/system/mailreceive/delete', 'sign=' . $this->session->data['sign'] . $url, 'SSL');

		$data['mails'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('limit_list'),
			'limit' => $this->config->get('limit_list')
		);

		$mail_total = $this->Madminsystemmailreceive->getTotalMailreceive();

		$results = $this->Madminsystemmailreceive->getMailreceives($filter_data);

		foreach ($results as $result) {
			$data['mails'][] = array(
				'mail_id'   => $result['mail_receive_id'],
				'namefrom'  => $result['namefrom'],
				'emailfrom' => $result['emailfrom'],
				'emailto' => $result['emailto'],
				'ip'     	=> $result['ip'],
				'date_added'=> $result['date_added'],
				'subject'   => $result['subject'],
				'message'   => $result['message'],
				'edit'      => $this->url->link('admin/system/mailreceive/edit', 'sign=' . $this->session->data['sign'] . '&mail_id=' . $result['mail_receive_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->bahasa->get('heading_title');
		
		$data['text_list'] = $this->bahasa->get('text_list');
		$data['text_no_results'] = $this->bahasa->get('text_no_results');
		$data['text_confirm'] = $this->bahasa->get('text_confirm');

		$data['column_name'] = $this->bahasa->get('column_name');
		$data['column_action'] = $this->bahasa->get('column_action');

		$data['button_insert'] = $this->bahasa->get('button_insert');
		$data['button_edit'] = $this->bahasa->get('button_edit');
		$data['button_delete'] = $this->bahasa->get('button_delete');

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

		$data['sort_date_added'] = $this->url->link('admin/system/mailreceive', 'sign=' . $this->session->data['sign'] . '&sort=date_added' . $url, 'SSL');
		$data['sort_namefrom'] = $this->url->link('admin/system/mailreceive', 'sign=' . $this->session->data['sign'] . '&sort=namefrom' . $url, 'SSL');
		$data['sort_emailfrom'] = $this->url->link('admin/system/mailreceive', 'sign=' . $this->session->data['sign'] . '&sort=emailfrom' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $mail_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('limit_list');
		$pagination->url = $this->url->link('admin/system/mailreceive', 'sign=' . $this->session->data['sign'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->bahasa->get('text_pagination'), ($mail_total) ? (($page - 1) * $this->config->get('limit_list')) + 1 : 0, ((($page - 1) * $this->config->get('limit_list')) > ($mail_total - $this->config->get('limit_list'))) ? $mail_total : ((($page - 1) * $this->config->get('limit_list')) + $this->config->get('limit_list')), $mail_total, ceil($mail_total / $this->config->get('limit_list')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['client'] 	= $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['url']    	= new Url(HTTP_SERVER, HTTPS_SERVER); 
		$data['sign']		= 'sign=' . $this->session->data['sign'];
		$data['header'] 	= $this->load->control('admin/home/header', $data);
		$data['column_left']= $this->load->view('admin/home/menu', $data);
		$data['footer'] 	= $this->load->view('admin/home/footer', $data);

		$this->response->setOutput($this->load->view('admin/system/mailreceive', $data));
	}

	protected function getForm() {
		$data = array_merge($this->bahasa->loadAll('default'),$this->bahasa->loadAll('admin/system/mailreceive'));
		
		$data['text_form'] = !isset($this->request->get['layout_id']) ? $this->bahasa->get('text_add') : $this->bahasa->get('text_edit');
		$data['text_default'] = $this->bahasa->get('text_default');
		$data['text_enabled'] = $this->bahasa->get('text_enabled');
		$data['text_disabled'] = $this->bahasa->get('text_disabled');
		$data['text_content_top'] = $this->bahasa->get('text_content_top');
		$data['text_content_bottom'] = $this->bahasa->get('text_content_bottom');
		$data['text_column_left'] = $this->bahasa->get('text_column_left');
		$data['text_column_right'] = $this->bahasa->get('text_column_right');

		$data['entry_name'] = $this->bahasa->get('entry_name');
		$data['entry_store'] = $this->bahasa->get('entry_store');
		$data['entry_route'] = $this->bahasa->get('entry_route');
		$data['entry_module'] = $this->bahasa->get('entry_module');
		$data['entry_position'] = $this->bahasa->get('entry_position');
		$data['entry_sort_order'] = $this->bahasa->get('entry_sort_order');

		$data['button_save'] = $this->bahasa->get('button_save');
		$data['button_cancel'] = $this->bahasa->get('button_cancel');
		$data['button_route_add'] = $this->bahasa->get('button_route_add');
		$data['button_module_add'] = $this->bahasa->get('button_module_add');
		$data['button_remove'] = $this->bahasa->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
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

		$data['breadcrumbs'][] = array(
			'text' => $this->bahasa->get('heading_title'),
			'href' => $this->url->link('admin/system/mailreceive', 'sign=' . $this->session->data['sign'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['layout_id'])) {
			$data['action'] = $this->url->link('admin/system/mailreceive/add', 'sign=' . $this->session->data['sign'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('admin/system/mailreceive/edit', 'sign=' . $this->session->data['sign'] . '&layout_id=' . $this->request->get['layout_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('admin/system/mailreceive', 'sign=' . $this->session->data['sign'] . $url, 'SSL');

		if (isset($this->request->get['layout_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$layout_info = $this->Madminsystemmailreceive->getLayout($this->request->get['layout_id']);
		}
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($layout_info)) {
			$data['name'] = $layout_info['name'];
		} else {
			$data['name'] = '';
		}

		//$this->load->model('setting/store');

		//$data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('admin/tools/image');
		
		if (isset($this->request->post['layout_content'])) {
			$data['layout_contents'] = $this->request->post['layout_content'];
		} elseif (isset($this->request->get['layout_id'])) {
			$data['layout_contents'] = $this->Madminsystemmailreceive->getLayoutContents($this->request->get['layout_id']);
			foreach($data['layout_contents'] as $content){
				$data['thumb'][] = $this->Madmintoolsimage->resize($content['image'], 100, 100);
			
			}
			//exit(json_encode($data['layout_contents']));
		} else {
			$data['layout_contents'] = array();
		}
		
		$data['no_image'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);
		
		$data['client'] 	= $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['url']    	= new Url(HTTP_SERVER, HTTPS_SERVER); 
		$data['sign']		= 'sign=' . $this->session->data['sign'];
		$data['header'] 	= $this->load->control('admin/home/header', $data);
		$data['column_left']= $this->load->view('admin/home/menu', $data);
		$data['footer'] 	= $this->load->view('admin/home/footer', $data);

		$this->response->setOutput($this->load->view('admin/system/mailreceive', $data));
	}

	protected function validateForm() {
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/system/mailreceive')) {
			$this->error['warning'] = $this->bahasa->get('error_permission');
		}

		if ((strlen($this->request->post['name']) < 3) || (strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->bahasa->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', 'admin/system/mailreceive')) {
			$this->error['warning'] = $this->bahasa->get('error_permission');
		}

		/*$this->load->model('setting/store');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');

		foreach ($this->request->post['selected'] as $layout_id) {
			if ($this->config->get('config_layout_id') == $layout_id) {
				$this->error['warning'] = $this->bahasa->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByLayoutId($layout_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->bahasa->get('error_store'), $store_total);
			}

			$product_total = $this->model_catalog_product->getTotalProductsByLayoutId($layout_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->bahasa->get('error_product'), $product_total);
			}

			$category_total = $this->model_catalog_category->getTotalCategoriesByLayoutId($layout_id);

			if ($category_total) {
				$this->error['warning'] = sprintf($this->bahasa->get('error_category'), $category_total);
			}

			$information_total = $this->model_catalog_information->getTotalInformationsByLayoutId($layout_id);

			if ($information_total) {
				$this->error['warning'] = sprintf($this->bahasa->get('error_information'), $information_total);
			}
		}*/

		return !$this->error;
	}
}