<?php
class Madminspatialwisata extends Model {
	
public function updategeomwisata($id,$data) {
	if (isset($data['maps'])&&($data['maps']['coordinate'])) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "wisata SET geom= ST_GeomFromText('".$this->db->escape($data['maps']['coordinate'])."'),geom_color='".$this->db->escape($data['maps']['color'])."' WHERE wisata_id ='".(int)$id."'");
	}
}

public function selectgeomwisata($id){
	$query = $this->db->query("SELECT ST_AsGeoJSON(geom)::json As coordinate,geom_color as color FROM " . DB_PREFIX . "wisata WHERE wisata_id = '" . (int)$id . "'");
	return array('maps' => $query->row);
}

public function wisata_jenis() {
	$query = $this->db->query("SELECT name,wisata_jenis_id FROM " . DB_PREFIX . "wisata_jenis");
	$arr = array();
	foreach($query->rows as $data){
		$arr = array_merge($arr,array($data['name']=>$data['wisata_jenis_id']));
	}
	return $arr;
}

	
	public function addwisata($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "wisata(wisata_jenis_id,name,address,phone,jambuka,jamtutup,biayamasuk,image,image_thumb) VALUES('".$this->db->escape($data['wisata_jenis_id'])."','".$this->db->escape($data['name'])."','".$this->db->escape($data['address'])."','".$this->db->escape($data['phone'])."','".$this->db->escape($data['jambuka'])."','".$this->db->escape($data['jamtutup'])."','".$this->db->escape($data['biayamasuk'])."','".$this->db->escape($data['image'])."','".$this->db->escape($data['image_thumb'])."')");
		$id = $this->db->getLastId();
		
		$this->updategeomwisata($id,$data);

	}
	
	public function editwisata($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "wisata SET wisata_jenis_id='".$this->db->escape($data['wisata_jenis_id'])."',name='".$this->db->escape($data['name'])."',address='".$this->db->escape($data['address'])."',phone='".$this->db->escape($data['phone'])."',jambuka='".$this->db->escape($data['jambuka'])."',jamtutup='".$this->db->escape($data['jamtutup'])."',biayamasuk='".$this->db->escape($data['biayamasuk'])."',image='".$this->db->escape($data['image'])."',image_thumb='".$this->db->escape($data['image_thumb'])."' WHERE wisata_id = '" . (int)$id . "'");
	
		$this->updategeomwisata($id,$data);
	
	}
	
	public function gettotalwisatas() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "wisata");
		return $query->row['total'];
	}
	
	public function deletewisata($id) {
		$this->db->query("DELETE FROM ".DB_PREFIX."wisata WHERE wisata_id = '" . (int)$id . "'");
	}
	
	public function getwisata($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "wisata WHERE wisata_id = '" . (int)$id . "'");
		$query->row = array_merge($query->row,$this->selectgeomwisata($id));


		//exit(json_encode($query->row));
		return $query->row;
	}
	
	public function getwisatas($data = array()) {
		$sql  = "SELECT * FROM " . DB_PREFIX . "wisata WHERE wisata_id>=0 [search]";
		
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
		return json_decode('{"direc":"admin\/spatial\/wisata","to_table":"","table":"wisata","template":"standart","to_field":"1","to_label":"1","to_placeholder":"1","to_edit":"1","layout_content":[{"tab":"spatial","tabactive":"1","inputname":"maps","inputtype":"mapseditor","fieldname":"maps","fieldtype":"","label":"maps","placeholder":"maps"},{"tab":"attribut","primary":"1","inputname":"wisata_id","inputtype":"hidden","fieldname":"wisata_id","fieldtype":"","label":"wisata_id","placeholder":"wisata_id"},{"required":"1","inputname":"wisata_jenis_id","inputtype":"select","fieldname":"wisata_jenis_id","fieldtype":"","label":"wisata jenis","placeholder":"wisata jenis"},{"listview":"1","required":"1","inputname":"name","inputtype":"text","fieldname":"name","fieldtype":"","label":"nama objek wisata","placeholder":"nama objek wisata"},{"listview":"1","inputname":"address","inputtype":"textarea","fieldname":"address","fieldtype":"","label":"alamat","placeholder":"alamat"},{"listview":"1","inputname":"phone","inputtype":"text","fieldname":"phone","fieldtype":"","label":"phone","placeholder":"phone"},{"inputname":"jambuka","inputtype":"text","fieldname":"jambuka","fieldtype":"","label":"jam buka","placeholder":"jam buka"},{"inputname":"jamtutup","inputtype":"text","fieldname":"jamtutup","fieldtype":"","label":"jam tutup","placeholder":"jam tutup"},{"inputname":"biayamasuk","inputtype":"text","fieldname":"biayamasuk","fieldtype":"","label":"biaya masuk","placeholder":"biaya masuk"},{"listview":"1","inputname":"image","inputtype":"image","fieldname":"image","fieldtype":"","label":"image","placeholder":"image"}],"select":{"2":{"table":"wisata_jenis","key":"name","value":"wisata_jenis_id"},"wisata_jenis_id":{"table":"wisata_jenis","key":"name","value":"wisata_jenis_id"}}}');
	}
}