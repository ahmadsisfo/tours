<?php

class CStep3 extends Controller {
	private $error = array();
	
	private function validate() {
		if (!$this->request->post['db_hostname']) {
			$this->error['db_hostname'] = $this->bahasa->get('error_db_hostname');
		}

		if (!$this->request->post['db_username']) {
			$this->error['db_username'] = $this->bahasa->get('error_db_username');
		}
		
		if (!$this->request->post['db_database']) {
			$this->error['db_database'] = $this->bahasa->get('eror_db_database');
		}

		if ($this->request->post['db_prefix'] && preg_match('/[^a-z0-9_]/', $this->request->post['db_prefix'])) {
			$this->error['db_prefix'] = $this->bahasa->get('error_db_prefix');
		}

		/*if ($this->request->post['db_driver'] == 'mysqli') {
			$mysql = @new mysqli($this->request->post['db_hostname'], $this->request->post['db_username'], $this->request->post['db_password'], $this->request->post['db_database']);

			if ($mysql->connect_error) {
				$this->error['warning'] = $this->bahasa->get('error_db_connect');
			} else {
				$mysql->close();
			}
		}*/
		
		if (!$this->request->post['username']) {
			$this->error['username'] = $this->bahasa->get('error_username');
		}

		if (!$this->request->post['password']) {
			$this->error['password'] = $this->bahasa->get('error_password');
		}

		if ((strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->bahasa->get('error_email');
		}

		/*if (!is_writable(DI_USERS . 'config.php')) {
			$this->error['warning'] = $this->bahasa->get('error_config') . DI_USERS . 'confin.php!';
		}*/

		if (!is_writable(DI_APP . 'config.php')) {
			$this->error['warning'] = $this->bahasa->get('error_config') . DI_APP . 'config.php!';
		}
		
		/*if($error_database != null){
			$this->error['warning'] = $this->bahasa->get('error_config').$error_database;
		}*/
		
		//set_error_handler("customError");
		//if(set_error_handler("customError")!=null){
			
		//}
		
		return !$this->error;
	}
	
	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			if(!isset($this->request->post['db_driver'])){
				$modeling = $this->request->post['db_driver'].'install';
				$this->load->model($modeling);
				$class = "M".$modeling; 
				$this->$class->database($this->request->post);
			}
			
			$output  = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\'	,\'' . 	HTTP_RNF . '\');' . "\n";
			$output .= 'define(\'HTTP_CLIENT\'	,\'' . 	HTTP_RNF . 'assets/client/\');' . "\n";
			$output .= 'define(\'HTTP_ASSETS\'	,\'' . 	HTTP_RNF . 'assets/\');' . "\n\n";
			
			//exit($this->request->post['protocol']);
			
			if($this->request->post['protocol']=="http"){
			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\'	,\'' . 	HTTP_RNF . '\');' . "\n";
			$output .= 'define(\'HTTPS_CLIENT\'	,\'' . 	HTTP_RNF . 'assets/client/\');' . "\n";
			$output .= 'define(\'HTTPS_ASSETS\'	,\'' . 	HTTP_RNF . 'assets/\');' . "\n\n";
			} else {
			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\'	,\'' . 	HTTPS_RNF . '\');' . "\n";
			$output .= 'define(\'HTTPS_CLIENT\'	,\'' . 	HTTPS_RNF . 'assets/client/\');' . "\n";
			$output .= 'define(\'HTTPS_ASSETS\'	,\'' . 	HTTPS_RNF . 'assets/\');' . "\n\n";	
			}
			
			
			$output .= '// DIR' . "\n";
			$output .= 'define(\'DI_\'			,\'' . DI_ . '\');' . "\n";
			$output .= 'define(\'DI_SYSTEMS\'		,\'' . DI_ . 'systems/\');' . "\n";
			$output .= 'define(\'DI_USERS\'		,\'' . DI_ . 'users/\');' . "\n";
			$output .= 'define(\'DI_BAHASA\'		,\'' . DI_ . 'users/bahasa/\');' . "\n";
			$output .= 'define(\'DI_CLIENT\'		,\'' . DI_ . 'assets/client/\');' . "\n";
			$output .= 'define(\'DI_TPL\'		,\'' . DI_ . 'assets/template/\');' . "\n";
			$output .= 'define(\'DI_LOGS\'		,\'' . DI_ . 'assets/logs/\');' . "\n";
			$output .= 'define(\'DI_CACHE\'		,\'' . DI_ . 'assets/cache/\');' . "\n";
			$output .= 'define(\'DI_IMAGE\'		,\'' . DI_ . 'assets/image/\');' . "\n";
			$output .= 'define(\'DI_DOWNLOAD\'	,\'' . DI_ . 'assets/download/\');' . "\n";
			$output .= 'define(\'DI_UPLOAD\'		,\'' . DI_ . 'assets/upload/\');' . "\n";
			$output .= 'define(\'DI_MODIF\'		,\'' . DI_ . 'assets/modification/\');' . "\n\n";
			
			$output .= '// TEMPLATE TYPE' . "\n";
			$output .= 'define(\'TPL_TYPE\'		,\'' . 	addslashes($this->request->post['tpl_type']) . '\');' . "\n\n";
			$output .= 'define(\'SES_ID\'			,\'' . 	addslashes(substr(md5($this->request->post['db_database']),7,5)) . '\');' . "\n\n";
			
			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\'		,\'' . 	addslashes($this->request->post['db_driver']) . '\');' . "\n";
			$output .= 'define(\'DB_HOST\'		,\'' . 	addslashes($this->request->post['db_hostname']) . '\');' . "\n";
			$output .= 'define(\'DB_USER\'		,\'' . 	addslashes($this->request->post['db_username']) . '\');' . "\n";
			$output .= 'define(\'DB_PASS\'		,\'' . 	addslashes($this->request->post['db_password']) . '\');' . "\n";
			$output .= 'define(\'DB_DBASE\'		,\'' . 	addslashes($this->request->post['db_database']) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\'		,\'' . 	addslashes($this->request->post['db_prefix']) . '\');' . "\n";

			$file = fopen(DI_APP . 'config.php', 'w');

			fwrite($file, $output);

			fclose($file);
			
			
			
			$this->response->redirect($this->url->link('step_4'));
		}

