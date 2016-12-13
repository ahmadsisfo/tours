<?php
class Madminobjectfiltergroup extends Model {
	public function addfilter_group($data) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "filter_group(name,sort_order) VALUES('" . $this->db->escape($data['name']) . "', '" . (int)$data['sort_order'] . "')");
	}

	public function editfilter_group($filter_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "filter_group SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE filter_group_id = '" . (int)$filter_group_id . "'");
	}

	public function deletefilter_group($filter_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_group WHERE filter_group_id = '" . (int)$filter_group_id . "'");
	}
	
	public function getfilter_group($filter_group_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "filter_group WHERE filter_group_id='". $this->db->escape($filter_group_id)."'";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getfilter_groups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "filter_group";
		
		$sort_data = array(
			'name',
			'sort_order'
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

	public function getTotalfilter_groups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "filter_group");

		return $query->row['total'];
	}

}