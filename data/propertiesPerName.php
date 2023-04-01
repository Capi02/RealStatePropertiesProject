<?php
include "../pages/includes/config/database.php";

try {
   $query = "SELECT * FROM properties INNER JOIN images_path ON images_path.id = properties.id INNER JOIN cities ON cities.id = properties.city_id WHERE cities.city = 'mcallen'";
   $st = $db->prepare($query);
   $st->execute();
   $rs = $st->fetchAll(PDO::FETCH_ASSOC);
   
   echo json_encode($rs);

} catch (PDOException $error) {
   echo $error->getMessage();
}
