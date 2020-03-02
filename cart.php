<?php
require_once('settings.php');

if(!isset($_SESSION['OCE']) || isset($_GET['empty'])) {
    $_SESSION['OCE'] = ['maxCourses'=>OCE_MAXCOURSES,'n'=>0,'courses'=>[],'term'=>''];
}

/* Handle Adding or removing courses */
if (isset($_GET['add'])) {
    /* CRN consists of Term|Session|Course # */
    $course = filter_input(INPUT_GET, 'add');
    $courseParts = explode('|', $course);
    /* if we don't have three components in this course reference number then error out */
    if (count($courseParts) != 3) {
        header("HTTP/1.0 403 Not Found");
        echoOut("incorrectly formatted course reference number");
        return false;
    }

    $thisTerm = $courseParts[0];
    if(!empty($_SESSION['OCE']['term']) && !strcmp($thisTerm,$_SESSION['OCE']['n'])) {
        echoOut("This course is scheduled for a different term than the other entries in your cart.  Your cart may only be for one term.  Please add this course after checking out the previous term or empty your cart and start over.");
        return false;
    }

    try {
        $db = parse_url(getenv('DATABASE_URL'));
        $db['path'] = trim($db['path'], '/');
        $dsn = "pgsql:host={$db['host']};port={$db['port']};dbname={$db['path']};sslmode=prefer";
        $db = new PDO($dsn, $db['user'], $db['pass']);
    } catch(Exception $exception) {
        require_once('pdoconnect.php');
        $db = pgsql_connect('heroku');
    }
    $query = "select * from peoplesoft.ps_courses where crs_term=:term and crs_class_number=:class;";
    $sel_course = $db->prepare($query);
    $sel_course->bindParam('term', $courseParts[0], PDO::PARAM_STR);
    $sel_course->bindParam('class', $courseParts[2], PDO::PARAM_INT);
    $sel_course->execute();
    $courseInfo = $sel_course->fetch(PDO::FETCH_ASSOC);
    if ($courseInfo) {
        //Make sure this CRN Isn't already in the cart
        foreach ($_SESSION['OCE']['courses'] as $existingCourse) {
            if (!strcmp($existingCourse['crn'], $course)) {
                echoOut("The specified course ({$course}) already exists in cart.");
                return false;
            }
        }
        $courseInfo['crn'] = $course;
        $_SESSION['OCE']['courses'][] = $courseInfo;
        $_SESSION['OCE']['term'] = $thisTerm;
    } else {
        //Course doesn't exist
        header("HTTP/1.0 404 Not Found");
        echoOut("The specified course ({$course}) could not be found.");
        return false;
    }
} else if (isset($_GET['empty'])) {
    $_SESSION['OCE'] = ['maxCourses'=>OCE_MAXCOURSES,'n'=>0,'courses'=>[],'term'=>''];
} else if (isset($_GET['remove'])) {
    $course = filter_input(INPUT_GET,'remove');
    foreach($_SESSION['OCE']['courses'] as $key => $c) {
        if(!strcmp($c['crn'],$course)) {
            unset($_SESSION['OCE']['courses'][$key]);
            //re-index array
            $_SESSION['OCE']['courses'] = array_values($_SESSION['OCE']['courses']);
        }
    }
    $_SESSION['OCE']['n'] = count($_SESSION['OCE']['courses']);
    if($_SESSION['OCE']['n']=0) {
        $_SESSION['OCE']['term'] ='';
    }

} else if (isset($_GET['checkout'])) {
    //the email is the value of 'checkout' and is the recipient's email address
    $email = filter_input(INPUT_GET,'checkout',FILTER_VALIDATE_EMAIL);
    $fullname = filter_input(INPUT_GET,'fullname',FILTER_SANITIZE_STRING);
    if(!$email) {
        if($_REQUEST['source'] && !strcasecmp('iframe',$_REQUEST['source'])) {
            echo "<p class='alert alert-danger'>Please enter a valid email address.</p>";
        } else {
            echoOut('Please enter a valid email address');
        }
    } else {
        /*
        there are two forms/processes: one for guests and one for umassd people who are already matriculated into a degree program
        */
        $email = strtolower($email);
        /* Intercept a possible @umassd.edu email */
        if (strstr($email, '@umassd.edu')) {
            header('Location: auth/index.php');
            die;
        }
        return oceCheckout($email,$fullname, 'GUEST', 'html');
    }
} else if(isset($_GET['deeplink'])) {
    //DERIVED_REGFRM1_CLASS_NBR
    //https://sm-prd.sa.umasscs.net/psc/saprdmobile/EMPLOYEE/SA/c/SA_LEARNER_SERVICES.SSR_SSENRL_CART.GBL?Page=SSR_SSENRL_CART&Action=A&ACAD_CAREER=GRAD&EMPLID=00297622&INSTITUTION=UMDAR&STRM=2930&DERIVED_REGFRM1_CLASS_NBR=12345
    //https://sm-prd.sa.umasscs.net/psc/saprdmobile/EMPLOYEE/SA/c/SA_LEARNER_SERVICES.SSR_SSENRL_CART.GBL?Page=SSR_SSENRL_CART&Action=A&INSTITUTION=UMDAR&STRM=2930&ACAD_CAREER=GRAD&DERIVED_REGFRM1_CLASS_NBR=12345&DERIVED_REGFRM2_CLASS_NBR=123456
}
if(OCE_ISIFRAME==false) {
    echoOut();
}





?>