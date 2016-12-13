<?php
class Madminobjectattribute extends Model {
	public function addattribute($data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute WHERE category_id = '" . (int)$this->db->escape($data['category_id']) . "'");
		
		if (isset($data['attribute'])) {
			foreach ($data['attribute'] as $content) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "attribute(category_id,attribute_group_id,sort_order) VALUES('".$this->db->escape($data['category_id'])."','".$this->db->escape($content['attribute_group_id'])."','".(int)$this->db->escape($content['sort_order'])."') ");
			}
		}
	}

	public function editattribute($attribute_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute WHERE category_id = '" . (int)$this->db->escape($attribute_id) . "'");
		
		if (isset($data['attribute'])) {
			//exit(json_encode($data));
			foreach ($data['attribute'] as $content) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "attribute(category_id,attribute_group_id,sort_order) VALUES('".$this->db->escape($attribute_id)."','".$this->db->escape($content['attribute_group_id'])."','".(int)$this->db->escape($content['sort_order'])."') ");
			}
		}
	}

	public function deleteattribute($attribute_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute WHERE category_id = '" . (int)$attribute_id . "'");
	}
	
	public function getattribute($attribute_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "attribute WHERE category_id='". $this->db->escape($attribute_id)."'";
		$query = $this->db->query($sql);
		$fix = array();
		$category_id = 0;
		foreach ($query->rows as $content){
			if($content['category_id'] != $category_id){
				$fix['category_id'] = $content['category_id']; 
				
			} 
			$fix['attribute_group_id'][] = $content['attribute_group_id']; 
			$fix['sort_order'][] = $content['sort_order']; 
			$category_id = $content['category_id'];
		}
		//exit(json_encode($fix));
		return $fix;
		
	}
	
	

	public function getattributes($data = array()) {
		$sql = "SELECT *,array(SELECT row_to_json(fg) FROM (SELECT b.attribute_id as id,c.name,c.type FROM " . DB_PREFIX . "attribute b, " . DB_PREFIX . "attribute_group c WHERE b.category_id=a.category_id AND b.attribute_group_id = c.attribute_group_id ORDER BY c.attribute_group_id) as fg) as attribute FROM " . DB_PREFIX . "category a";
		//$sql = "SELECT *,array(SELECT c.attribute_group_id FROM " . DB_PREFIX . "attribute b, " . DB_PREFIX . "attribute_group c WHERE b.category_id=a.category_id AND b.attribute_group_id = c.attribute_group_id ORDER BY c.attribute_group_id) as attribute_id, array(SELECT c.name FROM " . DB_PREFIX . "attribute b, " . DB_PREFIX . "attribute_group c WHERE b.category_id=a.category_id AND b.attribute_group_id = c.attribute_group_id ORDER BY c.attribute_group_id) as attribute_name FROM " . DB_PREFIX . "category a";
		
		$sort_data = array(
			'category',
			'category_id',
			'attribute_group_id',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}

		$query = $this->db->query($sql);
		
		$fix = array();
		$i = 0;
		$category_id = 0;
		foreach ($query->rows as $content){
			$rplc = array("{"  =>"[","}"  =>"]");
			foreach($rplc as $key => $value){
				$content['attribute'] = str_replace($key,$value,$content['attribute']);
			}
			$content['attribute'] = json_decode($content['attribute']);
			$attr = array();
			for($k=0; $k<count($content['attribute']); $k++){
				$rplc = array("]"  =>"}","["  =>"{");
				foreach($rplc as $key => $value){
					$content['attribute'][$k] = str_replace($key,$value,$content['attribute'][$k]);
				}
				$content['attribute'][$k] =  json_decode($content['attribute'][$k],TRUE);
			}
			$fix[] = $content;
		}
		
		return $fix;
	}
	
	public function getattributesform($category_id) {
		$sql = "SELECT *,array(SELECT row_to_json(fg) FROM (SELECT b.attribute_id as id,c.name,c.type FROM " . DB_PREFIX . "attribute b, " . DB_PREFIX . "attribute_group c WHERE b.category_id=a.category_id AND b.attribute_group_id = c.attribute_group_id ORDER BY c.attribute_group_id) as fg) as attribute FROM " . DB_PREFIX . "category a WHERE a.category_id='".(int)$category_id."'";
		//$sql = "SELECT *,array(SELECT c.attribute_group_id FROM " . DB_PREFIX . "attribute b, " . DB_PREFIX . "attribute_group c WHERE b.category_id=a.category_id AND b.attribute_group_id = c.attribute_group_id ORDER BY c.attribute_group_id) as attribute_id, array(SELECT c.name FROM " . DB_PREFIX . "attribute b, " . DB_PREFIX . "attribute_group c WHERE b.category_id=a.category_id AND b.attribute_group_id = c.attribute_group_id ORDER BY c.attribute_group_id) as attribute_name FROM " . DB_PREFIX . "category a";
		
		

		$query = $this->db->query($sql);
		
		$fix = array();
		$i = 0;
		$category_id = 0;
		foreach ($query->rows as $content){
			$rplc = array("{"  =>"[","}"  =>"]");
			foreach($rplc as $key => $value){
				$content['attribute'] = str_replace($key,$value,$content['attribute']);
			}
			$content['attribute'] = json_decode($content['attribute']);
			$attr = array();
			for($k=0; $k<count($content['attribute']); $k++){
				$rplc = array("]"  =>"}","["  =>"{");
				foreach($rplc as $key => $value){
					$content['attribute'][$k] = str_replace($key,$value,$content['attribute'][$k]);
				}
				$content['attribute'][$k] =  json_decode($content['attribute'][$k],TRUE);
			}
			$fix[] = $content;
		}
		
		return $fix;
	}

	public function getTotalattributes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute GROUP BY category_id");

		return $query->row['total'];
	}

}