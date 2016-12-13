<?php
class Madminspatialukm extends Model {
	
public function updategeomukm($id,$data) {
	if (isset($data['maps'])&&($data['maps']['coordinate'])) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "ukm SET geom= ST_GeomFromText('".$this->db->escape($data['maps']['coordinate'])."'),geom_color='".$this->db->escape($data['maps']['color'])."' WHERE ukm_id ='".(int)$id."'");
	}
}

public function selectgeomukm($id){
	$query = $this->db->query("SELECT ST_AsGeoJSON(geom)::json As coordinate,geom_color as color FROM " . DB_PREFIX . "ukm WHERE ukm_id = '" . (int)$id . "'");
	return array('maps' => $query->row);
}

public function ukm_jenis() {
	$query = $this->db->query("SELECT name,ukm_jenis_id FROM " . DB_PREFIX . "ukm_jenis");
	$arr = array();
	foreach($query->rows as $data){
		$arr = array_merge($arr,array($data['name']=>$data['ukm_jenis_id']));
	}
	return $arr;
}

	
	public function addukm($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "ukm(ukm_jenis_id,name,address,phone,produk,image,image_thumb) VALUES('".$this->db->escape($data['ukm_jenis_id'])."','".$this->db->escape($data['name'])."','".$this->db->escape($data['address'])."','".$this->db->escape($data['phone'])."','".$this->db->escape($data['produk'])."','".$this->db->escape($data['image'])."','".$this->db->escape($data['image_thumb'])."')");
		$id = $this->db->getLastId();
		
		$this->updategeomukm($id,$data);

	}
	
	public function editukm($id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "ukm SET ukm_jenis_id='".$this->db->escape($data['ukm_jenis_id'])."',name='".$this->db->escape($data['name'])."',address='".$this->db->escape($data['address'])."',phone='".$this->db->escape($data['phone'])."',produk='".$this->db->escape($data['produk'])."',image='".$this->db->escape($data['image'])."',image_thumb='".$this->db->escape($data['image_thumb'])."' WHERE ukm_id = '" . (int)$id . "'");
	
		$this->updategeomukm($id,$data);
	
	}
	
	public function gettotalukms() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ukm");
		return $query->row['total'];
	}
	
	public function deleteukm($id) {
		$this->db->query("DELETE FROM ".DB_PREFIX."ukm WHERE ukm_id = '" . (int)$id . "'");
	}
	
	public function getukm($id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ukm WHERE ukm_id = '" . (int)$id . "'");
		$query->row = array_merge($query->row,$this->selectgeomukm($id));


		//exit(json_encode($query->row));
		return $query->row;
	}
	
	public function getukms($data = array()) {
		$sql  = "SELECT * FROM " . DB_PREFIX . "ukm WHERE ukm_id>=0 [search]";
		
		$filter = '';
		if (!empty($data['filter_ukm_jenis_id'])) {
								$filter .= " AND upper(ukm_jenis_id) LIKE upper('%" . $this->db->escape($data['filter_ukm_jenis_id']) . "%')";
							}if (!empty($data['filter_name'])) {
								$filter .= " AND upper(name) LIKE upper('%" . $this->db->escape($data['filter_name']) . "%')";
							}if (!empty($data['filter_address'])) {
								$filter .= " AND upper(address) LIKE upper('%" . $this->db->escape($data['filter_address']) . "%')";
							}if (!empty($data['filter_phone'])) {
								$filter .= " AND upper(phone) LIKE upper('%" . $this->db->escape($data['filter_phone']) . "%')";
							}if (!empty($data['filter_image'])) {
								$filter .= " AND upper(image) LIKE upper('%" . $this->db->escape($data['filter_image']) . "%')";
							}

		$sql = ($filter)? str_replace('[search]',$filter,$sql): str_replace('[search]','',$sql);
		
		$sort_data = array('ukm_jenis_id','name','address','phone','image');
		
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
		return json_decode('{"direc":"admin\/spatial\/ukm","to_table":"","table":"ukm","template":"standart","to_field":"1","to_label":"1","to_placeholder":"1","to_edit":"1","layout_content":[{"tab":"spatial","tabactive":"1","inputname":"maps","inputtype":"mapseditor","fieldname":"maps","fieldtype":"","label":"maps","placeholder":"maps"},{"tab":"attribut","primary":"1","inputname":"ukm_id","inputtype":"hidden","fieldname":"ukm_id","fieldtype":"","label":"ukm_id","placeholder":"ukm_id"},{"listview":"1","required":"1","inputname":"ukm_jenis_id","inputtype":"select","fieldname":"ukm_jenis_id","fieldtype":"","label":"ukm jenis id","placeholder":"ukm jenis id"},{"listview":"1","required":"1","inputname":"name","inputtype":"text","fieldname":"name","fieldtype":"","label":"nama ukm","placeholder":"nama ukm"},{"listview":"1","inputname":"address","inputtype":"textarea","fieldname":"address","fieldtype":"","label":"alamat","placeholder":"alamat"},{"listview":"1","inputname":"phone","inputtype":"text","fieldname":"phone","fieldtype":"","label":"phone","placeholder":"phone"},{"inputname":"produk","inputtype":"textarea","fieldname":"produk","fieldtype":"","label":"produk","placeholder":"produk"},{"listview":"1","inputname":"image","inputtype":"image","fieldname":"image","fieldtype":"","label":"image","placeholder":"image"}],"select":{"2":{"table":"ukm_jenis","key":"name","value":"ukm_jenis_id"},"ukm_jenis_id":{"table":"ukm_jenis","key":"name","value":"ukm_jenis_id"}}}');
	}
}