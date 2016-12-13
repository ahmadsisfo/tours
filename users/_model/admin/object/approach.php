<?php
class Madminobjectapproach extends Model {
	public function addapproach($data) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "approach(keyword) VALUES('" . $this->db->escape($data['keyword']) . "')");
	}

	public function editapproach($approach_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "approach SET keyword = '" . $this->db->escape($data['keyword']) . "' WHERE approach_id = '" . (int)$approach_id . "'");
	}

	public function deleteapproach($approach_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "approach WHERE approach_id = '" . (int)$approach_id . "'");
	}
	
	public function getapproach($approach_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "approach WHERE approach_id='". $this->db->escape($approach_id)."'";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getapproachs($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "approach a";
		
		$sort_data = array(
			'keyword',
			
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY " . $sort_data[0];
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

	public function getTotalapproachs() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "approach");

		return $query->row['total'];
	}

}