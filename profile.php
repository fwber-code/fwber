<?php 

	session_start(); 

	require_once("_init.php");
    require_once("_profileVars.php");

	goHomeIfCookieNotSet();
	
	$profileDone=isProfileDone();

	//go to edit profile if profile isn't finished
	if($profileDone==0)
	{
		header('Location: '.getSiteURL().'/editprofile.php');
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
    <title><?php require_once("_names.php"); echo getSiteName(); ?> - My Profile<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
<?php include("h.php");?>
<div id="mainbody" align="center" class="matches">
	<table id="body_table">
		<tr>
			<td id="middle_column" align="center" valign="top">
				<div class="outerOutlineContainer" style="margin:0px;padding:0px;width:100%;">
				<div class="normalContainer" style="margin:0px;padding:0px;">
				<div class="whiteToGray" style="padding:2px;border-radius:8px;">
				<div align="center">
					<table >
						<tr>
							<td align="center">
									<div class="outerOutlineContainer" style="margin:0px;padding:0px;">
									<div class="" style="margin:0px;padding:0px;">
									<div class="whiteToGray" style="font-size:14pt;margin:0px;padding:8px;padding-left:4px; padding-right:4px;border-radius:8px;color:#666667;text-shadow:#fff 1px 1px 0px;">
                                        <a href="/uploadpics" id="button-uploadpics" class="headerbutton"></a>
                                        <br>
                                        Upload / Manage Pictures
									</div>
									</div>
									</div>
							</td>

							<td align="center">
									<div class="outerOutlineContainer" style="margin:0px;padding:0px;">
									<div class="" style="margin:0px;padding:0px;">
									<div class="whiteToGray" style="font-size:14pt;margin:0px;padding:8px;border-radius:8px;color:#666667;text-shadow:#fff 1px 1px 0px;">
										<a href="/editprofile" id="button-editprofile" class="headerbutton"></a>
										<br>
										Edit Your Profile
									</div>
									</div>
									</div>
							</td>

							<td align="center">
									<div class="outerOutlineContainer" style="margin:0px;padding:0px;">
									<div class="" style="margin:0px;padding:0px;">
									<div class="whiteToGray" style="font-size:14pt;margin:0px;padding:8px;padding-left:4px; padding-right:4px;border-radius:8px;color:#666667;text-shadow:#fff 1px 1px 0px;">
                                        <a href="/settings" id="button-settings" class="headerbutton"></a>
                                        <br>
                                        Account Settings
									</div>
									</div>
									</div>
							</td>
						</tr>
					</table>
				</div>
				</div>
				</div>
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
		<div style="border:#000 1px solid; border-radius:8px;margin:0px;">
			<div class="blueToWhiteCaption" style="border-bottom:0px;border-radius:0px;border-top-left-radius:8px;border-top-right-radius:8px;margin:0px;">
			    Your Public Profile
				<div class="smallText" style="color:#222;font-size:11pt">
				    This is how others see your profile at first.
				</div>
			</div>
			
			<div class="whiteToBlue" style="border-radius:0px;border-bottom-left-radius:8px;border-bottom-right-radius:8px;border:#0397ff 1px solid;border-top:0px;">
                <div style="border-radius:8px;">
                    <?php getProfile($g,"public"); ?>
                </div>
			</div>
		</div>
		<br>
		<br>
		<div style="border:#000 1px solid; border-radius:8px;margin:0px;">
			<div class="greenToWhiteCaption" style="border-bottom:0px;border-radius:0px;border-top-left-radius:8px;border-top-right-radius:8px;margin:0px;">
			Your "First Base" Profile
				<div class="smallText" style="color:#222;font-size:11pt">
				This is how your profile looks when you've expressed <b>mutual interest</b> with someone and offered to share pics.
				</div>
			<br>
			<input type="button" class="buttonGreen" name="<?php echo $g['id']; ?>" value='Show/Hide' onclick="toggleShowProfile('<?php echo "firstbase".$g['id']; ?>');"></input>
			</div>
			<div class="whiteToGreen" style="border-radius:0px;border-bottom-left-radius:8px;border-bottom-right-radius:8px;border:#6d6 1px solid;border-top:0px;padding:4px;">
                <div style="border-radius:8px;">
                    <?php getProfile($g,"firstbase"); ?>
                </div>
			</div>
		</div>
		<br>
		<br>
		<div style="border:#000 1px solid; border-radius:8px;margin:0px;">
			<div class="pinkToWhiteCaption" style="border-bottom:0px;border-radius:0px;border-top-left-radius:8px;border-top-right-radius:8px;margin:0px;">
			    Your "All The Way" Profile
				<div class="smallText" style="color:#222;font-size:11pt">
				    This is how your profile looks <b>fully unlocked</b>.
				</div>
			<br>
			<input type="button" class="buttonPink" name="<?php echo $g['id']; ?>" value='Show/Hide' onclick="toggleShowProfile('<?php echo "alltheway".$g['id']; ?>');"></input>
			</div>
			<div class="whiteToPink" style="border-radius:0px;border-bottom-left-radius:8px;border-bottom-right-radius:8px;border:#ff66cb 1px solid;border-top:0px;padding:4px;">
                <div style="border-radius:8px;">
					<?php getProfile($g,"alltheway"); ?>
                </div>
			</div>
		</div>
		<!-- show map here, highlight searched area -->

			</td> <!-- middle_column -->
		</tr>
	</table>
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

    function hidePrivateProfiles(profileBoxID)
    {
        document.getElementById("firstbase"+profileBoxID).style.display="none";
        document.getElementById("firstbase"+profileBoxID+"details").style.display="";

        document.getElementById("alltheway"+profileBoxID).style.display="none";
        document.getElementById("alltheway"+profileBoxID+"details").style.display="";
    }

    function toggleShowProfile(profileBoxID)
    {
        var e = document.getElementById(profileBoxID);
        $(e).toggle("blind",{direction: "vertical"},1000);
    }
</script>

<script type="text/javascript">hidePrivateProfiles('<?php echo $g['id']; ?>');</script>
</body>
</html>

