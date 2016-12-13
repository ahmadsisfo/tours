<?php
class Madmindataspatial extends Model {
	public function addspatial($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "spatial SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()");

		$spatial_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "spatial SET image = '" . $this->db->escape($data['image']) . "' WHERE spatial_id = '" . (int)$spatial_id . "'");
		}

		foreach ($data['spatial_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_description SET spatial_id = '" . (int)$spatial_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['spatial_store'])) {
			foreach ($data['spatial_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_to_store SET spatial_id = '" . (int)$spatial_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['spatial_attribute'])) {
			foreach ($data['spatial_attribute'] as $spatial_attribute) {
				if ($spatial_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_attribute WHERE spatial_id = '" . (int)$spatial_id . "' AND attribute_id = '" . (int)$spatial_attribute['attribute_id'] . "'");

					foreach ($spatial_attribute['spatial_attribute_description'] as $language_id => $spatial_attribute_description) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_attribute WHERE spatial_id = '" . (int)$spatial_id . "' AND attribute_id = '" . (int)$spatial_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'");

						$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_attribute SET spatial_id = '" . (int)$spatial_id . "', attribute_id = '" . (int)$spatial_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($spatial_attribute_description['text']) . "'");
					}
				}
			}
		}

		if (isset($data['spatial_option'])) {
			foreach ($data['spatial_option'] as $spatial_option) {
				if ($spatial_option['type'] == 'select' || $spatial_option['type'] == 'radio' || $spatial_option['type'] == 'checkbox' || $spatial_option['type'] == 'image') {
					if (isset($spatial_option['spatial_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_option SET spatial_id = '" . (int)$spatial_id . "', option_id = '" . (int)$spatial_option['option_id'] . "', required = '" . (int)$spatial_option['required'] . "'");

						$spatial_option_id = $this->db->getLastId();

						foreach ($spatial_option['spatial_option_value'] as $spatial_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_option_value SET spatial_option_id = '" . (int)$spatial_option_id . "', spatial_id = '" . (int)$spatial_id . "', option_id = '" . (int)$spatial_option['option_id'] . "', option_value_id = '" . (int)$spatial_option_value['option_value_id'] . "', quantity = '" . (int)$spatial_option_value['quantity'] . "', subtract = '" . (int)$spatial_option_value['subtract'] . "', price = '" . (float)$spatial_option_value['price'] . "', price_prefix = '" . $this->db->escape($spatial_option_value['price_prefix']) . "', points = '" . (int)$spatial_option_value['points'] . "', points_prefix = '" . $this->db->escape($spatial_option_value['points_prefix']) . "', weight = '" . (float)$spatial_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($spatial_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_option SET spatial_id = '" . (int)$spatial_id . "', option_id = '" . (int)$spatial_option['option_id'] . "', value = '" . $this->db->escape($spatial_option['value']) . "', required = '" . (int)$spatial_option['required'] . "'");
				}
			}
		}

		if (isset($data['spatial_discount'])) {
			foreach ($data['spatial_discount'] as $spatial_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_discount SET spatial_id = '" . (int)$spatial_id . "', customer_group_id = '" . (int)$spatial_discount['customer_group_id'] . "', quantity = '" . (int)$spatial_discount['quantity'] . "', priority = '" . (int)$spatial_discount['priority'] . "', price = '" . (float)$spatial_discount['price'] . "', date_start = '" . $this->db->escape($spatial_discount['date_start']) . "', date_end = '" . $this->db->escape($spatial_discount['date_end']) . "'");
			}
		}

		if (isset($data['spatial_special'])) {
			foreach ($data['spatial_special'] as $spatial_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_special SET spatial_id = '" . (int)$spatial_id . "', customer_group_id = '" . (int)$spatial_special['customer_group_id'] . "', priority = '" . (int)$spatial_special['priority'] . "', price = '" . (float)$spatial_special['price'] . "', date_start = '" . $this->db->escape($spatial_special['date_start']) . "', date_end = '" . $this->db->escape($spatial_special['date_end']) . "'");
			}
		}

		if (isset($data['spatial_image'])) {
			foreach ($data['spatial_image'] as $spatial_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_image SET spatial_id = '" . (int)$spatial_id . "', image = '" . $this->db->escape($spatial_image['image']) . "', sort_order = '" . (int)$spatial_image['sort_order'] . "'");
			}
		}

		if (isset($data['spatial_download'])) {
			foreach ($data['spatial_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_to_download SET spatial_id = '" . (int)$spatial_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['spatial_category'])) {
			foreach ($data['spatial_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_to_category SET spatial_id = '" . (int)$spatial_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['spatial_filter'])) {
			foreach ($data['spatial_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_filter SET spatial_id = '" . (int)$spatial_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		if (isset($data['spatial_related'])) {
			foreach ($data['spatial_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_related WHERE spatial_id = '" . (int)$spatial_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_related SET spatial_id = '" . (int)$spatial_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_related WHERE spatial_id = '" . (int)$related_id . "' AND related_id = '" . (int)$spatial_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_related SET spatial_id = '" . (int)$related_id . "', related_id = '" . (int)$spatial_id . "'");
			}
		}

		if (isset($data['spatial_reward'])) {
			foreach ($data['spatial_reward'] as $customer_group_id => $spatial_reward) {
				if ((int)$spatial_reward['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_reward SET spatial_id = '" . (int)$spatial_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$spatial_reward['points'] . "'");
				}
			}
		}

		if (isset($data['spatial_layout'])) {
			foreach ($data['spatial_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_to_layout SET spatial_id = '" . (int)$spatial_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'spatial_id=" . (int)$spatial_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		if (isset($data['spatial_recurrings'])) {
			foreach ($data['spatial_recurrings'] as $recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "spatial_recurring` SET `spatial_id` = " . (int)$spatial_id . ", customer_group_id = " . (int)$recurring['customer_group_id'] . ", `recurring_id` = " . (int)$recurring['recurring_id']);
			}
		}

		$this->cache->delete('spatial');

		return $spatial_id;
	}

	public function editspatial($spatial_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "spatial SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', ean = '" . $this->db->escape($data['ean']) . "', jan = '" . $this->db->escape($data['jan']) . "', isbn = '" . $this->db->escape($data['isbn']) . "', mpn = '" . $this->db->escape($data['mpn']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "spatial SET image = '" . $this->db->escape($data['image']) . "' WHERE spatial_id = '" . (int)$spatial_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_description WHERE spatial_id = '" . (int)$spatial_id . "'");

		foreach ($data['spatial_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_description SET spatial_id = '" . (int)$spatial_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_to_store WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_store'])) {
			foreach ($data['spatial_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_to_store SET spatial_id = '" . (int)$spatial_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_attribute WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (!empty($data['spatial_attribute'])) {
			foreach ($data['spatial_attribute'] as $spatial_attribute) {
				if ($spatial_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_attribute WHERE spatial_id = '" . (int)$spatial_id . "' AND attribute_id = '" . (int)$spatial_attribute['attribute_id'] . "'");

					foreach ($spatial_attribute['spatial_attribute_description'] as $language_id => $spatial_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_attribute SET spatial_id = '" . (int)$spatial_id . "', attribute_id = '" . (int)$spatial_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($spatial_attribute_description['text']) . "'");
					}
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_option WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_option_value WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_option'])) {
			foreach ($data['spatial_option'] as $spatial_option) {
				if ($spatial_option['type'] == 'select' || $spatial_option['type'] == 'radio' || $spatial_option['type'] == 'checkbox' || $spatial_option['type'] == 'image') {
					if (isset($spatial_option['spatial_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_option SET spatial_option_id = '" . (int)$spatial_option['spatial_option_id'] . "', spatial_id = '" . (int)$spatial_id . "', option_id = '" . (int)$spatial_option['option_id'] . "', required = '" . (int)$spatial_option['required'] . "'");

						$spatial_option_id = $this->db->getLastId();

						foreach ($spatial_option['spatial_option_value'] as $spatial_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_option_value SET spatial_option_value_id = '" . (int)$spatial_option_value['spatial_option_value_id'] . "', spatial_option_id = '" . (int)$spatial_option_id . "', spatial_id = '" . (int)$spatial_id . "', option_id = '" . (int)$spatial_option['option_id'] . "', option_value_id = '" . (int)$spatial_option_value['option_value_id'] . "', quantity = '" . (int)$spatial_option_value['quantity'] . "', subtract = '" . (int)$spatial_option_value['subtract'] . "', price = '" . (float)$spatial_option_value['price'] . "', price_prefix = '" . $this->db->escape($spatial_option_value['price_prefix']) . "', points = '" . (int)$spatial_option_value['points'] . "', points_prefix = '" . $this->db->escape($spatial_option_value['points_prefix']) . "', weight = '" . (float)$spatial_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($spatial_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_option SET spatial_option_id = '" . (int)$spatial_option['spatial_option_id'] . "', spatial_id = '" . (int)$spatial_id . "', option_id = '" . (int)$spatial_option['option_id'] . "', value = '" . $this->db->escape($spatial_option['value']) . "', required = '" . (int)$spatial_option['required'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_discount WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_discount'])) {
			foreach ($data['spatial_discount'] as $spatial_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_discount SET spatial_id = '" . (int)$spatial_id . "', customer_group_id = '" . (int)$spatial_discount['customer_group_id'] . "', quantity = '" . (int)$spatial_discount['quantity'] . "', priority = '" . (int)$spatial_discount['priority'] . "', price = '" . (float)$spatial_discount['price'] . "', date_start = '" . $this->db->escape($spatial_discount['date_start']) . "', date_end = '" . $this->db->escape($spatial_discount['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_special WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_special'])) {
			foreach ($data['spatial_special'] as $spatial_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_special SET spatial_id = '" . (int)$spatial_id . "', customer_group_id = '" . (int)$spatial_special['customer_group_id'] . "', priority = '" . (int)$spatial_special['priority'] . "', price = '" . (float)$spatial_special['price'] . "', date_start = '" . $this->db->escape($spatial_special['date_start']) . "', date_end = '" . $this->db->escape($spatial_special['date_end']) . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_image WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_image'])) {
			foreach ($data['spatial_image'] as $spatial_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_image SET spatial_id = '" . (int)$spatial_id . "', image = '" . $this->db->escape($spatial_image['image']) . "', sort_order = '" . (int)$spatial_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_to_download WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_download'])) {
			foreach ($data['spatial_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_to_download SET spatial_id = '" . (int)$spatial_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_to_category WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_category'])) {
			foreach ($data['spatial_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_to_category SET spatial_id = '" . (int)$spatial_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_filter WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_filter'])) {
			foreach ($data['spatial_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_filter SET spatial_id = '" . (int)$spatial_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_related WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_related WHERE related_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_related'])) {
			foreach ($data['spatial_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_related WHERE spatial_id = '" . (int)$spatial_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_related SET spatial_id = '" . (int)$spatial_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_related WHERE spatial_id = '" . (int)$related_id . "' AND related_id = '" . (int)$spatial_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_related SET spatial_id = '" . (int)$related_id . "', related_id = '" . (int)$spatial_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_reward WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_reward'])) {
			foreach ($data['spatial_reward'] as $customer_group_id => $value) {
				if ((int)$value['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_reward SET spatial_id = '" . (int)$spatial_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_to_layout WHERE spatial_id = '" . (int)$spatial_id . "'");

		if (isset($data['spatial_layout'])) {
			foreach ($data['spatial_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "spatial_to_layout SET spatial_id = '" . (int)$spatial_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'spatial_id=" . (int)$spatial_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'spatial_id=" . (int)$spatial_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "spatial_recurring` WHERE spatial_id = " . (int)$spatial_id);

		if (isset($data['spatial_recurring'])) {
			foreach ($data['spatial_recurring'] as $spatial_recurring) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "spatial_recurring` SET `spatial_id` = " . (int)$spatial_id . ", customer_group_id = " . (int)$spatial_recurring['customer_group_id'] . ", `recurring_id` = " . (int)$spatial_recurring['recurring_id']);
			}
		}

		$this->cache->delete('spatial');
	}

	public function copyspatial($spatial_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "spatial p WHERE p.spatial_id = '" . (int)$spatial_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data['spatial_attribute'] = $this->getspatialAttributes($spatial_id);
			$data['spatial_description'] = $this->getspatialDescriptions($spatial_id);
			$data['spatial_discount'] = $this->getspatialDiscounts($spatial_id);
			$data['spatial_filter'] = $this->getspatialFilters($spatial_id);
			$data['spatial_image'] = $this->getspatialImages($spatial_id);
			$data['spatial_option'] = $this->getspatialOptions($spatial_id);
			$data['spatial_related'] = $this->getspatialRelated($spatial_id);
			$data['spatial_reward'] = $this->getspatialRewards($spatial_id);
			$data['spatial_special'] = $this->getspatialSpecials($spatial_id);
			$data['spatial_category'] = $this->getspatialCategories($spatial_id);
			$data['spatial_download'] = $this->getspatialDownloads($spatial_id);
			$data['spatial_layout'] = $this->getspatialLayouts($spatial_id);
			$data['spatial_store'] = $this->getspatialStores($spatial_id);
			$data['spatial_recurrings'] = $this->getRecurrings($spatial_id);

			$this->addspatial($data);
		}
	}

	public function deletespatial($spatial_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_attribute WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_description WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_discount WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_filter WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_image WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_option WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_option_value WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_related WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_related WHERE related_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_reward WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_special WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_to_category WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_to_download WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_to_layout WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_to_store WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "spatial_recurring WHERE spatial_id = " . (int)$spatial_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE spatial_id = '" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'spatial_id=" . (int)$spatial_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_spatial WHERE spatial_id = '" . (int)$spatial_id . "'");

		$this->cache->delete('spatial');
	}

	public function getspatial($spatial_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'spatial_id=" . (int)$spatial_id . "') AS keyword FROM " . DB_PREFIX . "spatial p LEFT JOIN " . DB_PREFIX . "spatial_description pd ON (p.spatial_id = pd.spatial_id) WHERE p.spatial_id = '" . (int)$spatial_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getspatials($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "spatial p LEFT JOIN " . DB_PREFIX . "spatial_description pd ON (p.spatial_id = pd.spatial_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.spatial_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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

	public function getspatialsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial p LEFT JOIN " . DB_PREFIX . "spatial_description pd ON (p.spatial_id = pd.spatial_id) LEFT JOIN " . DB_PREFIX . "spatial_to_category p2c ON (p.spatial_id = p2c.spatial_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getspatialDescriptions($spatial_id) {
		$spatial_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_description WHERE spatial_id = '" . (int)$spatial_id . "'");

		foreach ($query->rows as $result) {
			$spatial_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $spatial_description_data;
	}

	public function getspatialCategories($spatial_id) {
		$spatial_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_to_category WHERE spatial_id = '" . (int)$spatial_id . "'");

		foreach ($query->rows as $result) {
			$spatial_category_data[] = $result['category_id'];
		}

		return $spatial_category_data;
	}

	public function getspatialFilters($spatial_id) {
		$spatial_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_filter WHERE spatial_id = '" . (int)$spatial_id . "'");

		foreach ($query->rows as $result) {
			$spatial_filter_data[] = $result['filter_id'];
		}

		return $spatial_filter_data;
	}

	public function getspatialAttributes($spatial_id) {
		$spatial_attribute_data = array();

		$spatial_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "spatial_attribute WHERE spatial_id = '" . (int)$spatial_id . "' GROUP BY attribute_id");

		foreach ($spatial_attribute_query->rows as $spatial_attribute) {
			$spatial_attribute_description_data = array();

			$spatial_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_attribute WHERE spatial_id = '" . (int)$spatial_id . "' AND attribute_id = '" . (int)$spatial_attribute['attribute_id'] . "'");

			foreach ($spatial_attribute_description_query->rows as $spatial_attribute_description) {
				$spatial_attribute_description_data[$spatial_attribute_description['language_id']] = array('text' => $spatial_attribute_description['text']);
			}

			$spatial_attribute_data[] = array(
				'attribute_id'                  => $spatial_attribute['attribute_id'],
				'spatial_attribute_description' => $spatial_attribute_description_data
			);
		}

		return $spatial_attribute_data;
	}

	public function getspatialOptions($spatial_id) {
		$spatial_option_data = array();

		$spatial_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "spatial_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.spatial_id = '" . (int)$spatial_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($spatial_option_query->rows as $spatial_option) {
			$spatial_option_value_data = array();

			$spatial_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_option_value WHERE spatial_option_id = '" . (int)$spatial_option['spatial_option_id'] . "'");

			foreach ($spatial_option_value_query->rows as $spatial_option_value) {
				$spatial_option_value_data[] = array(
					'spatial_option_value_id' => $spatial_option_value['spatial_option_value_id'],
					'option_value_id'         => $spatial_option_value['option_value_id'],
					'quantity'                => $spatial_option_value['quantity'],
					'subtract'                => $spatial_option_value['subtract'],
					'price'                   => $spatial_option_value['price'],
					'price_prefix'            => $spatial_option_value['price_prefix'],
					'points'                  => $spatial_option_value['points'],
					'points_prefix'           => $spatial_option_value['points_prefix'],
					'weight'                  => $spatial_option_value['weight'],
					'weight_prefix'           => $spatial_option_value['weight_prefix']
				);
			}

			$spatial_option_data[] = array(
				'spatial_option_id'    => $spatial_option['spatial_option_id'],
				'spatial_option_value' => $spatial_option_value_data,
				'option_id'            => $spatial_option['option_id'],
				'name'                 => $spatial_option['name'],
				'type'                 => $spatial_option['type'],
				'value'                => $spatial_option['value'],
				'required'             => $spatial_option['required']
			);
		}

		return $spatial_option_data;
	}

	public function getspatialOptionValue($spatial_id, $spatial_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "spatial_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.spatial_id = '" . (int)$spatial_id . "' AND pov.spatial_option_value_id = '" . (int)$spatial_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getspatialImages($spatial_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_image WHERE spatial_id = '" . (int)$spatial_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getspatialDiscounts($spatial_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_discount WHERE spatial_id = '" . (int)$spatial_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getspatialSpecials($spatial_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_special WHERE spatial_id = '" . (int)$spatial_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getspatialRewards($spatial_id) {
		$spatial_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_reward WHERE spatial_id = '" . (int)$spatial_id . "'");

		foreach ($query->rows as $result) {
			$spatial_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $spatial_reward_data;
	}

	public function getspatialDownloads($spatial_id) {
		$spatial_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_to_download WHERE spatial_id = '" . (int)$spatial_id . "'");

		foreach ($query->rows as $result) {
			$spatial_download_data[] = $result['download_id'];
		}

		return $spatial_download_data;
	}

	public function getspatialStores($spatial_id) {
		$spatial_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_to_store WHERE spatial_id = '" . (int)$spatial_id . "'");

		foreach ($query->rows as $result) {
			$spatial_store_data[] = $result['store_id'];
		}

		return $spatial_store_data;
	}

	public function getspatialLayouts($spatial_id) {
		$spatial_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_to_layout WHERE spatial_id = '" . (int)$spatial_id . "'");

		foreach ($query->rows as $result) {
			$spatial_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $spatial_layout_data;
	}

	public function getspatialRelated($spatial_id) {
		$spatial_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spatial_related WHERE spatial_id = '" . (int)$spatial_id . "'");

		foreach ($query->rows as $result) {
			$spatial_related_data[] = $result['related_id'];
		}

		return $spatial_related_data;
	}

	public function getRecurrings($spatial_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "spatial_recurring` WHERE spatial_id = '" . (int)$spatial_id . "'");

		return $query->rows;
	}

	public function getTotalspatials($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.spatial_id) AS total FROM " . DB_PREFIX . "spatial p LEFT JOIN " . DB_PREFIX . "spatial_description pd ON (p.spatial_id = pd.spatial_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalspatialsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spatial WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalspatialsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spatial WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalspatialsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spatial WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalspatialsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spatial WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalspatialsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spatial_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalspatialsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spatial WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalspatialsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spatial_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalspatialsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spatial_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalspatialsByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spatial_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalspatialsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spatial_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
