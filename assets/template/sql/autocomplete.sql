public function [autocompletetable]($data) {
	$sql  =  "SELECT [autocompletefield] FROM " . DB_PREFIX . "[autocompletetable] WHERE [autocompletefilter] ";
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
	$arr = array();
	foreach($query->rows as $data){
		$arr = array_merge($arr,array([autocompletearray]));
	}
	return $arr;
}

public function update[autocompletetabletarget]($id,$data){
	if (isset($data['[inputname]'])) {
		$this->db->query("DELETE FROM ".DB_PREFIX."[autocompletetabletarget] WHERE [id]='" . (int)$id . "'");
		foreach ($data['[inputname]'] as $item){
			$this->db->query("INSERT INTO " . DB_PREFIX . "[autocompletetabletarget]([autocompletefieldtarget]) VALUES(
			'".(int)$id."',
			'".(int)$this->db->escape($item['value'])."')");
		}	
	}
}

public function select[autocompletetabletarget]($id){
	$query = $this->db->query("SELECT a.[key] as key,a.[value] as value FROM " . DB_PREFIX . "[autocompletetable] a, " . DB_PREFIX . "[autocompletetabletarget] b WHERE a.[value]=b.[value] AND b.[id] = '" . (int)$id . "'");
	return array('[fieldname]' => $query->rows);
}