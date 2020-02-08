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
include("_profileVars.php");
include("_secrets.php");
include("_globals.php");
include("_emailFunctions.php");

	//first make sure we are a legit user.
	if(validateSessionOrCookiesReturnLoggedIn()==false){header('Location: '.getSiteURL());return;}//full auth for actions
	
	goHomeIfCookieNotSet();

	//make sure we've got an action.
	if(!isset($_GET['emailMatches'])||empty($_GET['emailMatches']))exit('no emailMatches');
	if(!isset($_GET['emailInterested'])||empty($_GET['emailInterested']))exit('no emailInterested');
	if(!isset($_GET['emailApproved'])||empty($_GET['emailApproved']))exit('no emailApproved');

	$db = mysqli_connect($dburl,$dbuser,$dbpass);
	if(!$db)exit(mysqli_connect_error());
	
	$email = mysqli_escape_string($db,$_SESSION["email"]);	
	
	$emailMatches=0;
	$emailInterested=0;	
	$emailApproved=0;	
	
	if($_GET['emailMatches']=="true")$emailMatches=1;
	if($_GET['emailInterested']=="true")$emailInterested=1;
	if($_GET['emailApproved']=="true")$emailApproved=1;

	$dbquerystring = 
	sprintf("UPDATE ".$dbname.".users SET emailMatches = '%s',emailInterested = '%s',emailApproved = '%s' WHERE email='%s'",
	$emailMatches,
	$emailInterested,
	$emailApproved,
	$email
	);
	if(!mysqli_query($db,$dbquerystring))exit("didn't work");
	
	//done
	mysqli_close($db);

	//send back done
	echo "done";

