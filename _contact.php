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

    if($_SERVER["REQUEST_METHOD"] != "POST"){header('Location: '.getSiteURL());exit();}

include("_profileVars.php");
include("_secrets.php");
include("_globals.php");
include("_emailFunctions.php");

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    //make sure we've got an action.
    $message = "";
    $subject = "";
    $emailAddress = "";

    if(!isset($_POST['message'])||empty($_POST['message']))exit('message'); else $message= mysqli_escape_string($db,convert_line_breaks($_POST['message']));
    if(!isset($_POST['subject'])||empty($_POST['subject']))exit('subject'); else $subject= mysqli_escape_string($db,$_POST['subject']);

    if(isset($_POST['emailAddress'])&&!empty($_POST['emailAddress']))
    $emailAddress= mysqli_escape_string($db,$_POST['emailAddress']);

    if($message!="")
    {
        sendContactEmail($subject,$message,$emailAddress);
    }

    mysqli_close($db);

?>
<!doctype html>
<html lang="en">
<head>
    <title><?php require_once("_names.php"); echo getSiteName(); ?> - Contact Us<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
	<?php include("h.php");?>
	<div align="center" style="font-size:16px;">
        Thank you for contacting us, we will get back to you.
	</div>
    <meta http-equiv="refresh" content="3;url=/"/>
    <?php include("f.php");?>
</body>
</html>

