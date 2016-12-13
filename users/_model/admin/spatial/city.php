<?php
class Madminspatialcity extends Model {
	
public function updategeomcity($id,$data) {
	if (isset($data['maps'])&&($data['maps']['coordinate'])) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "city SET geom= ST_GeomFromText('".$this->db->escape($data['maps']['coordinate'])."'),geom_color='".$this->db->escape($data['maps']['color'])."' WHERE city_id ='".(int)$id."'");
	}
}

public function selectgeomcity($id){
	$query = $this->db->query("SELECT ST_AsGeoJSON(geom)::json As coordinate,geom_color as color FROM " . DB_PREFIX . "city WHERE city_id = '" . (int)$id . "'");
	return array('maps' => $query->row);
}

	
	public function addcity($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "city(name,description) VALUES('".$this->db->escape($data['name'])."','".$this->db->escape($data['description'])."')");
		$id = $this->db->getLastId();
		
		$this->updategeomcity($id,$data);

	}
	
	public function editcity($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "city SET name='".$this->db->escape($data['name'])."',description='".$this->db->escape($data['description'])."' WHERE city_id = '" . (int)$id . "'");
	
		$this->updategeomcity($id,$data);
	
	}
	
	public function gettotalcitys() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "city");
		return $query->row['total'];
	}
	
	public function deletecity($id) {
		$this->db->query("DELETE FROM ".DB_PREFIX."city WHERE city_id = '" . (int)$id . "'");
	}
	
	public function getcity($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "city WHERE city_id = '" . (int)$id . "'");
		$query->row = array_merge($query->row,$this->selectgeomcity($id));


		//exit(json_encode($query->row));
		return $query->row;
	}
	
	public function getcitys($data = array()) {
		$sql  = "SELECT * FROM " . DB_PREFIX . "city WHERE city_id>=0 [search]";
		
		$filter = '';
		if (!empty($data['filter_name'])) {
								$filter .= " AND upper(name) LIKE upper('%" . $this->db->escape($data['filter_name']) . "%')";
							}if (!empty($data['filter_description'])) {
								$filter .= " AND upper(description) LIKE upper('%" . $this->db->escape($data['filter_description']) . "%')";
							}

		$sql = ($filter)? str_replace('[search]',$filter,$sql): str_replace('[search]','',$sql);
		
		$sort_data = array('name','description');
		
		$sql .= (isset($data['sort']) && in_array($data['sort'], $sort_data))? " ORDER BY " . $data['sort']:" ORDER BY name";
		$sql .= (isset($data['order']) && ($data['order'] == 'DESC'))? " DESC":" ASC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
			
			switch(DB_DRIVER){
				case 'pgsql': $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
					break;
				default 	: 	$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
		}

		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getlayoutform(){
		return json_decode('{"direc":"admin\/spatial\/city","to_table":"","table":"city","template":"standart","to_field":"1","to_label":"1","to_placeholder":"1","to_edit":"1","layout_content":[{"tab":"spatial","tabactive":"1","inputname":"maps","inputtype":"mapseditor","fieldname":"maps","fieldtype":"","label":"maps","placeholder":"maps"},{"tab":"attribut","primary":"1","inputname":"city_id","inputtype":"hidden","fieldname":"city_id","fieldtype":"","label":"city_id","placeholder":"city_id"},{"listview":"1","required":"1","inputname":"name","inputtype":"text","fieldname":"name","fieldtype":"","label":"nama kota","placeholder":"nama kota"},{"listview":"1","required":"1","inputname":"description","inputtype":"summernote","fieldname":"description","fieldtype":"","label":"keterangan","placeholder":"keterangan"}]}');
	}
}