<?php
class Cadmintoolshandling extends Controller {
	private $error 	= array();
	private $table 	= 'handling';
    private $direc 	= 'admin/tools/handling';
	private $id		= 'id';
	private $fields	= array("category_id", "name", "attribute", "edit");
	
	public function index() {
		$this->document->setTitle($this->table." list");
		$this ->getList();
	}
	
	public function add() {
		$table	=  	$this->table;
		$direc	=  	$this->direc;
		$model	=	str_replace("/", "", $direc);
		$id		= 	$this->id;

		$data	= $this->bahasa->loadAll($direc);
		
		$this->document->setTitle("add ".$table);
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm($data)) {
			
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']	:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']	:'';
			$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']	:'';
			//$this->session->data['success'] = $data['text_success'];
			
			$primary_key		= '';
			$field 				= array();
			$fieldlist  		= array();
			$required	  		= array();
			$entry     			= '';
			$column     		= '';
			$listcolumn			= '';
			$listcontent		= '';
			$listfilter			= '';
			$listfilterjs		= '';
			$listfiltermodel	= '';
			$placeholder		= '';
			$form				= '';
			$prependform		= '';
			$tabstatus			= false;
			$tambahan			= '';
			$tambahansql		= '';
			$tambahanfungsiupdate	= '';
			$tambahanfungsiselect	= '';
			$insertfield 		= array();
			$insertvalues		= array();	
			$updatevalues		= array();	
			$table 				= $this->request->post['table'];
			$direc				= $this->request->post['direc'];
			$model				= str_replace("/","",str_replace("_","",$this->request->post['direc']));
			
