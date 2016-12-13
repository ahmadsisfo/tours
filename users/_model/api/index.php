<?php
class Mapiindex extends Model {
	private $tokm = 1000;
	
	public function migration(){
		$query = $this->db->query("SELECT ukm_id,image FROM ".DB_PREFIX."ukm");
		foreach ($query->rows as $item){
			$this->db->query("UPDATE ".DB_PREFIX."ukm SET image_thumb = '".$item['image']."' WHERE ukm_id='".$item['ukm_id']."'");
			echo'success';
		}
		
	}
	
	public function getCategory(){
		$result = array();
		$query = $this->db->query("SELECT * FROM ".DB_PREFIX."objek");
		$result = $query->rows;
		
		$arrtable = array("ukm","resto","wisata","penginapan");
		$sql_rating = "";
		$sql_views  = "";
		foreach($arrtable as $table){		
			if($sql_rating != ""){
				$sql_rating .= " UNION ALL ";
			}
			if($sql_views != ""){
				$sql_views .= " UNION ALL ";
			}
			$sql_rating .= " SELECT '".$table."' as kategori,'TOP RATING' as top, a.".$table."_id as id,name,a.phone, address, a.image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city, (SELECT AVG(rating) as rating FROM ".DB_PREFIX.$table."_rating b WHERE a.".$table."_id=b.".$table."_id) FROM ".DB_PREFIX.$table." a ";
			$sql_views .= " SELECT '".$table."' as kategori,'TOP VIEWER' as top, a.".$table."_id as id,name,a.phone, address, a.image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city, views FROM ".DB_PREFIX.$table." a  ";
		}
		$sql_rating .= " ORDER BY rating DESC NULLS LAST LIMIT 1";
		$sql_views  .= " ORDER BY views DESC NULLS LAST LIMIT 1";
		$qry_rating = $this->db->query($sql_rating);
		$qry_views = $this->db->query($sql_views);
		$result[] = $qry_rating->row;
		$result[] = $qry_views->row;
		
		return $result;
	}
	
	public function updateTracking($data){
		if($data['user_id']!=""){
			$check = $this->db->query("SELECT activity,last_point FROM ".DB_PREFIX."user WHERE user_id='".$data['user_id']."'");
			if($check->row['activity']!=null && $check->row['last_point']!=null){
				
				//Out door
				$sql="";
				$arrtable = array("ukm","resto","wisata","penginapan");
				foreach($arrtable as $table){		
					if($sql != ""){
						$sql .= " UNION ALL ";
					}
					$sql .= " SELECT  name FROM ".DB_PREFIX.$table." a WHERE (SELECT ST_Intersects(ST_MakePoint(".$data['latlng']."),ST_Buffer(a.geom,0.0001)) as status )=true AND (SELECT ST_Contains(ST_MakePoint(".$data['latlng']."),a.geom) as status )=false ";
					
				}
				$visited = $this->db->query($sql);
				if($visited->num_rows){
					$this->db->query("UPDATE ".DB_PREFIX."user SET start_time_transit='NOW()'  WHERE user_id=".(int)$data['user_id']." AND start_time_transit IS NULL ");
					$this->db->query("UPDATE ".DB_PREFIX."user SET end_time_transit='NOW()' WHERE user_id=".(int)$data['user_id']." ");
					
				} else {
					$ch = $this->db->query("SELECT last_point, start_time_transit as start, age(NOW(),start_time_transit) as timer FROM ".DB_PREFIX."user WHERE user_id='".$data['user_id']."' AND start_time_transit IS NOT NULL");
					if($ch->num_rows){
						foreach($ch->rows as $us){
							switch($data['tracking_mode']){
								case '1' : $timing = '00:00:10'; break;
								case '2' : $timing = '00:30:00'; break;
								case '3' : $timing = '00:10:00'; break;
								default  : $timing = '00:20:00'; break;
							}
							if(strtotime($us['timer'])>strtotime($timing)){
								$this->db->query("INSERT INTO ".DB_PREFIX."user_visit(user_id,geom,start_time_visited,end_time_visited) VALUES(".(int)$data['user_id'].",'".$us['last_point']."','".$us['start']."','NOW()') ");
							}
						}
					}
					$this->db->query("UPDATE ".DB_PREFIX."user SET start_time_transit=NULL, end_time_transit=NULL WHERE user_id=".(int)$data['user_id']."");
				}
				
				$this->db->query("UPDATE ".DB_PREFIX."user SET activity=ST_AddPoint(activity, ST_MakePoint(".$data['latlng'].")),last_point=ST_MakePoint(".$data['latlng'].") WHERE user_id='".$data['user_id']."' AND ST_Distance(last_point,st_makepoint(".$data['latlng']."))>=0.00001 ");
				
				//In door
				$sql="";
				$arrtable = array("ukm","resto","wisata","penginapan");
				foreach($arrtable as $table){		
					if($sql != ""){
						$sql .= " UNION ALL ";
					}
					$sql .= " SELECT  name FROM ".DB_PREFIX.$table." a WHERE (SELECT ST_Contains(ST_MakePoint(".$data['latlng']."),a.geom) as status )=true  ";					
				}
				$visited2 = $this->db->query($sql);
				if($visited2->num_rows){
					$this->db->query("UPDATE ".DB_PREFIX."user SET start_time_transit='NOW()'  WHERE user_id=".(int)$data['user_id']." AND start_time_transit IS NULL ");
					$this->db->query("UPDATE ".DB_PREFIX."user SET end_time_transit='NOW()' WHERE user_id=".(int)$data['user_id']." ");
				} else {
					$ch = $this->db->query("SELECT last_point, start_time_transit as start, age(NOW(),start_time_transit) as timer FROM ".DB_PREFIX."user WHERE user_id='".$data['user_id']."' AND start_time_transit IS NOT NULL");
					if($ch->num_rows){
						foreach($ch->rows as $us){
							$this->db->query("INSERT INTO ".DB_PREFIX."user_visit(user_id,geom,start_time_visited,end_time_visited) VALUES(".(int)$data['user_id'].",'".$us['last_point']."','".$us['start']."','NOW()') ");
						}
					}
					$this->db->query("UPDATE ".DB_PREFIX."user SET start_time_transit=NULL, end_time_transit=NULL WHERE user_id=".(int)$data['user_id']."");
				}
				
			} else if($check->row['last_point']!= null) {
				$this->db->query("UPDATE ".DB_PREFIX."user SET activity=ST_MakeLine(last_point,ST_MakePoint(".$data['latlng'].")),last_point=ST_MakePoint(".$data['latlng'].") WHERE user_id='".$data['user_id']."' AND ST_Distance(last_point,st_makepoint(".$data['latlng']."))>0.00001 ");
				
			} else {
				$this->db->query("UPDATE ".DB_PREFIX."user SET last_point=ST_MakePoint(".$data['latlng'].") WHERE user_id='".$data['user_id']."'");
			}
			
			$user_id = $data['user_id'];
			$sql="";
			$arrtable = array("ukm","resto","wisata","penginapan");
			foreach($arrtable as $table){		
				if($sql != ""){
					$sql .= " UNION ALL ";
				}
				$sql .= " SELECT '".$table."' as kategori,'intersect' as intersect, a.".$table."_id as id,name,a.phone, address, a.image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city FROM ".DB_PREFIX.$table." a WHERE (ST_Intersects(st_makepoint(".$data['latlng']."),ST_Buffer(a.geom,0.0003)))=true GROUP BY id ";
			}
			$qry = $this->db->query($sql);
			return $qry->row;
		}
	}
	
