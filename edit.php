<?php
include_once "layout/header.php";

if (!$_SESSION["user"]) {
    header("location: index.php");
}
?>
<div class="min-h-screen bg-gray-100 flex items-center mx-5 md:mx-0">
    <div class="container mx-auto max-w-md shadow-md hover:shadow-lg transition duration-300">
        <form action="functions/editComment.php" id="comment_form_edit" method="post">
            <div class="py-12 p-10 bg-white rounded-xl  ">
                <div id="errors" class="hidden bg-red-100 text-red-700 border-red-700 py-2 px-4 w-full mb-6 rounded">

                </div>
                <input type="hidden" name="chat_id" value="<?= $_GET["chat_id"] ?>">
                <input type="hidden" name="room_id" value="<?= $_GET["room_id"] ?>">
                <div class="mb-6">
                    <label class="mr-4 text-gray-700 font-bold inline-block mb-2" for="username">ویرایش پیام</label>
                    <input type="text" name="chatEdit" value="<?= $_GET["chat"] ?? "" ?>"
                           class="border bg-gray-100 py-2 px-4 w-full outline-none focus:ring-2 focus:ring-indigo-400 rounded"
                           id="username" placeholder="نام کاربری"/>
                </div>

                <button type="button" name="btn_update"
                        class="w-full mt-6 text-indigo-50 font-bold bg-indigo-600 py-3 rounded-md hover:bg-indigo-500 transition duration-300">
                    ویرایش
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function (e) {
        const validation = new Validation();
        const errors = document.getElementById("errors");
        const btnUpdate = document.querySelector("button[name='btn_update']");

        const form = document.getElementById("comment_form_edit");

        form.onsubmit = (e) => {
            e.preventDefault();
        }
        btnUpdate.addEventListener("click", function (e) {
            errors.innerHTML = "";
            e.preventDefault();
            validation.addInput($('input[name="chatEdit"]'), "متن پیام").required().maxlength(100);

            $.each(validation.errorList(), function (i, val) {
                if (errors.classList.contains("hidden")) {
                    errors.classList.remove("hidden")
                }
                errors.innerHTML += val[0] + "<br>";
            })

            if (validation.noneError()) {
                errors.classList.add("hidden")
                form.submit();
            }
        })
    })
</script>
<?php
include_once "layout/footer.php";
?>