			//exit(json_encode($this->request->post));
			if(isset($this->request->post['layout_content'])){
				$i=0;
				foreach($this->request->post['layout_content'] as $lay){
					if(isset($lay['primary'])){
						$primary_key = $lay['fieldname'];
						//exit($primary_key );
					} 
				}
				foreach($this->request->post['layout_content'] as $lay){
					if(isset($lay['tab'])){
						 $tab = str_replace(" ","_",$lay['tab']);
						 if(isset($lay['tabactive'])&&($lay['tabactive'])){
							 $form .= (!$prependform)?'<div class="tab-pane active" id="tab-'.$tab.'"><div class="tab-content">':'</div></div><div class="tab-pane active" id="tab-'.$tab.'"><div class="tab-content">';
							 $prependform .= '<li class="active"><a href="#tab-'.$tab.'" data-toggle="tab">'.$lay['tab'].'</a></li>';
						 } else {
							 $form .= (!$prependform)?'<div class="tab-pane " id="tab-'.$tab.'"><div class="tab-content">':'</div></div><div class="tab-pane " id="tab-'.$tab.'"><div class="tab-content">';
							 $prependform .= '<li><a href="#tab-'.$tab.'" data-toggle="tab">'.$lay['tab'].'</a></li>';
						 }
						 $tabstatus = true;
						 //exit($form);
					}
					if(isset( $lay['fieldname'])){
					$field[] = $lay['fieldname'];
					
					$requir = (isset($lay['required'])&&$lay['required'])?'required':'';
					$rplc = array(
						'[inputname]' => $lay['inputname'], '[fieldname]' => $lay['fieldname'], '[placeholder]' => $lay['placeholder'], '[required]'=>$requir, "[direc]"=> $direc,
					);
					$form 		.= $this->make->change(file_get_contents(DI_TPL.'form/'.$lay['inputtype'].'.html'),$rplc);
					
					$entry  	.= '$_[\'entry_'.$lay['inputname'].'\'] = \''.$lay['label'].'\';'.PHP_EOL;
					$column  	.= '$_[\'column_'.$lay['inputname'].'\'] = \''.$lay['label'].'\';'.PHP_EOL;
					$placeholder.= '$_[\'placeholder_'.$lay['inputname'].'\'] = \''.$lay['placeholder'].'\';'.PHP_EOL;
					
					if(isset($this->request->post['select'][$i])){
						if($lay['inputtype']=='select'){
							$this->request->post['select'][$lay['inputname']] = $this->request->post['select'][$i];
							$tambahan .= "private \$select_".$lay['fieldname']." = array('table'=>'".$this->request->post['select'][$i]['table']."','key'=>'".$this->request->post['select'][$i]['key']."','value'=>'".$this->request->post['select'][$i]['value']."');".PHP_EOL;
							
							$rplc = array(
								'[selecttable]' => $this->request->post['select'][$i]['table'], 
								'[selectfield]' => $this->request->post['select'][$i]['key'].','.$this->request->post['select'][$i]['value'], 
								'[selectarray]' => "\$data['".$this->request->post['select'][$i]['key']."']=>\$data['".$this->request->post['select'][$i]['value']."']",
							);
							$tambahansql .= $this->make->change(file_get_contents(DI_TPL.'sql/select.sql'),$rplc).PHP_EOL;
						} else {
							unset($this->request->post['select'][$i]);
						}
					}
					
					//exit(json_encode($this->request->post));
					if(isset($this->request->post['autocomplete'][$i])){
						
						if($lay['inputtype']=='autocomplete'){
							
							$this->request->post['autocomplete'][$lay['inputname']] = $this->request->post['autocomplete'][$i];
							$tambahan .= "private \$autocomplete_".$lay['fieldname']." = array('table'=>'".$this->request->post['autocomplete'][$i]['table']."','key'=>'".$this->request->post['autocomplete'][$i]['key']."','value'=>'".$this->request->post['autocomplete'][$i]['value']."');".PHP_EOL;
							
							$rplc = array(
								'[autocompletetable]' => $this->request->post['autocomplete'][$i]['table'],
								'[autocompletetabletarget]' => $this->request->post['autocomplete'][$i]['target'],
								'[autocompletefilter]'=> 'lower('.$this->request->post['autocomplete'][$i]['key'].') LIKE lower(\'%".$data[\'filter_name\']."%\')', 
								'[autocompletefield]' => $this->request->post['autocomplete'][$i]['key'].','.$this->request->post['autocomplete'][$i]['value'], 
								'[autocompletefieldtarget]' => $primary_key.','.$this->request->post['autocomplete'][$i]['value'], 
								'[autocompletearray]' => "\$data['".$this->request->post['autocomplete'][$i]['key']."']=>\$data['".$this->request->post['autocomplete'][$i]['value']."']",
								'[id]' 			=> $primary_key,
								'[inputname]' 	=> $lay['inputname'],
								'[fieldname]' 	=> $lay['fieldname'],
								'[key]'			=> $this->request->post['autocomplete'][$i]['key'],
								'[value]'			=> $this->request->post['autocomplete'][$i]['value'],
							);
							$tambahansql 			.= $this->make->change(file_get_contents(DI_TPL.'sql/autocomplete.sql'),$rplc).PHP_EOL .PHP_EOL;
							$tambahanfungsiupdate 	.= '$this->update'.$this->request->post['autocomplete'][$i]['target'].'($id,$data);'.PHP_EOL;
							$tambahanfungsiselect 	.= '$query->row = array_merge($query->row,$this->select'.$this->request->post['autocomplete'][$i]['target'].'($id));'.PHP_EOL;
						} else {
							unset($this->request->post['autocomplete'][$i]);
						}
						//continue;
					}
					
					if($lay['inputtype']=='mapseditor'){
						//exit($primary_key );
						$rplc = array(
							'[table]' => $this->request->post['table'], 
							'[id]' 	  => $primary_key,
							'[fieldname]' 	=> $lay['fieldname'],
							'[inputname]' 	=> $lay['inputname'],
						);
						$tambahansql .= $this->make->change(file_get_contents(DI_TPL.'sql/mapseditor.sql'),$rplc).PHP_EOL;
						$tambahanfungsiupdate 	.= '$this->updategeom'.$this->request->post['table'].'($id,$data);'.PHP_EOL;
						$tambahanfungsiselect 	.= '$query->row = array_merge($query->row,$this->selectgeom'.$this->request->post['table'].'($id));'.PHP_EOL .PHP_EOL;
					} 
					
					$ignore = array('autocomplete','mapseditor');
					if(!isset($lay['primary'])&&!in_array($lay['inputtype'],$ignore)) {
						
						$insertfield[] 		= $lay['fieldname'];
						$insertvalues[]  	= '".$this->db->escape($data[\''.$lay['inputname'].'\'])."';
						$updatevalues[]  	= $lay['fieldname'] .'=\'".$this->db->escape($data[\''.$lay['inputname'].'\'])."\'';
						
						if($lay['inputtype']=="image"){
							$insertfield[] 		= 'image_thumb';
							$insertvalues[]  	= '".$this->db->escape($data[\'image_thumb\'])."';
							$updatevalues[]  	= 'image_thumb=\'".$this->db->escape($data[\'image_thumb\'])."\'';
						}
					}
					
					if(isset($lay['required'])&&$lay['required']){
						$required[] = $lay['inputname'];
					}
					
					if(isset($lay['listview'])&&$lay['listview']){
						
						$fieldlist[] = $lay['fieldname'];
						$listcolumn .= '<td class="text-left"><?php if ($sort == \''.$lay['fieldname'].'\') { ?>
							<a href="<?php echo $sort_'.$lay['fieldname'].'; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_'.$lay['inputname'].'; ?></a>
							<?php } else { ?>
							<a href="<?php echo $sort_'.$lay['fieldname'].'; ?>"><?php echo $column_'.$lay['inputname'].'; ?></a>
							<?php } ?></td>'.PHP_EOL;
						if($lay['inputtype']=="image"){
							$listcontent .= '<td class="text-center"><?php if ($item[\''.$lay['fieldname'].'\']) { ?>
									<img src="<?php echo$item[\''.$lay['fieldname'].'\']; ?>" alt="<?php echo $item[\''.$lay['fieldname'].'\']; ?>" class="img-thumbnail" />
									<?php } else { ?><span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span><?php } ?></td>'.PHP_EOL;
						}else{
							$listcontent .= '<td class="text-left"><?php echo $item[\''.$lay['fieldname'].'\']; ?></td>'.PHP_EOL;
						}
						$listfilterjs	 .= 'var filter_'.$lay['inputname'].' = $(\'input[name="filter_'.$lay['fieldname'].'"]\').val();
							if (filter_'.$lay['inputname'].') { url += \'&filter_'.$lay['fieldname'].'=\' + encodeURIComponent(filter_'.$lay['inputname'].');}'.PHP_EOL;
						$listfiltermodel .= 'if (!empty($data[\'filter_'.$lay['inputname'].'\'])) {
								$filter .= " AND upper('.$lay['fieldname'].') LIKE upper(\'%" . $this->db->escape($data[\'filter_'.$lay['inputname'].'\']) . "%\')";
							}';		
						$listfilter	 .= '<div class="col-sm-3"><div class="form-group">
							<label class="control-label" for="input-'.$lay['fieldname'].'"><?php echo $entry_'.$lay['inputname'].'; ?></label>
							<input type="text" name="filter_'.$lay['fieldname'].'" value="<?php echo $filter_'.$lay['fieldname'].'; ?>" placeholder="<?php echo $entry_'.$lay['inputname'].'; ?>" id="input-'.$lay['fieldname'].'" class="form-control" />
						 </div></div>'.PHP_EOL;
					}$i++;
				}}
				if($tabstatus){
					$form = '<ul class="nav nav-tabs">'.$prependform.'</ul><div class="tab-content">'.$form.'</div>';
				}
			}
			$editable = (isset($this->request->post['to_edit'])&& $this->request->post['to_edit'])? true:0;
			
