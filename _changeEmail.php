<?php

    session_start();

include("_init.php");
include("_debug.php");
include("_names.php");

    if($_SERVER["REQUEST_METHOD"] != "POST"){header('Location: '.getSiteURL());exit();}

include("_profileVars.php");
include("_secrets.php");
include("_globals.php");
include("_emailFunctions.php");

    //first make sure we are a legit user.
    if(deleteCookiesIfInvalid()==false){header('Location: '.getSiteURL());exit();}//full auth for actions

    goHomeIfCookieNotSet();

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    //make sure we've got an action.
    if(!isset($_POST['newEmail'])||empty($_POST['newEmail']))exit('newEmail'); else $newEmail= mysqli_escape_string($db,$_POST['newEmail']);
    if(!isset($_POST['verifyEmail'])||empty($_POST['verifyEmail']))exit('verifyEmail'); else $verifyEmail= mysqli_escape_string($db,$_POST['verifyEmail']);

    if($newEmail!=$verifyEmail)exit("emails don't match");

    $email = mysqli_escape_string($db,$_SESSION["email"]);

    //make sure email isn't in use
    $dbquerystring = sprintf("SELECT id FROM ".$dbname.".users WHERE email='%s'",$newEmail);
    $dbquery = mysqli_query($db,$dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);

    if($dbresults)exit("Error");

    //get my userid
    $dbquerystring = sprintf("SELECT id, verifyHash FROM ".$dbname.".users WHERE email='%s'",$email);
    $dbquery = mysqli_query($db,$dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);

    $userid = $dbresults['id'];
    $verifyHash = $dbresults['verifyHash'];

    //set new email address
    //set verify to 0
    $dbquerystring =
    sprintf("UPDATE ".$dbname.".users SET email = '%s', verified = '0' WHERE email='%s'",
    $newEmail,
    $email
    );
    if(!mysqli_query($db,$dbquerystring))exit("didn't work");

    //done
    mysqli_close($db);

    sendNewEmailAddressVerificationEmail($newEmail,$verifyHash);

    setcookie("email","",time()-1000,'/',".".getSiteDomain());
    setcookie("token","",time()-1000,'/',".".getSiteDomain());

    session_destroy();

?>
<!doctype html>
<html lang="en">
<head>
    <title><?php require_once("_names.php"); echo getSiteName(); ?> - Change Email<?php require_once("_init.php");echo getTitleTagline();?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
	<?php include("h.php");?>

	<div id="mainbody" align="center"> 
		<br>
        <br>
        <br>
		<div style="font-size:14px;">
		Please check your email at <?php echo $email; ?> to verify your account.
		</div>
	</div>
    <?php include("f.php");?>
</body>
</html>
