<?php
class Tracker {
	protected $errors 	= array();
	protected $service 	= 'api.ipinfodb.com';
	protected $version 	= 'v3';
	protected $apiKey 	= '';

	public function __construct(){}

	public function __destruct(){}

	public function getInfo(){
		//Load the class
		/*foreach($_SERVER as $key => $value){
			echo$key .' => '.$value.'<br/>' ;
		}
		exit();
		*/
		
		$this->setKey('add239113f3536578110c6f4db6111ea0c07e18ed9d238d0891b49ea8dc8ad91');
		
		$info['ip']				= $_SERVER['REMOTE_ADDR'];
		$info['query_string'] 	= $_SERVER['QUERY_STRING'];
		$info['http_referer'] 	= isset($_SERVER['HTTP_COOKIE'])?$_SERVER['HTTP_COOKIE']:'mobile web/ubuntu';
		$info['http_user_agent']= $_SERVER['HTTP_USER_AGENT'];
		
		
		//Get errors and locations
		$info['locations'] 		= $this->getCity($info['ip']);
		$info['errors'] 		= $this->getError();
		$info['country']		= '';
		$info['city']			= '';
		
		//Getting the result
		if (!empty($locations) && is_array($locations)) {
			foreach ($locations as $field => $val) {
				if ($field == 'countryName')
					$info['country']= $val;
				if ($field == 'cityName')
					$info['city'] 	= $val;
			}
		}
		
		if  ($this->is_bot())$info['isbot'] = 1;
		else $info['isbot'] = 0;
	
		$info['date'] = date("Y-m-d");
		$info['time'] = date("H:i:s");
		
		return $info;
	}
	
	private function is_bot(){
		$botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi",
		"looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
		"Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
		"crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
		"msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
		"Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
		"Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot",
		"Butterfly","Twitturls","Me.dium","Twiceler");
		foreach($botlist as $bot){
			if(strpos($_SERVER['HTTP_USER_AGENT'], $bot) !== false)
			return true;
		}
		return false;
	}
	
	//IP TO LOCATION ================================
	private function setKey($key){
		if(!empty($key)) $this->apiKey = $key;
	}

	private function getError(){
		return implode("\n", $this->errors);
	}

	private function getCountry($host){
		return $this->getResult($host, 'ip-country');
	}

	private function getCity($host){
		return $this->getResult($host, 'ip-city');
	}

	private function getResult($host, $name){
		$ip = @gethostbyname($host);

		// if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
		if(filter_var($ip, FILTER_VALIDATE_IP)){
			$xml = @file_get_contents('http://' . $this->service . '/' . $this->version . '/' . $name . '/?key=' . $this->apiKey . '&ip=' . $ip . '&format=xml');


			if (get_magic_quotes_runtime()){
				$xml = stripslashes($xml);
			}

			try{
				$response = @new SimpleXMLElement($xml);

				foreach($response as $field=>$value){
					$result[(string)$field] = (string)$value;
				}

				return $result;
			}
			catch(Exception $e){
				$this->errors[] = $e->getMessage();
				return;
			}
		}

		$this->errors[] = '"' . $host . '" is not a valid IP address or hostname.';
		return;
	}
}