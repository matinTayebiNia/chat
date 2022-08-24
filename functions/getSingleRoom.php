<?php
session_start();
require_once "functions.php";

$user = $_SESSION["user"];
$room_id = $_POST["room_id"];

$sql = "
    select users.id,users.username,
    user_room.user_id,user_room.room_id,user_room.rule,rooms.picture,rooms.name
    from users inner  join  user_room 
    on user_room.user_id=users.id
    inner join rooms
    on user_room.room_id=rooms.id
    where user_room.room_id=:room_id 
";
$pdo = database();
$stmt = $pdo->prepare($sql);
$stmt->bindValue("room_id", $room_id);
$stmt->execute();
$room = $stmt->fetchAll();
$users = [];
for ($i = 0; $i < count($room); $i++) {
    $users [] = ["username" => $room[$i]["username"], "id" => $room[$i]["id"],];
    unset($room[$i]["username"]);
    unset($room[$i]["id"]);
}
$room=$room[0];

$room["users"] = $users;
echo json_encode($room);