			$rplc = array(
				"[table]" 	=> $table, 
				"[direc]"	=> $direc,
				"[model]"	=> $model,
				"[field]"	=> "array('".str_replace(",","','",implode(",",$field))."')",
				"[required]"=> "array('".str_replace(",","','",implode(",",$required))."')",
				"[fieldlist]" => "array('".str_replace(",","','",implode(",",$fieldlist))."')",
				"[id]" 		=> $primary_key,
				"[editable]"=> $editable,
				"[tambahan]"=> $tambahan,
			);
			$this->make->script(DI_USERS .'_control/'. $this->request->post['direc'].'.php',file_get_contents(DI_TPL.'_control/'.$this->request->post['template'].'.php'),$rplc);
			
			$rplc = array(
				"[table]" 			=> $table, 
				"[direc]"			=> $direc,
				"[model]"			=> $model,
				"[post]"			=> json_encode($this->request->post),
				"[fieldlist]" 		=> "array('".str_replace(",","','",implode(",",$fieldlist))."')",
				"[listfiltermodel]"	=> $listfiltermodel,
				"[insertfield]"		=> implode(",",$insertfield),
				"[insertvalues]"	=> "'".str_replace(",","','",implode(",",$insertvalues))."'",
				"[updatevalues]"	=> implode(",",$updatevalues),
				"[id]" 				=> $primary_key,
				"[tambahansql]" 	=> $tambahansql,
				"[tambahanfungsiupdate]" 	=> $tambahanfungsiupdate,
				"[tambahanfungsiselect]" 	=> $tambahanfungsiselect,
			);
			$this->make->script(DI_USERS .'_model/'. $this->request->post['direc'].'.php',file_get_contents(DI_TPL.'_model/'.$this->request->post['template'].'.php'),$rplc);
			
