<?php

session_start();
require_once "functions.php";
$pdo=database();
$outgoing_id = $_SESSION['user']["id"];
$statement = $pdo->prepare( "SELECT username,id FROM users WHERE NOT id=:id");
$statement->execute(["id"=>$outgoing_id]);
$users=$statement->fetchAll();
$result = [];
foreach ($users as $user) {
    $result [$user["username"]] = $user["id"];
}

echo json_encode($result);