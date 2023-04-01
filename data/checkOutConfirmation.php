<?php
require "../pages/includes/head.php";
require "../pages/includes/config/database.php";

$order_number = uniqid("ord-");
$user_id = $_POST["user_id"];
$property = $_POST["property_id"];
$subtotal = $_POST["property_price"];

try {
    
    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $cellphone = isset($_POST["cellphone"]) ? $_POST["cellphone"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";


    if ($username != "" || $cellphone != "" || $email != "") {
        $orderSuccess = true;

        $sql = $db->prepare("INSERT INTO orders (user_id, num_order, order_date, id_payment_method ) VALUES(?,?, NOW(), 1)");
        $sql->bindParam(1, $user_id);
        $sql->bindParam(2, $order_number);

        $sql->execute();
        $order_id = $db->lastInsertId();

        if ($orderSuccess) {
            $sql = ("INSERT INTO details (user_cellphone, subtotal, property_id, order_id) VALUES(?,?,?,?)");
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $cellphone);
            $stmt->bindParam(2, $subtotal);
            $stmt->bindParam(3, $property);
            $stmt->bindParam(4, $order_id);
            $stmt->execute() or die(print($stmt->$errorInfo()));

            echo json_encode("true");
        }
    }
} catch (PDOException $error) {
    echo $error;
    echo json_encode("false");
    die();
}
