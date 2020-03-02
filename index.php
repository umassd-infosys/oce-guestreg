<?php
    require_once('settings.php');
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
    <div id="checkout-form">
    <form method="get" action="index.php" id="oce-form">
        <input type="hidden" name="source" value="iframe">
        <p>
            Enter your name and email address below and click &quot;Check Out&quot;.
            You will be sent a form in a few moments that you must complete and electronically sign.
        </p>
        <label for="fullname">Please enter your full name:</label>
        <input type="text" name="fullname" id="fullname">
        <br/>
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
    </div>

    <script>
        $(document).ready(function(){
            $('#oce-form').on('submit',function() {
                $('#wait').show();
                $('#checkout-form').hide();
                return true;
            });
        });
    </script>

<?php
require_once('templates/footer.php');
?>