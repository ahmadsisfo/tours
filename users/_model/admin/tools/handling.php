<?php
class Madmintoolshandling extends Model {
	public function addhandling($data) {
		$table = $data['table'];
		$field = '';
		$primarykey ='';
		if(isset($data['layout_content'])){
			$i=0;
			foreach($data['layout_content'] as $lay){
				$field .= $lay['fieldname'].' '.$lay['fieldtype'].','; 
				if(isset($lay['primary'])){
					$primarykey = $lay['fieldname'];
				}
			}
		}
		$query = $this->db->query("SELECT COUNT(relname) as a FROM pg_class WHERE relname='".DB_PREFIX . $table."'");
		if($query->row['a']){
			$this->db->query("DROP TABLE ".DB_PREFIX . $table);
			$this->db->query("CREATE TABLE ".DB_PREFIX. $table."
				(
				  ".$field."
				  CONSTRAINT ".$table."_pkey PRIMARY KEY (".$primarykey.")
				)
				WITH (
				  OIDS=FALSE
				);
				ALTER TABLE ".DB_PREFIX.$table."
				  OWNER TO postgres;
			");
		} else {
			$this->db->query("CREATE TABLE ".DB_PREFIX. $table."
				(
				  ".$field."
				  CONSTRAINT ".$table."_pkey PRIMARY KEY (".$primarykey.")
				)
				WITH (
				  OIDS=FALSE
				);
				ALTER TABLE ".DB_PREFIX.$table."
				  OWNER TO postgres;
			");
			
		}
	}
}

/*
-- DROP TABLE tb_wisata_jenis;

CREATE TABLE tb_wisata_jenis
(
  wisata_jenis_id serial NOT NULL,
  name character varying(100),
  ket text,
  CONSTRAINT tb_wisata_jenis_pkey PRIMARY KEY (wisata_jenis_id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE tb_wisata_jenis
  OWNER TO postgres;
*/