<?php include_once "layout/header.php";
require_once "functions/login.inc.php";
if (isset($_SESSION["user"])) {
    header("location: rooms.php");
}
?>
<?php include_once "errors.php"; ?>
<div class="min-h-screen bg-gray-100 flex items-center mx-5 md:mx-0">
    <div class="container mx-auto max-w-md shadow-md hover:shadow-lg transition duration-300">
        <form action="" id="login_form" method="post">
            <div class="py-12 p-10 bg-white rounded-xl  ">
                <div id="errors" class="hidden bg-red-100 text-red-700 border-red-700 py-2 px-4 w-full mb-6 rounded">

                </div>
                <div class="mb-6">
                    <label class="mr-4 text-gray-700 font-bold inline-block mb-2" for="username">نام کاربری</label>
                    <input type="text" name="username" value="<?= !empty($username) ? $username : '' ?>"
                           class="border bg-gray-100 py-2 px-4 w-full outline-none focus:ring-2 focus:ring-indigo-400 rounded"
                           id="username" placeholder="نام کاربری"/>
                </div>
                <div class="">
                    <label class="mr-4 text-gray-700 font-bold inline-block mb-2" for="password">رمز عبور</label>
                    <input type="password"
                           class="border bg-gray-100 py-2 px-4 w-full outline-none focus:ring-2 focus:ring-indigo-400 rounded"
                           id="password" name="password" placeholder="رمز عبور"/>
                </div>
                <a href="register.php"
                   class="text-sm text-gray-700 inline-block mt-4 hover:text-indigo-600 hover:underline hover:cursor-pointer transition duration-200">
                    من اکانت ندارم</a>

                <button type="submit" name="btn_login"
                        class="w-full mt-6 text-indigo-50 font-bold bg-indigo-600 py-3 rounded-md hover:bg-indigo-500 transition duration-300">
                    ورود
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
        $("button[name='btn_login']").click(function (e) {
            e.preventDefault();
            errorsDom.empty();
            validation.addInput($('#username'), "نام کاربری").required().minlength(3).matchRegex("^[a-zA-Z0-9_]+$").maxlength(32);
            validation.addInput($('#password'), "رمز عبور").required().minlength(4).maxlength(32);

            $.each(validation.errorList(), function (i, val) {
                if (errorsDom.hasClass("hidden")) {
                    errorsDom.removeClass("hidden")
                }
                errorsDom.append(val[0] + "<br>");
            })

            if (validation.noneError()) {
                errorsDom.addClass("hidden")
                $("form#login_form").submit();
            }
        })
    })
</script>
<?php include_once "layout/footer.php" ?>
