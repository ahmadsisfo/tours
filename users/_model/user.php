<?php 
class Muser extends Model {
	private $user_id;
	private $username;
	private $permission = array();
	
	public function index() {
		if (isset($this->session->data[SES_ID.'user_id'])) {
			$Quser = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE user_id = '" . (int)$this->session->data[SES_ID.'user_id'] . "' AND status = '1'");
			if ($Quser->num_rows) {
				$this->user_id  = $Quser->row['user_id'];
				$this->username = $Quser->row['username'];
				$this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data[SES_ID.'user_id'] . "'");
				$Quser_group = $this->db->query("SELECT name,permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$Quser->row['user_group_id'] . "'");
				$permissions = unserialize($Quser_group->row['permission']);
				if (is_array($permissions)) {
					foreach ($permissions as $key => $value) {
						$this->permission[$key] = $value;
					}
				}
				$this->session->data['usergroup']   = $Quser_group->row['name'];
				$this->session->data['userimage']    = $Quser->row['image'];
				$this->session->data['username']    = $Quser->row['username'];
				$this->session->data['permissions'] = $permissions;
				
				//exit(SERVER_INFO);
			} else {
				$this->logout();
			}
		} 
	}
	
	public function login($username, $password) {
		$Quser = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape(md5($password)) . "' AND status = '1' ");
		if ($Quser->num_rows) {
			$this->session->data[SES_ID.'user_id'] = $Quser->row['user_id'];
			$this->user_id  = $Quser->row['user_id'];
			$this->username = $Quser->row['username'];
			$this->db->query("UPDATE ".DB_PREFIX."user SET ip = '".$this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . (int)$this->session->data[SES_ID.'user_id'] . "'");
			$Quser_group = $this->db->query("SELECT name,permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int)$Quser->row['user_group_id'] . "'");
			$permissions = unserialize($Quser_group->row['permission']);
			if (is_array($permissions)) {
				foreach ($permissions as $key => $value) {
					$this->permission[$key] = $value;
				}
			}
			$this->session->data['usergroup']   = $Quser_group->row['name'];
			$this->session->data['userimage']    = $Quser->row['image'];
			$this->session->data['username']    = $Quser->row['username'];
			$this->session->data['permissions'] = $permissions;
			
			return true;
		} else 
			return false;
	}
	
	public function logout() {
		unset($this->session->data[SES_ID.'user_id']);
		$this->user_id	= '';
		$this->username	= '';
	}
	
	public function isLogged() {
		return $this->user_id;
	}

	public function getId() {
		return $this->user_id;
	}

	public function getUserName() {
		return $this->username;
	}
	
	public function hasPermission($key, $value) {
		if  (isset($this->permission[$key])) 
			return in_array($value, $this->permission[$key]);
		else 
			return false;
	}
	
	public function tracking($data){
		$check = $this->db->query("SELECT `id` FROM `" . DB_PREFIX . "tracker` WHERE `ip`='" .$this->db->escape($data['ip']). "' AND `country`='" .$this->db->escape($data['country']). "' AND `city`='" .$this->db->escape($data['city']). "'");
		//exit(json_encode($check->row['id']));
		if($check->num_rows > 0){
			$this->db->query("insert into `" . DB_PREFIX . "tracker_detail` (`tracker_id`,`date`, `time`, `url`) VALUES ('".$check->row['id']."','".$this->db->escape($data['date'])."', '".$this->db->escape($data['time'])."', '".$this->db->escape($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'])."')");
		} else {
			$this->db->query("insert into `" . DB_PREFIX . "tracker` (`country`,`city`, `ip`, `query_string`, `http_referer`, `http_user_agent`, `isbot`) VALUES ('".$this->db->escape($data['country'])."','".$this->db->escape($data['city'])."', '".$this->db->escape($data['ip'])."', '".$this->db->escape($data['query_string'])."', '".$this->db->escape($data['http_referer'])."' ,'".$this->db->escape($data['http_user_agent'])."' , '".$this->db->escape($data['isbot'])."')");
			$check = $this->db->query("SELECT `id` FROM `" . DB_PREFIX . "tracker` WHERE `ip`='" .$this->db->escape($data['ip']). "' AND `country`='" .$this->db->escape($data['country']). "' AND `city`='" .$this->db->escape($data['city']). "'");
			$this->db->query("insert into `" . DB_PREFIX . "tracker_detail` (`tracker_id`,`date`, `time`, `url`) VALUES ('".$check->row['id']."','".$this->db->escape($data['date'])."', '".$this->db->escape($data['time'])."', '".$this->db->escape($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'])."')");
		}
		return true;
	}
}