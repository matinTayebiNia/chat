<?php

require_once "functions.php";
$errors = [];
if (isset($_POST["username"])) {
    $login = false;


    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    $errors["username"] = required($username, "نام کاربری");
    $errors["username"] = array_merge($errors["username"], minLength($username, 3, "نام کاربری"));
    $errors["username"] = array_merge($errors["username"], maxLength($username, 32, "نام کاربری"));

    $errors["password"] = required($password, "رمز عبور");
    $errors["password"] = array_merge($errors["password"], minLength($password, 4, "رمز عبور"));
    $errors["password"] = array_merge($errors["password"], maxLength($password, 32, "رمز عبور"));

    if (NoneError($errors)) {

        $pdo = database();
        $statement = $pdo->prepare("select username,email,id
        from users where username=:username AND password=:password");
        $statement->bindValue("username", $username);
        $statement->bindValue("password", $password);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            $_SESSION["user"] = $statement->fetch();
            header("location: rooms.php");
        } else {
            $errors["username"] = ["wrongPasswordOrUsername" => "نام کاربری یا رمز عبور اشتباه است"];
        }
    }
}