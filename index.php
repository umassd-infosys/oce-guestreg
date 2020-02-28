<?php

    /* Landing page for checkout */
    //Make sure there are courses in the cart
    if(empty($_SESSION['OCE']['courses'])) {
        require_once(__DIR__.'/templates/emptycart.php');
        die;
    }




    //require_once('settings.php');
    require_once(__DIR__.'/templates/header.php');
    include('cart.php');


?>
<!--
    intended to be embedded via an iframe
-->
    <form method="get" action="index.php">
        <input type="hidden" name="source" value="iframe">
        <p>
        To complete your registration, enter your email address below and click &quot;Check Out&quot;
            You will be emailed a form momentarily.
        </p>
        <label for="checkout">
            Please enter your email:
        </label>
        <input type="email" name="checkout" id="checkout">

        <p>
        <input class="btn btn-lg btn-auto-icon btn-primary" style="max-width:10em;" required type="submit" value="Check Out">
        </p>
        <?php if(OCE_ISIFRAME==true) { ?>
             <input type="hidden" name="iframe" value="true" >
        <?php } ?>
    </form>

<?php
require_once('templates/footer.php');
?>