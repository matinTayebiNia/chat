<?php
$errors = [];
require_once "functions.php";
$comment_id = $_POST["comment_id"];
$room_id = $_POST["room_id"];
$errors["comment_id"] = required($comment_id, "کد کامنت");

if (NoneError($errors)) {
    $pdo=database();
    $stmt=$pdo->prepare("DELETE FROM comments WHERE id= :comment_id;");
    $stmt->bindValue("comment_id",$comment_id);
    $stmt->execute();
    header("location: ../chat.php?room_id=$room_id");
}