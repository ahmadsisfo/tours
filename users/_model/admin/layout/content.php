<?php
class MAdminLayoutContent extends Model {
	public function addLayout($data) {
		//$this->event->trigger('pre.admin.add.layout', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "layout_type SET name = '" . $this->db->escape($data['name']) . "'");

		$layout_id = $this->db->getLastId();

		if (isset($data['layout_content'])) {
			foreach ($data['layout_content'] as $content) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_content` SET `layout_type_id` = '" . (int)$layout_id . "', `title` = '" . $this->db->escape($content['title']) . "',`image` = '" . $this->db->escape($content['image']) . "',`description` = '" . $this->db->escape($content['description']) . "', `key` = '" . $this->db->escape($content['key']) . "', `value` = '" . $this->db->escape($content['value']) . "'");
			}
		}
	
		//$this->event->trigger('post.admin.add.layout', $layout_id);

		return $layout_id;
	}

	public function editLayout($layout_id, $data) {
		//$this->event->trigger('pre.admin.edit.layout', $data);

		$this->db->query("UPDATE `" . DB_PREFIX . "layout_type` SET `name` = '" . $this->db->escape($data['name']) . "' WHERE `layout_type_id` = '" . (int)$layout_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_content` WHERE `layout_type_id` = '" . (int)$layout_id . "'");

		if (isset($data['layout_content'])) {
			foreach ($data['layout_content'] as $content) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_content` SET `layout_type_id` = '" . (int)$layout_id . "', `title` = '" . $this->db->escape($content['title']) . "',`image` = '" . $this->db->escape($content['image']) . "',`description` = '" . $this->db->escape($content['description']) . "', `key` = '" . $this->db->escape($content['key']) . "', `value` = '" . $this->db->escape($content['value']) . "'");
			}
		}
		
		//$this->event->trigger('post.admin.edit.layout', $layout_id);
	}

	public function deleteLayout($layout_id) {
		//$this->event->trigger('pre.admin.delete.layout', $layout_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_type WHERE layout_type_id = '" . (int)$layout_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "layout_content WHERE layout_type_id = '" . (int)$layout_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "layout_module WHERE layout_id = '" . (int)$layout_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
		//$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		//$this->event->trigger('post.admin.delete.layout', $layout_id);
	}

	public function getLayout($layout_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "layout_type WHERE layout_type_id = '" . (int)$layout_id . "'");

		return $query->row;
	}

	public function getLayouts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "layout_type";
		
		$sort_data = array('name');

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

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getLayoutContents($layout_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_content WHERE layout_type_id = '" . (int)$layout_id . "'");

		return $query->rows;
	}
	
	public function getTotalLayouts() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "layout_type");

		return $query->row['total'];
	}
}