<?php
class Cadmintoolsfilemanager extends Controller { 
	public function index() {
		$this->load->model('user');
		
		if (!$this->Muser->isLogged() || !isset($this->request->get['sign']) || ($this->request->get['sign'] != $this->session->data['sign'])) {
			$this->response->redirect($this->url->link('public/index'));
		}
		
		$data 			= $this->bahasa->loadAll('admin/tools/filemanager');
		$filter_name	= isset($this->request->get['filter_name'])?
			rtrim(str_replace(array('../', '..\\', '..', '*'), '', $this->request->get['filter_name']), '/'):null;

		$directory 		= isset($this->request->get['directory'])?
			rtrim(DI_IMAGE.'manager/' . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/'):
			$directory = DI_IMAGE.'manager/';

		$page 			= isset($this->request->get['page'])? $this->request->get['page']: 1;
		$data['images'] = array();

		$this->load->model('admin/tools/image');

		$directories 	= glob($directory . '/' . $filter_name . '*', GLOB_ONLYDIR);

		if (!$directories) 	$directories = array();

		$files 			= glob($directory . '/' . $filter_name . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);

		if (!$files) 		$files = array();

		// Merge directories and files
		$images 		= array_merge($directories, $files);

		// Get total number of files and directories
		$image_total 	= count($images);

		// Split the array based on current page number and max number of items per page of 10
		$images 		= array_splice($images, ($page - 1) * 16, 16);

		foreach ($images as $image) {
			$name = str_split(basename($image), 14);

			if (is_dir($image)) {
				$url = '';

				if (isset($this->request->get['target'])) {
					$url .= '&target=' . $this->request->get['target'];
				}

				if (isset($this->request->get['thumb'])) {
					$url .= '&thumb=' . $this->request->get['thumb'];
				}

				$data['images'][] = array(
					'thumb' => '',
					'name'  => implode(' ', $name),
					'type'  => 'directory',
					'path'  => substr($image, strlen(DI_IMAGE)),
					'href'  => $this->url->link('admin/tools/filemanager', 'sign=' . $this->session->data['sign'] . '&directory=' . urlencode(substr($image, strlen(DI_IMAGE.'manager/' ))) . $url, 'SSL')
				);
			} elseif (is_file($image)) {
				if ($this->request->server['HTTPS']) {
					$server = HTTPS_ASSETS;
				} else {
					$server = HTTP_ASSETS;
				}

				$data['images'][] = array(
					'thumb' => $this->Madmintoolsimage->resize(substr($image, strlen(DI_IMAGE)), 100, 100),
					'name'  => implode(' ', $name),
					'type'  => 'image',
					'path'  => substr($image, strlen(DI_IMAGE)),
					'href'  => $server . 'image/' . substr($image, strlen(DI_IMAGE))
				);
			}
		}

		//exit(json_encode($data['images']));
		$data['sign'] 		= $this->session->data['sign'];

		$data['directory'] 	= isset($this->request->get['directory'])?			
			urlencode($this->request->get['directory']):'';

		$data['filter_name']= isset($this->request->get['filter_name'])?
			$this->request->get['filter_name']:'';

		$data['target'] 	= isset($this->request->get['target'])?
			$this->request->get['target']:'';

		$data['thumb'] 		= isset($this->request->get['thumb'])?
			$this->request->get['thumb']:'';

		$url  = '';

		if (isset($this->request->get['directory'])) {
			$pos = strrpos($this->request->get['directory'], '/');
			if ($pos) {
				$url .= '&directory=' . urlencode(substr($this->request->get['directory'], 0, $pos));
			}
		}
		$url .= isset($this->request->get['target'])?
			'&target=' . $this->request->get['target']:'';
		$url .= isset($this->request->get['thumb'])?
			'&thumb=' . $this->request->get['thumb']:'';

		$data['parent'] = $this->url->link('admin/tools/filemanager', 'sign=' . $this->session->data['sign'] . $url, 'SSL');

		$url  = '';
		$url .= isset($this->request->get['directory'])?
			'&directory=' . urlencode($this->request->get['directory']):'';
		$url .= isset($this->request->get['target'])?
			'&target=' . $this->request->get['target']:'';
		$url .= isset($this->request->get['thumb'])?
			'&thumb=' . $this->request->get['thumb']:'';

		$data['refresh'] = $this->url->link('admin/tools/filemanager', 'sign=' . $this->session->data['sign'] . $url, 'SSL');

		$url  = '';
		$url .= isset($this->request->get['directory'])?
			'&directory=' . urlencode(html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8')):'';
		$url .= isset($this->request->get['filter_name'])?
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8')):'';
		$url .= isset($this->request->get['target'])?
			$url .= '&target=' . $this->request->get['target']:'';
		$url .= isset($this->request->get['thumb'])?
			$url .= '&thumb=' . $this->request->get['thumb']:'';

		$pagination 		= new Pagination();
		$pagination->total 	= $image_total;
		$pagination->page 	= $page;
		$pagination->limit 	= 16;
		$pagination->url 	= $this->url->link('admin/tools/filemanager', 'sign=' . $this->session->data['sign'] . $url . '&page={page}', 'SSL');

		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['pagination'] = $pagination->render();
		//$this->make->script('filemanager.html', $this->load->view('admin/tools/filemanager', $data));
		
		$replace = '';
		if(isset($this->request->get['ajax_mode'])){
			$replace = array('https://' => 'http://', '?way='=>'?ajax_mode&way=');
		}
		$this->response->setOutput($this->load->view('admin/tools/filemanager', $data),'',$replace);
	}

	public function upload() {
		
		$data = $this->bahasa->loadAll('admin/tools/filemanager');
		$this->load->model('user');
		
		if (!$this->Muser->isLogged() || !isset($this->request->get['sign']) || ($this->request->get['sign'] != $this->session->data['sign'])) {
			$this->response->redirect($this->url->link('public/index'));
		}
		
		$json = array();

		if (!$this->Muser->hasPermission('modify', 'admin/tools/filemanager')) {
			$json['error'] = $data['error_permission'];
		}
		
		$directory = isset($this->request->get['directory'])?
			rtrim(DI_IMAGE.'manager/' . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/'):
			DI_IMAGE.'manager/';
	
		if (!is_dir($directory)) {
			$json['error'] = $data['error_directory'];
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

				if ((strlen($filename) < 3) || (strlen($filename) > 255)) {
					$json['error'] = $data['error_filename'];
				}

				// Allowed file extension types
				$allowed = array(
					'jpg',
					'jpeg',
					'gif',
					'png'
				);

				if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
					$json['error'] = $data['error_filetype'];
				}

				// Allowed file mime types
				$allowed = array(
					'image/jpeg',
					'image/pjpeg',
					'image/png',
					'image/x-png',
					'image/gif'
				);

				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $data['error_filetype'];
				}

				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents($this->request->files['file']['tmp_name']);

				if (preg_match('/\<\?php/i', $content)) {
					$json['error'] = $data['error_filetype'];
				}

				// Return any upload error
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $data['error_upload_' . $this->request->files['file']['error']];
				}
			} else {
				$json['error'] = $data['error_upload'];
			}
		}

