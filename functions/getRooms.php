<?php


session_start();
require_once "functions.php";

$sql="
    select rooms.id, rooms.picture, rooms.name from rooms inner  join  user_room 
    on user_room.room_id=rooms.id
    where user_room.user_id=:user_id 
";

$pdo=database();
$statement=$pdo->prepare($sql);
$statement->bindValue("user_id",$_SESSION["user"]["id"]);
$statement->execute();
$rooms=$statement->fetchAll();

$output='';
foreach ($rooms as $room){
    $output.= '  <div class="user-list my-5  flex items-center justify-between border-b border-[#e6e6e6] pb-5 ">
                                            <a href="chat.php?room_id='.$room["id"].'">
                                                <div class="flex items-center gap-4 ">
                                                    <img src="public/uploads/'.$room["picture"].'" class="w-12 h-12 rounded-full border"
                                                         alt="' . $room["name"] . '">
                                                    <div class="detail ">
                                                        <span class="text-xl font-semibold">' .$room["name"]. '</span>
                                                        <p class="text-gray-500"></p>
                                                    </div>
                                                </div>
                                            </a>
                                           
                                        </div>';
}
echo $output;