<?php
class Madminspatialukmjenis extends Model {
	
	
	public function addukm_jenis($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ukm_jenis(name,ket) VALUES('".$this->db->escape($data['name'])."','".$this->db->escape($data['keterangan'])."')");
		$id = $this->db->getLastId();
		
		
	}
	
	public function editukm_jenis($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "ukm_jenis SET name='".$this->db->escape($data['name'])."',ket='".$this->db->escape($data['keterangan'])."' WHERE ukm_jenis_id = '" . (int)$id . "'");
	
			
	}
	
	public function gettotalukm_jeniss() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ukm_jenis");
		return $query->row['total'];
	}
	
	public function deleteukm_jenis($id) {
		$this->db->query("DELETE FROM ".DB_PREFIX."ukm_jenis WHERE ukm_jenis_id = '" . (int)$id . "'");
	}
	
	public function getukm_jenis($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ukm_jenis WHERE ukm_jenis_id = '" . (int)$id . "'");
		
		//exit(json_encode($query->row));
		return $query->row;
	}
	
	public function getukm_jeniss($data = array()) {
		$sql  = "SELECT * FROM " . DB_PREFIX . "ukm_jenis WHERE ukm_jenis_id>=0 [search]";
		
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
		return json_decode('{"direc":"admin\/spatial\/ukm_jenis","to_table":"","table":"ukm_jenis","template":"standart","to_field":"1","to_label":"1","to_placeholder":"1","to_edit":"1","layout_content":[{"primary":"0","inputname":"ukm_jenis_id","inputtype":"hidden","fieldname":"ukm_jenis_id","fieldtype":"","label":"ukm_jenis_id","placeholder":"ukm_jenis_id"},{"listview":"1","required":"1","inputname":"name","inputtype":"text","fieldname":"name","fieldtype":"","label":"name","placeholder":"name"},{"listview":"1","required":"1","inputname":"keterangan","inputtype":"textarea","fieldname":"ket","fieldtype":"","label":"keterangan","placeholder":"keterangan"}]}');
	}
}