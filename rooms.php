<?php
include_once "layout/header.php";

if (!$_SESSION["user"]) {
    header("location: index.php");
}
$user = $_SESSION["user"];

?>

<div id="div_room"
     class="relative hidden bg-white p-6 rounded-lg flex flex-col items-center  max-w-md top-40 right-[35%]">
    <form id="form_room" action="#" class="space-y-4" method="post">
        <div id="errors" class="hidden bg-red-100 text-red-700 border-red-700 py-2 px-4 w-96 rounded">

        </div>
        <div class="space-y-2 w-96">
            <label for="file">عکس گروه</label>
            <input type="file" name="file" id="file"
                   class="w-full rounded py-2 h-10 border  focus:outline-none focus:ring ">
        </div>
        <div class="space-y-2 w-96">
            <label for="RoomName" class="">نام اتاق :</label>
            <input type="text" id="RoomName" name="RoomName"
                   class="w-full rounded py-2 h-10 border  focus:outline-none focus:ring ">
        </div>
        <div class="space-y-2 w-96">
            <label for="Member" class="block">عضو های اتاق</label>
            <select name="Member[]" class="w-96 no-scrollbar  block focus:outline-none focus:ring" id="Member"
                    multiple>

            </select>
        </div>
        <div class="space-y-2 w-96">
            <button id="btn_room" class="w-full py-3 px-3 text-white bg-[#333333] text-lg rounded">
                ثبت
            </button>
        </div>
    </form>
</div>
<div class="container mx-auto flex justify-center items-center min-h-screen ">
    <section class="bg-white relative w-[450px] shadow rounded-lg py-7 px-6 ">
        <header class="flex items-center justify-between border-b border-[#e6e6e6] pb-5">
            <div class="flex items-center  ">
                <img src="public/images/585e4bd7cb11b227491c3397.png" class="w-14 h-14 rounded-full border" alt="#">
                <div class="detail mx-4 ">
                    <span class="text-xl font-semibold"><?= $user["username"] ?></span>

                </div>
            </div>
            <div class="flex flex-col text-center">
                <a href="#" onclick="event.preventDefault();document.getElementById('logout_form').submit()"
                   class="px-4 py-2  rounded text-white bg-[#333333] ">خروج</a>
                <a href="#" id="createRoom" class="px-4 py-2 mt-2 rounded text-white bg-[#333333] ">ساخت اتاق</a>
            </div>
        </header>
        <form action="functions/logout.php" method="post" id="logout_form">

        </form>
        <div id="roomList" class="max-h-[350px] overflow-y-auto no-scrollbar">

        </div>
    </section>
</div>
<script>

    $(document).ready(function () {
        const roomList = $('#roomList'),
            createRoom = $('#createRoom'),
            div_room = $('#div_room'),
            member = $('#Member'),
            formRoom = document.getElementById("form_room"),
            btn_room = $('#btn_room'),
            errorBox = $('#errors');

        setInterval(() => {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "functions/getRooms.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;


                        roomList.html(data)
                    }
                }
            }
            xhr.send();
        }, 500)


        formRoom.onsubmit = function (event) {
            event.preventDefault();
        };
        btn_room.click(function (e) {
                errorBox.empty();
                e.preventDefault();
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "functions/setRoom.php", true);
                xhr.onload = () => {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            let data = JSON.parse(xhr.response)
                            if (typeof data.errors !== "undefined") {
                                let errors = Object.values(data.errors)
                                errors = errors.map(error => {
                                    return Object.values(error)[0];
                                })
                                $.each(errors, function (i, key) {
                                    if (errorBox.hasClass("hidden")) {
                                        errorBox.removeClass("hidden")
                                    }
                                    errorBox.append(`${key}<br>`)
                                });
                            } else if (typeof data.success !== "undefined") {
                                location.reload();
                            }
                        }
                    }
                }
                const formData = new FormData(formRoom);
                xhr.send(formData);

            }
        )

        member.select2({
            placeholder: 'انتخاب عضو',
        });

        member.on('select2:open', function (e) {
            $.getJSON("functions/getUsers.php", function (data) {
                $.each(data, function (i, val) {
                    if (!($(`#Member option[value=${val}]`).length > 0)) {
                        // Create a DOM Option and pre-select by default
                        const newOption = new Option(i, val, false, false);
                        // Append it to the select
                        member.append(newOption).trigger('change');
                    }
                })
            })
        });

        createRoom.click(function (e) {
            e.preventDefault();
            div_room.toggle("hidden")
            $.getJSON("functions/getUsers.php", function (data) {
                $.each(data, function (i, val) {
                    if (!($(`#Member option[value=${val}]`).length > 0)) {
                        // Create a DOM Option and pre-select by default
                        const newOption = new Option(i, val, false, false);
                        // Append it to the select
                        member.append(newOption).trigger('change');
                    }
                })
            })
        })

    })
</script>
<?php

include_once "layout/footer.php"
?>

