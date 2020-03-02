<?php
require_once('../settings.php');

//Make sure there are courses in the cart
if(empty($_SESSION['OCE']['courses'])) {
    require_once('../templates/emptycart.php');
    die;
}

/* Route a UMassD Person to the appropriate form */
$user = $_SERVER['REMOTE_USER'] or die('user could not be located');
$user = strtolower($user);
/* in heroku we use this for db */
if(isset($_ENV['DATABASE_URL'])) {
    $db = parse_url(getenv('DATABASE_URL'));
    $db['path'] = trim($db['path'], '/');
    $dsn = "pgsql:host={$db['host']};port={$db['port']};dbname={$db['path']};sslmode=prefer";
    $db = new PDO($dsn, $db['user'], $db['pass']);
} else {
    /* on sandcastle we use this */
    require_once('pdoconnect.php');
    $db = pgsql_connect('heroku');
}
$query = <<< _Q_
select spp_program,spp_plan,displayname from peoplesoft.ps_student_program_plan
join infrastructure.ad_userinfo on umassisisid=spp_emplid
where spp_program_status='AC' and samaccountname=:username
_Q_;
$sel_program_plan = $db->prepare($query);
$sel_program_plan->bindParam('username',$user,PDO::PARAM_STR);
$sel_program_plan->execute() or die ('could not execute query');
$program_plan = $sel_program_plan->fetchAll(PDO::FETCH_ASSOC);
$displayName = $user;
$hasProgramPlan = false;
foreach($program_plan as $pp) {
    $hasProgramPlan = true;
    $displayName = $pp['displayname'];
    if(isOCEProgramPlan($pp['spp_program'],$pp['spp_plan'])==true) {
        //Online Students cannot use this system -- must go into umassd
        require_once('../templates/matriculated.php');
        include('matriculated.php');
        die;
    }
}
$thisTerm = $_SESSION['OCE']['term'];
$lastTwoOfTerm = substr($thisTerm,-2);
if($hasProgramPlan==true && ($lastTwoOfTerm==20 || $lastTwoOfTerm==40)) {
    //Day School students in the Summer and Intersession should use COIN to register
    include('matriculated.php');
    die;

}  else if($hasProgramPlan) {
    /* This person has a program plan stack AND it isn't for Winter or Summer, so give them the day school form */
    oceCheckout($user.'@umassd.edu',$displayName,'DAY');
}
/* if we got here, we should be good to go for the GUEST form */
oceCheckout($user.'@umassd.edu',$displayName,'GUEST','html');

function isOCEProgramPlan($program,$plan) {
    $ocePlanTypes = ['OGC','OLBS','OLBA','OGP','OLCT','OLMN'];
    $planType = explode('-',$plan,2)[1];
    //Is this plan type in the list of OCE Plan Types?
    if(in_array($planType,$ocePlanTypes)) {
        return true;
    }
    return false;
}

?>