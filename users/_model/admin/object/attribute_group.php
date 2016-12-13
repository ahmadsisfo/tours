<?php
class Madminobjectattributegroup extends Model {
	public function addattribute_group($data) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group(name,type) VALUES('" . $this->db->escape($data['name']) . "', '" . $this->db->escape($data['type'])  . "')");
	}

	public function editattribute_group($attribute_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "attribute_group SET name = '" . $this->db->escape($data['name']) . "', type = '" . $this->db->escape($data['type'])  . "' WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
	}

	public function deleteattribute_group($attribute_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_group WHERE attribute_group_id = '" . (int)$attribute_group_id . "'");
	}
	
	public function getattribute_group($attribute_group_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "attribute_group WHERE attribute_group_id='". $this->db->escape($attribute_group_id)."'";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getattribute_groups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "attribute_group";
		
		$sort_data = array(
			'name',
			'type'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
		}

		$query = $this->db->query($sql);
		
		//exit(json_encode($query->rows));
		return $query->rows;
	}

	public function getTotalattribute_groups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "attribute_group");

		return $query->row['total'];
	}

}