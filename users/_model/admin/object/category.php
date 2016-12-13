<?php
class Madminobjectcategory extends Model {
	public function addcategory($data) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "category(name,parent_id,image,status) VALUES('" . $this->db->escape($data['name']) . "', '" . (int)$data['parent_id'] . "', '" . $this->db->escape($data['image']) . "', '" . (int)$data['status'] . "')");
	}

	public function editcategory($category_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "category SET name = '" . $this->db->escape($data['name']) . "', parent_id = '" . (int)$data['parent_id'] . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "' WHERE category_id = '" . (int)$category_id . "'");
	}

	public function deletecategory($category_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
	}

	public function getparents($data = array()) {
		$sql = "SELECT category_id AS parent_id, name AS name FROM " . DB_PREFIX . "category";
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getcategory($category_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "category WHERE category_id='". $this->db->escape($category_id)."'";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getcategorys($data = array()) {
		$sql = "SELECT *, (SELECT name as parent FROM ".DB_PREFIX."category ca WHERE ca.category_id = cb.parent_id) FROM " . DB_PREFIX . "category cb";
		
		$sort_data = array(
			'name',
			'status',
			'image'
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

	public function getTotalcategorys() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

		return $query->row['total'];
	}

}