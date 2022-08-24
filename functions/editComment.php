<?php

require_once "functions.php";

$errors = [];

$editComment = $_POST["chatEdit"];
$chat_id = $_POST["chat_id"];
$room_id = $_POST["room_id"];

$errors["editComment"] = required($editComment, "متن پیام");
$errors["editComment"] = array_merge($errors["editComment"], maxLength($editComment, 100, "متن پیام"));

if (NoneError($errors)) {
    $pdo = database();
    $stmt = $pdo->prepare("update comments set comment=:editComment where id=:id ");
    $stmt->bindValue("editComment", $editComment);
    $stmt->bindValue("id", $chat_id);
    $stmt->execute();
    header("location: ../chat.php?room_id=$room_id");
} else {
    foreach ($errors as $key => $value) {
        if (empty($value)) {
            unset($errors[$key]);
        }
    }
    echo json_encode(["errors" => $errors]);
}