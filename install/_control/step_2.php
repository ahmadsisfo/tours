<?php
class CStep2 extends Controller {
	private $error = array();

	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->response->redirect($this->url->link('step_3'));
		}

		$this->document->setTitle($this->bahasa->get('heading_step_2'));

		$data['heading_step_2'] 		= $this->bahasa->get('heading_step_2');
		$data['heading_step_2_small'] 	= $this->bahasa->get('heading_step_2_small');

		$data['text_license'] 			= $this->bahasa->get('text_license');
		$data['text_installation'] 		= $this->bahasa->get('text_installation');
		$data['text_configuration'] 	= $this->bahasa->get('text_configuration');
		$data['text_finished'] 			= $this->bahasa->get('text_finished');
		$data['text_install_php'] 		= $this->bahasa->get('text_install_php');
		$data['text_install_extension'] = $this->bahasa->get('text_install_extension');
		$data['text_install_file'] 		= $this->bahasa->get('text_install_file');
		$data['text_install_directory'] = $this->bahasa->get('text_install_directory');
		$data['text_setting'] 			= $this->bahasa->get('text_setting');
		$data['text_current'] 			= $this->bahasa->get('text_current');
		$data['text_required'] 			= $this->bahasa->get('text_required');
		$data['text_extension'] 		= $this->bahasa->get('text_extension');
		$data['text_file'] 				= $this->bahasa->get('text_file');
		$data['text_directory'] 		= $this->bahasa->get('text_directory');
		$data['text_status'] 			= $this->bahasa->get('text_status');
		$data['text_on'] 				= $this->bahasa->get('text_on');
		$data['text_off'] 				= $this->bahasa->get('text_off');
		$data['text_missing'] 			= $this->bahasa->get('text_missing');
		$data['text_writable'] 			= $this->bahasa->get('text_writable');
		$data['text_unwritable'] 		= $this->bahasa->get('text_unwritable');
		$data['text_version'] 			= $this->bahasa->get('text_version');
		$data['text_global'] 			= $this->bahasa->get('text_global');
		$data['text_magic'] 			= $this->bahasa->get('text_magic');
		$data['text_file_upload'] 		= $this->bahasa->get('text_file_upload');
		$data['text_session'] 			= $this->bahasa->get('text_session');
		$data['text_global'] 			= $this->bahasa->get('text_global');
		$data['text_db'] 				= $this->bahasa->get('text_db');
		$data['text_mysqli'] 			= $this->bahasa->get('text_mysqli');
		$data['text_mysql'] 			= $this->bahasa->get('text_mysql');
		$data['text_mpdo'] 				= $this->bahasa->get('text_mpdo');
		$data['text_pgsql'] 			= $this->bahasa->get('text_pgsql');
		$data['text_gd'] 				= $this->bahasa->get('text_gd');
		$data['text_curl'] 				= $this->bahasa->get('text_curl');
		$data['text_mcrypt'] 			= $this->bahasa->get('text_mcrypt');
		$data['text_zlib'] 				= $this->bahasa->get('text_zlib');
		$data['text_zip'] 				= $this->bahasa->get('text_zip');
		$data['text_mbstring'] 			= $this->bahasa->get('text_mbstring');
		$data['button_continue'] 		= $this->bahasa->get('button_continue');
		$data['button_back'] 			= $this->bahasa->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('step_2');

		$data['php_version'] 		= phpversion();
		$data['register_globals'] 	= ini_get('register_globals');
		$data['magic_quotes_gpc'] 	= ini_get('magic_quotes_gpc');
		$data['file_uploads'] 		= ini_get('file_uploads');
		$data['session_auto_start'] = ini_get('session_auto_start');

		if (!array_filter(array('mysql', 'mysqli', 'pgsql', 'pdo'), 'extension_loaded')) {
			$data['db'] = false;
		} else {
			$data['db'] = true;
		}

		$data['gd'] 			= extension_loaded('gd');
		$data['curl'] 			= extension_loaded('curl');
		$data['mcrypt_encrypt'] = function_exists('mcrypt_encrypt');
		$data['zlib'] 			= extension_loaded('zlib');
		$data['zip'] 			= extension_loaded('zip');
		$data['iconv'] 			= function_exists('iconv');
		$data['mbstring'] 		= extension_loaded('mbstring');

		$data['config_catalog'] = DI_APP . 'config.php';
		$data['config_admin'] 	= DI_APP . 'admin/config.php';

		$data['cache'] 			= DI_ASSETS . 'cache';
		$data['logs'] 			= DI_ASSETS . 'logs';
		$data['download'] 		= DI_ASSETS . 'download';
		$data['upload'] 		= DI_ASSETS . 'upload';
		$data['image'] 			= DI_ASSETS . 'image';
		$data['image_cache']	= DI_ASSETS . 'image/cache';
		$data['image_data'] 	= DI_ASSETS . 'image/manager';

		$data['back'] = $this->url->link('step_1');

		$data['footer'] = $this->load->control('footer');
		$data['header'] = $this->load->control('header');

		$this->response->setOutput($this->load->view('step_2.tpl', $data));
	}

	private function validate() {
		if (phpversion() < '5.3') {
			$this->error['warning'] = $this->bahasa->get('warning_phpversion');
		}

		if (!ini_get('file_uploads')) {
			$this->error['warning'] = $this->bahasa->get('warning_fileuploads');
		}

		if (ini_get('session.auto_start')) {
			$this->error['warning'] = $this->bahasa->get('warning_autostart');
		}

		if (!array_filter(array('mysql', 'mysqli', 'pdo', 'pgsql'), 'extension_loaded')) {
			$this->error['warning'] = $this->bahasa->get('warning_mysql');
		}

		if (!extension_loaded('gd')) {
			$this->error['warning'] = $this->bahasa->get('warning_gd');
		}

		if (!extension_loaded('curl')) {
			$this->error['warning'] = $this->bahasa->get('warning_curl');
		}

		if (!function_exists('mcrypt_encrypt')) {
			$this->error['warning'] = $this->bahasa->get('warning_mcrypt');
		}

		if (!extension_loaded('zlib')) {
			$this->error['warning'] = $this->bahasa->get('warning_zlib');
		}

		if (!extension_loaded('zip')) {
			$this->error['warning'] = $this->bahasa->get('warning_zip');
		}

		if (!function_exists('iconv')) {
			if (!extension_loaded('mbstring')) {
				$this->error['warning'] = $this->bahasa->get('warning_iconv');
			}
		}

		if (!file_exists(DI_APP . 'config.php')) {
			$this->error['warning'] = $this->bahasa->get('warning_confineexist');
		} elseif (!is_writable(DI_APP . 'config.php')) {
			$this->error['warning'] = $this->bahasa->get('warning_confinewritable');
		}

		if (!is_writable(DI_ASSETS . 'cache')) {
			$this->error['warning'] = $this->bahasa->get('warning_cache');
		}

		if (!is_writable(DI_ASSETS . 'logs')) {
			$this->error['warning'] = $this->bahasa->get('warning_logs');
		}

		if (!is_writable(DI_ASSETS . 'download')) {
			$this->error['warning'] = $this->bahasa->get('warning_download');
		}
		
		if (!is_writable(DI_ASSETS . 'upload')) {
			$this->error['warning'] = $this->bahasa->get('warning_upload');
		}
		
		if (!is_writable(DI_ASSETS . 'image')) {
			$this->error['warning'] = $this->bahasa->get('warning_image');
		}
		/*
		if (!is_writable(DI_ASSETS . 'image/cache')) {
			$this->error['warning'] = $this->bahasa->get('warning_imagechace');
		}

		if (!is_writable(DI_ASSETS . 'image/catalog')) {
			$this->error['warning'] = $this->bahasa->get('warning_imagecatalog');
		}
		*/
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}