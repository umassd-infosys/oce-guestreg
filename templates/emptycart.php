<?php
require_once(__DIR__. '/../settings.php');
include('header.php');
?>
<!-- HTML Stub for Students who should just use COIN to Register -->
<p class="alert alert-danger">
    There are no courses in your cart! Please add courses to your cart before checking out.
    <p>
    <small>This may happen if you wait a very long time to checkout.</small>
    </p>
    <br/>
    <a href="<?=OCE_HOME?>" target="_PARENT">Return to the Course List</a>
</p>
<?php
include('footer.php');
?>