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


    require_once("_init.php");
    require_once("_secrets.php");

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    //make sure we've got an action.
    if(!isset($_GET['email'])||empty($_GET['email']))exit('no email'); else $email= mysqli_escape_string($db,$_GET['email']);
    if(!isset($_GET['verifyHash'])||empty($_GET['verifyHash']))exit('no verify'); else $verifyHash= mysqli_escape_string($db,$_GET['verifyHash']);
    if(!isset($_GET['type'])||empty($_GET['type']))exit('no type'); else $type= mysqli_escape_string($db,$_GET['type']);

    //types are:
    //all - uncheck all email
    //matches - just new match notices
    //interested - matches requesting permissions
    //approved - matches that approved my requests

    $message = "";
    if($type=="all")$message="all mail notifications.";
    else if($type=="matches")$message="any new match notifications.";
    else if($type=="interested")$message="any private profile request notifications.";
    else if($type=="approved")$message="any notifications for approval of your private profile requests.";
    else exit("unknown type");

    $dbquerystring = sprintf("SELECT id, verifyHash, emailMatches, emailInterested, emailApproved FROM ".$dbname.".users WHERE email='%s'",$email);
    $dbquery = mysqli_query($db,$dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);

    $success=false;

    if($dbresults)
    {
        $emailMatches = $dbresults['emailMatches'];
        $emailInterested = $dbresults['emailInterested'];
        $emailApproved = $dbresults['emailApproved'];

        if($type=="all")
        {
            $emailMatches=0;
            $emailInterested=0;
            $emailApproved=0;
        }

        if($type=="matches")$emailMatches=0;
        if($type=="interested")$emailInterested=0;
        if($type=="approved")$emailApproved=0;

        if($verifyHash==$dbresults['verifyHash'])
        {
            $dbquerystring =
            sprintf("UPDATE ".$dbname.".users SET emailMatches = '%s', emailInterested = '%s', emailApproved = '%s' WHERE email='%s'",
            $emailMatches,
            $emailInterested,
            $emailApproved,
            $email
            );
            if(!mysqli_query($db,$dbquerystring))exit("didn't work");

            //done
            mysqli_close($db);

            $success=true;
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
	<title><?php require_once("_names.php"); echo getSiteName(); ?> - Unsubscribe<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
<?php include("h.php");?>
<div id="mainbody" align="center">
	<br>
	<br>
	<table id="body_table">
		<tr>
			<td id="middle_column" align="center" valign="top">
			<br>
			<br>
			<br>
			
<?php
	if($success)
	{
?>
		<div style="color:#0096ff;font-size:14pt;text-shadow:#aad 1px 1px 1px;">
			You have been unsubscribed from <?php echo $message; ?><br><br>
			If you change your mind, you can always change it back in your <a href="/settings">Settings</a> page.
		</div>
<?php
	}
	else
	{
		echo '<meta http-equiv="refresh" content="5;url='.getSiteURL().'/signin"/>';
?>
		<div style="color:#000;font-size:14pt;text-shadow:#aaa 1px 1px 1px;">
			<br>
            <br>
			<div style="color:#000;font-size:12pt;text-shadow:#aaa 1px 1px 1px;">
				Something went wrong.<br>
				Please set your preferences manually in your <a href="/settings">Settings</a> page.
				<br>
			</div>
		</div>

<?php
	}
?>
			</td>
		</tr>
	</table>
	<br>
</div>
<?php include("f.php");?>
</body>
</html>

