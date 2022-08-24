<?php
/**
 * @var $errors
 */
?>
<?php if (count($errors) > 0)  : ?>

    <div id="errorServer"
         class=" mx-auto border border-red-700 bg-red-100 fixed flex flex-col space-y-1 justify-center p-8  top-14 right-14 rounded max-w-md  transition ">
        <button type="button" id="close"
                class=" relative -top-4 -right-4 bg-red-500 rounded py-2 px-2 self-start hover:bg-red-400">
            <img src="public/images/close.png" class="w-5 h-5" alt="close">
        </button>
        <div class="  text-red-700 border-red-700    w-full ">
            <?php
            $err = array_values($errors);
            foreach ($err as $error) :?>
                <?php foreach ($error as $item): ?>
                    <?= $item ?><br>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