		$this->document->setTitle($this->bahasa->get('heading_step_3'));
	
		$data['heading_step_3'] 		= $this->bahasa->get('heading_step_3');
		$data['heading_step_3_small'] 	= $this->bahasa->get('heading_step_3_small');
		$data['text_license'] 			= $this->bahasa->get('text_license');
		$data['text_installation'] 		= $this->bahasa->get('text_installation');
		$data['text_configuration'] 	= $this->bahasa->get('text_configuration');
		$data['text_finished'] 			= $this->bahasa->get('text_finished');
		$data['text_db_connection'] 	= $this->bahasa->get('text_db_connection');
		$data['text_tpl_type'] 			= $this->bahasa->get('text_tpl_type');
		$data['text_db_administration'] = $this->bahasa->get('text_db_administration');
		$data['text_mysqli'] 			= $this->bahasa->get('text_mysqli');
		$data['text_mysql'] 			= $this->bahasa->get('text_mysql');
		$data['text_mpdo'] 				= $this->bahasa->get('text_mpdo');
		$data['text_pgsql'] 			= $this->bahasa->get('text_pgsql');
		$data['text_mmsql'] 			= $this->bahasa->get('text_mmsql');
		$data['entry_db_driver'] 		= $this->bahasa->get('entry_db_driver');
		$data['entry_db_hostname'] 		= $this->bahasa->get('entry_db_hostname');
		$data['entry_db_username'] 		= $this->bahasa->get('entry_db_username');
		$data['entry_db_password'] 		= $this->bahasa->get('entry_db_password');
		$data['entry_db_database'] 		= $this->bahasa->get('entry_db_database');
		$data['entry_db_prefix'] 		= $this->bahasa->get('entry_db_prefix');
		$data['entry_tpl_type'] 		= $this->bahasa->get('entry_tpl_type');
		$data['entry_tpl_protocol'] 	= $this->bahasa->get('entry_tpl_protocol');
		$data['entry_username'] 		= $this->bahasa->get('entry_username');
		$data['entry_password'] 		= $this->bahasa->get('entry_password');
		$data['entry_email'] 			= $this->bahasa->get('entry_email');
		$data['button_continue'] 		= $this->bahasa->get('button_continue');
		$data['button_back'] 			= $this->bahasa->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] 		= $this->error['warning'];
		} else {
			$data['error_warning'] 		= '';
		}

		if (isset($this->error['db_hostname'])) {
			$data['error_db_hostname'] = $this->error['db_hostname'];
		} else {
			$data['error_db_hostname'] = '';
		}

		if (isset($this->error['db_username'])) {
			$data['error_db_username'] = $this->error['db_username'];
		} else {
			$data['error_db_username'] = '';
		}

		if (isset($this->error['db_database'])) {
			$data['error_db_database'] = $this->error['db_database'];
		} else {
			$data['error_db_database'] = '';
		}

		if (isset($this->error['db_prefix'])) {
			$data['error_db_prefix'] = $this->error['db_prefix'];
		} else {
			$data['error_db_prefix'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		$data['action'] = $this->url->link('step_3');

		if (isset($this->request->post['db_driver'])) {
			$data['db_driver'] = $this->request->post['db_driver'];
		} else {
			$data['db_driver'] = '';
		}

		if (isset($this->request->post['db_hostname'])) {
			$data['db_hostname'] = $this->request->post['db_hostname'];
		} else {
			$data['db_hostname'] = 'localhost';
		}

		if (isset($this->request->post['db_username'])) {
			$data['db_username'] = html_entity_decode($this->request->post['db_username']);
		} else {
			$data['db_username'] = '';
		}

		if (isset($this->request->post['db_password'])) {
			$data['db_password'] = html_entity_decode($this->request->post['db_password']);
		} else {
			$data['db_password'] = '';
		}

		if (isset($this->request->post['db_database'])) {
			$data['db_database'] = html_entity_decode($this->request->post['db_database']);
		} else {
			$data['db_database'] = '';
		}

		if (isset($this->request->post['db_prefix'])) {
			$data['db_prefix'] = html_entity_decode($this->request->post['db_prefix']);
		} else {
			$data['db_prefix'] = 'f_';
		}

		if (isset($this->request->post['tpl_type'])) {
			$data['tpl_type'] = $this->request->post['tpl_type'];
		} else {
			$data['tpl_type'] = '.html';
		}
		
		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} else {
			$data['username'] = 'admin';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		$data['mysqli'] = extension_loaded('mysqli');
		$data['mysql'] 	= extension_loaded('mysql');
		$data['pdo'] 	= extension_loaded('pdo');
		$data['pgsql'] 	= extension_loaded('pgsql');
		$data['mssql'] 	= extension_loaded('mssql');
		
		$data['back'] 	= $this->url->link('step_2');

		$data['footer'] = $this->load->control('footer');
		$data['header'] = $this->load->control('header');

		$this->response->setOutput($this->load->view('step_3.tpl', $data));
	}
}

function customError($errno, $errstr) {
    echo "<div style='background:#eee; padding:30px'><b>Error:</b> [$errno] $errstr</div>";
    exit();
}

set_error_handler("customError");
