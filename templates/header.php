<?php
require_once(__DIR__.'/../settings.php');
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" media="screen, print" href="https://www.umassd.edu/media/supportingfiles/layoutassets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen, print" href="https://www.umassd.edu/media/supportingfiles/layoutassets/bootstrap/css/style.min.css">
    <link rel="stylesheet" type="text/css" media="screen, print" href="https://www.umassd.edu/media/supportingfiles/layoutassets/bootstrap/css/fonts/font-family-university.min.css">
    <link rel="stylesheet" type="text/css" href="https://www.umassd.edu/media/supportingfiles/layoutassets/bootstrap/css/css-add-ons/online_style.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/fontawesome.min.js">
    <style type="text/css">
        /* app loader */
        .wait {
            background-color: #3892d3;
            color: white;
            transition: background-color 0.6s ease-in-out 0s;
            font: 14px "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif;
        }
        .spinner {
            width: 6em;
            height: 6em;
            color: white;
            background-color: white;
            margin: 1em auto;
            -webkit-animation: rotateplane 1.2s infinite ease-in-out;
            animation: rotateplane 1.2s infinite ease-in-out;
        }

        @-webkit-keyframes rotateplane {
            0% { -webkit-transform: perspective(120px) }
            50% { -webkit-transform: perspective(120px) rotateY(180deg) }
            100% { -webkit-transform: perspective(120px) rotateY(180deg)  rotateX(180deg) }
        }

        @keyframes rotateplane {
            0% {
                transform: perspective(120px) rotateX(0deg) rotateY(0deg);
                -webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg)
            }
            50% {
                transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
                -webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg)
            }
            100% {
                transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
                -webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
            }
        }
    </style>

    <title>UMassD Online and Continuing Education Guest Registration</title>
</head>
<body>
<div class='wait' id="wait" style="display:none">
    <div style="width:40em;margin:auto;text-align:center;">
        <div style="font-size:2em;font-weight:bold;">
            Sending form&hellip;
        </div>
        <div class="spinner"></div>
    </div>
</div>
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
