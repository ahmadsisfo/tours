<?php
class Madmintoolsbackup extends Model {
	public function restore($sql) {
		foreach (explode(";\n", $sql) as $sql) {
			$sql = trim($sql);
			if ($sql) {
				$sql = preg_replace('#(CREATE TABLE IF NOT EXISTS `)'.DB_PREFIX.'(.*?)#', '$1$2', $sql);
				$sql = preg_replace('#(INSERT INTO `)'.DB_PREFIX.'(.*?)#', '$1$2', $sql);
				$sql = preg_replace('#(CREATE TABLE IF NOT EXISTS `)(.*?`)#','$1'.DB_PREFIX.'$2', $sql);
				$sql = preg_replace('#(INSERT INTO `)(.*?`)#','$1'.DB_PREFIX.'$2', $sql);
				$this->db->query($sql);
			}
		}
		$this->cache->delete('*');
	}

	public function getTables() {
		$table_data = array();
		$query = $this->db->query("SHOW TABLES FROM `" . DB_DBASE . "`");
		foreach ($query->rows as $result) {
			if (substr($result['Tables_in_' . DB_DBASE], 0, strlen(DB_PREFIX)) == DB_PREFIX) {
				if (isset($result['Tables_in_' . DB_DBASE])) {
					$table_data[] = $result['Tables_in_' . DB_DBASE];
				}
			}
		}
		return $table_data;
	}

	public function backup($tables) {
		//$this->event->trigger('pre.admin.backup', $tables);
		$output = '';
		foreach ($tables as $table) {
			if (DB_PREFIX) {
				if (strpos($table, DB_PREFIX) === false) {
					$status = false;
				} else {
					$status = true;
				}
			} else {
				$status = true;
			}

			if ($status) {
				$output .= 'TRUNCATE TABLE `' . $table . '`;' . "\n\n";

				$query = $this->db->query("SELECT * FROM `" . $table . "`");

				foreach ($query->rows as $result) {
					$fields = '';

					foreach (array_keys($result) as $value) {
						$fields .= '`' . $value . '`, ';
					}

					$values = '';

					foreach (array_values($result) as $value) {
						$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
						$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
						$value = str_replace('\\', '\\\\',	$value);
						$value = str_replace('\'', '\\\'',	$value);
						$value = str_replace('\\\n', '\n',	$value);
						$value = str_replace('\\\r', '\r',	$value);
						$value = str_replace('\\\t', '\t',	$value);

						$values .= '\'' . $value . '\', ';
					}

					$output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
				}

				$output .= "\n\n";
			}
		}
		//$this->event->trigger('post.admin.backup');
		return $output;
	}
}