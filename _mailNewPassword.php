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
    require_once("_secrets.php");

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    //make sure we've got an action.
    if(!isset($_GET['email'])||empty($_GET['email']))exit('no email'); else $email= mysqli_escape_string($db,$_GET['email']);
    if(!isset($_GET['verifyHash'])||empty($_GET['verifyHash']))exit('no verify'); else $verifyHash= mysqli_escape_string($db,$_GET['verifyHash']);

    $dbquerystring = sprintf("SELECT id, verifyHash, dateJoined FROM ".$dbname.".users WHERE email='%s'",$email);
    $dbquery = mysqli_query($db,$dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);

    $success=false;
		
	if($dbresults)
	{
		if($verifyHash==$dbresults['verifyHash'])
		{
			//make new password	
			$length = 8;
			$chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ23456789';
            $count = mb_strlen($chars);

            for ($i = 0, $newPass = ''; $i < $length; $i++)
            {
                $index = rand(0, $count - 1);
                $newPass .= mb_substr($chars, $index, 1);
            }

			$dateJoined = $dbresults['dateJoined'];

			$success=true;
			$success = sendNewPasswordEmail($email,$newPass,$verifyHash);
			
			if($success==true) //commit the new pass to database.
			{
				$dbquerystring = 
				sprintf("UPDATE ".$dbname.".users SET passwordHash = '%s' WHERE email='%s'",
				getSaltedPassword($newPass,$dateJoined),
				$email
				);
				if(!mysqli_query($db,$dbquerystring))exit("didn't work");
				
				//done
				mysqli_close($db);
			}
		}
	}
?>
<!doctype html>
<html lang="en">
<head>
	<title><?php require_once("_names.php"); echo getSiteName(); ?> - Reset Password<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
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
			We've sent your new password to you. Please check your email!
            <br>
            <br>
		</div>
<?php
	}
	else
	{
		echo '<meta http-equiv="refresh" content="5;url='.getSiteURL().'/signin"/>';
?>
		<div style="color:#000;font-size:14pt;text-shadow:#aaa 1px 1px 1px;">
			<br><br>
			
			<div style="color:#000;font-size:12pt;text-shadow:#aaa 1px 1px 1px;">
				Something went wrong. Your password has not been changed. Please try again later.<br>
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

