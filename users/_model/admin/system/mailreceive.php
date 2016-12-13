<?php
class MAdminsystemmailreceive extends Model {
	public function addMailreceive($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "mail_receive SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW(), emailfrom = '" . $this->db->escape(strtolower($data['emailfrom'])) . "', namefrom = '" . $this->db->escape($data['namefrom']) . "', phone = '" . $this->db->escape($data['phone']) . "', emailto = '" . $this->db->escape($data['emailto']) . "', subject = '" . $this->db->escape($data['subject']) . "', message = '" . $this->db->escape($data['message']) . "', status = '1'");

		$mail_id = $this->db->getLastId();

		return $mail_id;
	}

	public function deleteLayout($layout_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "mail_receive WHERE mail_receive_id = '" . (int)$mail_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "layout_content WHERE layout_type_id = '" . (int)$layout_id . "'");
	}

	public function getMailreceive($mail_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "mail_receive WHERE mail_receive_id = '" . (int)$mail_id . "'");

		return $query->row;
	}

	public function getMailreceives($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "mail_receive";
		
		$sort_data = array('name');

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
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

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalMailreceive() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "mail_receive");

		return $query->row['total'];
	}
}