	public function rate($data = array()){
		if(isset($data['kategori'])){
			$this->db->query("UPDATE ".DB_PREFIX.$data['kategori']."_rating SET rating='".$this->db->escape($data['rating'])."',date_modified='NOW()' WHERE ".$data['kategori']."_id='".$this->db->escape($data['objek_id'])."' AND user_id='".$this->db->escape($data['user_id'])."'");
			
			$this->generateSimiliarityInternal($data);
			$this->generatePrediction($data);
			$this->generateSimiliarityExternal($data);
		}
	}
	
	public function getlinestring($data){
		$qry = $this->db->query("
			 SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features 
					FROM (SELECT 'Feature' As type , ST_AsGeoJSON(a.activity)::json As geometry , row_to_json((SELECT l 
						FROM (SELECT a.username) As l )) As properties 
							FROM ".DB_PREFIX."user As a  WHERE user_id=".(int)$data['user_id']."
						) As f ");
		$json = str_replace('\"','"',json_encode($qry->row));
		$json = str_replace('"[','[', $json);
		$json = str_replace(']"',']', $json);
		return $json;
	}
	
	public function getpolygon($data){
		$qry = $this->db->query("
			 SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features 
					FROM (SELECT 'Feature' As type , ST_AsGeoJSON(a.geom)::json As geometry , row_to_json((SELECT l 
						FROM (SELECT a.name) As l )) As properties 
							FROM ".DB_PREFIX.$data['kategori']." As a  WHERE ".$data['kategori']."_id=".(int)$data['objek_id']."
						) As f ");
		$json = str_replace('\"','"',json_encode($qry->row));
		$json = str_replace('"[','[', $json);
		$json = str_replace(']"',']', $json);
		return $json;
	}
	
	public function getRecommendationByObjek($data){
		//echo'bismillah';
		$ignore = array('penginapan','wisata','resto','ukm');
		$ignore_rel = array(
			'penginapan_rel_resto',
			'penginapan_rel_ukm',
			'penginapan_rel_wisata',
			'resto_rel_ukm',
			'wisata_rel_resto',
			'wisata_rel_ukm');
		$recommended = array();
		
		if(isset($data['kategori'])&&isset($data['objek_id'])){
						
			foreach($ignore as $table){
				$field = "row_number() over (order by name) as no, '".$table."' as kategori, a.".$table."_id as id, name, phone, address, image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city";
				$sql   = "SELECT ".$field.",'nearby' as nearby FROM ".DB_PREFIX.$table." a WHERE a.".$table."_id NOT IN(".(int)$data['objek_id'].") ORDER BY geom <-> (SELECT geom as current FROM ".DB_PREFIX.$data['kategori']." WHERE ".$data['kategori']."_id=".(int)$data['objek_id'].") ASC  LIMIT 1 ";
				$qry   = $this->db->query($sql);
				$recommended[] = $qry->row;
			}
			
			$data_kategori = $data['kategori'];
			
			$recommended_bysim = array();
			$recommended_byviews = array();
			foreach($ignore as $objek){
				if($data_kategori!=$objek){
					$ketemu = false;
					if(in_array($data_kategori.'_rel_'.$objek, $ignore_rel)){
						$table = $data_kategori.'_rel_'.$objek;
						$ketemu = true;
					} else if(in_array($objek.'_rel_'.$data_kategori, $ignore_rel)) {
						$table = $objek.'_rel_'.$data_kategori;
						$ketemu = true;
					}
					
					if($ketemu){
						$field = "row_number() over (order by name) as no, '".$objek."' as kategori, a.".$objek."_id as id, name, phone, address, image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city";
						$query = $this->db->query("SELECT ".$field.",'related' as related FROM ".DB_PREFIX.$objek." a WHERE a.".$objek."_id NOT IN(".(int)$data['objek_id'].") ORDER BY (SELECT sim_slope FROM ".DB_PREFIX.$table." b WHERE a.".$objek."_id = b.".$objek."_id AND ".$data_kategori."_id=".(int)$data['objek_id'].") DESC  LIMIT 1 ");
						$recommended_bysim[] = $query->row;				
						
						$field = "row_number() over (order by name) as no, '".$objek."' as kategori, a.".$objek."_id as id, name, phone, address, image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city";
						$query = $this->db->query("SELECT ".$field.",'viewer' as viewer FROM ".DB_PREFIX.$objek." a WHERE a.".$objek."_id NOT IN(".(int)$data['objek_id'].") ORDER BY (SELECT views FROM ".DB_PREFIX.$table." b WHERE a.".$objek."_id = b.".$objek."_id AND ".$data_kategori."_id=".(int)$data['objek_id'].") DESC  LIMIT 1 ");
						$recommended_byviews[] = $query->row;									
					}
				} else {
					$field = "row_number() over (order by name) as no, '".$objek."' as kategori, a.".$objek."_id as id, name, phone, address, image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city";
					$query = $this->db->query("SELECT ".$field.",'related' as related FROM ".DB_PREFIX.$objek." a WHERE a.".$objek."_id NOT IN(".(int)$data['objek_id'].")  ORDER BY (SELECT sim_slope FROM ".DB_PREFIX.$objek."_rel b WHERE (b.".$objek."_id=".(int)$data['objek_id']." OR a.".$objek."_id=b.".$objek."_id) AND (b.".$objek."_id2=".(int)$data['objek_id']." OR b.".$objek."_id2=a.".$objek."_id)) DESC  LIMIT 1 ");
					$recommended_bysim[] = $query->row;
					
					$field = "row_number() over (order by name) as no, '".$objek."' as kategori, a.".$objek."_id as id, name, phone, address, image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city";
					$query = $this->db->query("SELECT ".$field.",'viewer' as viewer FROM ".DB_PREFIX.$objek." a WHERE a.".$objek."_id NOT IN(".(int)$data['objek_id'].") ORDER BY (SELECT sim_slope FROM ".DB_PREFIX.$objek."_rel b WHERE (b.".$objek."_id=".(int)$data['objek_id']." OR a.".$objek."_id=b.".$objek."_id) AND (b.".$objek."_id2=".(int)$data['objek_id']." OR b.".$objek."_id2=a.".$objek."_id)) DESC  LIMIT 1 ");
					$recommended_byviews[] = $query->row;
				}
			}
			$zord = array_merge($recommended_bysim,$recommended_byviews);
			$result = array_merge($recommended,$zord);
			return $result;
		}
		
	}
	
	public function getRecommendation($data){
		//echo'bismillah';
		$recommended = array();
		$data['user_id'] = isset($data['user_id'])? $data['user_id']:0;
		$data['user_id'] = ($data['user_id']!="")? $data['user_id']:0;
		if(isset($data['kategori'])&&isset($data['latlng'])){
			
			$table = $data['kategori'];
			$latlng= explode(",",$data['latlng']);
			$latlng= $latlng[0]." ".$latlng[1];
			$field = "row_number() over (order by name) as no, '".$table."' as kategori, a.".$table."_id as id, name, phone, address, image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, ST_Distance(geom,ST_GeomFromText('POINT(".$latlng.")'))/1000 as km, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city";
			
			if($data['user_id']){
				$rating_sql = "SELECT ".$field.", AVG(rating) as rating, AVG(predict_rating) as predict FROM ".DB_PREFIX.$table." a LEFT JOIN ".DB_PREFIX.$data['kategori']."_rating b ON a.".$table."_id=b.".$table."_id AND b.user_id='".$data['user_id']."' GROUP BY id ORDER BY predict DESC NULLS LAST, rating DESC NULLS LAST LIMIT 7";
			} else {
				$rating_sql = "SELECT ".$field.", (SELECT AVG(rating) as rating FROM ".DB_PREFIX.$table."_rating b WHERE a.".$table."_id=b.".$table."_id),(SELECT AVG(predict_rating) as predict FROM ".DB_PREFIX.$table."_rating b WHERE a.".$table."_id=b.".$table."_id)	FROM ".DB_PREFIX.$table." a ORDER BY rating DESC NULLS LAST, predict DESC NULLS LAST LIMIT 7";
			}
			
			
			$sql = array(
				"rating" 	=> $rating_sql,
				"jarak" 	=> "SELECT ".$field." FROM ".DB_PREFIX.$table." a ORDER BY geom <-> st_makepoint(".$data['latlng'].") ASC  LIMIT 7",
				
				"views" 	=> "SELECT ".$field.", (SELECT SUM(views) FROM ".DB_PREFIX.$data['kategori']."_rating b WHERE a.".$table."_id=b.".$table."_id) as sum FROM ".DB_PREFIX.$table." a ORDER BY sum DESC LIMIT 7",
				
				"byrating" 	=> "SELECT ".$field." FROM ".DB_PREFIX.$table." a WHERE ".$table."_id IN ([byrating]) AND ".$table."_id NOT IN ([notin]) ORDER BY geom <-> st_makepoint(".$data['latlng'].") ASC  LIMIT 1",
				
				"byviews" 	=> "SELECT ".$field." FROM ".DB_PREFIX.$table." a WHERE ".$table."_id IN ([byviews]) AND ".$table."_id NOT IN ([notin]) ORDER BY geom <-> st_makepoint(".$data['latlng'].") ASC  LIMIT 1",
				
				//"byjarak" 	=> "SELECT ".$field.", (SELECT AVG(rating) as rating FROM ".DB_PREFIX.$data['kategori']."_rating b WHERE a.".$table."_id=b.".$table."_id), (SELECT AVG(predict_rating) as predict FROM ".DB_PREFIX.$data['kategori']."_rating b WHERE a.".$table."_id=b.".$table."_id) FROM ".DB_PREFIX.$table." a WHERE ".$table."_id IN ([byjarak]) AND ".$table."_id NOT IN ([notin])  ORDER BY rating DESC NULLS LAST, predict DESC NULLS LAST LIMIT 1",
				
				//"xbyviews" 	=> "SELECT ".$field.", (SELECT AVG(rating) as rating FROM ".DB_PREFIX.$data['kategori']."_rating b WHERE a.".$table."_id=b.".$table."_id), (SELECT AVG(predict_rating) as predict FROM ".DB_PREFIX.$data['kategori']."_rating b WHERE a.".$table."_id=b.".$table."_id) FROM ".DB_PREFIX.$table." a WHERE ".$table."_id IN ([xbyviews]) AND ".$table."_id NOT IN ([notin])  ORDER BY rating DESC NULLS LAST, predict DESC NULLS LAST LIMIT 1",
				
				//"xbyjarak" 	=> "SELECT ".$field.", (SELECT SUM(views) FROM ".DB_PREFIX.$data['kategori']."_rating b WHERE a.".$table."_id=b.".$table."_id) as sum FROM ".DB_PREFIX.$table." a WHERE ".$table."_id IN ([xbyjarak]) AND ".$table."_id NOT IN ([notin]) ORDER BY sum DESC LIMIT 1",
				
				//"xbyrating" => "SELECT ".$field.", (SELECT SUM(views) FROM ".DB_PREFIX.$data['kategori']."_rating b WHERE a.".$table."_id=b.".$table."_id) as sum FROM ".DB_PREFIX.$table." a WHERE ".$table."_id IN ([xbyrating]) AND ".$table."_id NOT IN ([notin]) ORDER BY sum DESC LIMIT 1",
			);
			
			$in = array();
			$notin	= "";
			$id     = array();
			foreach($sql as $key => $value){
				$utama = false;
				if($key=="jarak"||$key=="rating"||$key=="views"||$key=="rating_user"){
					$utama = true; 
				} else {
					$index = str_replace("by","",$key);
					$index = str_replace("x","",$index);
					$value = str_replace("[".$key."]", $in[$index], $value);
					$value = str_replace("[notin]", $notin, $value);
				}
				$qry = $this->db->query($value);
				if(isset($qry->row['id'])&&!in_array($qry->row['id'],$id)){
					$recommended[] = $qry->row;
					$id[] 		   = $qry->row['id'];
					if($notin != ""){$notin.=",";}
					$notin        .= $qry->row['id'];
				}
				if($utama){
					$in[$key] = "";
					foreach($qry->rows as $row){
						if($in[$key] != ""){$in[$key] .= ",";}
						$in[$key] .= $row['id'];
					}
				}
			}
		}
		return $recommended;
	}
	
	public function getRateByObjekId($data){
		if(isset($data['kategori'])&&isset($data['objek_id'])){
			
			$qry = $this->db->query("SELECT AVG(rating), COUNT(rating), SUM(views),
				(SELECT COUNT(case when rating=0 then rating end) as s0),
				(SELECT COUNT(case when rating=1 then rating end) as s1),
				(SELECT COUNT(case when rating=2 then rating end) as s2),
				(SELECT COUNT(case when rating=3 then rating end) as s3),
				(SELECT COUNT(case when rating=4 then rating end) as s4),
				(SELECT COUNT(case when rating=5 then rating end) as s5)
			FROM ".DB_PREFIX.$data['kategori']."_rating z WHERE z.".$data['kategori']."_id='".$data['objek_id']."' ");
			
			$ignore_rel = array(
			'penginapan_rel_resto',
			'penginapan_rel_ukm',
			'penginapan_rel_wisata',
			'resto_rel_ukm',
			'wisata_rel_resto',
			'wisata_rel_ukm');
			
			if(isset($data['kategori_2'])){
				$this->db->query("UPDATE ".DB_PREFIX.$data['kategori']." SET views = (views+1) WHERE ".$data['kategori']."_id='".(int)$data['objek_id']."'");	
				$this->db->query("UPDATE ".DB_PREFIX.$data['kategori']."_rating SET views = (views+1) WHERE ".$data['kategori']."_id='".(int)$data['objek_id']."' AND user_id='".(int)$data['user_id']."'");	
				
				if($data['kategori_1'] != "" && $data['objek_id_1']!=""){
					if($data['kategori']==$data['kategori_1']){
						$this->db->query("UPDATE ".DB_PREFIX.$data['kategori']."_rel SET views = (views+1) WHERE 
							(".$data['kategori']."_id='".(int)$data['objek_id_1']."' OR ".$data['kategori']."_id='".(int)$data['objek_id']."') AND 
							(".$data['kategori']."_id2='".(int)$data['objek_id']."' OR ".$data['kategori']."_id2='".(int)$data['objek_id_1']."')
						");	
					} else {
						$ketemu = false;
						if(in_array($data['kategori_1'].'_rel_'.$data['kategori'], $ignore_rel)){
							$table  = $data['kategori_1'].'_rel_'.$data['kategori'];
							$ketemu = true;
						} else if(in_array($data['kategori'].'_rel_'.$data['kategori_1'], $ignore_rel)) {
							$table  = $data['kategori'].'_rel_'.$data['kategori_1'];
							$ketemu = true;
						}
						if($ketemu){
							$this->db->query("UPDATE ".DB_PREFIX.$table." SET views = (views+1) WHERE 
								".$data['kategori']."_id='".(int)$data['objek_id']."' AND ".$data['kategori_1']."_id='".(int)$data['objek_id_1']."'
							");	
						}
					}
				} 
				
				if($data['kategori_2'] != "" && $data['objek_id_2']!=""){
					if($data['kategori']==$data['kategori_2']){
						$this->db->query("UPDATE ".DB_PREFIX.$data['kategori']."_rel SET views = (views+1) WHERE 
							(".$data['kategori']."_id='".(int)$data['objek_id_2']."' OR ".$data['kategori']."_id='".(int)$data['objek_id']."') AND 
							(".$data['kategori']."_id2='".(int)$data['objek_id']."' OR ".$data['kategori']."_id2='".(int)$data['objek_id_2']."')
						");	
					} else {
						$ketemu = false;
						if(in_array($data['kategori_2'].'_rel_'.$data['kategori'], $ignore_rel)){
							$table  = $data['kategori_2'].'_rel_'.$data['kategori'];
							$ketemu = true;
						} else if(in_array($data['kategori'].'_rel_'.$data['kategori_2'], $ignore_rel)) {
							$table  = $data['kategori'].'_rel_'.$data['kategori_2'];
							$ketemu = true;
						}
						if($ketemu){
							$this->db->query("UPDATE ".DB_PREFIX.$table." SET views = (views+1) WHERE 
								".$data['kategori']."_id='".(int)$data['objek_id']."' AND ".$data['kategori_2']."_id='".(int)$data['objek_id_2']."'
							");	
						}
					}
				} 
			}
			return $qry->row;	
		}else{
			return false;
		}
	}
	
	public function getintersect($data){
		if(isset($data['user_id'])&& $data['user_id']!=""){
			$user_id = $data['user_id'];
			$sql="";
			$arrtable = array("ukm","resto","wisata","penginapan");
			foreach($arrtable as $table){		
				$in = $this->db->query("SELECT  st_y(st_centroid(geom)) as lat,st_x(st_centroid(geom)) as lng FROM ".DB_PREFIX."user_visit WHERE user_id=".(int)$data['user_id']."");
				foreach($in->rows as $row){
					if($sql != ""){
						$sql .= " UNION ALL ";
					}
					//$sql .= " SELECT '".$table."' as kategori,'intersect' as intersect, a.".$table."_id as id,name,a.phone, address, a.image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city FROM ".DB_PREFIX.$table." a WHERE (SELECT ST_Intersects(ST_MakeLine(b.activity),ST_Buffer(a.geom,0.0001)) as status FROM ".DB_PREFIX."user b WHERE b.user_id=".(int)$user_id.")=true GROUP BY id ";
					$sql .= " SELECT '".$table."' as kategori,'intersect' as intersect, a.".$table."_id as id,name,a.phone, address, a.image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city FROM ".DB_PREFIX.$table." a WHERE (SELECT ST_Intersects(ST_MakePoint(".$row['lng'].",".$row['lat']."),ST_Buffer(a.geom,0.0001)) as status)=true GROUP BY id ";
					
				}
				
			}
			//echo $sql;
			if($sql != ""){
				$qry = $this->db->query($sql);
				//echo$sql;
				return $qry->rows;
			} else {
				
				return array();
			}
		} else {
			
		}
	}
	
	public function getSearch($data = array(),$pagination=false){
		$latlng= explode(",",$data['latlng']);
		$latlng= $latlng[0]." ".$latlng[1];
		
		$inter  = array();
		if($pagination == false){
			if(isset($data['user_id'])&& $data['user_id']!=""){
				$inter = $this->getintersect($data);
				$notin = array();
				foreach($inter as $item){
					if(!isset($notin[$item['kategori']])){
						$notin[$item['kategori']] = $item['id'];
					} else {
						$notin[$item['kategori']] .= ",".$item['id'];
					}
				}
			}
		}
		
		$field = "";
		$leftjoin = "";
		$condition = "";
		$table = $data['filter_kategori'];
		if(empty($data['filter_rating'])==0.0||empty($data['filter_rating'])==0.0){
			$data['filter_rating'] = null;
		}
		$where  = false;
		
		if(isset($data['filter_kategori']) && $data['filter_kategori']!="" && $data['filter_kategori']!="all"){
			if(!empty($data['filter_rating'])){
				$field = ", AVG(m.rating) ";
				$leftjoin = " LEFT JOIN ".DB_PREFIX.$table."_rating m  ON a.".$table."_id=m.".$table."_id AND m.rating>=".$data['filter_rating']." ";
				$condition = " WHERE (SELECT AVG(m.rating) FROM ".DB_PREFIX.$table."_rating m  WHERE a.".$table."_id=m.".$table."_id AND m.rating>=".$data['filter_rating'].") IS NOT NULL";
				$where = true;
			}
			$ignore = "";
			if(isset($notin[$table])){
				if(!$where){
					$ignore .= " WHERE "; $where = true;
				} else {
					$ignore .= " AND ";
				}
				$ignore .= $table."_id NOT IN (".$notin[$table].")";
			}
			$sql = "SELECT row_number() over (order by name) as no, '".$table."' as kategori, a.".$table."_id as id,name,phone, address, image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city".$field." FROM ".DB_PREFIX.$table." a ".$leftjoin." ".$condition." ".$ignore." [search] GROUP BY id";
		} else {
			$sql="";
			$arrtable = array("penginapan","ukm","resto","wisata");
			foreach($arrtable as $table){
				if(!empty($data['filter_rating'])){
					$field = ", AVG(m.rating) ";
					$leftjoin = " LEFT JOIN ".DB_PREFIX.$table."_rating m  ON a.".$table."_id=m.".$table."_id AND m.rating>=".$data['filter_rating']." ";
					$condition = " WHERE (SELECT AVG(m.rating) FROM ".DB_PREFIX.$table."_rating m  WHERE a.".$table."_id=m.".$table."_id AND m.rating>=".$data['filter_rating'].") IS NOT NULL";
					$where = true;
				}
				$ignore = "";
				if(isset($notin[$table])){
					if(!$where){
						$ignore .= " WHERE ";
					} else {
						$ignore .= " AND ";
					}
					$ignore .= $table."_id NOT IN (".$notin[$table].")";
				}
				if($sql != ""){
					$sql .= " UNION ALL ";
				}
				$sql .= " SELECT '".$table."' as kategori, a.".$table."_id as id,name,phone, address, image as gambar, image_thumb as thumb, (st_y(st_centroid(geom)),st_x(st_centroid(geom))) as point, (SELECT name FROM ".DB_PREFIX."city z WHERE ST_contains(z.geom, a.geom)) as city".$field." FROM ".DB_PREFIX.$table." a ".$leftjoin." ".$condition." ".$ignore." [search] GROUP BY id ";
			}
		}
		
		$filter = '';
		if (!empty($data['filter_name'])) {
			if(!$where){
				$filter .= " WHERE "; $where = true;
			} else {
				$filter .= " AND ";
			}
			$filter .= " (upper(name) LIKE upper('%" . $this->db->escape($data['filter_name']) . "%')";
			$filter .= " OR upper(address) LIKE upper('%" . $this->db->escape($data['filter_name']) . "%')";
			$filter .= " OR upper(phone) LIKE upper('%" . $this->db->escape($data['filter_name']) . "%'))";
		}
		if (!empty($data['filter_radius'])&&!empty($data['latlng'])) {
			if(!$where){
				$filter .= " WHERE ";
			} else {
				$filter .= " AND ";
			}
			$filter .= "ST_Distance_Sphere(geom, ST_GeomFromText('POINT(".$latlng.")'))/".$this->tokm." <= (".(float)$data['filter_radius'].")";
		}
		
		$sql = ($filter)? str_replace('[search]',$filter,$sql): str_replace('[search]','',$sql);
		$sort_data = array('name','address','phone');
		$sql .= (isset($data['sort']) && in_array($data['sort'], $sort_data))? " ORDER BY " . $data['sort']:" ORDER BY name";
		$sql .= (isset($data['order']) && ($data['order'] == 'DESC'))? " DESC":" ASC";
		
		if($pagination==true){
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) $data['start'] = 0;
				if ($data['limit'] < 1) $data['limit'] = 20;
				
				switch(DB_DRIVER){
					case 'pgsql': $sql .= " LIMIT " . (int)$data['limit'] . " OFFSET " . (int)$data['start'];
						break;
					default 	: 	$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}
			}
		}
		//exit($sql);
		$query = $this->db->query($sql);
		return array_merge($inter,$query->rows);
		
		
	}
	
	public function getParticipant($data){
		if(isset($data['email_config'])&&isset($data['reg_id'])&&isset($data['username'])&&isset($data['picture'])){
			$quser = $this->db->query("SELECT * FROM ".DB_PREFIX."user WHERE user_group_id != 1 AND email='".$this->db->escape($data['email_config'])."' AND password='".md5($this->db->escape($data['reg_id']))."'");
			if ($quser->num_rows) {
			} else {
				$this->db->query("INSERT INTO ".DB_PREFIX."user(username,email,password,image,ip,status,date_added,user_group_id) VALUES(
					'".$this->db->escape($data['username'])."',
					'".$this->db->escape($data['email_config'])."',
					'".md5($this->db->escape($data['reg_id']))."',
					'".$this->db->escape($data['picture'])."',
					'".$this->db->escape($this->request->server['REMOTE_ADDR'])."',
					1,'NOW()',2
				)");
				//$this->generateToursByUser();
				$quser = $this->db->query("SELECT * FROM ".DB_PREFIX."user WHERE user_group_id != 1 AND user_id='".(int)$this->db->getLastId()."'");
				
			}
			return $quser->row;
		} else {
			return false;
		}
	}
	
	public function generateSimiliarityInternal($data){
		$ignore = array('resto','ukm','penginapan','wisata');
		$temp 	 	= array();
		$recorder	= array();
		$table 	 	= $data['kategori'];
		$getuser = $this->db->query("SELECT user_id FROM ".DB_PREFIX."user");
		foreach ($getuser->rows as $user){
			if(isset($data['kategori']) && in_array($data['kategori'],$ignore)){
				$query = $this->db->query("SELECT ".$table."_id as id, ".$table."_id2 as id2 FROM ".DB_PREFIX.$table."_rel a ");
				foreach($query->rows as $item){					
					$ratingI = $this->db->query("SELECT rating, (SELECT AVG(b.rating) FROM ".DB_PREFIX.$table."_rating b WHERE b.user_id = '".(int)$user['user_id']."') as rata FROM ".DB_PREFIX.$table."_rating a WHERE user_id='".(int)$user['user_id']."' AND rating IS NOT NULL AND ".$table."_id='".$item['id']."'");
					$ratingJ = $this->db->query("SELECT rating, (SELECT AVG(b.rating) FROM ".DB_PREFIX.$table."_rating b WHERE b.user_id = '".(int)$user['user_id']."') as rata FROM ".DB_PREFIX.$table."_rating a WHERE user_id='".(int)$user['user_id']."' AND rating IS NOT NULL AND ".$table."_id='".$item['id2']."'");		
					if(($ratingI->num_rows) && ($ratingJ->num_rows)){
						$gabungan_rating = array_merge($ratingI->rows,$ratingJ->rows);
						$pasangan        = $item['id'].'&'.$item['id2'];
						if(!in_array($pasangan,$recorder)){
							$recorder[] = $pasangan;
						} 
						$temp[$pasangan][] = $gabungan_rating;	
					}
				}
			}
		}
		
		$sim = $this->getSimiliarityValue($temp);
		$this->db->query("UPDATE ".DB_PREFIX.$table."_rel SET sim_slope='0'");
		foreach($sim as $key=>$value){
			$key = explode("&",$key);
			$this->db->query("UPDATE ".DB_PREFIX.$table."_rel SET sim_slope='".$value."' WHERE ".$table."_id='".$key[0]."' AND ".$table."_id2='".$key[1]."'");
		}			
	}
	
	public function generateSimiliarityExternal($data){
		$ignore = array('resto','ukm','penginapan','wisata');
		$ignore_rel = array(
			'penginapan_rel_resto',
			'penginapan_rel_ukm',
			'penginapan_rel_wisata',
			'resto_rel_ukm',
			'wisata_rel_resto',
			'wisata_rel_ukm');
		
		
		$getuser = $this->db->query("SELECT user_id FROM ".DB_PREFIX."user");
		foreach ($getuser->rows as $user){
			if(isset($data['kategori']) && in_array($data['kategori'],$ignore)){
				$data_kategori = $data['kategori'];
				foreach($ignore as $objek){
					if($data_kategori!=$objek){
						$ketemu = false;
						if(in_array($data_kategori.'_rel_'.$objek, $ignore_rel)){
							$table = $data_kategori.'_rel_'.$objek;
							$ketemu = true;
						} else if(in_array($objek.'_rel_'.$data_kategori, $ignore_rel)) {
							$table = $objek.'_rel_'.$data_kategori;
							$ketemu = true;
						}
						
						if($ketemu){
							$temp 	 	= array();
							$query = $this->db->query("SELECT ".$objek."_id, ".$data_kategori."_id FROM ".DB_PREFIX.$table." WHERE ".$data_kategori."_id='".$data['objek_id']."'");
							foreach($query->rows as $item){					
								$ratingI = $this->db->query("SELECT rating, (SELECT AVG(b.rating) FROM ".DB_PREFIX.$objek."_rating b WHERE b.user_id = '".(int)$user['user_id']."') as rata FROM ".DB_PREFIX.$objek."_rating a WHERE user_id='".(int)$user['user_id']."' AND rating IS NOT NULL AND ".$objek."_id='".$item[$objek.'_id']."'");
								$ratingJ = $this->db->query("SELECT rating, (SELECT AVG(b.rating) FROM ".DB_PREFIX.$data_kategori."_rating b WHERE b.user_id = '".(int)$user['user_id']."') as rata FROM ".DB_PREFIX.$data_kategori."_rating a WHERE user_id='".(int)$user['user_id']."' AND rating IS NOT NULL AND ".$data_kategori."_id='".$item[$data_kategori.'_id']."'");		
								if(($ratingI->num_rows) && ($ratingJ->num_rows)){
									$gabungan_rating = array_merge($ratingI->rows,$ratingJ->rows);
									$pasangan        = $item[$objek.'_id'].'&'.$item[$data_kategori.'_id'];
									$temp[$pasangan][] = $gabungan_rating;	
								}
							}
							$sim = $this->getSimiliarityValue($temp);
							$this->db->query("UPDATE ".DB_PREFIX.$table." SET sim_slope='0'");
							foreach($sim as $key=>$value){
								$key = explode("&",$key);
								$this->db->query("UPDATE ".DB_PREFIX.$table." SET sim_slope='".$value."' WHERE ".$objek."_id='".$key[0]."' AND ".$data_kategori."_id='".$key[1]."'");
							}		
						}
					}
				}
			}
		}	
	}
	
	public function getSimiliarityValue($temp = array()){
		$sim = array();
		foreach($temp as $key => $value){
			(float)$Eueu[$key]  = 0;
			(float)$PRui[$key]  = 1.0;
			(float)$ERui1[$key] = 0;
			(float)$ERui2[$key] = 0;
			(float)$VERui[$key] = 0;
			(float)$sim[$key]	= 0;
			foreach ($value as $item){
				$ganjil = true;
				foreach($item as $Rui){
					//echo$string = (float)$Rui['rating']."-".(float)$Rui['rata']."=";
					$PRui[$key] = $PRui[$key] *  ((float)$Rui['rating']-(float)$Rui['rata']);
					//echo"<br/>";
					if($ganjil){
						$ERui1[$key]	= $ERui1[$key] + pow(((float)$Rui['rating']-(float)$Rui['rata']),2);
						$ganjil = false;
					}else{
						$ERui2[$key]	= $ERui2[$key] + pow(((float)$Rui['rating']-(float)$Rui['rata']),2);
						$ganjil = true;
					}
				}
				$Eueu[$key]  = $Eueu[$key] + $PRui[$key]; 
				//echo"<br/>";
			}
			//echo$Eueu[$key];
			//echo"<br/>";
			$VERui[$key] = sqrt($ERui1[$key]) * sqrt($ERui2[$key]);
			//echo"<br/>";
			if($VERui[$key]!=0){
				$sim[$key] = round($Eueu[$key]/$VERui[$key],2);
			} 
			//echo $sim[$key];
			//echo"<br/><hr/>";
		}
		return $sim;
	}
	
	public function generatePrediction($data){
		$ignore = array('resto','ukm','penginapan','wisata');
		
		$temp 	 	= array();
		$recorder	= array();
		$table 	 	= $data['kategori'];
		$getuser = $this->db->query("SELECT user_id FROM ".DB_PREFIX."user");
		foreach ($getuser->rows as $user){
			if(isset($data['kategori']) && in_array($data['kategori'],$ignore)){
				$query = $this->db->query("SELECT ".$table."_id as id FROM ".DB_PREFIX.$table." ORDER BY id");
				
				foreach($query->rows as $item){
					(float)$ERuis = 0;
					(float)$Eis	  = 0;	
					(float)$PiA	  = 0;	

					$rating = $this->db->query("SELECT a.user_id,a.rating,a.".$table."_id, b.sim_slope as sim
					FROM ".DB_PREFIX.$table."_rating a 
					LEFT JOIN ".DB_PREFIX.$table."_rel b ON b.".$table."_id = '".$item['id']."' AND b.".$table."_id2=a.".$table."_id AND b.sim_slope>0  
					WHERE a.user_id='".$user['user_id']."' AND a.".$table."_id !='".$item['id']."' AND b.sim_slope IS NOT NULL");
					foreach($rating->rows as $rate){
						$ERuis = $ERuis + ((float)$rate['rating']*(float)$rate['sim']);
						$Eis   = $Eis + (float)$rate['sim'];
					}
					if($Eis	!= 0){
						$PiA = round($ERuis/$Eis,2);	
					}
					echo $PiA."<br/>";
					$this->db->query("UPDATE ".DB_PREFIX.$table."_rating SET predict_rating = '".$PiA."' WHERE ".$table."_id ='".$item['id']."' AND user_id='".$user['user_id']."'");
				}
				
			}
		}
	}
	
	public function generateToursByUser(){
		$ignore = array('resto','ukm','penginapan','wisata');
		$getuser = $this->db->query("SELECT user_id FROM ".DB_PREFIX."user");
		foreach ($getuser->rows as $user){
			foreach ($ignore as $data_kategori){
				$kategori = $this->db->query("SELECT ".$data_kategori."_id FROM ".DB_PREFIX.$data_kategori);
				foreach($kategori->rows as $item){
					$check = $this->db->query("SELECT * FROM ".DB_PREFIX.$data_kategori."_rating WHERE ".$data_kategori."_id='".(int)$item[$data_kategori.'_id']."' AND user_id='".$user['user_id']."'");
					if(!$check->num_rows){
						$this->db->query("INSERT INTO ".DB_PREFIX.$data_kategori."_rating(".$data_kategori."_id,user_id) VALUES('".(int)$item[$data_kategori.'_id']."','".(int)$user['user_id']."')");
					} 
				}
			}
		}
	}
	
	public function generateToursByObjek(){
		$ignore = array('resto','ukm','penginapan','wisata');
		$ignore_rel = array(
			'penginapan_rel_resto',
			'penginapan_rel_ukm',
			'penginapan_rel_wisata',
			'resto_rel_ukm',
			'wisata_rel_resto',
			'wisata_rel_ukm');
		
		foreach ($ignore as $data_kategori){
			$kategori = $this->db->query("SELECT ".$data_kategori."_id FROM ".DB_PREFIX.$data_kategori);
			foreach($kategori->rows as $item){
				foreach($kategori->rows as $item2){
					if($item[$data_kategori.'_id']!= $item2[$data_kategori.'_id']){
						$ch = $this->db->query("SELECT * FROM ".DB_PREFIX.$data_kategori."_rel WHERE 
							(".$data_kategori."_id='".(int)$item[$data_kategori.'_id']."' OR ".$data_kategori."_id='".(int)$item2[$data_kategori.'_id']."') AND 
							(".$data_kategori."_id2='".(int)$item[$data_kategori.'_id']."' OR ".$data_kategori."_id2='".(int)$item2[$data_kategori.'_id']."')");
						if(!$ch->num_rows){
							$this->db->query("INSERT INTO ".DB_PREFIX.$data_kategori."_rel(".$data_kategori."_id,".$data_kategori."_id2) VALUES('".(int)$item[$data_kategori.'_id']."','".(int)$item2[$data_kategori.'_id']."')");
						}
					}
				}
				foreach($ignore as $objek){
					if($data_kategori!=$objek){
						$ketemu = false;
						if(in_array($data_kategori.'_rel_'.$objek, $ignore_rel)){
							$table = $data_kategori.'_rel_'.$objek;
							$ketemu = true;
						} else if(in_array($objek.'_rel_'.$data_kategori, $ignore_rel)) {
							$table = $objek.'_rel_'.$data_kategori;
							$ketemu = true;
						}
						
						if($ketemu){
							$qry = $this->db->query("SELECT ".$objek."_id FROM ".DB_PREFIX.$objek);
							foreach($qry->rows as $list){
								$che = $this->db->query("SELECT * FROM ".DB_PREFIX.$table." WHERE 
									".$data_kategori."_id='".(int)$item[$data_kategori.'_id']."' AND 
									".$objek."_id='".(int)$list[$objek.'_id']."' ");
								if(!$che->num_rows){
									$this->db->query("INSERT INTO ".DB_PREFIX.$table."(".$data_kategori."_id,".$objek."_id) VALUES('".(int)$item[$data_kategori.'_id']."','".(int)$list[$objek.'_id']."')");
								}
							}
						}
					}
				}
				
			}
		}
	}
}
?>