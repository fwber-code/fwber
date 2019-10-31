<?php 

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
        <h4 class="h3 mb-3 font-weight-normal text-center">Manage Your Profile</h4>

                                        <a href="/managepics" id="button-uploadpics" class="headerbutton"></a>
                                        <br>
                                        Manage Pictures

										<a href="/editprofile" id="button-editprofile" class="headerbutton"></a>
										<br>
										Edit Your Profile

                                        <a href="/settings" id="button-settings" class="headerbutton"></a>
                                        <br>
                                        Account Settings
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
        <div class="card p-5 m-2 shadow-sm blueToWhite" style="display:inline-block;">
            Your Public Profile
            <div class="smallText" style="color:#222;font-size:11pt">
                This is how others see your profile at first.
            </div>

            <?php getProfile($g,"public"); ?>
        </div>
		<br>
		<br>
        <div class="card p-5 m-2 shadow-sm greenToWhite" style="display:inline-block;">
			Your "First Base" Profile
				<div class="smallText" style="color:#222;font-size:11pt">
				This is how your profile looks when you've expressed <b>mutual interest</b> with someone and offered to share pics.
				</div>
                <?php getProfile($g,"firstbase"); ?>
		</div>
		<br>
		<br>
        <div class="card p-5 m-2 shadow-sm pinkToWhite" style="display:inline-block;">
			    Your "All The Way" Profile
				<div class="smallText" style="color:#222;font-size:11pt">
				    This is how your profile looks <b>fully unlocked</b>.
				</div>
                <?php getProfile($g,"alltheway"); ?>
		</div>

		<?php //TODO: show map here, highlight searched area ?>

</div>
<?php include("f.php");?>

<script src="/js/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>

<script type="text/javascript">
    function toggleExpandProfile(profileBoxID)
    {
        var e = document.getElementById(profileBoxID+"details");
        $(e).toggle("blind","",1000);
    }
</script>

</body>
</html>

