<?php
$errors = [];
require_once "functions.php";
$message = htmlspecialchars($_POST["message"]);
$user_id = htmlspecialchars($_POST["message_user_id"]);
$room_id = htmlspecialchars($_POST["room_id"]);

$errors["message"] = required($message, "پیام");
$errors["message"] = array_merge($errors["message"], maxLength($message, 100, "پیام"));


if (NoneError($errors)) {

    $stmt = addComment($user_id, $message, $room_id);

    if ( $stmt->execute()) {
        echo json_encode(["success" => true]);
    }

}
