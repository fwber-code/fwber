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

	include("_debug.php");
	include("_names.php");
	include("_init.php");
    include("_profileVars.php");

	goHomeIfCookieNotSet();
	
	$profileDone=isProfileDone();

	//go to edit profile if profile isn't finished
	if($profileDone==0)
	{
		header('Location: '.getSiteURL().'/editprofile');
		exit();
	}

	//if profile is done, fill in all the boxes from the database, otherwise it will use the initialized values in _profileVars.php
	if($profileDone==1)
	{
		initAllVariablesFromDB();
	}
?>
<!doctype html>
<html lang="en">
<head>
    <title><?php echo getSiteName(); ?> - Manage Your Profile<?php echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
<?php include("h.php");?>
<div id="" align="center" class="matches">
    <br>
    <br>
    <br>
    <br>
    <div class="card p-5 m-2 shadow-sm" style="display:inline-block;">
        <a class="btn btn-outline-secondary my-0 px-3 mx-1" href="/managepics">Manage Pictures</a>
        <a class="btn btn-outline-secondary my-0 px-3 mx-1" href="/editprofile">Edit Profile</a>
        <a class="btn btn-outline-secondary my-0 px-3 mx-1" href="/settings">Account Settings</a>
    </div>

<?php
    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    $email = mysqli_escape_string($db,$_SESSION["email"]);

    $dbquerystring =
    "SELECT * FROM ".$dbname.".users WHERE email = '{$email}'";

    $dbquery = mysqli_query($db,$dbquerystring);

    if($dbquery)
    {
        $g = mysqli_fetch_array($dbquery);
        mysqli_free_result($dbquery);
    }
    mysqli_close($db);

    $g['age'] = getAge($g['birthdayMonth'],$g['birthdayDay'],$g['birthdayYear']);
    $g['calculatedDistance']=getDistanceBetweenPoints($lat,$lon,$g['lat'],$g['lon']);
    $g['commonDesires']="All";

    include("_getProfile.php");
?>
		<br>
        <div class="card p-5 m-2 shadow-sm blueToWhite" style="display:inline-block; color:#222; text-shadow:#eee 1px 1px 0px;">
            Your Public Profile
            <div class="smallText" style="color:#222;font-size:11pt">
                This is how others see your profile at first.
            </div>

            <?php getProfile($g,"public"); ?>
        </div>
		<br>
		<br>
        <div class="card p-5 m-2 shadow-sm pinkToWhite" style="display:inline-block; color:#222; text-shadow:#eee 1px 1px 0px;">
			    Your Private Profile
				<div class="smallText" style="color:#222;font-size:11pt">
				    This is how your profile looks fully unlocked after you have agreed to match someone.
				</div>
                <?php getProfile($g,"private"); ?>
		</div>
        <br>
		<?php //TODO: show map here, highlight searched area ?>

</div>
<?php include("f.php");?>

<script src="/js/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>



</body>
</html>

