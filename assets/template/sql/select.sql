public function [selecttable]() {
	$query = $this->db->query("SELECT [selectfield] FROM " . DB_PREFIX . "[selecttable]");
	$arr = array();
	foreach($query->rows as $data){
		$arr = array_merge($arr,array([selectarray]));
	}
	return $arr;
}
