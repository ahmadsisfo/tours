<?php
class Madmindataspatial extends Model {
	public function addspatial($data) {
		if (isset($data['spatial'])) {
			$con = $data['spatial']; 
			if(isset($con['geomtype']) && $con['geomtype'] != null){
				$this->db->query("INSERT INTO " . DB_PREFIX . "object(category_id,name,address,description,image,date_added,date_modif,geom) VALUES(
					'".(int)$this->db->escape($con['category_id'])."',
					'".$this->db->escape($con['name'])."',
					'".$this->db->escape($con['address'])."',
					'".$this->db->escape($con['description'])."',
					'".$this->db->escape($con['image'])."',
					'now()','now()',ST_GeomFromText('".$con['geomtype']."(".$con['geom'].")')
					) ");
			}else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "object(category_id,name,address,description,image,date_added,date_modif) VALUES(
					'".(int)$this->db->escape($con['category_id'])."',
					'".$this->db->escape($con['name'])."',
					'".$this->db->escape($con['address'])."',
					'".$this->db->escape($con['description'])."',
					'".$this->db->escape($con['image'])."',
					'now()','now()'
					) ");
			}
			
			$object_gid = $this->db->getLastId();
			if (isset($data['spatial_filter'])) {
				foreach ($data['spatial_filter'] as $filter){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_to_filter(object_gid,filter_id) VALUES(
					'".(int)$object_gid."',
					'".(int)$this->db->escape($filter)."')");
				}	
			}
			
			if (isset($data['spatial_related'])) {
				foreach ($data['spatial_related'] as $related){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_related(object_gid,related_gid) VALUES(
					'".(int)$object_gid."',
					'".(int)$this->db->escape($related)."')");
				}	
			}
			
			if (isset($data['spatial_special'])) {
				foreach ($data['spatial_special'] as $special){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_special(object_gid,price,description,date_start,date_end,priority) VALUES(
					'".(int)$object_gid."',
					'".$this->db->escape($special['price'])."',
					'".$this->db->escape($special['description'])."',
					'".$this->db->escape(date("d-m-Y", strtotime($special['date_start'])))."',
					'".$this->db->escape(date("d-m-Y", strtotime($special['date_end'])))."',
					'".(int)$this->db->escape($special['priority'])."'
					)");
				}	
			}
			
			if (isset($data['spatial_comment'])) {
				foreach ($data['spatial_comment'] as $special){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_comment(object_gid,user_id,comment,date_added,time,status) VALUES(
					'".(int)$object_gid."',
					'".$this->db->escape($special['username'])."',
					'".$this->db->escape($special['comment'])."',
					'".$this->db->escape(date("d-m-Y", strtotime($special['date'])))."',
					'".date("G:i:s", strtotime($special['time']))."',
					'".$this->db->escape($special['status'])."'
					)");
				}	
			}
			
			if (isset($data['spatial_image'])) {
				foreach ($data['spatial_image'] as $image){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_image(object_gid,image,name,description,sort_order,date_added) VALUES(
					'".(int)$object_gid."',
					'".$this->db->escape($image['image'])."',
					'".$this->db->escape($image['name'])."',
					'".$this->db->escape($image['description'])."',
					'".(int)$this->db->escape($image['sort_order'])."',
					'now()')");
				}	
			}
			
