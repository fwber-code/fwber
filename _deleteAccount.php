<?php
/*
    Copyright 2020 FWBer.com

    This file is part of FWBer.

    FWBer is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    FWBer is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero Public License for more details.

    You should have received a copy of the GNU Affero Public License
    along with FWBer.  If not, see <https://www.gnu.org/licenses/>.
*/
    session_start();

    require_once("_init.php");
	
	if($_SERVER["REQUEST_METHOD"] != "POST"){header('Location: '.getSiteURL());exit();}

    require_once("_profileVars.php");
    require_once("_dbSettings.php");
    require_once("_globals.php");

    //first make sure we are a legit user.
    if(validateSessionOrCookiesReturnLoggedIn()==false){header('Location: '.getSiteURL());exit();}//full auth for actions

    goHomeIfCookieNotSet();

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    //make sure we've got an action.
    if(!isset($_POST['myPass'])||empty($_POST['myPass']))exit('myPass'); else $myPass= mysqli_escape_string($db,$_POST['myPass']);
    if(!isset($_POST['myPassAgain'])||empty($_POST['myPassAgain']))exit('myPassAgain'); else $myPassAgain= mysqli_escape_string($db,$_POST['myPassAgain']);
    if(!isset($_POST['goodbyeCheck'])||empty($_POST['goodbyeCheck']))exit('goodbyeCheck'); else $goodbyeCheck= mysqli_escape_string($db,$_POST['goodbyeCheck']);

    if($myPass!=$myPassAgain)exit("Passwords don't match");
    if($goodbyeCheck!="goodbye")exit("Didn't say goodbye.");

    //authenticate old pass
    $email = mysqli_real_escape_string($db,$_SESSION["email"]);

    $dbquerystring = sprintf("SELECT passwordHash, dateJoined, publicPics, privatePics FROM ".$dbname.".users WHERE email='%s'",$email);
    $dbquery = mysqli_query($db, $dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);
    mysqli_free_result($dbquery);

    $message = "";

    if(
        $dbresults==null
        ||$dbresults['passwordHash']==null
        ||getSaltedPassword($myPass,$dbresults['dateJoined'])!=$dbresults['passwordHash']
    )
    {
        $message = "Password was wrong.";
    }

    if($message=="")
    {
        //connect to database, find any pictures for me.
        //delete them in the db too.
        $publicPics=explode(",",trim(trim($dbresults['publicPics']),","));
        $privatePics=explode(",",trim(trim($dbresults['privatePics']),","));

        //delete pics
        foreach($publicPics as &$s)
        {
            if($s=="")continue;

            unlink("./fwberImageStore/".$s);
            unlink("./fwberImageStore/".$s."_b");

            $s = "";
        }

        foreach($privatePics as &$s)
        {
            if($s=="")continue;

            unlink("./fwberImageStore/".$s);
            unlink("./fwberImageStore/".$s."_b");

            $s = "";
        }

        //delete pics in database
        $dbquerystring =
        sprintf("UPDATE ".$dbname.".users SET publicPics = '%s',privatePics = '%s' WHERE email='%s'",
        trim(trim(implode(",",$publicPics)),","),
        trim(trim(implode(",",$privatePics)),","),
        $email
        );
        if(!mysqli_query($db,$dbquerystring))exit("didn't work");

        //remove user from database
        $dbquerystring =
        sprintf("DELETE FROM ".$dbname.".users WHERE email='%s'",
        $email
        );
        if(!mysqli_query($db,$dbquerystring))exit("didn't work");

        //delete cookies
        setcookie("email","",time()-1000,'/',".".getSiteDomain());
        setcookie("token","",time()-1000,'/',".".getSiteDomain());

        session_destroy();

        mysqli_close($db);

        $message = "Your account has been deleted.";

    }

    ?>
<!doctype html>
<html lang="en">
<head>
    <title><?php require_once("_names.php"); echo getSiteName(); ?> - Delete Account<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
	<?php include("h.php");?>
	<div id="mainbody" align="center"> 
		<br>
        <br>
        <br>
		<div style="font-size:14px;">
			<?php echo $message; ?>
		</div>
	</div>
    <?php include("f.php");?>
</body>
</html>