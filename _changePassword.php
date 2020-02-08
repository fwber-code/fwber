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

    if(validateSessionOrCookiesReturnLoggedIn()==false){header('Location: '.getSiteURL());return;}//full auth for actions

    goHomeIfCookieNotSet();

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    if(!isset($_POST['oldPass'])||empty($_POST['oldPass']))exit('oldPass'); else $oldPass= mysqli_escape_string($db,$_POST['oldPass']);
    if(!isset($_POST['newPass'])||empty($_POST['newPass']))exit('newPass'); else $newPass= mysqli_escape_string($db,$_POST['newPass']);
    if(!isset($_POST['verifyPass'])||empty($_POST['verifyPass']))exit('verifyPass'); else $verifyPass= mysqli_escape_string($db,$_POST['verifyPass']);

    if($newPass!=$verifyPass)exit("Passwords don't match");

    //authenticate old pass
    $email = mysqli_escape_string($db,$_SESSION["email"]);

    $dbquerystring = sprintf("SELECT passwordHash, dateJoined, dateLastSignedIn FROM ".$dbname.".users WHERE email='%s'",$email);
    $dbquery = mysqli_query($db,$dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);
    mysqli_free_result($dbquery);

    $message = "";

    if(
        $dbresults==null
        ||$dbresults['passwordHash']==null
        ||getSaltedPassword($oldPass,$dbresults['dateJoined'])!=$dbresults['passwordHash']
    )
    {
        $message = "Old password was wrong.";
    }

    if($message=="")
    {
        $dateJoined = $dbresults['dateJoined'];

        //set new pass hash in database
        $dbquerystring =
        sprintf("UPDATE ".$dbname.".users SET passwordHash = '%s' WHERE email='%s'",
        getSaltedPassword($newPass,$dateJoined),
        $email
        );
        if(!mysqli_query($db,$dbquerystring))exit("didn't work");

        //delete cookies
        setcookie("email","",time()-1000,'/',".".getSiteDomain());
        setcookie("token","",time()-1000,'/',".".getSiteDomain());

        session_destroy();

        mysqli_close($db);

        $message = "Password changed. Please sign in using your new password.";
    }
?>
<!doctype html>
<html lang="en">
<head>
    <title><?php require_once("_names.php"); echo getSiteName(); ?> - Change Password<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
	<?php include("h.php");?>
	<div id="mainbody" align="center"> 
		<br><br><br>
		
		<div style="font-size:14px;">
			<?php echo $message; ?>
		</div>
	</div>
    <?php include("f.php");?>
</body>
</html>