			if(isset($data['attribute'])){
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute WHERE category_id='". (int)$this->db->escape($con['category_id'])."'");
				//exit(json_encode($data['attribute'][4]));
				foreach( $query->rows as $att){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_to_attribute(object_gid,attribute_id,value) VALUES(
					'".(int)$object_gid."',
					'".(int)$att['attribute_id']."',
					'".$data['attribute'][$att['attribute_id']]."'
					)");
				}
				
			}
		}
	}

	public function editspatial($object_gid, $data) {
		if (isset($data['spatial'])) {
			$con = $data['spatial']; 
			//exit(json_encode($con['geom']));
			if(isset($con['geomtype']) && $con['geomtype'] != null){
				$this->db->query("UPDATE " . DB_PREFIX . "object SET 
				category_id = '".(int)$this->db->escape($con['category_id'])."',
				name = '".$this->db->escape($con['name'])."',
				address = '".$this->db->escape($con['address'])."',
				description = '".$this->db->escape($con['description'])."',
				image = '".$this->db->escape($con['image'])."',
				date_modif = 'now()',
				geom =  ST_GeomFromText('".$con['geomtype']."(".$con['geom'].")')
				WHERE gid = '".(int)$object_gid."' ");
			} else {
				$this->db->query("UPDATE " . DB_PREFIX . "object SET 
				category_id = '".(int)$this->db->escape($con['category_id'])."',
				name = '".$this->db->escape($con['name'])."',
				address = '".$this->db->escape($con['address'])."',
				description = '".$this->db->escape($con['description'])."',
				image = '".$this->db->escape($con['image'])."',
				date_modif = 'now()'
				WHERE gid = '".(int)$object_gid."' ");
			}
			if (isset($data['spatial_filter'])) {
				$this->db->query("DELETE FROM ".DB_PREFIX."object_to_filter WHERE object_gid='" . (int)$object_gid . "'");
				foreach ($data['spatial_filter'] as $filter){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_to_filter(object_gid,filter_id) VALUES(
					'".(int)$object_gid."',
					'".(int)$this->db->escape($filter)."')");
				}	
			}
			
			if (isset($data['spatial_related'])) {
				$this->db->query("DELETE FROM ".DB_PREFIX ."object_related WHERE object_gid = '" . (int)$object_gid . "'");
				foreach ($data['spatial_related'] as $related){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_related(object_gid,related_gid) VALUES(
					'".(int)$object_gid."',
					'".(int)$this->db->escape($related)."')");
				}	
			}
			
			if (isset($data['spatial_special'])) {
				$this->db->query("DELETE FROM ".DB_PREFIX ."object_special WHERE object_gid = '" . (int)$object_gid . "'");
				foreach ($data['spatial_special'] as $special){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_special(object_gid,price,description,date_start,date_end,priority) VALUES(
					'".(int)$object_gid."',
					'".$this->db->escape($special['price'])."',
					'".$this->db->escape($special['description'])."',
					'".$this->db->escape(date("d-m-Y", strtotime($special['date_start'])))."',
					'".$this->db->escape(date("d-m-Y", strtotime($special['date_end'])))."',
					'".(int)$this->db->escape($special['priority'])."'
					)");
				}	
			}
			
			if (isset($data['spatial_comment'])) {
				$this->db->query("DELETE FROM ".DB_PREFIX ."object_comment WHERE object_gid = '" . (int)$object_gid . "'");
				foreach ($data['spatial_comment'] as $special){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_comment(object_gid,user_id,comment,date_added,time,status) VALUES(
					'".(int)$object_gid."',
					'".(int)$this->db->escape($special['username'])."',
					'".$this->db->escape($special['comment'])."',
					'".$this->db->escape(date("d-m-Y", strtotime($special['date'])))."',
					'".date("G:i:s", strtotime($special['time']))."',
					'".$this->db->escape($special['status'])."'
					)");
				}	
			}

			
			if (isset($data['spatial_image'])) {
				$this->db->query("DELETE FROM ".DB_PREFIX ."object_image WHERE object_gid = '" . (int)$object_gid . "'");
				foreach ($data['spatial_image'] as $image){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_image(object_gid,image,name,description,sort_order,date_added) VALUES(
					'".(int)$object_gid."',
					'".$this->db->escape($image['image'])."',
					'".$this->db->escape($image['name'])."',
					'".$this->db->escape($image['description'])."',
					'".(int)$this->db->escape($image['sort_order'])."',
					'now()')");
				}	
			}
			
			if(isset($data['attribute'])){
				$this->db->query("DELETE FROM ".DB_PREFIX ."object_to_attribute WHERE object_gid = '" . (int)$object_gid . "'");
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute WHERE category_id='". (int)$this->db->escape($con['category_id'])."'");
				//exit(json_encode($data['attribute'][4]));
				foreach( $query->rows as $att){
					$this->db->query("INSERT INTO " . DB_PREFIX . "object_to_attribute(object_gid,attribute_id,value) VALUES(
					'".(int)$object_gid."',
					'".(int)$att['attribute_id']."',
					'".$data['attribute'][$att['attribute_id']]."'
					)");
				}
				
			}
		}
	}

	public function deletespatial($object_gid) {
		$this->db->query("DELETE FROM ".DB_PREFIX."object WHERE gid = '" . (int)$object_gid . "'");
		$this->db->query("DELETE FROM ".DB_PREFIX."object_to_attribute WHERE object_gid='" . (int)$object_gid . "'");
		$this->db->query("DELETE FROM ".DB_PREFIX."object_to_filter WHERE object_gid='" . (int)$object_gid . "'");
		$this->db->query("DELETE FROM ".DB_PREFIX."object_related WHERE object_gid='" . (int)$object_gid . "'");
		$this->db->query("DELETE FROM ".DB_PREFIX."object_special WHERE object_gid='" . (int)$object_gid . "'");
		$this->db->query("DELETE FROM ".DB_PREFIX."object_image WHERE object_gid='" . (int)$object_gid . "'");
	}
	
	public function getspatial($spatial_id){
		$sql = "SELECT gid,name,description,address,category_id,image,(SELECT row_to_json(fc) 
				FROM (SELECT ST_AsGeoJSON(a.geom)::json As geometry , row_to_json((SELECT l 
				FROM (SELECT ST_X(ST_Centroid(a.geom)) AS lon, ST_Y(ST_CENTROID(a.geom)) As lat) As l )) As properties 
				) As fc) as geom FROM " . DB_PREFIX . "object a WHERE gid='". $this->db->escape($spatial_id)."'";
		$query = $this->db->query($sql);
		$geom = $query->rows[0]['geom'];
		$Array = json_decode($geom, true);

		foreach ($Array as $key => $value) {
			   //echo " $key ";
			foreach ($value as $k => $val)   {
				//echo "$k = $val <br />";
				$query->rows[0]['thegeom'][$k] = $val;
			}    
		}
		
		unset($query->rows[0]['geom']);
		$polygon = "[";
		if($query->rows[0]['thegeom']['coordinates'] != null){
			foreach($query->rows[0]['thegeom']['coordinates'] as $coordinate){
				if(is_array($coordinate[0])){
					foreach($coordinate as $coor){
						if(is_array($coor[0])){
							foreach($coor as $co){
								$polygon .= "{ lat: ".$co[1].", lng :".$co[0]." },"; 
								
							}
						}else{
							$polygon .= "{ lat: ".$coor[1].", lng :".$coor[0]." },"; 
						}
					}
				} else {
					$polygon .= "";
				}
			}
		}
		$polygon .= "]";
		$query->rows[0]['thegeom']['coordinates'] = str_replace(",]","]",$polygon);
		$query->rows[0]['thegeom']['center1'] = "{lat:".$query->rows[0]['thegeom']['lat'].",lng:".$query->rows[0]['thegeom']['lon']."}"; 
		$query->rows[0]['thegeom']['center2'] = "".$query->rows[0]['thegeom']['lat'].",".$query->rows[0]['thegeom']['lon'].""; 
		//exit(json_encode($query->rows[0]['thegeom']));
		return $query->rows[0];
	}
	
	public function getspatialimages($spatial_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "object_image WHERE object_gid='". $this->db->escape($spatial_id)."'";
		$query = $this->db->query($sql);
		//exit(json_encode($query->rows));
		return $query->rows;
	}

	public function getspatials($data = array()) {
		$sql = "SELECT gid,name,address,date_added,image,(SELECT name FROM " . DB_PREFIX . "category d WHERE d.category_id=a.category_id) as category, array(SELECT row_to_json(fg) FROM (SELECT name FROM " . DB_PREFIX . "object_to_filter b," . DB_PREFIX . "filter c WHERE b.object_gid=a.gid AND b.filter_id=c.filter_id [search]) as fg) as filter FROM " . DB_PREFIX . "object a WHERE gid != 0 ";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND upper(name) LIKE upper('%" . $this->db->escape($data['filter_name']) . "%')";
		}

		if (!empty($data['filter_address'])) {
			$sql .= " AND upper(address) LIKE upper('%" . $this->db->escape($data['filter_address']) . "%')";
		}

		if (!empty($data['filter_filter'])) {
			$sql = str_replace("[search]"," AND upper(c.name) LIKE upper('%" . $this->db->escape($data['filter_filter']) . "%')", $sql);
		}else{
			$sql = str_replace("[search]","", $sql);
		}
		
		/*if (isset($data['category']) && !is_null($data['filter_price'])) {
			$sql .= " AND a.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}*/
		
		$sql .= " GROUP BY gid";
		
		$sort_data = array(
			'name',
			'address',
			'date_added',
			'category'
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

		//$query = $this->db->query($sql);
		
		$query = $this->db->query($sql);
		
		$fix = array();
		$i = 0;
		$category_id = 0;
		foreach ($query->rows as $content){
			$rplc = array("{"  =>"[","}"  =>"]");
			foreach($rplc as $key => $value){
				$content['filter'] = str_replace($key,$value,$content['filter']);
			}
			$content['filter'] = json_decode($content['filter']);
			$attr = array();
			for($k=0; $k<count($content['filter']); $k++){
				$rplc = array("]"  =>"}","["  =>"{");
				foreach($rplc as $key => $value){
					$content['filter'][$k] = str_replace($key,$value,$content['filter'][$k]);
				}
				$content['filter'][$k] =  json_decode($content['filter'][$k],TRUE);
			}
			$fix[] = $content;
		}
		//exit(json_encode($fix));
		return $fix;
		
		//return $query->rows;
	}

	public function getTotalspatials() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "object");

		return $query->row['total'];
	}
	
	public function getSpatialFilters($spatial_id) {
		$spatial_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "object_to_filter WHERE object_gid = '" . (int)$spatial_id . "'");

		foreach ($query->rows as $result) {
			$spatial_filter_data[] = $result['filter_id'];
		}

		return $spatial_filter_data;
	}
	
	public function getSpatialRelated($spatial_id) {
		$spatial_related_data = array();

		$query = $this->db->query("SELECT *,(SELECT name FROM " . DB_PREFIX . "object b WHERE a.related_gid=b.gid) as name FROM " . DB_PREFIX . "object_related a WHERE object_gid = '" . (int)$spatial_id . "'");

		//exit(json_encode($query->rows));
		return $query->rows;
	}
	
	public function getSpatialAttribute($spatial_id) {
		$sql = "SELECT array(SELECT row_to_json(fg) FROM (SELECT b.attribute_id as id,c.name,c.type,d.value FROM " . DB_PREFIX . "attribute b, " . DB_PREFIX . "attribute_group c," . DB_PREFIX . "object_to_attribute d WHERE b.attribute_group_id = c.attribute_group_id AND b.attribute_id = d.attribute_id AND d.object_gid='".$spatial_id."' ORDER BY c.attribute_group_id) as fg) as attribute";

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
		
		//exit(json_encode($fix[0]['attribute']));
		return $fix[0]['attribute'];
	}
	
	public function getSpatialSpecials($spatial_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "object_special WHERE object_gid = '" . (int)$spatial_id . "' ORDER BY priority, price");
		//exit(json_encode($query->rows));
		return $query->rows;
	}
	
	public function getSpatialComments($spatial_id) {
		$query = $this->db->query("SELECT * ,(SELECT username FROM " . DB_PREFIX . "user b WHERE b.user_id=a.user_id) as username FROM " . DB_PREFIX . "object_comment a WHERE object_gid = '" . (int)$spatial_id . "' ORDER BY date_added,time");
		//exit(json_encode($query->rows));
		return $query->rows;
	}
	
	public function migrasi() {
		$query = $this->db->query("SELECT * FROM ukm ORDER BY nama");
		$category = 5;
		$attribute = array(
			
			23=>'telepon',
			24=>'produk'
			
			
		);
		foreach ($query->rows as $content){
			$this->db->query("INSERT INTO " . DB_PREFIX . "object(name,address,geom,category_id,date_added,date_modif) VALUES('".$this->db->escape($content['nama'])."','".$this->db->escape($content['alamat'])."','".$content['geom']."','".(int)$category."','now()','now()') ");
			$object_gid = $this->db->getLastId();
			foreach($attribute as $key => $value){
				$this->db->query("INSERT INTO ". DB_PREFIX . "object_to_attribute(object_gid,attribute_id,value) VALUES('".(int)$object_gid."','".(int)$key."','".$this->db->escape($content[$value])."')");
			}
		}
		exit(json_encode($query->rows));
		//return $query->rows;
	}
}