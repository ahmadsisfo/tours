public function updategeom[table]($id,$data) {
	if (isset($data['[inputname]'])&&($data['[inputname]']['coordinate'])) {
		$query = $this->db->query("UPDATE " . DB_PREFIX . "[table] SET geom= ST_GeomFromText('".$this->db->escape($data['[inputname]']['coordinate'])."'),geom_color='".$this->db->escape($data['[inputname]']['color'])."' WHERE [id] ='".(int)$id."'");
	}
}

public function selectgeom[table]($id){
	$query = $this->db->query("SELECT ST_AsGeoJSON(geom)::json As coordinate,geom_color as color FROM " . DB_PREFIX . "[table] WHERE [id] = '" . (int)$id . "'");
	return array('[fieldname]' => $query->row);
}
