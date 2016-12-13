<?php
class Madminspatialpenginapan extends Model {
	
public function updategeompenginapan($id,$data) {
	if (isset($data['maps'])&&($data['maps']['coordinate'])) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "penginapan SET geom= ST_GeomFromText('".$this->db->escape($data['maps']['coordinate'])."'),geom_color='".$this->db->escape($data['maps']['color'])."' WHERE penginapan_id ='".(int)$id."'");
	}
}

public function selectgeompenginapan($id){
	$query = $this->db->query("SELECT ST_AsGeoJSON(geom)::json As coordinate,geom_color as color FROM " . DB_PREFIX . "penginapan WHERE penginapan_id = '" . (int)$id . "'");
	return array('maps' => $query->row);
}

	
	public function addpenginapan($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "penginapan(name,address,phone,jumlahkamar,fasilitas,image,image_thumb) VALUES('".$this->db->escape($data['name'])."','".$this->db->escape($data['address'])."','".$this->db->escape($data['phone'])."','".$this->db->escape($data['jumlahkamar'])."','".$this->db->escape($data['fasilitas'])."','".$this->db->escape($data['image'])."','".$this->db->escape($data['image_thumb'])."')");
		$id = $this->db->getLastId();
		
		$this->updategeompenginapan($id,$data);

	}
	
	public function editpenginapan($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "penginapan SET name='".$this->db->escape($data['name'])."',address='".$this->db->escape($data['address'])."',phone='".$this->db->escape($data['phone'])."',jumlahkamar='".$this->db->escape($data['jumlahkamar'])."',fasilitas='".$this->db->escape($data['fasilitas'])."',image='".$this->db->escape($data['image'])."',image_thumb='".$this->db->escape($data['image_thumb'])."' WHERE penginapan_id = '" . (int)$id . "'");
	
		$this->updategeompenginapan($id,$data);
	
	}
	
	public function gettotalpenginapans() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "penginapan");
		return $query->row['total'];
	}
	
	public function deletepenginapan($id) {
		$this->db->query("DELETE FROM ".DB_PREFIX."penginapan WHERE penginapan_id = '" . (int)$id . "'");
	}
	
	public function getpenginapan($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "penginapan WHERE penginapan_id = '" . (int)$id . "'");
		$query->row = array_merge($query->row,$this->selectgeompenginapan($id));


		//exit(json_encode($query->row));
		return $query->row;
	}
	
	public function getpenginapans($data = array()) {
		$sql  = "SELECT * FROM " . DB_PREFIX . "penginapan WHERE penginapan_id>=0 [search]";
		
		$filter = '';
		if (!empty($data['filter_name'])) {
								$filter .= " AND upper(name) LIKE upper('%" . $this->db->escape($data['filter_name']) . "%')";
							}if (!empty($data['filter_address'])) {
								$filter .= " AND upper(address) LIKE upper('%" . $this->db->escape($data['filter_address']) . "%')";
							}if (!empty($data['filter_phone'])) {
								$filter .= " AND upper(phone) LIKE upper('%" . $this->db->escape($data['filter_phone']) . "%')";
							}if (!empty($data['filter_image'])) {
								$filter .= " AND upper(image) LIKE upper('%" . $this->db->escape($data['filter_image']) . "%')";
							}

		$sql = ($filter)? str_replace('[search]',$filter,$sql): str_replace('[search]','',$sql);
		
		$sort_data = array('name','address','phone','image');
		
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
		return json_decode('{"direc":"admin\/spatial\/penginapan","to_table":"","table":"penginapan","template":"standart","to_field":"1","to_label":"1","to_placeholder":"1","to_edit":"1","layout_content":[{"tab":"spatial","tabactive":"1","inputname":"maps","inputtype":"mapseditor","fieldname":"maps","fieldtype":"","label":"maps","placeholder":"maps"},{"tab":"attribut","primary":"1","inputname":"penginapan_id","inputtype":"hidden","fieldname":"penginapan_id","fieldtype":"","label":"penginapan_id","placeholder":"penginapan_id"},{"listview":"1","required":"1","inputname":"name","inputtype":"text","fieldname":"name","fieldtype":"","label":"nama penginapan","placeholder":"nama penginapan"},{"listview":"1","inputname":"address","inputtype":"textarea","fieldname":"address","fieldtype":"","label":"alamat","placeholder":"alamat"},{"listview":"1","inputname":"phone","inputtype":"text","fieldname":"phone","fieldtype":"","label":"phone","placeholder":"phone"},{"inputname":"jumlahkamar","inputtype":"number","fieldname":"jumlahkamar","fieldtype":"","label":"jumlah kamar","placeholder":"jumlah kamar"},{"inputname":"fasilitas","inputtype":"textarea","fieldname":"fasilitas","fieldtype":"","label":"fasilitas","placeholder":"fasilitas"},{"listview":"1","inputname":"image","inputtype":"image","fieldname":"image","fieldtype":"","label":"image","placeholder":"image"}]}');
	}
}