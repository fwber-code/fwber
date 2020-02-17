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

    include("_init.php");
    include("_debug.php");
    include("_names.php");
    include("_secrets.php");

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

            include("_emailFunctions.php");
            //send verification email
            sendPasswordResetVerificationEmail($emailAddress,$verifyHash);

            echo "done";
        }
        else echo "<br><br>Please wait 1 hour before requesting a password reset for this email address. This prevents spam in case this address does not have a ".getSiteName()." account.";

    }
