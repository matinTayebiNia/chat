<?php
session_start();

require_once "functions.php";

if (!$_SESSION["user"]) {
    header("location: index.php");
}
$errors = [];
$user_id = $_SESSION["user"]["id"];
$user_username = $_SESSION["user"]["username"];
$room_id_upload = $_POST["room_id_upload"];
$uploadImage = $_FILES["uploadImage"];

$img_name = "";
$img_type = "";
$tmp_name = "";

if (count($uploadImage) === 0) {
    $errors['file'] = ["file" => "فایل آپلود نشده"];
} else {
    $img_name = $uploadImage["name"];
    $img_type = $uploadImage["type"];
    $tmp_name = $uploadImage["tmp_name"];

    $img_explode = explode(".", $img_name);
    $img_ext = end($img_explode);
    $extensions = ["png", "jpg", "jpeg"];
    if (!in_array($img_ext, $extensions)) {
        $errors['file'] = ["file" => "پسوند فایل معتبر نیست"];
    }
}

if (NoneError($errors)) {
    $time = time();
    $new_image_name = $time . $img_name;
    $targetFile = "../public/uploadsChats/" . $new_image_name;

    $user = ["id" => $user_id, "username" => $user_username];
    $stmt = addComment($user_id, $new_image_name, $room_id_upload);
    if ($stmt->execute()) {
        if (move_uploaded_file($tmp_name, $targetFile))
            echo json_encode(["success" => true]);
        else
            echo json_encode(["errors" => [["server" => "مشکلی در آپلود عکس به وجود اکده لطفا بعدا امتحان کنید. "]]]);
    }

}
