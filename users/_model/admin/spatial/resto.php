<?php
class Madminspatialresto extends Model {
	
public function updategeomresto($id,$data) {
	if (isset($data['maps'])&&($data['maps']['coordinate'])) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "resto SET geom= ST_GeomFromText('".$this->db->escape($data['maps']['coordinate'])."'),geom_color='".$this->db->escape($data['maps']['color'])."' WHERE resto_id ='".(int)$id."'");
	}
}

public function selectgeomresto($id){
	$query = $this->db->query("SELECT ST_AsGeoJSON(geom)::json As coordinate,geom_color as color FROM " . DB_PREFIX . "resto WHERE resto_id = '" . (int)$id . "'");
	return array('maps' => $query->row);
}

	
	public function addresto($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "resto(name,address,phone,jambuka,jamtutup,menu,image,image_thumb) VALUES('".$this->db->escape($data['name'])."','".$this->db->escape($data['address'])."','".$this->db->escape($data['phone'])."','".$this->db->escape($data['jambuka'])."','".$this->db->escape($data['jamtutup'])."','".$this->db->escape($data['menu'])."','".$this->db->escape($data['image'])."','".$this->db->escape($data['image_thumb'])."')");
		$id = $this->db->getLastId();
		
		$this->updategeomresto($id,$data);

	}
	
	public function editresto($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "resto SET name='".$this->db->escape($data['name'])."',address='".$this->db->escape($data['address'])."',phone='".$this->db->escape($data['phone'])."',jambuka='".$this->db->escape($data['jambuka'])."',jamtutup='".$this->db->escape($data['jamtutup'])."',menu='".$this->db->escape($data['menu'])."',image='".$this->db->escape($data['image'])."',image_thumb='".$this->db->escape($data['image_thumb'])."' WHERE resto_id = '" . (int)$id . "'");
	
		$this->updategeomresto($id,$data);
	
	}
	
	public function gettotalrestos() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "resto");
		return $query->row['total'];
	}
	
	public function deleteresto($id) {
		$this->db->query("DELETE FROM ".DB_PREFIX."resto WHERE resto_id = '" . (int)$id . "'");
	}
	
	public function getresto($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "resto WHERE resto_id = '" . (int)$id . "'");
		$query->row = array_merge($query->row,$this->selectgeomresto($id));


		//exit(json_encode($query->row));
		return $query->row;
	}
	
	public function getrestos($data = array()) {
		$sql  = "SELECT * FROM " . DB_PREFIX . "resto WHERE resto_id>=0 [search]";
		
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
		return json_decode('{"direc":"admin\/spatial\/resto","to_table":"","table":"resto","template":"standart","to_field":"1","to_label":"1","to_placeholder":"1","to_edit":"1","layout_content":[{"tab":"spatial","tabactive":"1","inputname":"maps","inputtype":"mapseditor","fieldname":"maps","fieldtype":"","label":"maps","placeholder":"maps"},{"tab":"attribut","primary":"1","inputname":"resto_id","inputtype":"hidden","fieldname":"resto_id","fieldtype":"","label":"resto_id","placeholder":"resto_id"},{"listview":"1","required":"1","inputname":"name","inputtype":"text","fieldname":"name","fieldtype":"","label":"nama tempat makan","placeholder":"nama tempat makan"},{"listview":"1","inputname":"address","inputtype":"textarea","fieldname":"address","fieldtype":"","label":"alamat","placeholder":"alamat"},{"listview":"1","inputname":"phone","inputtype":"text","fieldname":"phone","fieldtype":"","label":"phone","placeholder":"phone"},{"inputname":"jambuka","inputtype":"text","fieldname":"jambuka","fieldtype":"","label":"jam buka","placeholder":"jam buka"},{"inputname":"jamtutup","inputtype":"text","fieldname":"jamtutup","fieldtype":"","label":"jam tutup","placeholder":"jam tutup"},{"inputname":"menu","inputtype":"textarea","fieldname":"menu","fieldtype":"","label":"menu","placeholder":"menu"},{"listview":"1","inputname":"image","inputtype":"image","fieldname":"image","fieldtype":"","label":"image","placeholder":"image"}]}');
	}
}