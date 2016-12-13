<?php
class Madminspatialobjek extends Model {
	
	
	public function addobjek($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "objek(name,image,ket) VALUES('".$this->db->escape($data['name'])."','".$this->db->escape($data['image'])."','".$this->db->escape($data['keterangan'])."')");
		$id = $this->db->getLastId();
		
		
	}
	
	public function editobjek($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "objek SET name='".$this->db->escape($data['name'])."',image='".$this->db->escape($data['image'])."',ket='".$this->db->escape($data['keterangan'])."' WHERE objek_id = '" . (int)$id . "'");
	
			
	}
	
	public function gettotalobjeks() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "objek");
		return $query->row['total'];
	}
	
	public function deleteobjek($id) {
		$this->db->query("DELETE FROM ".DB_PREFIX."objek WHERE objek_id = '" . (int)$id . "'");
	}
	
	public function getobjek($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "objek WHERE objek_id = '" . (int)$id . "'");
		
		//exit(json_encode($query->row));
		return $query->row;
	}
	
	public function getobjeks($data = array()) {
		$sql  = "SELECT * FROM " . DB_PREFIX . "objek WHERE objek_id>=0 [search]";
		
		$filter = '';
		if (!empty($data['filter_name'])) {
								$filter .= " AND upper(name) LIKE upper('%" . $this->db->escape($data['filter_name']) . "%')";
							}if (!empty($data['filter_keterangan'])) {
								$filter .= " AND upper(ket) LIKE upper('%" . $this->db->escape($data['filter_keterangan']) . "%')";
							}

		$sql = ($filter)? str_replace('[search]',$filter,$sql): str_replace('[search]','',$sql);
		
		$sort_data = array('name','ket');
		
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
		return json_decode('{"direc":"admin\/spatial\/objek","to_table":"","table":"objek","template":"standart","to_label":"1","to_placeholder":"1","to_edit":"1","layout_content":[{"primary":"0","inputname":"objek_id","inputtype":"hidden","fieldname":"objek_id","fieldtype":"serial","label":"objek_id","placeholder":"objek_id"},{"listview":"1","required":"1","inputname":"name","inputtype":"text","fieldname":"name","fieldtype":"character varying(20)","label":"name","placeholder":"name"},{"inputname":"image","inputtype":"image","fieldname":"image","fieldtype":"text","label":"image","placeholder":"image"},{"listview":"1","inputname":"keterangan","inputtype":"textarea","fieldname":"ket","fieldtype":"text","label":"keterangan","placeholder":"keterangan"}]}');
	}
}