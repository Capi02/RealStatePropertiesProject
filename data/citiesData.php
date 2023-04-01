<?php
include "../pages/includes/config/database.php";

$sql = "SELECT * FROM cities"; //getting all the cities
$stmt = $db->prepare($sql);
$stmt->execute();
$rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($rs); //converting it in json format
