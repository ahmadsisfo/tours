<?php
class CStep1 extends Controller {
	public function index() {
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->response->redirect($this->url->link('step_2'));
		}
		
		$this->document->setTitle($this->bahasa->get('heading_step_1'));

		$data['heading_step_1'] 		= $this->bahasa->get('heading_step_1');
		$data['heading_step_1_small'] 	= $this->bahasa->get('heading_step_1_small');
		$data['text_license'] 			= $this->bahasa->get('text_license');
		$data['text_installation'] 		= $this->bahasa->get('text_installation');
		$data['text_configuration'] 	= $this->bahasa->get('text_configuration');
		$data['text_finished'] 			= $this->bahasa->get('text_finished');
		$data['text_terms'] 			= $this->bahasa->get('text_terms');
		$data['button_continue'] 		= $this->bahasa->get('button_continue');

		$data['action'] = $this->url->link('step_1');

		$data['footer'] = $this->load->control('footer');
		$data['header'] = $this->load->control('header');

		$this->response->setOutput($this->load->view('step_1.tpl', $data));
	}
}