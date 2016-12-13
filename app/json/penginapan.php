<?php
require '../connect.php';

$querysearch="SELECT row_to_json(fc)
	FROM ( 
		SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features FROM (SELECT 'Feature' As type
		, ST_AsGeoJSON(lg.geom)::json As geometry
		FROM penginapan As lg 
        ) As f 
	)  As fc;
	";

$hasil=pg_query($querysearch);
while($data=pg_fetch_array($hasil)){
	$load=$data['row_to_json'];
}
echo $load;
?>

