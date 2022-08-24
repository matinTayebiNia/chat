<?php
require_once "functions.php";
$errors = [];
if (isset($_POST["username"])) {

    $name = htmlspecialchars($_POST["name"]);
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    $errors["name"] = required($name, "نام");
    $errors["name"] = array_merge($errors["name"], minLength($name, 3, "نام"));
    $errors["name"] = array_merge($errors["name"], maxLength($name, 32, "نام"));
    $errors["name"] = array_merge($errors["name"], matchRegex($name, "/^[a-z]/", "نام"));

    $errors["username"] = required($username, "نام کاربری");
    $errors["username"] = array_merge($errors["username"], checkIsUnique($username, "username", "نام کاربری","users"));
    $errors["username"] = array_merge($errors["username"], minLength($username, 3, "نام کاربری"));
    $errors["username"] = array_merge($errors["username"], maxLength($username, 32, "نام کاربری"));
    $errors["username"] = array_merge($errors["username"], matchRegex($username, "/^[a-zA-Z0-9_]+$/", "نام کاربری"));

    $errors["email"] = required($email, "ایمیل");
    $errors["email"] = array_merge($errors["email"], is_Email($email, "ایمیل"));
    $errors["email"] = array_merge($errors["email"], checkIsUnique($email, "email", "ایمیل","users"));

    $errors["password"] = required($password, "رمز عبور");
    $errors["password"] = array_merge($errors["password"], minLength($password, 4, "رمز عبور"));
    $errors["password"] = array_merge($errors["password"], maxLength($password, 32, "رمز عبور"));

    if (NoneError($errors)) {
        $message = doCreateUser($name, $username, $email, $password);
        header("location: index.php");
    }

}