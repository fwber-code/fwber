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

include("_init.php");
include("_secrets.php");
include("_names.php");
include("_debug.php");
?>
<!doctype html>
<html lang="en">
<head>
    <title><?php require_once("_names.php"); echo getSiteName(); ?> - Verify Email Address<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
<?php include("h.php");?>
<div class="col-sm-12 my-auto text-center">
<?php
    if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['verifyHash']) && !empty($_GET['verifyHash']))
    {
        $db = mysqli_connect($dburl,$dbuser,$dbpass);
        if(!$db)exit(mysqli_connect_error());

        $email = mysqli_escape_string($db,$_GET['email']); // Set email variable
        $verifyHash = mysqli_escape_string($db,$_GET['verifyHash']); // Set hash variable

        $dbquery = mysqli_query($db,"SELECT email, verifyHash, verified FROM ".$dbname.".users WHERE email='".$email."' AND verifyHash='".$verifyHash."' AND verified='0'");
        $matches  = mysqli_num_rows($dbquery);

        if($matches>0)
        {
            mysqli_query($db,"UPDATE ".$dbname.".users SET verified='1' WHERE email='".$email."' AND verifyHash='".$verifyHash."' AND verified='0'");
            echo '<meta http-equiv="refresh" content="3;url='.getSiteURL().'/signin"/>';
?>
		<div style="color:#0096ff;font-size:14pt;text-shadow:#aad 1px 1px 1px;">
			Your email address has been verified.<br><br>
			
			<div style="color:#000;font-size:12pt;text-shadow:#aaa 1px 1px 1px;">
				You can now sign in.<br>
				<br>
			</div>
		</div>

<?php
        }
        else
        {

            $dbquery = mysqli_query($db,"SELECT email, verifyHash, verified FROM ".$dbname.".users WHERE email='".$email."' AND verifyHash='".$verifyHash."' AND verified='1'");
            $matches  = mysqli_num_rows($dbquery);

            if($matches>0)
            {
?>

                    <div style="color:#000;font-size:12pt;text-shadow:#aaa 1px 1px 1px;">
                        Your email address has already been verified. Please try signing in.<br>
                        <br>
                    </div>


<?php
                echo '<meta http-equiv="refresh" content="3;url='.getSiteURL().'/signin"/>';

            }
            else
            {
?>

                    <div style="color:#000;font-size:12pt;text-shadow:#aaa 1px 1px 1px;">
                        Your email address could not be found. Please register an account..<br>
                        <br>
                    </div>


<?php
                echo '<meta http-equiv="refresh" content="3;url='.getSiteURL().'/join"/>';
            }


        }
    }
?>

</div>
<?php include("f.php");?>
</body>
</html>