		if (!$json) {
			move_uploaded_file($this->request->files['file']['tmp_name'], $directory . '/' . $filename);

			$json['success'] = $data['text_uploaded'];
			//$json['success'] = 
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function folder() {
		$data = $this->bahasa->loadAll('admin/tools/filemanager');
		$this->load->model('user');
		
		if (!$this->Muser->isLogged() || !isset($this->request->get['sign']) || ($this->request->get['sign'] != $this->session->data['sign'])) {
			$this->response->redirect($this->url->link('public/index'));
		}

		$json = array();

		// Check user has permission
		if (!$this->Muser->hasPermission('modify', 'admin/tools/filemanager')) {
			$json['error'] = $data['error_permission'];
		}

		// Make sure we have the correct directory
		//exit($this->request->server['REQUEST_URI']);
		if (isset($this->request->get['directory'])) {
			$directory = rtrim(DI_IMAGE.'manager/'. str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
		} else {
			$directory = DI_IMAGE.'manager/' ;
		}

		// Check its a directory
		if (!is_dir($directory)) {
			$json['error'] = $data['error_directory'];
		}

		if (!$json) {
			// Sanitize the folder name
			$folder = str_replace(array('../', '..\\', '..'), '', basename(html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8')));

			// Validate the filename length
			if ((strlen($folder) < 3) || (strlen($folder) > 128)) {
				$json['error'] = $data['error_folder'];
			}

			// Check if directory already exists or not
			if (is_dir($directory . '/' . $folder)) {
				$json['error'] = $data['error_exists'];
			}
		}

		if (!$json) {
			mkdir($directory . '/' . $folder, 0777);

			$json['success'] = $data['text_directory'];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$data = $this->bahasa->loadAll('admin/tools/filemanager');
		$this->load->model('user');
		
		if (!$this->Muser->isLogged() || !isset($this->request->get['sign']) || ($this->request->get['sign'] != $this->session->data['sign'])) {
			$this->response->redirect($this->url->link('public/index'));
		}

		$json = array();

		// Check user has permission
		if (!$this->Muser->hasPermission('modify', 'admin/tools/filemanager')) {
			$json['error'] = $data['error_permission'];
		}

		if (isset($this->request->post['path'])) {
			$paths = $this->request->post['path'];
		} else {
			$paths = array();
		}

		// Loop through each path to run validations
		foreach ($paths as $path) {
			$path = rtrim(DI_IMAGE . str_replace(array('../', '..\\', '..'), '', $path), '/');

			// Check path exsists
			if ($path == DI_IMAGE.'manager/') {
				$json['error'] = $data['error_delete'];

				break;
			}
		}

		if (!$json) {
			// Loop through each path
			foreach ($paths as $path) {
				$path = rtrim(DI_IMAGE . str_replace(array('../', '..\\', '..'), '', $path), '/');

				// If path is just a file delete it
				if (is_file($path)) {
					unlink($path);

				// If path is a directory beging deleting each file and sub folder
				} elseif (is_dir($path)) {
					$files = array();

					// Make path into an array
					$path = array($path . '*');

					// While the path array is still populated keep looping through
					while (count($path) != 0) {
						$next = array_shift($path);

						foreach (glob($next) as $file) {
							// If directory add to path array
							if (is_dir($file)) {
								$path[] = $file . '/*';
							}

							// Add the file to the files to be deleted array
							$files[] = $file;
						}
					}

					// Reverse sort the file array
					rsort($files);

					foreach ($files as $file) {
						// If file just delete
						if (is_file($file)) {
							unlink($file);

						// If directory use the remove directory function
						} elseif (is_dir($file)) {
							rmdir($file);
						}
					}
				}
			}

			$json['success'] = $data['text_delete'];
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}