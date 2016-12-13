<?php
class Madminsystemsetting extends Model {
	public function addsetting($data) {
	    $this->db->query("INSERT INTO " . DB_PREFIX . "setting(app_id,sgroup,skey,svalue,serialized) VALUES('" . (int)$this->db->escape($data['store_id']) . "', '" . $this->db->escape($data['sgroup']) . "', '" . $this->db->escape($data['skey']) . "', '" . $this->db->escape($data['svalue']) . "', '" . $this->db->escape($data['serialized']) . "')");
	}

	public function editsetting($setting_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET app_id = '" . (int)$this->db->escape($data['store_id']) . "', sgroup = '" . $this->db->escape($data['sgroup']) . "', skey = '" . $this->db->escape($data['skey']) . "',svalue = '" . $this->db->escape($data['svalue']) . "',serialized = '" . $this->db->escape($data['serialized']) . "' WHERE setting_id = '" . (int)$setting_id . "'");
	}

	public function deletesetting($setting_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE setting_id = '" . (int)$setting_id . "'");
	}
	
	public function getsetting($setting_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "setting WHERE setting_id='". $this->db->escape($setting_id)."'";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getsettings($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "setting a";
		
		$sort_data = array(
			'app_id',
			'sgroup',
			'skey',
			'svalue',
			'serialized',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY skey";
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

	public function getTotalsettings() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting");
		return $query->row['total'];
	}

}