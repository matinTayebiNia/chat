<?php
include_once "layout/header.php";
if (!$_SESSION["user"]) {
    header("location: index.php");
}

?>
<div class="grid place-items-center    items-center justify-center">
    <div id="memberOfRoom" class=" w-full flex flex-col rounded items-center bg-white p-6  my-4">

    </div>
    <div class="container mx-auto flex justify-center items-center min-h-screen ">
        <section class="bg-white relative w-[450px] shadow rounded-lg ">
            <header class="flex justify-between items-center  py-5 px-8 ">
                <div class="flex items-center gap-4 ">
                    <a href="rooms.php"><i class="fa fa-arrow-right text-xl text-[#333333]"></i></a>
                    <img src="" id="img_room" class="w-14 h-14 rounded-full border" alt="#">
                    <div class="detail ">
                        <span id="title_room" class="text-xl font-semibold"></span>
                    </div>
                </div>
                <div>
                    <button class="px-5 py-2 rounded bg-[#333333] text-white text-lg cursor-pointer "
                            type="button" id="btn_open_upload" data-modal-toggle="defaultModal">
                        آپلود عکس
                    </button>
                </div>
            </header>
            <div id="chat-box"
                 class="chat-box flex flex-col  overflow-y-auto no-scrollbar space-y-8  h-[500px] bg-[#f7f7f7] pl-7 pt-2 pb-5 pr-5 shadow-inner shadow ">


            </div>
            <form action="#" id="typing-area" class="typing-area py-5 px-8 flex  ">
                <input id="input_send" name="message" type="text"
                       class="h-10 w-[calc(100%-58px)]  px-2 py-0 focus:outline-none border rounded"
                       placeholder="پیام وارد کنید...  ">
                <input type="hidden" name="room_id" value="">

                <input type="hidden" name="message_user_id" value="<?= $_SESSION["user"]["id"] ?>">
                <input type="hidden" name="message_user_username" value="<?= $_SESSION["user"]["username"] ?>">
                <button id="btn_send" class=" w-14 rounded-l bg-[#333333] text-white text-lg cursor-pointer "
                        type="button">
                    <i class="fa fa-paper-plane" aria-hidden="true"></i></button>

            </form>

        </section>
    </div>
</div>

<div id="defaultModal" tabindex="-1" aria-hidden="true"
     class=" fixed  top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden max-w-md overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-full max-w-2xl p-4 md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow ">
            <!-- Modal header -->
            <div class="flex justify-between p-4 border-b rounded-t ">
                <h3 class="text-xl font-semibold text-gray-900 ">
                    بارگذاری تصویر
                </h3>
                <button type="button" id="close"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 mx-0  inline-flex items-center "
                        data-modal-toggle="defaultModal">
                    <svg class="w-5 h-5 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <form id="upload_form" method="post" enctype="multipart/form-data" action="#">
                <div class="p-6 space-y-6">
                    <div class="space-y-3">
                        <input type="hidden" name="user_id" value="<?= $_SESSION["user"]["id"] ?>">
                        <input type="hidden" name="user_username" value="<?= $_SESSION["user"]["username"] ?>">
                        <input type="hidden" name="room_id_upload" value="<?= $_GET["room_id"] ?>">
                        <label for="uploadImage">بارگذاری تصویر</label>
                        <input type="file" id="uploadImage" name="uploadImage"
                               class="w-full rounded h-10 px-2 focus:ring focus:outline-none">
                    </div>

                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 gap-2 border-t border-gray-200 rounded-b ">

                    <button id="btnUpload" data-modal-toggle="defaultModal" type="button"
                            class="px-5 py-2 rounded bg-[#333333] text-white text-lg cursor-pointer">
                        بارگذاری
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function getDetailRoom() {

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "functions/getSingleRoom.php", true);
        xhr.onload = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    let data = JSON.parse(xhr.response);
                    console.log(data)
                    $('#img_room').attr("src", `public/uploads/${data.picture}`)
                    $("input[name='room_id']").val(data.room_id);
                    $("#title_room").html(data.name);
                    $.each(data.users, function (i, key) {

                        $('#memberOfRoom').append(`
                          <div class="user-list my-5 w-full  flex items-center justify-between border-b border-[#e6e6e6] pb-5 ">
                                <div class="flex items-center gap-4 ">
                                    <img src="public/images/585e4bd7cb11b227491c3397.png" class="w-12 h-12 rounded-full border"
                                    alt="user">
                                    <div id='memberOfRoom' class="detail  ">
                                           <span  class="text-xl font-semibold">${key.username}</span>
                                           <input type="hidden" name="user_id" value="${key.user_id}" >
                                           <input type="hidden" name="role_id" value="${key.role}" >
                                    </div>
                                </div>

                            </div>
                        `)
                    })
                }
            }
        }
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        const params = `room_id=${GetQueryStringParams("room_id")}`;
        xhr.send(params);
    }

    function openUploadForm() {
        $('#btn_open_upload').click(function (e) {
            e.preventDefault();
            $('#defaultModal').toggle("hidden");
            $('input[name="room_id_upload"]').val(GetQueryStringParams("room_id"));
        })
        $('#close').click(function (e) {
            e.preventDefault();
            $('#defaultModal').hide();
        })
    }

    function addComment() {
        const form = document.getElementById("typing-area");
        form.onsubmit = (e) => {
            e.preventDefault();
        }
        $('#btn_send').click(function (e) {
            const formData = new FormData(form);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "functions/addComment.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;
                        $('#input_send').val(null)
                    }
                }
            }
            xhr.send(formData);
        })
    }

    function getChats() {
        const chatBox = $("#chat-box");
        setInterval(() => {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "functions/getComments.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;
                        chatBox.html(data)
                        if (!chatBox.hasClass("active")) {
                            scrollToButton();
                        }
                    }
                }
            }
            const params = `room_id=${GetQueryStringParams("room_id")}`
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(params);
        }, 500)

        function scrollToButton() {
            chatBox.scrollTop(chatBox.height());
        }
    }

    function addImageChat() {
        $('#btnUpload').click(function (e) {
            e.preventDefault();
            const form_upload = document.getElementById("upload_form");
            const formData = new FormData(form_upload);
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "functions/addUploadeComment.php", true);
            xhr.onload = () => {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        let data = xhr.response;
                        if (typeof data.success !== "undefined") {
                            location.reload();
                        }
                    }
                }
            }
            xhr.send(formData);
        })
    }

    $(document).ready(function () {
        getDetailRoom();
        openUploadForm();
        addComment();
        getChats();
        addImageChat();
    })
</script>

<?php
include_once "layout/footer.php";

?>