			$rplc = array(
				"[table]" 		=> str_replace("_"," ",$table),
				"[entry]"   	=> $entry,
				"[column]"   	=> $column,
				"[placeholder]"	=> $placeholder,
			);
			$this->make->script(DI_USERS .'bahasa/'.$this->config->get('bahasa').'/'. $this->request->post['direc'].'.php',file_get_contents(DI_TPL.'bahasa/'.$this->request->post['template'].'.php'),$rplc);
			
			
			$rplc = array(
				"[table]" 	=> $table,
				"[id]" 		=> $primary_key,
				"[form]" 	=> $form
			);
			$this->make->script(DI_USERS .'_view/'.$this->request->post['direc'].'_form'.TPL_TYPE, file_get_contents(DI_TPL.'_view/'.$this->request->post['template'].'_form.html'),$rplc);
			
			$rplc = array(
				"[table]" 		=> $table,
				"[listcolumn]"	=> $listcolumn,
				"[listcontent]" => $listcontent,
				"[listfilter]" 	=> $listfilter,
				"[listfilterjs]"=> $listfilterjs,
				"[id]" 			=> $primary_key,
				"[direc]" 		=> $direc
			);
			$this->make->script(DI_USERS .'_view/'.$this->request->post['direc'].'_list'.TPL_TYPE,file_get_contents(DI_TPL.'_view/'.$this->request->post['template'].'_list.html'),$rplc);
			
			if(isset($this->request->post['to_database'])&&($this->request->post['to_database'])){
				$table	=  	$this->table;
				$direc	=  	$this->direc;
				$model	=	"M".str_replace("/", "", $direc);
				$this->load->model($direc);
				$funcadd = 'add'.$table;
				$this->$model->$funcadd($this->request->post);
			}
			
			$this->cache->set($model,$this->request->post,true);
			
