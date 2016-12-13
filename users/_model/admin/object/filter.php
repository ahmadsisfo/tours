<?php
class Madminobjectfilter extends Model {
	public function addfilter($data) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "filter(name,filter_group_id,sort_order) VALUES('" . $this->db->escape($data['name']) . "', '" . $this->db->escape($data['filter_group_id']) . "', '" . $this->db->escape($data['sort_order']) . "')");
	}

	public function editfilter($filter_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "filter SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE filter_id = '" . (int)$filter_id . "'");
	}

	public function deletefilter($filter_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter WHERE filter_id = '" . (int)$filter_id . "'");
	}
	
	public function getfilter($filter_id){
		$sql = "SELECT *,(SELECT name FROM " . DB_PREFIX . "filter_group b WHERE b.filter_group_id=a.filter_group_id) as filter_group FROM " . DB_PREFIX . "filter a WHERE a.filter_id='". $this->db->escape($filter_id)."'";
		$query = $this->db->query($sql);
		//exit(json_encode($query->rows));
		return $query->rows;
	}

	public function getfilters($data = array()) {
		$sql = "SELECT *,(SELECT name FROM " . DB_PREFIX . "filter_group b WHERE b.filter_group_id=a.filter_group_id ) as filter_group FROM " . DB_PREFIX . "filter a [search]";
		if (!empty($data['filter_name'])) {
			$sql = str_replace("[search]"," WHERE upper(a.name) LIKE upper('%" . $this->db->escape($data['filter_name']) . "%')", $sql);
		} else {
			$sql = str_replace("[search]","", $sql);
		}
		
		
		
		$sort_data = array(
			'name',
			'filter_group',
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

	public function getTotalfilters() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "filter");

		return $query->row['total'];
	}

}