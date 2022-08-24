<?php

function database(): PDO
{
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "chat";
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;", $username, $password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $pdo;
}

function required($var, string $input): array
{
    return empty($var) ? ["required" => " لطفا فیلد $input را وارد کنید "] : [];
}

function minLength(string $var, int $length, string $input): array
{
    if ($var)
        return iconv_strlen($var) < $length ?
            ["min" => " فیلد $input  باید بیشتر از  $length کارکتر باشد  "] : [];
    return required($var, $input);

}


function maxLength(string $var, int $length, string $input): array
{
    if ($var)
        return iconv_strlen($var) > $length ?
            ["max" => " فیلد $input  باید کمتر از  $length کارکتر باشد  "] : [];
    return required($var, $input);

}

function is_Email(string $var, string $input): array
{
    if (!required($var, $input))
        return !filter_var($var, FILTER_VALIDATE_EMAIL) ?
            ["email" => "ایمیل معتبر نیست"] : [];
    return required($var, $input);
}


#[ArrayShape(["unique" => "string"])]
function checkIsUnique($var, $column, $input, $table): array
{
    $pdo = database();

    $statement = $pdo->prepare("select * from $table where $column = :var");
    $statement->bindValue("var",$var);
    $statement->execute();
    if ($statement->rowCount() > 0) {
        return ["unique" => "فیلد $input  تکراری میباشد. "];
    }
    return [];
}

function createUniqueId(): int
{
    return rand(time(), 100000000);
}

function getUsers(): mixed
{
    $data = file_get_contents(users());
    return json_decode($data, true);
}

function matchRegex(string $var, string $regex, string $input): array
{
    if (!required($var, $input))
        return !preg_match($regex, $var) ?
            ["match" => " فیلد $input معتبر نمیباشد."] : [];
    return required($var, $input);
}

function NoneError(array $errors): bool
{
    return empty(array_filter($errors));
}


function fileCreateWrite(string $name, string $username, string $email, string $password): bool|string
{
    $file = fopen(users(), "w");
    $array_data = array();
    $extra = array(
        "id" => createUniqueId(),
        "name" => $name,
        'username' => $username,
        'email' => $email,
        'password' => $password,
    );
    $array_data[] = $extra;
    $final_data = json_encode($array_data);
    fclose($file);
    return $final_data;
}

function fileWriteAppend(string $name, string $username, string $email, string $password): bool|string
{
    $current_data = file_get_contents(users());
    $array_data = json_decode($current_data, true) ?? array();

    $extra = array(
        "id" => createUniqueId(),
        "name" => $name,
        'username' => $username,
        'email' => $email,
        'password' => $password,
    );
    $array_data[] = $extra;
    return json_encode($array_data);
}

/**
 * @param string $username
 * @param string $email
 * @param mixed $password
 * @return string
 */
function doCreateUser(string $name, string $username, string $email, mixed $password): string
{

    $pdo = database();
    $message = "";
    $statement = $pdo->prepare("insert into users
    (name ,username,email,password) values 
    (:name,:username,:email,:password)");
    $statement->bindValue("name", $name);
    $statement->bindValue("username", $username);
    $statement->bindValue("email", $email);
    $statement->bindValue("password", $password);
    if ($statement->execute())
        $message = "<label class='alert alert-success'>ثبت نام با موفقیت انجام شد </p>";
    return $message;
}

function getRooms()
{
    $data = file_get_contents(rooms());
    return json_decode($data, true);
}

/**
 * @param string $user_id
 * @param string $message
 * @param string $room_id
 * @return false|PDOStatement
 */
function addComment(string $user_id, string $message, string $room_id): PDOStatement|false
{
    var_dump($user_id);
    var_dump($room_id);
    $pdo = database();
    $stmt = $pdo->prepare("insert into comments (user_id,comment,room_id) values (:user_id,:comment,:room_id)");
    $stmt->bindValue("user_id", intval($user_id));
    $stmt->bindValue("comment", $message);
    $stmt->bindValue("room_id", intval($room_id));
    return $stmt;
}
/**
 * @param PDO $pdo
 * @param mixed $room_id
 * @return mixed
 */
function getAdmin(PDO $pdo, mixed $room_id): mixed
{
    $sql1 = "
    select user_room.rule,user_room.user_id from users 
    inner join user_room 
    on user_room.user_id=users.id
    where user_room.room_id =:room_id
    ";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->bindValue("room_id", $room_id);
    $stmt1->execute();
    $admin = $stmt1->fetch();
    return $admin;
}


function uploadImageChat()
{
    return "../public/uploadsChats/";
}


