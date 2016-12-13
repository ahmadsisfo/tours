<?php
class MpgsqlInstall extends Model {
	public function database($data) {
		$db = new _DB($data['db_driver'], $data['db_hostname'], $data['db_username'], $data['db_password'], $data['db_database']);
		
		if($data['db_driver']=="pgsql"){
			$db->query("INSERT INTO " . $data['db_prefix'] . "user(user_id,user_group_id,username,password, firstname, lastname, email, status, date_added) VALUES('1', '1', '" . $db->escape($data['username']) . "', '" . $db->escape(md5($data['password'])) . "', 'Adminis', 'trator', '" . $db->escape($data['email']) . "', '1', NOW())");

		}
		
	}
}
