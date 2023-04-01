<?php
include "../pages/includes/head.php";
include "../pages/includes/mobileNavBar.php";
include "../pages/includes/navBar.php";
require "../pages/includes/config/database.php"; /* DB connection */

try {
    // getting the order of the user
    $sql = "SELECT *, orders.id as id FROM orders INNER JOIN details ON details.order_id = orders.id INNER JOIN images_path on images_path.property_id = details.property_id WHERE orders.user_id = {$_SESSION["user_id"]}";
    $pdo = $db->prepare($sql);
    $pdo->execute();
    $rs = $pdo->fetchAll(PDO::FETCH_ASSOC);

    var_dump($rs);

} catch (PDOException $error) {
    echo $error;
}


?>

<section class="section" >
    <div class="container">
        <h1 class="text-center">Purchases</h1>
        

    </div> <!-- Container -->


</section> <!-- Section -->