			$this->session->data['success'] = $data['text_success'];
			//$this->response->redirect($this->url->link($direc.'/add', 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		}
		$this->getForm($data);
	}
	
	public function edit() {
		$this->add();
	}
	
	public function delete() {
		$table	=  	$this->table;
		$direc	=  	$this->direc;
		$model	=	str_replace("/", "", $direc);
		$id		= 	$this->id;

		$data = $this->bahasa->loadAll($direc);
		
		if (isset($this->request->post['selected']) && $this->validateDelete($data)) {
			
			foreach ($this->request->post['selected'] as $lay) {
				
				$location = array('_control/'=>'.php','_model/'=>'.php','_view/'=>'_list.html','_view/'=>'_form.html','bahasa/'.$this->config->get('bahasa'.'/')=>'.php');
				foreach ($location as $key => $value){
					$path = DI_USERS.$key.$lay.$value;  
					if (is_file($path)) {
						unlink($path);
					}
				}
			}
			$url  = '';
			$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']	:'';
			$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']	:'';
			$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']	:'';
			$this->session->data['success'] = $data['text_success'];			
			$this->response->redirect($this->url->link($direc, 'sign=' . $this->session->data['sign'] . $url, 'SSL'));
		} else {
			$data['error_checked'] = 'Pilih Item yang akan dihapus terlebih dahulu';
		}
		$this->getList($data);
	}
	
	protected function validateForm($data) {
		
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', $this->direc)) 
			$this->error['warning'] = $data['error_permission'];
		if ((strlen($this->request->post['table']) < 3) || (strlen($this->request->post['table']) > 128)) 
			$this->error['name'] = $data['error_name'];
		
		return !$this->error;
	}
	
	protected function validateDelete($data) {
		$this->load->model('user');
		if (!$this->Muser->hasPermission('modify', $this->direc)) {
			$this->error['warning'] = $data['error_permission'];
		}
		return !$this->error;
	}
	
	protected function getList() {
		
		$table	=  	$this->table;
		$direc	=  	$this->direc;
		$model	=	str_replace("/", "", $direc);
		$fields	=  	$this->fields;
		$id		= 	$this->id;
		
		$functotal	= "gettotal".$table."s";
		$funcselect	= "get".$table."s";
		
		$data 	= 	$this->bahasa->loadAll($direc);

		//$this->load->model($direc);

		$sign	= 'sign=' . $this->session->data['sign'];
		
		$sort  	= isset($this->request->get['sort'])  ? $this->request->get['sort']  : 'name';
		$order  = isset($this->request->get['order']) ? $this->request->get['order'] :  'ASC';
		$page   = isset($this->request->get['page'])  ? $this->request->get['page']  : 	    1;
		$limit 	= $this->config->get('limit_list');
		
		//=================== mempertahankan URL ================
		$url 	= '';
		$url   .= isset($this->request->get['sort'])  ? '&sort='  . $this->request->get['sort']  :'';
		$url   .= isset($this->request->get['order']) ? '&order=' . $this->request->get['order'] :'';
		$url   .= isset($this->request->get['page'])  ? '&page='  . $this->request->get['page']  :'';

		$breads = array(
			'home' 	 => 'admin/home/dashboard',
			$table.' list' => $direc
		);
		
		foreach($breads as $key => $value) {	
			$data['breadcrumbs'][] = array(
				'text' => $key,
				'href' => $this->url->link($value, $sign, 'SSL')
			);
		}
		
		$data['insert'] = $this->url->link($direc.'/add'   , $sign. $url, 'SSL');
		$data['delete'] = $this->url->link($direc.'/delete', $sign. $url, 'SSL');

		$filter 	= array(
			'sort'  =>  $sort,
			'order' =>  $order,
			'start' => ($page - 1) * $limit,
			'limit' =>  $limit
		);

		//$total		= $this->$model->$functotal();
		//$results	= $this->$model->$funcselect($filter);

		$data[$table.'s']  = array();
		/*foreach ($results as $result) {
			$arrend = array();
			foreach ($fields as $field){
				if($field == "status"){
					$arrfield = array(
						'status'     => ($result['status'] ? $data['text_enabled'] : $data['text_disabled'])
					);
				} else if ($field == "edit") {
					$arrfield  = array(
						'edit' => $this->url->link($direc.'/edit', $sign. '&'.$id.'=' . $result[$id] . $url, 'SSL')
					);
				} else {
					$arrfield = array($field => $result[$field]);
				}
				$arrend = array_merge($arrend,$arrfield);
			}
			$data[$table.'s'][] = $arrend;
		}*/
		
		$ignore = array(
			'public/index',
			'public/forgotten',
			'admin/home/dashboard',
			'admin/system/user',
			'admin/system/user_group',
			'admin/system/setting',
			'admin/system/menu',
			'admin/tools/handling',
			'admin/tools/error_log',
			'admin/tools/edit_script',
			'admin/tools/filemanager',
			'admin/tools/fileexplorer',
			'admin/login'
		);
		
		$data['layouts'] = array();

		$files = array_merge(glob(DI_USERS . '_control/*/*/*.php'),glob(DI_USERS . '_control/*/*.php'));
		$max_length_part = 0;
		
		foreach ($files as $file) {
			$part = explode('/', dirname($file));
			if(count($part)>$max_length_part){
				$max_length_part = count($part);
			}
			//echo $max_length_part;
			
			if(count($part) == $max_length_part){
				$permission = $part[$max_length_part-2] . '/' . $part[$max_length_part-1] . '/';
			}else if(count($part) < $max_length_part){
				$permission = $part[$max_length_part-2] . '/';
			}
			$permission .=  basename($file, '.php');

			if (!in_array($permission, $ignore)) {
				$edit="";
				$cache = str_replace("/","",str_replace("_","",$permission));
				if($this->cache->get($cache)){
					$edit = $this->url->link($direc."/add", $sign. $url . '&id='.$cache, 'SSL');
				}
				$data['layouts'][] = array($permission => $edit);
			}
		}
		//exit(json_encode($data['layouts']));
				

		$data['error_warning'] = isset($this->error['warning'])			? $this->error['warning']:'';
		$data['success'] 	   = isset($this->session->data['success'])	? $this->session->data['success']:'';
		$data['selected'] 	   = isset($this->request->post['selected'])?(array)$this->request->post['selected']:array();
		
		if (isset($this->session->data['success']))	unset($this->session->data['success']);
		
		//=================== header Table ==============

		$url  =  '';
		$url .= ($order == 'ASC')? '&order=DESC' : '&order=ASC';
		$url .=  isset($this->request->get['page'])? '&page=' . $this->request->get['page']:'';

		foreach ($fields as $field){
			if($field != "edit") {
				$data['sort_'.$field] 	= $this->url->link($direc, $sign. '&sort='.$field . $url, 'SSL');
			}
		}

		//=================== pagination ================
		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort='  . $this->request->get['sort']:'';
		$url .= isset($this->request->get['order'])?'&order=' . $this->request->get['order']:'';

		$pagination 		= new Pagination();
		//$pagination->total 	= $total;
		$pagination->page 	= $page;
		$pagination->limit 	= $limit;
		$pagination->url 	= $this->url->link($direc, $sign. $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		//$data['results'] 	= sprintf($data['text_pagination'], ($total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total - $limit)) ? $total : ((($page - 1) * $limit) + $limit), $total, ceil($total / $limit));
		$data['sort'] 		= $sort;
		$data['order'] 		= $order;

		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view($direc.'_list', $data);
		$this->response->setOutput($output);
	}
	
	protected function getForm($data) {
		
		$table	=  	$this->table;
		$direc	=  	$this->direc;
		$model	=	str_replace("/", "", $direc);
		$fields	=  	$this->fields;
		$id		= 	$this->id;
		
		$funcselect	= "get".$table;
		
		$defvalue = array(
			'direc'=> '',  'table'=>'', 'layout_content'=>'', 'templates'=>'', 'template'=>'', 'select'=>'', 'autocomplete'=>''
		);
		$data['error_name']	= isset($this->error['categoryname'])?$this->error['categoryname']:'';
		$files = glob(DI_TPL . 'form/*.html');
		foreach ($files as $file) {
			$data['option_inputtype'][] = basename($file, '.html');
		}
		
		/*$this->load->model('admin/object/attribute_group');
		$data['attribute_groups'] = $this->Madminobjectattributegroup->getattribute_groups();
		$this->load->model('admin/object/attribute');
		$data['attribute_rec'] = isset($this->request->get['category_id'])?$this->Madminobjectattribute->getattribute($this->request->get['category_id']):array();
		$this->load->model('admin/object/category');
		$data['categorys'] = $this->Madminobjectcategory->getcategorys();
		*/
		
		$iteminfo = array();
		if (isset($this->request->get[$id]) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$iteminfo = $this->cache->get($this->request->get[$id]);
			//exit(json_encode($iteminfo));
		}
		
		$data 	=  array_merge($data, $this->value->set($this->request->post,$iteminfo,$defvalue));
		
		$files = array_merge(glob(DI_TPL . '_control/*/*.php'),glob(DI_TPL . '_control/*.php'));
		foreach ($files as $file) {
			$data['templates'][] = basename($file, '.php');
		}
		$sign   = 'sign=' . $this->session->data['sign'];
		
		$data['text_form'] 			=!isset($this->request->get[$id]) ? $data['text_add'] : $data['text_edit'];
		$data['error_warning'] 		= isset($this->error['warning'])? $this->error['warning']:'';
		$data['error_name'] 		= isset($this->error['name'])? $this->error['name']:'';
		$data['success'] 			= isset($this->session->data['success'])? $this->session->data['success']:'';
		
		if(isset($this->session->data['success']))unset($this->session->data['success']);
		
		$url  = '';
		$url .= isset($this->request->get['sort']) ?'&sort=' . $this->request->get['sort']	:'';
		$url .= isset($this->request->get['order'])?'&order='. $this->request->get['order']	:'';
		$url .= isset($this->request->get['page']) ?'&page=' . $this->request->get['page']	:'';

		$data['action'] = !isset($this->request->get[$id])?
			$this->url->link($direc.'/add' , $sign. $url, 'SSL'):
			$this->url->link($direc.'/edit', $sign. '&'.$id.'=' . $this->request->get[$id] . $url, 'SSL');
			
		$data['cancel'] = $this->url->link($direc.'', $sign. $url, 'SSL');

		$data['password'] = isset($this->request->post['password'])? $this->request->post['password']:'';
		$data['confirm']  = isset($this->request->post['confirm']) ? $this->request->post['confirm']:'';

		$breads = array(
		    'home' 	   			=> 'admin/home/dashboard',
			 $table 	   		=>  $direc,
			 $data['text_form'] => '',
		);
		foreach($breads as $key => $value) {	
			if($value == ''||$value == '#') {
				$data['breadcrumbs'][] = array('text' => $key,'href' => '');
			} else {
				$data['breadcrumbs'][] = array(
					'text' => $key,
					'href' => $this->url->link($value, $sign, 'SSL')
				);
			}
		}

		$this->load->model('admin/tools/image');

		if (isset($this->request->post['image']) && is_file(DI_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->Madmintoolsimage->resize($this->request->post['image'], 100, 100);
		} elseif (isset($info['image']) &&!empty($info) && $info['image'] && is_file(DI_IMAGE . $info['image'])) {
			$data['thumb'] = $this->Madmintoolsimage->resize($info['image'], 100, 100);
		} elseif (isset($info[0]['image']) &&!empty($info) && $info[0]['image'] && is_file(DI_IMAGE . $info[0]['image'])) {
			$data['thumb'] = $this->Madmintoolsimage->resize($info[0]['image'], 100, 100);	
		} else {
			$data['thumb'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);
		}

		$data['no_image'] = $this->Madmintoolsimage->resize('no_image.png', 100, 100);

		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER);
		$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT:HTTP_CLIENT;
		$data['header'] = $this->load->control('admin/home/header'); 		
		$data['sign']	= 'sign=' . $this->session->data['sign'];
		$data['footer'] = $this->load->view('admin/home/footer', $data);
		$output 		= $this->load->view($direc.'_form', $data);
		$this->response->setOutput($output);
	}
	
	public function autoform() {
		$json = array();

		if (isset($this->request->get['category_id'])) {
			$this->load->model('admin/object/attribute');

			$filter_data = array(
				'category_id' => $this->request->get['category_id'],
				'sort'        => 'category_id',
				'order'       => 'ASC',
			);

			$results = $this->Madminobjectattribute->getattributesform($filter_data['category_id']);

			foreach ($results as $result) {
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'attribute'   => $result['attribute'],
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
