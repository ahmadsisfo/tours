<?php
class M[model] extends Model {
	
[tambahansql]	
	public function add[table]($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "[table]([insertfield]) VALUES([insertvalues])");
		$id = $this->db->getLastId();
		
		[tambahanfungsiupdate]
	}
	
	public function edit[table]($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "[table] SET [updatevalues] WHERE [id] = '" . (int)$id . "'");
	
		[tambahanfungsiupdate]	
	}
	
	public function gettotal[table]s() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "[table]");
		return $query->row['total'];
	}
	
	public function delete[table]($id) {
		$this->db->query("DELETE FROM ".DB_PREFIX."[table] WHERE [id] = '" . (int)$id . "'");
	}
	
	public function get[table]($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "[table] WHERE [id] = '" . (int)$id . "'");
		[tambahanfungsiselect]
		//exit(json_encode($query->row));
		return $query->row;
	}
	
	public function get[table]s($data = array()) {
		$sql  = "SELECT * FROM " . DB_PREFIX . "[table] WHERE [id]>=0 [search]";
		
		$filter = '';
		[listfiltermodel]

		$sql = ($filter)? str_replace('[search]',$filter,$sql): str_replace('[search]','',$sql);
		
		$sort_data = [fieldlist];
		
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
		return json_decode('[post]');
	}
}