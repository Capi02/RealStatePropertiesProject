<?php
include "../pages/includes/config/database.php";

try {
   $query = "SELECT *, properties.id AS id FROM properties INNER JOIN images_path ON images_path.id = properties.id INNER JOIN cities on cities.id = properties.city_id ORDER BY properties.id ASC;";
   $st = $db->prepare($query);
   $st->execute();
   $rs = $st->fetchAll(PDO::FETCH_ASSOC);
   

   echo json_encode($rs);


} catch (PDOException $error) {
   echo $error->getMessage();
}
