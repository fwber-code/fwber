<?php

    session_start();

    require_once("_init.php");
    require_once("_secrets.php");

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    //make sure we've got an action.
    if(!isset($_GET['emailAddress'])||empty($_GET['emailAddress']))exit('emailAddress'); else $emailAddress= mysqli_escape_string($db,$_GET['emailAddress']);

    $dbquerystring = sprintf("SELECT id, lastPasswordResetTime FROM ".$dbname.".users WHERE email='%s'",$emailAddress);
    $dbquery = mysqli_query($db,$dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);

    if($dbresults)
    {
        $lastPasswordResetTime = $dbresults['lastPasswordResetTime'];

        if(time()>$lastPasswordResetTime+60*60*1)
        {
            $lastPasswordResetTime=time();

            //make new hash
            $verifyHash = md5(rand(0,1000));

            //set verify to 0, set lastPasswordResetTime
            $dbquerystring =
            sprintf("UPDATE ".$dbname.".users SET verifyHash = '%s', lastPasswordResetTime = '%s' WHERE email='%s'",
            $verifyHash,
            $lastPasswordResetTime,
            $emailAddress
            );
            if(!mysqli_query($db,$dbquerystring))exit("didn't work");

            //done
            mysqli_close($db);

            //send verification email
            sendPasswordResetVerificationEmail($emailAddress,$verifyHash);

            echo "done";
        }
        else echo "<br><br>Please wait 1 hour before requesting a password reset for this email address. This prevents spam in case this address does not have a ".getSiteName()." account.";

    }
