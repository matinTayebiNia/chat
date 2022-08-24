<?php
session_start();
require_once "functions.php";
$errors = [];

$name = htmlspecialchars($_POST["RoomName"]);
$member = $_POST["Member"] ?? null;
$image = $_FILES["file"];

$errors["name"] = required($name, "نام اتاق");
$errors["member"] = required($member, "عضو");
$errors["name"] = array_merge($errors["name"], checkIsUnique($name, "name", "نام اتاق", "rooms"));
$img_name = "";
$img_type = "";
$tmp_name = "";

if (count($image) === 0) {
    $errors['file'] = ["file" => "فایل آپلود نشده"];
} else {
    $img_name = $image["name"];
    $img_type = $image["type"];
    $tmp_name = $image["tmp_name"];

    $img_explode = explode(".", $img_name);
    $img_ext = end($img_explode);
    $extensions = ["png", "jpg", "jpeg"];
    if (!in_array($img_ext, $extensions)) {
        $errors['file'] = ["file" => "پسوند فایل معتبر نیست"];
    }
}


if (NoneError($errors)) {
    $users = [];
    foreach ($member as $item) {
        $users[] = ["id" => intval($item), "role" => 1];
    }
    $users = array_merge($users, [["id"=>$_SESSION["user"]["id"], "role" => 3]]);

    $time = time();
    $new_image_name = $time . $img_name;
    $targetFile = "../public/uploads/" . $new_image_name;

    $pdo = database();
    var_dump($new_image_name);
    $statement = $pdo->prepare("insert into rooms (name,picture) values (:roomName,:prcture)");
    $statement->execute(["roomName" => $name, "prcture" => $new_image_name]);
    $result =  $pdo->lastInsertId();
    foreach ($users as $user) {

        $stmt = $pdo->prepare("insert into user_room (user_id,room_id,rule) values (:user_id,:room_id,:rule)");
        $stmt->bindValue("user_id",$user["id"]);
        $stmt->bindValue("rule", $user["role"]);
        $stmt->bindValue("room_id", $result);
        $stmt->execute();
    }
    if (move_uploaded_file($tmp_name, $targetFile))
        echo json_encode(["success" => true]);
    else
        echo json_encode(["errors" => [["server" => "مشکلی در آپلود عکس به وجود اکده لطفا بعدا امتحان کنید. "]]]);

    } else {
    foreach ($errors as $key => $value) {
        if (empty($value)) {
            unset($errors[$key]);
        }
    }
    echo json_encode(["errors" => $errors]);
}