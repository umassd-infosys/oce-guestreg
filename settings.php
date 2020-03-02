<?php
session_start();

/* Settings for OCE Guest Registration Process */

/* Will need some prod/dev switch logic here */
//if(isset($_ENV['PROD_SERVER']) && !strcasecmp($_ENV['PROD_SERVER'],'YES')) {
/* Production End Points */
    define('OCE_GUESTCHECKOUT', 'http://webapps.umassd.edu/esig/templates/oce_guest_reg.php');
    define('OCE_DAYCHECKOUT', 'https://webapps.umassd.edu/esig/templates/oce_day_reg.php');
/*
DEVELOPMENT
    define('OCE_GUESTCHECKOUT', 'http://sandcastle.umassd.edu/mrobinson/docusign/esig/templates/oce_guest_reg.php');
    define('OCE_DAYCHECKOUT', 'https://sandcastle.umassd.edu/mrobinson/docusign/esig/auth/oce_day_reg.php');
}
*/
/* Maximum number of courses that may be used for this tool */
define('OCE_HOME','https://www.umassd.edu/online/courses');
define('OCE_MAXCOURSES',3);
if(isset($_REQUEST['iframe'])) {
    define('OCE_ISIFRAME',true);
} else {
    define('OCE_ISIFRAME',false);
}


function oceCheckout($email,$name,$type='GUEST',$cb='') {
    if(!strcasecmp($type,'DAY')) {
        $url = OCE_DAYCHECKOUT;
    } else {
        $url = OCE_GUESTCHECKOUT;
    }

    /* Build the courses from our cart */
    $c = [];
    foreach($_SESSION['OCE']['courses'] as $crs) {
        $c[] = $crs['crn'];
    }
    $c = array_unique($c);
    $courses = implode(',',$c);
    $id = uniqid();
    $docusignUrl = $url."?name={$name}&email={$email}&oce_gr_id={$id}&courses={$courses}";
    file_get_contents($docusignUrl);
    $_SESSION['OCE'] = ['maxCourses'=>OCE_MAXCOURSES,'n'=>0,'courses'=>[],'term'=>''];

    /* Handle possible callback */
    if(empty($cb)) {
        echoOut(null, 'OK ' . $docusignUrl);
    } else if(!strcasecmp($cb,'html')) {
        include('templates/complete.php');
    }
    session_destroy();
}

function echoOut($error=null,$msg=null) {
    //return json data packet
    $data = [
        'n' => count($_SESSION['OCE']['courses']),
        'courses'=>$_SESSION['OCE']['courses']
    ];
    if($error!=null) {
        $data['error'] = $error;
    }
    if($msg!=null) {
        $data['message'] = $msg;
    }
    header("Access-Control-Allow-Origin: *");
    echo $_GET['callback'] . "(" . json_encode($data) .");";
}

?>