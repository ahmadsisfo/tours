<?php
class Capiindex extends Controller {
	private $error = array();
	
	public function category() {		
	
		$data['title'] 			= $this->document->getTitle();
		$data['url']	  		= new Url(HTTP_SERVER, HTTPS_SERVER); 
		$data['client']			= $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		
		$this->load->model('api/index');
		$json = array();
		$json = $this->Mapiindex->getCategory();
		//$data['header'] 		= $this->load->view('public/header', $data);
		//$data['footer'] 	    = $this->load->view('public/footer', $data);
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
	public function tracking(){
		$this->load->model('api/index');
		$json = $this->Mapiindex->updateTracking($this->request->get);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function search($pagination = false){
		$this->load->model('api/index');
		$limit 	= $this->config->get('limit_list');
		$kategori 	= isset($this->request->get['kategori'])  	? $this->request->get['kategori']	 : 	'';
		$katakunci 	= isset($this->request->get['katakunci'])  	? $this->request->get['katakunci']   : 	'';
		$radius 	= isset($this->request->get['radius'])  	? $this->request->get['radius']	     : 	'';
		$rating 	= isset($this->request->get['ratingmin'])  	? $this->request->get['ratingmin']   : 	'';
		$latlng 	= isset($this->request->get['latlng'])  	? $this->request->get['latlng']   : 	'';
		$user_id 	= isset($this->request->get['user_id'])  	? $this->request->get['user_id']   : 	'';
		$sort  		= isset($this->request->get['sort'])  ? $this->request->get['sort']	 	 : 'name';
		$order  	= isset($this->request->get['order']) ? $this->request->get['order']   	 :  'ASC';
		$page   	= isset($this->request->get['page'])  ? $this->request->get['page'] 	 : 	    1;

		$filter 	= array(
			'filter_name' => $katakunci,
			'filter_radius' => $radius,
			'filter_rating' => $rating,
			'filter_kategori' => $kategori,
			'latlng'  => $latlng,
			'user_id' => $user_id,
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page) * $limit,
			'limit' => $limit
		);
		
		$json = array();
		if($pagination){
			$json = $this->Mapiindex->getSearch($filter,false);		
		}else {
			$json = $this->Mapiindex->getSearch($filter,true);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getallobjek(){
		$this->search(true);
	}
	
	public function getintersect(){
		$this->load->model('api/index');
		$json = $this->Mapiindex->getintersect($this->request->get);		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getlinestring(){
		$this->load->model('api/index');
		$json = $this->Mapiindex->getlinestring($this->request->get);		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($json);
	}
	
	public function getpolygon(){
		$this->load->model('api/index');
		$json = $this->Mapiindex->getpolygon($this->request->get);		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($json);
	}
	
	public function rate(){
		$this->load->model('api/index');
		$rating   	= isset($this->request->get['rating'])   ? $this->request->get['rating'] : 0;
		$objek_id   = isset($this->request->get['objek_id']) ? $this->request->get['objek_id'] : 0;
		$user_id   	= isset($this->request->get['user_id'])  ? $this->request->get['user_id'] : 0;
		$kategori  	= isset($this->request->get['kategori'])  ? $this->request->get['kategori'] : '';
		$data 	= array(
			'rating' 	=> $rating,
			'objek_id' 	=> $objek_id,
			'user_id' 	=> $user_id,
			'kategori'	=> $kategori
		);
		$this->Mapiindex->rate($data);
		
	}
	
	public function signin() {
		$this->load->model('api/index');
		$json = $this->Mapiindex->getParticipant($this->request->get);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
	public function predict() {
		$this->load->model('api/index');
		$json = $this->Mapiindex->getRecommendation($this->request->get);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getRecommendationByKategori() {
		$this->load->model('api/index');
		$json = $this->Mapiindex->getRecommendation($this->request->get);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getRecommendationByObjek() {
		$this->load->model('api/index');
		$json = $this->Mapiindex->getRecommendationByObjek($this->request->get);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function chartrating(){
		$this->load->model('api/index');
		$json = $this->Mapiindex->getRateByObjekId($this->request->get);
		$data['s1'] 	= "";
		$data['ticks']	= "";
		foreach($json as $key=>$value){
			switch($key){
				case 'count' :
					$data['count'] 	= $value;
				break;
				case 'avg' :
					$data['avg'] 	= round($value,2);
				break;
				case 'sum' :
					$data['sum'] 	= $value;
				break;
				default:
					if($data['s1']!=""){
						$data['s1']		.= ",";
						$data['ticks']	.= ",";
					}
					$data['s1'] 	.= $value;
					$data['ticks']	.= "'".$key."'";
				break;
			}
			if($key == "count"){
			$data['count'] = $value[$key];
			}
		}
		$data['url']    = new Url(HTTP_SERVER, HTTPS_SERVER); 	 
		//$data['client'] = $this->request->server['HTTPS']? HTTPS_CLIENT :  HTTP_CLIENT;
		$data['client'] = "./assets/client/";
		
		$output 		= $this->load->view('api/chartrating', $data);
		//$this->response->addHeader('Content-Type: application/html');
		$this->response->setOutput($output);
	}
	
	public function coba(){
		$data=$this->request->get;
		/*$in = $this->db->query("SELECT st_y(st_centroid(geom)) as lat,st_x(st_centroid(geom)) as lng FROM ".DB_PREFIX."user_visit WHERE user_id=".(int)$data['user_id']."");
		foreach($in->rows as $row){
					echo $row['lat'];
				}*/
				
		$this->load->model('api/index');
		$json = $this->Mapiindex->getIntersect($this->request->get);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	public function migration(){
		$this->load->model('api/index');
		$json = $this->Mapiindex->migration($this->request->get);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

