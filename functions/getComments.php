<?php
session_start();
$errors = [];
require_once "functions.php";
$currentUser = $_SESSION["user"];
$room_id = $_POST["room_id"];
$errors["room_id"] = required($room_id, "کد اتاق");
if (NoneError($errors)) {
    $sql = "
        select  comments.comment,user_room.rule, 
               comments.id as comment_id,users.username,user_room.user_id
        from users inner join user_room
        on users.id=user_room.user_id
        inner join  rooms
        on rooms.id=user_room.room_id
        inner join comments
        on comments.user_id=users.id
        where user_room.room_id=:room_id
    ";
    $pdo = database();
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue("room_id", $room_id);
    $stmt->execute();
    $res = $stmt->fetchAll();
    $output = "";
    $btn_deleteAndUpdate = "";
    $admin = getAdmin($pdo, $room_id);

    foreach ($res as $chat) {

        if ($currentUser["id"] == $chat["user_id"]) {

            if (is_file(uploadImageChat() . $chat["comment"])) {
                $output .= '<div class=" ">

                    <div  class="details_comment  ">
                        <img src="public/uploadsChats/' . $chat["comment"] . '" class="w-28 h-28 rounded" alt="pic">
                     
                        <div id="deleteAndUpdate" class="flex gap-2  justify-start items-center mt-2">
                        <form method="post"  action="functions/deleteComment.php">
                           <input type="hidden" name="comment_id" value="' . $chat["comment_id"] . '">
                               <input type="hidden" name="room_id" value="' . $room_id . '">
                                   <button type="submit" >
                                       <i class="fa text-red-700 fa-trash" aria-hidden="true"></i>
                                   </button>
                        </form>
                        
                      
                        </div>
                    </div>';
            } else {

                $output .= '<div class=" ">

                    <div  class="details_comment  ">
                        <p class="bg-[#333333]  chat-outgoing  ">' . $chat["comment"] . '</p>
                        <div id="deleteAndUpdate" class="flex gap-2  justify-start items-center mt-2">
                        <form method="post"  action="functions/deleteComment.php">
                           <input type="hidden" name="comment_id" value="' . $chat["comment_id"] . '">
                               <input type="hidden" name="room_id" value="' . $room_id . '">
                                   <button type="submit" >
                                       <i class="fa text-red-700 fa-trash" aria-hidden="true"></i>
                                   </button>
                        </form>
                        
                       
                                   <a href="edit.php?chat_id=' . $chat['comment_id'] . '&chat=' . $chat['comment'] . '&room_id=' . $room_id . '" >
                                       <i class="fa text-yellow-700 fa-pen" aria-hidden="true"></i>
                                   </a>
                        
                        </div>
                    </div>
                </div>';
            }
        } else {
            if ( $currentUser["id"] == $admin["user_id"]) {
                $btn_deleteAndUpdate = '
                 <div id="deleteAndUpdate" class="flex gap-2  justify-end items-center mt-2">
                   <form method="post" action="functions/deleteComment.php">
                      <input type="hidden" name="comment_id" value="' . $chat["comment_id"] . '">
                      <input type="hidden" name="room_id" value="' . $room_id . '">
                      <button type="submit" >
                         <i class="fa text-red-500 fa-trash" aria-hidden="true"></i>
                      </button>
                   </form>
                </div>
                ';
            }

            if (is_file(uploadImageChat() . $chat["comment"])) {
                $output .= '<div class="flex self-end justify-end gap-2 items-center " xmlns="http://www.w3.org/1999/html">
                   
                    <div class="  ">
                        <img src="public/uploadsChats/' . $chat["comment"] . '"  class="w-28 h-28  rounded" alt="pic">
                        <strong class="font-semibold text-xl">:' . $chat["username"] . '</strong>

                     ' . $btn_deleteAndUpdate . '
                    </div>
                     <img src="public/images/585e4bd7cb11b227491c3397.png" class="w-12 h-12 rounded-full border self-end"
                         alt="#">
                </div>';
            } else {
                $output .= '<div class="flex self-end gap-2 items-center " xmlns="http://www.w3.org/1999/html">
                   
                    <div class="details_comment w-full">
                        <p class=" chat-incoming  ">
                        :</strong class="font-bold  text-xl" >' . $chat["username"] .
                    "</strong> <br> " . $chat["comment"] . '</p>
                     ' . $btn_deleteAndUpdate . '
                    </div>
                     <img src="public/images/585e4bd7cb11b227491c3397.png" class="w-12 h-12 rounded-full border self-center"
                         alt="#">
                </div>';
            }
        }

    }
    echo $output;
}
