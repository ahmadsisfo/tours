<?php
class CFooter extends Controller {
	public function index() {
		$data['text_project'] = $this->bahasa->get('text_project');
		$data['text_documentation'] = $this->bahasa->get('text_documentation');
		$data['text_support'] = $this->bahasa->get('text_support');
		$data['text_footer'] = $this->bahasa->get('text_footer');

		return $this->load->view('footer.tpl', $data);
	}
}
