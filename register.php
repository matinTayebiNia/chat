<?php
include_once "layout/header.php";
if (isset($_SESSION["user"])) {
    header("location: rooms.php");
}
require_once "functions/register.inc.php";
/**
 * @var $errors
 */
?>
<?php include_once "errors.php";?>
<div class=" min-h-screen  bg-gray-100 flex   items-center">

    <div class="container mx-auto my-10 max-w-md shadow-md hover:shadow-lg transition ">
        <form action="" id="register-form" method="post">
            <div class="py-12 p-10 bg-white rounded-xl  ">
                <div id="errors" class="hidden bg-red-100 text-red-700 border-red-700 py-2 px-4 w-full rounded">

                </div>
                <div class="my-3">
                    <label class="mr-4 text-gray-700 font-bold inline-block mb-2" for="name">نام</label>
                    <input type="text" name="name" id="name" value="<?= !empty($name) ? $name : '' ?>"
                           class="border bg-gray-100 py-2 px-4 w-full outline-none focus:ring-2 focus:ring-indigo-400 rounded"
                           placeholder="نام"/>
                </div>
                <div class="my-3">
                    <label class="mr-4 text-gray-700 font-bold inline-block mb-2" for="username">نام کاربری</label>
                    <input type="text" name="username" id="username"  value="<?= !empty($username) ? $username : '' ?>"
                           class="border bg-gray-100 py-2 px-4 w-full outline-none focus:ring-2 focus:ring-indigo-400 rounded"
                           placeholder="نام کاربری"/>
                </div>
                <div class="my-3">
                    <label class="mr-4 text-gray-700 font-bold inline-block mb-2" for="email">ایمیل</label>
                    <input type="text" name="email" id="email" value="<?= !empty($email) ? $email : '' ?>"
                           class="border bg-gray-100 py-2 px-4 w-full outline-none focus:ring-2 focus:ring-indigo-400 rounded"
                           placeholder="ایمیل"/>
                </div>
                <div class="my-3">
                    <label class="mr-4 text-gray-700 font-bold inline-block mb-2" for="password">رمز عبور</label>
                    <input type="password" name="password" id="password"
                           class="border bg-gray-100 py-2 px-4 w-full outline-none focus:ring-2 focus:ring-indigo-400 rounded"
                           placeholder="رمز عبور"/>
                </div>
                <a href="index.php"
                   class="text-sm text-gray-700 my-6 inline-block mt-4 hover:text-indigo-600 hover:underline hover:cursor-pointer transition duration-200">
                    من اکانت دارم</a>
                <button type="button" name="btn_register"
                        class="w-full mt-6 text-indigo-50 font-bold bg-indigo-600 py-3 rounded-md hover:bg-indigo-500 transition duration-300">
                    ثبت نام
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        const validation = new Validation();
        let errorsDom = $("div#errors");
        $("button#close").click(function (e) {
            e.preventDefault();
            $('div#errorServer').hide();
        })
        $("button[name='btn_register'").click(function (e) {
            e.preventDefault();

            errorsDom.empty();
            validation.addInput($('input#name'), "نام").required().minlength(3).matchRegex("^[a-z]").maxlength(32);
            validation.addInput($('input#username'), "نام کاربری").required().minlength(3).matchRegex("^[a-zA-Z0-9_]+$").maxlength(32);
            const re =
                /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
            validation.addInput($('input#email'), "ایمیل").required().matchRegex(re);
            validation.addInput($('input#password'), "رمز عبور").required().minlength(4).maxlength(32);

            $.each(validation.errorList(), function (i, val) {
                if (errorsDom.hasClass("hidden")) {
                    errorsDom.removeClass("hidden")
                }
                errorsDom.append(val[0] + "<br>");
            })

            if (validation.noneError()) {
                errorsDom.addClass("hidden")
                $("form#register-form").submit();
            }
        })
    })
</script>

<?php include_once "layout/footer.php" ?>
