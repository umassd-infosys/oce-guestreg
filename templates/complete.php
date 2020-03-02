<?php
require_once(__DIR__.'/../settings.php');
include('header.php');
?>

    Check the supplied email (<i><?=$email?></i>) momentarily to complete this process via an electronic DocuSign form.
    If it does not arrive, please double check your spam or junk mail folders.
    If you supplied an incorrect email address, then please re-submit with a correct address.

    <a target="_PARENT" href="<?=OCE_HOME?>">Return to the Course List.</a>

<?php
include('footer.php');
die;
?>