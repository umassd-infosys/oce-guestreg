<?php
require_once(__DIR__.'/../settings.php');
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" media="screen, print" href="https://www.umassd.edu/media/supportingfiles/layoutassets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen, print" href="https://www.umassd.edu/media/supportingfiles/layoutassets/bootstrap/css/style.min.css">
    <link rel="stylesheet" type="text/css" media="screen, print" href="https://www.umassd.edu/media/supportingfiles/layoutassets/bootstrap/css/fonts/font-family-university.min.css">
    <link rel="stylesheet" type="text/css" href="https://www.umassd.edu/media/supportingfiles/layoutassets/bootstrap/css/css-add-ons/online_style.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/fontawesome.min.js">
    <title>UMassD Online and Continuing Education Guest Registration</title>
</head>
<body>
<div class="container">

<?php
if(OCE_ISIFRAME == false) {
    ?>

    <div class="navbar bg-navbar navbar online"><div class="container-fluid"><a class="svgBrand" href="https://www.umassd.edu/online/" title="UMass Dartmouth Online &amp; Continuing Education Home"><span style="background: none, url(https://www.umassd.edu/media/supportingfiles/layoutassets/logos/online-logo-2x-invert.png) no-repeat 0 50%;background: none, url(https://www.umassd.edu/media/supportingfiles/layoutassets/logos/online-continuing-ed-logo.svg) no-repeat 0 50%;background-size: contain;"></span></a></div></div>

    <header role="banner">
    <div class="page-header bg-blue-dark">
        <h1 class="page-title">Course Registration</h1>
    </div>
    </header>
<?php
}

?>
