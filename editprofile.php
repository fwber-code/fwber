<?php
    session_start();

    include("_debug.php");
    include("_init.php");
    include("_names.php");

?>
<!doctype html>
<html lang="en">
<head>
	<title><?php echo getSiteName(); ?> - Edit Profile<?php echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
<?php

    // initialize variables
	include("_profileVars.php");


	//first make sure we are a legit user.
	if(validateSessionOrCookiesReturnLoggedIn()==false){echo '<meta http-equiv="refresh" content="1;url='.getSiteURL().'"/>';return;}//full auth for actions

	goHomeIfCookieNotSet();

	$profileDone=isProfileDone();

	// handle post
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		//fill in all the variables
		updateAllVariablesFromPOSTData();

		$wasProfileDone=$profileDone;

		//if we got this far, it means the form was valid. now let's put our variables into the database
		updateDBWithAllVariables();

?>
		
		<br>
        <br>
		Just a moment...
		<br>
        <br>
		
<?php
		if($wasProfileDone==0)
		{
			//notify all matches

            //get all matches
            $db = mysqli_connect($dburl,$dbuser,$dbpass);
            if(!$db)exit(mysqli_connect_error());

            $email = mysqli_escape_string($db,$_SESSION["email"]);

            include("_getMatches.php");
            $result_array = getMatches($email);

            //foreach
            if(count($result_array)>0)
            {
                foreach($result_array as &$g)
                {
                    //if they have checked allowed emailMatches
                    if($g['emailMatches']==1)
                    {
                        $myFirstName=$firstName;
                        $theirEmailAddress=$g['email'];

                        sendMatchNoticeEmail($myFirstName,$theirEmailAddress,$g['verifyHash']);
                    }
                }
            }
		}
		
		//-------------------------------------------------	
		
		//if they don't have any pictures, redirect to managepics.php
		
		//connect to database
		$db = mysqli_connect($dburl,$dbuser,$dbpass);
		if(!$db)exit(mysqli_connect_error());
		
		$email = mysqli_escape_string($db,$_SESSION["email"]);
	
		//get my userid
		$dbquerystring = sprintf("SELECT publicPics, privatePics FROM ".$dbname.".users WHERE email='%s'",$email);
		$dbquery = mysqli_query($db,$dbquerystring);
		$dbresults = mysqli_fetch_array($dbquery);
		
		$publicPics=explode(",",trim(trim($dbresults['publicPics']),","));
		$privatePics=explode(",",trim(trim($dbresults['privatePics']),","));
		
		mysqli_free_result($dbquery);
	
		//done with the db
		mysqli_close($db);
		
		if(count($publicPics)==1&&$publicPics[0]==""&&count($privatePics)==1&&$privatePics[0]=="")
		{

			echo '<meta http-equiv="refresh" content="1;url='.getSiteURL().'/managepics"/>';
			return;
		}

		//now let's redirect to profile.php so they can see their handiwork and upload pictures.
		echo '<meta http-equiv="refresh" content="1;url='.getSiteURL().'/profile"/>';
		return;
	}

	//if profile is done, fill in all the boxes from the database, otherwise it will use the initialized values in _profileVars.php
	if($profileDone==1)
	{
		initAllVariablesFromDB();
	}		

?>
</head>
<body class="d-flex flex-column h-100">
    <?php include("h.php");?>
    <div class="col-sm-12 my-auto text-center">
        <br>
        <br>
        <br>
        <br>
        <h1 class="h3 mb-3 font-weight-normal text-center"> Edit Profile</h1>

        <div class="smallText" style="font-size:16px;">
        <br>
        <span style="font-weight:bold;font-size:24pt;color:#8af;"><?php echo getSiteName();?> is different.</span><br>
        <?php echo getSiteName();?> automatically connects you with matches based on your preferences.<br>
        We want to match you with the right results, so please choose your options carefully.<br>
        </div>
        <br>

	<form class="form-signin" action="editprofile" method="POST" enctype="multipart/form-data" name="editProfileFormName" id="editProfileFormID">
	<fieldset style="text-align:center;border:none;">

		<div class="captionText">
		Area
		<div class="smallText">
		We'll only match you in the area you specify.<br>
		</div>
		</div>

		<?php //TODO: show floating avatar which changes in realtime ?>
		<?php //TODO: dropdown for country, if not USA change zip code to city with javascript ?>

        <table>
            <tbody>
                <tr>
                    <td style="text-align:right; width:40%;">
                        <label for="country">Country</label>
                    </td>
                    <td style="text-align:left;">
                        <select name="country" id="country" class="required" style="max-width:200px;">
                        <?php include("_countrySelector.php"); ?>
                        </select>
                    </td>
                </tr>

                <?php //TODO: get city with geocode using javascript ?>
                <tr>
                    <td style="text-align:right;">
                        <label for="postalCode">Zip / Postal Code</label>
                    </td>
                    <td style="text-align:left;">
                        <input type="text" class="required" class="postalCode" name="postalCode" id="postalCode" style="width:8em" <?php if($profileDone==1)echo 'value="'.$postalCode.'"'; ?>>
                    </td>
                </tr>
                <?php //TODO: if not in USA, do kilometers, don't use zip code. ?>
                <tr>
                    <td style="text-align:right;">
                     <label for="distance">Distance To Search</label>
                    </td>
                    <td style="text-align:left;">
                        <select name="distance" id="distance" class="required" >
                            <option value="dist0m"  <?php if($profileDone==1&&$distance=="dist0m"	)echo 'selected="selected"'; ?>>Same City As Me</option>
                            <option value="dist5m" <?php if($profileDone==1&&$distance=="dist5m"	)echo 'selected="selected"'; ?>>5 Miles</option>
                            <option value="dist10m" <?php if($profileDone==1&&$distance=="dist10m"	)echo 'selected="selected"'; ?>>10 Miles</option>
                            <option value="dist20m" <?php if($profileDone==1&&$distance=="dist20m"	)echo 'selected="selected"'; ?>>20 Miles</option>
                            <option value="dist50m" <?php if($profileDone==1&&$distance=="dist50m"	)echo 'selected="selected"'; if($profileDone==0)echo 'selected="selected"'; ?>>50 Miles</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php //TODO: show map here, highlight searched area ?>

		<div class="saveButton"></div>
		
		<div class="captionText">
		About You
		</div>

        <table>
            <tbody>
            <tr>
                <td style="text-align:right; width:40%;">
                    <label for="firstName">Your Nickname</label>
                </td>
                <td style="text-align:left;">
                    <input type="text" class="required" id="firstName" name="firstName" style="width:12em" <?php if($profileDone==1)echo 'value="'.$firstName.'"'; ?>>
                </td>
            </tr>
            <tr>
                <td style="text-align:right;">
                    <label>Your Date Of Birth</label>
                </td>
                <td style="text-align:left;">
                    <label for="birthdayMonth" class="sr-only">Month</label>
                    <input type="text" class="required" id="birthdayMonth" name="birthdayMonth" style="width:5em" <?php if($profileDone==1)echo 'value="'.$birthdayMonth.'"'; ?> placeholder="MM">

                    <label for="birthdayDay" class="sr-only">Day</label>
                    <input type="text" class="required" id="birthdayDay" name="birthdayDay" style="width:5em" <?php if($profileDone==1)echo 'value="'.$birthdayDay.'"'; ?> placeholder="DD">

                    <label for="birthdayYear" class="sr-only">Year</label>
                    <input type="text" class="required" id="birthdayYear" name="birthdayYear" style="width:5em" <?php if($profileDone==1)echo 'value="'.$birthdayYear.'"'; ?> placeholder="YYYY">
                </td>




            </tr>

            <tr>
                <td style="text-align:right;">
		            <label for="gender">Your Gender</label>
                </td>
                <td style="text-align:left;">
                    <select name="gender" id="gender" class="required" onclick = "toggleMaleFemale();">
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option id="male" 	value="male" 		<?php if($profileDone==1&&$gender=="male"			)echo 'selected="selected"'; ?>>Man</option>
                        <option id="female" value="female" 	<?php if($profileDone==1&&$gender=="female"	            )echo 'selected="selected"'; ?>>Woman</option>
                        <option id="mf" 		value="mf" 			<?php if($profileDone==1&&$gender=="mf"				)echo 'selected="selected"'; ?>>Couple Man + Woman (MF)</option>
                        <option id="mm" 		value="mm" 			<?php if($profileDone==1&&$gender=="mm"				)echo 'selected="selected"'; ?>>Couple Man + Man (MM)</option>
                        <option id="ff" 		value="ff" 			<?php if($profileDone==1&&$gender=="ff"				)echo 'selected="selected"'; ?>>Couple Woman + Woman (FF)</option>
						<option id="group" 	value="group" 		<?php if($profileDone==1&&$gender=="group"			)echo 'selected="selected"'; ?>>Group</option>
                        <option id="mtf" 	value="mtf" 		<?php if($profileDone==1&&$gender=="mtf"			)echo 'selected="selected"'; ?>>TS Woman (MTF)</option>
                        <option id="ftm" 	value="ftm" 		<?php if($profileDone==1&&$gender=="ftm"			)echo 'selected="selected"'; ?>>TS Man (FTM)</option>
                        <option id="cdmtf" 	value="cdmtf" 		<?php if($profileDone==1&&$gender=="cdmtf"			)echo 'selected="selected"'; ?>>CD Woman (MTF)</option>
                        <option id="cdftm" 	value="cdftm" 		<?php if($profileDone==1&&$gender=="cdftm"			)echo 'selected="selected"'; ?>>CD Man (FTM)</option>
                    </select>
                </td>
            </tr>


            <tr>
                <td style="text-align:right;">
                    <label for="body">Your Body</label>
                </td>
                <td style="text-align:left;">
                    <select name="body" id="body" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="tiny" 		<?php if($profileDone==1&&$body=="tiny"				)echo 'selected="selected"'; ?>>Small</option>
                        <option value="slim" 		<?php if($profileDone==1&&$body=="slim"				)echo 'selected="selected"'; ?>>Slim</option>
                        <option value="average" 	<?php if($profileDone==1&&$body=="average"			)echo 'selected="selected"'; ?>>Average</option>
                        <option value="muscular" 	<?php if($profileDone==1&&$body=="muscular"			)echo 'selected="selected"'; ?>>Muscular</option>
                        <option value="curvy" 		<?php if($profileDone==1&&$body=="curvy"			)echo 'selected="selected"'; ?>>Curvy</option>
                        <option value="thick" 		<?php if($profileDone==1&&$body=="thick"			)echo 'selected="selected"'; ?>>Thick</option>
                        <option value="bbw" 		<?php if($profileDone==1&&$body=="bbw"				)echo 'selected="selected"'; ?>>BBW/BBM</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td style="text-align:right;">
                    <label for="hairColor">Your Hair Color</label>
                </td>
                <td style="text-align:left;">
                    <select name="hairColor" id="hairColor" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="light" 		<?php if($profileDone==1&&$hairColor=="light"				)echo 'selected="selected"'; ?>>Light</option>
                        <option value="medium" 	    <?php if($profileDone==1&&$hairColor=="medium"				)echo 'selected="selected"'; ?>>Medium</option>
                        <option value="dark" 		<?php if($profileDone==1&&$hairColor=="dark"				)echo 'selected="selected"'; ?>>Dark</option>
                        <option value="red" 		<?php if($profileDone==1&&$hairColor=="red"					)echo 'selected="selected"'; ?>>Red</option>
                        <option value="gray" 		<?php if($profileDone==1&&$hairColor=="gray"				)echo 'selected="selected"'; ?>>Gray</option>
                        <option value="other" 		<?php if($profileDone==1&&$hairColor=="other"				)echo 'selected="selected"'; ?>>Other</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td style="text-align:right;">
                    <label for="ethnicity">Your Ethnicity</label>
                </td>
                <td style="text-align:left;">
                    <select name="ethnicity" id="ethnicity" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="white" 		<?php if($profileDone==1&&$ethnicity=="white"				)echo 'selected="selected"'; ?>>White</option>
                        <option value="asian" 		<?php if($profileDone==1&&$ethnicity=="asian"				)echo 'selected="selected"'; ?>>Asian</option>
                        <option value="latino" 	    <?php if($profileDone==1&&$ethnicity=="latino"				)echo 'selected="selected"'; ?>>Latino</option>
                        <option value="indian" 	    <?php if($profileDone==1&&$ethnicity=="indian"				)echo 'selected="selected"'; ?>>Indian</option>
                        <option value="black" 		<?php if($profileDone==1&&$ethnicity=="black"				)echo 'selected="selected"'; ?>>Black</option>
                        <option value="other" 		<?php if($profileDone==1&&$ethnicity=="other"				)echo 'selected="selected"'; ?>>Other</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td style="text-align:right;">
                    <label for="hairLength">Your Hair Length</label>
                </td>
                <td style="text-align:left;">
                    <select name="hairLength" id="hairLength" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="bald" 		<?php if($profileDone==1&&$hairLength=="bald"				)echo 'selected="selected"'; ?>>Bald</option>
                        <option value="short" 		<?php if($profileDone==1&&$hairLength=="short"				)echo 'selected="selected"'; ?>>Short</option>
                        <option value="medium" 	<?php if($profileDone==1&&$hairLength=="medium"				)echo 'selected="selected"'; ?>>Medium</option>
                        <option value="long" 		<?php if($profileDone==1&&$hairLength=="long"				)echo 'selected="selected"'; ?>>Long</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td style="text-align:right;">
                    <label for="bedroomPersonality">Your Sexual Confidence</label>
                </td>
                <td style="text-align:left;">
                    <select name="bedroomPersonality" id="bedroomPersonality" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="passive" 		<?php if($profileDone==1&&$bedroomPersonality=="passive"				)echo 'selected="selected"'; ?>>Very Passive</option>
                        <option value="shy" 			<?php if($profileDone==1&&$bedroomPersonality=="shy"					)echo 'selected="selected"'; ?>>Introverted/Shy</option>
                        <option value="confident" 		<?php if($profileDone==1&&$bedroomPersonality=="confident"				)echo 'selected="selected"'; ?>>Extroverted/Confident</option>
                        <option value="aggressive" 	<?php if($profileDone==1&&$bedroomPersonality=="aggressive"				)echo 'selected="selected"'; ?>>Very Aggressive</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td style="text-align:right;">
                    <label for="overallLooks">Your Overall Looks</label>
                </td>
                <td style="text-align:left;">
                    <select name="overallLooks" id="overallLooks" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="ugly" 			<?php if($profileDone==1&&$overallLooks=="ugly"					)echo 'selected="selected"'; ?>>Downright Ugly</option>
                        <option value="plain" 			<?php if($profileDone==1&&$overallLooks=="plain"				)echo 'selected="selected"'; ?>>Plain</option>
                        <option value="quirky" 		<?php if($profileDone==1&&$overallLooks=="quirky"				)echo 'selected="selected"'; ?>>Some Quirks</option>
                        <option value="average" 		<?php if($profileDone==1&&$overallLooks=="average"				)echo 'selected="selected"'; ?>>Average</option>
                        <option value="attractive" 	<?php if($profileDone==1&&$overallLooks=="attractive"			)echo 'selected="selected"'; ?>>Attractive</option>
                        <option value="hottie" 		<?php if($profileDone==1&&$overallLooks=="hottie"				)echo 'selected="selected"'; ?>>Hottie</option>
                        <option value="superModel" 	<?php if($profileDone==1&&$overallLooks=="superModel"			)echo 'selected="selected"'; ?>>Super Model</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td style="text-align:right;">
		            <label for="intelligence">Your Intelligence</label>
                </td>
                <td style="text-align:left;">
                    <select name="intelligence" id="intelligence" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="goodHands" 		<?php if($profileDone==1&&$intelligence=="goodHands"			)echo 'selected="selected"'; ?>>Slower Than Most</option>
                        <option value="bitSlow" 		<?php if($profileDone==1&&$intelligence=="bitSlow"				)echo 'selected="selected"'; ?>>A Bit Slow</option>
                        <option value="average" 		<?php if($profileDone==1&&$intelligence=="average"				)echo 'selected="selected"'; ?>>Average</option>
                        <option value="faster" 		<?php if($profileDone==1&&$intelligence=="faster"				)echo 'selected="selected"'; ?>>A Bit Clever</option>
                        <option value="genius" 		<?php if($profileDone==1&&$intelligence=="genius"				)echo 'selected="selected"'; ?>>Faster Than Most</option>
                    </select>
                </td>
		    </tr>

            <tr>
                <td style="text-align:right;">
                    <label for="tattoos">Your Tattoos</label>
                </td>
                <td style="text-align:left;">
                    <select name="tattoos" id="tattoos" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="none" 		<?php if($profileDone==1&&$tattoos=="none"					)echo 'selected="selected"'; ?>>None</option>
                        <option value="some" 		<?php if($profileDone==1&&$tattoos=="some"					)echo 'selected="selected"'; ?>>Some</option>
                        <option value="allOver" 	<?php if($profileDone==1&&$tattoos=="allOver"				)echo 'selected="selected"'; ?>>All Over!</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td style="text-align:right;">
		            <label for="pubicHair">Your Pubic Hair</label>
                </td>
                <td style="text-align:left;">
                    <select name="pubicHair" id="pubicHair" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="shaved" 		<?php if($profileDone==1&&$pubicHair=="shaved"				)echo 'selected="selected"'; ?>>Shaved</option>
                        <option value="trimmed" 		<?php if($profileDone==1&&$pubicHair=="trimmed"				)echo 'selected="selected"'; ?>>Short Trimmed</option>
                        <option value="cropped" 		<?php if($profileDone==1&&$pubicHair=="cropped"				)echo 'selected="selected"'; ?>>Average Cropped</option>
                        <option value="natural" 		<?php if($profileDone==1&&$pubicHair=="natural"				)echo 'selected="selected"'; ?>>Natural</option>
                        <option value="hairy" 			<?php if($profileDone==1&&$pubicHair=="hairy"				)echo 'selected="selected"'; ?>>Very Hairy</option>
                    </select>
                </td>
		    </tr>

            <tr id="myPenisSize" class="maleAttrib">
                <td style="text-align:right;">
		            <label for="penisSize">Your Penis Size</label>
                </td>
                <td style="text-align:left;">
                    <select name="penisSize" id="penisSize" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="tiny" 		<?php if($profileDone==1&&$penisSize=="tiny"				)echo 'selected="selected"'; ?>>Small</option>
                        <option value="skinny" 	<?php if($profileDone==1&&$penisSize=="skinny"				)echo 'selected="selected"'; ?>>Skinny</option>
                        <option value="average" 	<?php if($profileDone==1&&$penisSize=="average"				)echo 'selected="selected"'; ?>>Average</option>
                        <option value="thick" 		<?php if($profileDone==1&&$penisSize=="thick"				)echo 'selected="selected"'; ?>>Thick</option>
                        <option value="huge" 		<?php if($profileDone==1&&$penisSize=="huge"				)echo 'selected="selected"'; ?>>Huge</option>
                    </select>
                </td>
            </tr>

            <tr id="myBodyHair" class="maleAttrib">
                <td style="text-align:right;">
		            <label for="bodyHair">Your Overall Body Hair</label>
                </td>
                <td style="text-align:left;">
                    <select name="bodyHair" id="bodyHair" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="smooth" 		<?php if($profileDone==1&&$bodyHair=="smooth"				)echo 'selected="selected"'; ?>>Smooth</option>
                        <option value="average" 		<?php if($profileDone==1&&$bodyHair=="average"				)echo 'selected="selected"'; ?>>Average</option>
                        <option value="hairy" 			<?php if($profileDone==1&&$bodyHair=="hairy"				)echo 'selected="selected"'; ?>>Hairy</option>
                    </select>
                </td>
            </tr>

            <tr id="myBreastSize" class="femaleAttrib">
                <td style="text-align:right;">
		            <label for="breastSize">Your Breast Size</label>
                </td>
                <td style="text-align:left;">
                    <select name="breastSize" id="breastSize" class="required" >
                        <option value="" disabled="disabled" <?php if($profileDone==0)echo 'selected="selected"'; ?>></option>
                        <option value="tiny" 		<?php if($profileDone==1&&$breastSize=="tiny"				)echo 'selected="selected"'; ?>>None</option>
                        <option value="small" 		<?php if($profileDone==1&&$breastSize=="small"				)echo 'selected="selected"'; ?>>Small</option>
                        <option value="average" 	<?php if($profileDone==1&&$breastSize=="average"			)echo 'selected="selected"'; ?>>Average</option>
                        <option value="large" 		<?php if($profileDone==1&&$breastSize=="large"				)echo 'selected="selected"'; ?>>Large</option>
                        <option value="huge" 		<?php if($profileDone==1&&$breastSize=="huge"				)echo 'selected="selected"'; ?>>Huge</option>
                    </select>
                </td>
		    </tr>
            </tbody>
        </table>

<div class="saveButton"></div>
<div class="captionText">
Other About You
</div>
<table style="width:100%;">
    <tbody>
    <tr>
       <td style="width:30%;"></td>
       <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_smokeCigarettes" 	value="b_smokeCigarettes" 	<?php if($profileDone==1&&$b_smokeCigarettes==1		)echo 'checked="checked"'; ?>> I Smoke Cigarettes</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_lightDrinker" 		value="b_lightDrinker" 		<?php if($profileDone==1&&$b_lightDrinker==1		)echo 'checked="checked"'; ?>> I'm A Light Drinker</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_heavyDrinker" 		value="b_heavyDrinker" 		<?php if($profileDone==1&&$b_heavyDrinker==1		)echo 'checked="checked"'; ?>> I'm A Heavy Drinker</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_smokeMarijuana" 	value="b_smokeMarijuana" 		<?php if($profileDone==1&&$b_smokeMarijuana==1		)echo 'checked="checked"'; ?>> I Smoke Marijuana</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_psychedelics" 		value="b_psychedelics" 			<?php if($profileDone==1&&$b_psychedelics==1			)echo 'checked="checked"'; ?>> I Use Psychedelics</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_otherDrugs" 		value="b_otherDrugs" 			<?php if($profileDone==1&&$b_otherDrugs==1			)echo 'checked="checked"'; ?>> I Use Other Drugs</input></label><br>
<br>                                               
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_marriedTheyKnow" 	value="b_marriedTheyKnow" 	<?php if($profileDone==1&&$b_marriedTheyKnow==1		)echo 'checked="checked"'; ?>> I Am Married And They Know</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_marriedSecret" 	value="b_marriedSecret" 		<?php if($profileDone==1&&$b_marriedSecret==1		)echo 'checked="checked"'; ?>> I Am Married And They Don't Know</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_poly" 				value="b_poly" 				<?php if($profileDone==1&&$b_poly==1				)echo 'checked="checked"'; ?>> I Am In A Poly Relationship</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_disability" 		value="b_disability" 			<?php if($profileDone==1&&$b_disability==1			)echo 'checked="checked"'; ?>> I Have A Disability</input></label><br>
<br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_haveWarts" 		value="b_haveWarts" 			<?php if($profileDone==1&&$b_haveWarts==1			)echo 'checked="checked"'; ?>> I Have Genital Warts (HPV)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_haveHerpes" 		value="b_haveHerpes" 			<?php if($profileDone==1&&$b_haveHerpes==1			)echo 'checked="checked"'; ?>> I Have Genital Herpes (HSV)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_haveHepatitis" 	value="b_haveHepatitis" 		<?php if($profileDone==1&&$b_haveHepatitis==1		)echo 'checked="checked"'; ?>> I Have Hepatitis</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_haveOtherSTI" 		value="b_haveOtherSTI" 		<?php if($profileDone==1&&$b_haveOtherSTI==1		)echo 'checked="checked"'; ?>> I Have An Other STI</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_haveHIV" 			value="b_haveHIV" 				<?php if($profileDone==1&&$b_haveHIV==1				)echo 'checked="checked"'; ?>> I Have HIV</input></label><br>
        </td>
        </tr>
    </tbody>
</table>
<br>
<?php //TODO: show generated avatar here, highlight searched area ?>
<div class="saveButton"></div>
<div class="captionText">
What You're Looking For
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this);toggleMaleFemale();" id="b_wantGenderMan" 			name="b_wantGenderMan" 			value="b_wantGenderMan" 			<?php if($profileDone==1&&$b_wantGenderMan==1				)echo 'checked="checked"'; ?>> Man</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this);toggleMaleFemale();" id="b_wantGenderWoman" 			name="b_wantGenderWoman" 			value="b_wantGenderWoman" 		<?php if($profileDone==1&&$b_wantGenderWoman==1				)echo 'checked="checked"'; ?>> Woman</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this);toggleMaleFemale();" id="b_wantGenderCoupleMF" 		name="b_wantGenderCoupleMF" 		value="b_wantGenderCoupleMF" 	<?php if($profileDone==1&&$b_wantGenderCoupleMF==1			)echo 'checked="checked"'; ?>> Couple Man + Woman (MF)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this);toggleMaleFemale();" id="b_wantGenderCoupleMM" 		name="b_wantGenderCoupleMM" 		value="b_wantGenderCoupleMM" 	<?php if($profileDone==1&&$b_wantGenderCoupleMM==1			)echo 'checked="checked"'; ?>> Couple Man + Man (MM)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this);toggleMaleFemale();" id="b_wantGenderCoupleFF" 		name="b_wantGenderCoupleFF" 		value="b_wantGenderCoupleFF" 	<?php if($profileDone==1&&$b_wantGenderCoupleFF==1			)echo 'checked="checked"'; ?>> Couple Woman + Woman (FF)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this);toggleMaleFemale();" id="b_wantGenderGroup" 			name="b_wantGenderGroup" 			value="b_wantGenderGroup" 		<?php if($profileDone==1&&$b_wantGenderGroup==1				)echo 'checked="checked"'; ?>> Group</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this);toggleMaleFemale();" id="b_wantGenderTSWoman" 		name="b_wantGenderTSWoman" 		value="b_wantGenderTSWoman" 		<?php if($profileDone==1&&$b_wantGenderTSWoman==1			)echo 'checked="checked"'; ?>> TS Woman (MTF)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this);toggleMaleFemale();" id="b_wantGenderTSMan" 			name="b_wantGenderTSMan" 			value="b_wantGenderTSMan" 		<?php if($profileDone==1&&$b_wantGenderTSMan==1				)echo 'checked="checked"'; ?>> TS Man (FTM)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this);toggleMaleFemale();" id="b_wantGenderCDWoman" 		name="b_wantGenderCDWoman" 		value="b_wantGenderCDWoman" 		<?php if($profileDone==1&&$b_wantGenderCDWoman==1			)echo 'checked="checked"'; ?>> CD Woman (MTF)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this);toggleMaleFemale();" id="b_wantGenderCDMan" 			name="b_wantGenderCDMan" 			value="b_wantGenderCDMan" 		<?php if($profileDone==1&&$b_wantGenderCDMan==1				)echo 'checked="checked"'; ?>> CD Man (FTM)</input></label><br>
    </td>
    </tr>
    </tbody>
</table>
<br>

<label>Age Range</label>
<label for="wantAgeFrom" class="d-sm-inline mb-0">From</label><input type="text" class="required" name="wantAgeFrom" id="wantAgeFrom" style="width:3em" <?php echo 'value="'.$wantAgeFrom.'"'; ?>>
<label for="wantAgeTo" class="d-sm-inline mb-0">To</label><input type="text" class="required"  name="wantAgeTo" id="wantAgeTo" style="width:3em" <?php echo 'value="'.$wantAgeTo.'"'; ?>>
<br>

		<div class="saveButton"></div>
         <br>
		<div class="captionText">
		Choose Your Types
            <div class="smallText">
                Uncheck what you don't want.<br>
            </div>
		</div>


<div class="captionText">
    Their Body
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBodyTiny" 		value="b_wantBodyTiny" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBodyTiny==1				))echo 'checked="checked"'; ?>> Small</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBodySlim" 		value="b_wantBodySlim" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBodySlim==1				))echo 'checked="checked"'; ?>> Slim</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBodyAverage" 	value="b_wantBodyAverage" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantBodyAverage==1				))echo 'checked="checked"'; ?>> Average</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBodyMuscular" 	value="b_wantBodyMuscular" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantBodyMuscular==1			))echo 'checked="checked"'; ?>> Muscular</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBodyCurvy" 	value="b_wantBodyCurvy" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBodyCurvy==1				))echo 'checked="checked"'; ?>> Curvy</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBodyThick" 	value="b_wantBodyThick" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBodyThick==1				))echo 'checked="checked"'; ?>> Thick</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBodyBBW" 		value="b_wantBodyBBW" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBodyBBW==1					))echo 'checked="checked"'; ?>> BBW/BBM</input></label><br>
        </td>
    </tr>
    </tbody>
</table>
<br>

<div class="captionText">
    Their Ethnicity
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantEthnicityWhite" 		value="b_wantEthnicityWhite" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantEthnicityWhite==1	))echo 'checked="checked"'; ?>> White</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantEthnicityAsian" 		value="b_wantEthnicityAsian" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantEthnicityAsian==1	))echo 'checked="checked"'; ?>> Asian</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantEthnicityLatino"		value="b_wantEthnicityLatino" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantEthnicityLatino==1	))echo 'checked="checked"'; ?>> Latino</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantEthnicityIndian" 		value="b_wantEthnicityIndian" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantEthnicityIndian==1	))echo 'checked="checked"'; ?>> Indian</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantEthnicityBlack" 		value="b_wantEthnicityBlack" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantEthnicityBlack==1	))echo 'checked="checked"'; ?>> Black</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantEthnicityOther" 		value="b_wantEthnicityOther" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantEthnicityOther==1	))echo 'checked="checked"'; ?>> Other</input></label><br>
    </td>
    </tr>
    </tbody>
</table>
<br>

<div class="captionText">
    Their Hair Color
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantHairColorLight" 		value="b_wantHairColorLight" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantHairColorLight==1		))echo 'checked="checked"'; ?>> Light</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantHairColorMedium" 		value="b_wantHairColorMedium" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantHairColorMedium==1		))echo 'checked="checked"'; ?>> Medium</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantHairColorDark" 		value="b_wantHairColorDark" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantHairColorDark==1		))echo 'checked="checked"'; ?>> Dark</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantHairColorRed" 			value="b_wantHairColorRed" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantHairColorRed==1		))echo 'checked="checked"'; ?>> Red</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantHairColorGray" 		value="b_wantHairColorGray" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantHairColorGray==1		))echo 'checked="checked"'; ?>> Gray</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantHairColorOther" 		value="b_wantHairColorOther" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantHairColorOther==1		))echo 'checked="checked"'; ?>> Other</input></label><br>
    </td>
    </tr>
    </tbody>
</table>
<br>

<div class="captionText">
    Their Hair Length
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantHairLengthBald" 		value="b_wantHairLengthBald" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantHairLengthBald==1		))echo 'checked="checked"'; ?>> Bald</input></label>    <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantHairLengthShort"		value="b_wantHairLengthShort" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantHairLengthShort==1		))echo 'checked="checked"'; ?>> Short</input></label>   <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantHairLengthMedium" 	value="b_wantHairLengthMedium" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantHairLengthMedium==1	))echo 'checked="checked"'; ?>> Medium</input></label>  <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantHairLengthLong" 		value="b_wantHairLengthLong" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantHairLengthLong==1		))echo 'checked="checked"'; ?>> Long</input></label>    <br>
    </td>
    </tr>
    </tbody>
</table>
<br>

<div class="captionText">
    Their Tattoos
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantTattoosNone" 		value="b_wantTattoosNone" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantTattoosNone==1		))echo 'checked="checked"'; ?>> None</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantTattoosSome" 		value="b_wantTattoosSome" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantTattoosSome==1		))echo 'checked="checked"'; ?>> Some</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantTattoosAllOver" 	value="b_wantTattoosAllOver" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantTattoosAllOver==1	))echo 'checked="checked"'; ?>> All Over!</input></label><br>
    </td>
    </tr>
    </tbody>
</table>
<br>


<div class="captionText">
    Their Looks
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantLooksUgly" 		value="b_wantLooksUgly" 			<?php if($profileDone==0 || ($profileDone==1&&$b_wantLooksUgly==1			))echo 'checked="checked"'; ?>> Downright Ugly</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantLooksPlain" 		value="b_wantLooksPlain" 			<?php if($profileDone==0 || ($profileDone==1&&$b_wantLooksPlain==1			))echo 'checked="checked"'; ?>> Plain</input></label>         <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantLooksQuirks" 		value="b_wantLooksQuirks" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantLooksQuirks==1			))echo 'checked="checked"'; ?>> Some Quirks</input></label>   <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantLooksAverage" 		value="b_wantLooksAverage" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantLooksAverage==1		))echo 'checked="checked"'; ?>> Average</input></label>       <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantLooksAttractive" 	value="b_wantLooksAttractive" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantLooksAttractive==1		))echo 'checked="checked"'; ?>> Attractive</input></label>    <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantLooksHottie" 		value="b_wantLooksHottie" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantLooksHottie==1			))echo 'checked="checked"'; ?>> Hottie</input></label>        <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantLooksSuperModel" 	value="b_wantLooksSuperModel" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantLooksSuperModel==1		))echo 'checked="checked"'; ?>> Super Model</input></label>   <br>
    </td>
    </tr>
    </tbody>
</table>
<br>

<div class="captionText">
    Their Intelligence
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantIntelligenceSlow" 	value="b_wantIntelligenceSlow" 			<?php if($profileDone==0 || ($profileDone==1&&$b_wantIntelligenceSlow==1		))echo 'checked="checked"'; ?>> Slow Than Most</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantIntelligenceBitSlow" 	value="b_wantIntelligenceBitSlow" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantIntelligenceBitSlow==1		))echo 'checked="checked"'; ?>> A Bit Slow</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantIntelligenceAverage" 	value="b_wantIntelligenceAverage" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantIntelligenceAverage==1		))echo 'checked="checked"'; ?>> Average</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantIntelligenceFaster" 	value="b_wantIntelligenceFaster" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantIntelligenceFaster==1		))echo 'checked="checked"'; ?>> A Bit Clever</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantIntelligenceGenius" 	value="b_wantIntelligenceGenius" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantIntelligenceGenius==1		))echo 'checked="checked"'; ?>> Faster Than Most</input></label><br>
    </td>
    </tr>
    </tbody>
</table>
<br>

<div class="captionText">
    Their Bedroom Confidence
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBedroomPersonalityPassive" 		value="b_wantBedroomPersonalityPassive" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBedroomPersonalityPassive==1			))echo 'checked="checked"'; ?>> Very Passive</input></label>          <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBedroomPersonalityShy" 			value="b_wantBedroomPersonalityShy" 			<?php if($profileDone==0 || ($profileDone==1&&$b_wantBedroomPersonalityShy==1				))echo 'checked="checked"'; ?>> Introverted/Shy</input></label>       <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBedroomPersonalityConfident" 	value="b_wantBedroomPersonalityConfident" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBedroomPersonalityConfident==1			))echo 'checked="checked"'; ?>> Extroverted/Confident</input></label> <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBedroomPersonalityAggressive" 	value="b_wantBedroomPersonalityAggressive" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantBedroomPersonalityAggressive==1		))echo 'checked="checked"'; ?>> Very Aggressive</input></label>           <br>
    </td>
    </tr>
    </tbody>
</table>
<br>

<div class="captionText">
    Their Pubic Hair
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantPubicHairShaved" 		value="b_wantPubicHairShaved" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantPubicHairShaved==1		))echo 'checked="checked"'; ?>> Shaved</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantPubicHairTrimmed" 	value="b_wantPubicHairTrimmed" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantPubicHairTrimmed==1	))echo 'checked="checked"'; ?>> Short Trimmed</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantPubicHairAverage" 	value="b_wantPubicHairAverage" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantPubicHairAverage==1	))echo 'checked="checked"'; ?>> Average Cropped</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantPubicHairNatural" 	value="b_wantPubicHairNatural" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantPubicHairNatural==1	))echo 'checked="checked"'; ?>> Natural</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantPubicHairHairy" 		value="b_wantPubicHairHairy" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantPubicHairHairy==1		))echo 'checked="checked"'; ?>> Very Hairy</input></label><br>
    </td>
    </tr>
    </tbody>
</table>
<br>
			
					
			
<div id="wantPenisSize" class="wantMaleAttrib">
<div class="captionText">
    Their Penis Size
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantPenisSizeTiny" 		value="b_wantPenisSizeTiny" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantPenisSizeTiny==1			))echo 'checked="checked"'; ?>> Small</input></label>  <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantPenisSizeSkinny" 		value="b_wantPenisSizeSkinny" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantPenisSizeSkinny==1			))echo 'checked="checked"'; ?>> Skinny</input></label> <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantPenisSizeAverage" 	value="b_wantPenisSizeAverage" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantPenisSizeAverage==1		))echo 'checked="checked"'; ?>> Average</input></label>    <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantPenisSizeThick" 		value="b_wantPenisSizeThick" 	<?php if($profileDone==0 || ($profileDone==1&&$b_wantPenisSizeThick==1			))echo 'checked="checked"'; ?>> Thick</input></label>      <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantPenisSizeHuge"		value="b_wantPenisSizeHuge" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantPenisSizeHuge==1			))echo 'checked="checked"'; ?>> Huge</input></label>   <br>
    </td>
    </tr>
    </tbody>
</table>
    <br>
</div>

					
			
<div id="wantBodyHair" class="wantMaleAttrib">
<div class="captionText">
    Their Body Hair
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBodyHairSmooth" 		value="b_wantBodyHairSmooth" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBodyHairSmooth==1		))echo 'checked="checked"'; ?>> Smooth</input></label>      <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBodyHairAverage" 		value="b_wantBodyHairAverage" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBodyHairAverage==1		))echo 'checked="checked"'; ?>> Average</input></label> <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBodyHairHairy" 		value="b_wantBodyHairHairy" 			<?php if($profileDone==0 || ($profileDone==1&&$b_wantBodyHairHairy==1		))echo 'checked="checked"'; ?>> Hairy</input></label>   <br>
    </td>
    </tr>
    </tbody>
    </table>
    <br>
</div>

			
<div id="wantBreastSize" class="wantFemaleAttrib">
<div class="captionText">
    Their Breast Size
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBreastSizeTiny" 		value="b_wantBreastSizeTiny" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBreastSizeTiny==1		))echo 'checked="checked"'; ?>> None</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBreastSizeSmall" 		value="b_wantBreastSizeSmall" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBreastSizeSmall==1		))echo 'checked="checked"'; ?>> Small</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBreastSizeAverage" 	value="b_wantBreastSizeAverage" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBreastSizeAverage==1	))echo 'checked="checked"'; ?>> Average</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBreastSizeLarge" 		value="b_wantBreastSizeLarge" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBreastSizeLarge==1		))echo 'checked="checked"'; ?>> Large</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBreastSizeHuge" 		value="b_wantBreastSizeHuge" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantBreastSizeHuge==1		))echo 'checked="checked"'; ?>> Huge</input></label><br>
    </td>
    </tr>
    </tbody>
    </table>
    <br>
</div>


<div class="saveButton"></div>


<div class="captionText">
    Shared Sexual Interests
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantSafeSex" 		value="b_wantSafeSex" 		<?php if($profileDone==0 || ($profileDone==1&&$b_wantSafeSex==1		))echo 'checked="checked"'; ?>> Safe Sex</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantBarebackSex" 	value="b_wantBarebackSex" 	<?php if($profileDone==1&&$b_wantBarebackSex==1			)echo 'checked="checked"'; ?>> Bareback Sex</input></label><br>
<br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantOralGive" 		value="b_wantOralGive" 		<?php if($profileDone==1&&$b_wantOralGive==1			)echo 'checked="checked"'; ?>> Oral (I Like Giving)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantOralReceive" 	value="b_wantOralReceive" 	<?php if($profileDone==1&&$b_wantOralReceive==1			)echo 'checked="checked"'; ?>> Oral (I Like Receiving)</input></label><br>
<br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantAnalTop" 		value="b_wantAnalTop" 		<?php if($profileDone==1&&$b_wantAnalTop==1				)echo 'checked="checked"'; ?>> Anal (I Like Giving)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantAnalBottom" 	value="b_wantAnalBottom" 		<?php if($profileDone==1&&$b_wantAnalBottom==1			)echo 'checked="checked"'; ?>> Anal (I Like Receiving)</input></label><br>
<br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantFilming" 		value="b_wantFilming" 		<?php if($profileDone==1&&$b_wantFilming==1				)echo 'checked="checked"'; ?>> Filming/Being Filmed</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantVoyeur" 			value="b_wantVoyeur" 			<?php if($profileDone==1&&$b_wantVoyeur==1				)echo 'checked="checked"'; ?>> Watching (I Am A Voyeur)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantExhibitionist" value="b_wantExhibitionist" 	<?php if($profileDone==1&&$b_wantExhibitionist==1		)echo 'checked="checked"'; ?>> Showing Off (I Am An Exhibitionist)</input></label><br>
<br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantRoleplay" 		value="b_wantRoleplay" 		<?php if($profileDone==1&&$b_wantRoleplay==1			)echo 'checked="checked"'; ?>> Roleplay</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantSpanking" 		value="b_wantSpanking" 		<?php if($profileDone==1&&$b_wantSpanking==1			)echo 'checked="checked"'; ?>> Spanking/Light Bondage</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantDom" 			value="b_wantDom" 				<?php if($profileDone==1&&$b_wantDom==1					)echo 'checked="checked"'; ?>> Serious BDSM (I Am Dom)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantSub" 			value="b_wantSub" 				<?php if($profileDone==1&&$b_wantSub==1					)echo 'checked="checked"'; ?>> Serious BDSM (I Am Sub)</input></label><br>
<br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantStrapon" 		value="b_wantStrapon" 		<?php if($profileDone==1&&$b_wantStrapon==1				)echo 'checked="checked"'; ?>> Strapons or Pegging</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantCuckold" 		value="b_wantCuckold" 		<?php if($profileDone==1&&$b_wantCuckold==1				)echo 'checked="checked"'; ?>> Cuckold/HotWife</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_wantFurry" 			value="b_wantFurry" 			<?php if($profileDone==1&&$b_wantFurry==1				)echo 'checked="checked"'; ?>> Furdom/Furry</input></label><br>
</td>
    </tr>
    </tbody>
</table>
<br>


<div class="saveButton"></div>


<div class="captionText">
    Where To Meet
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_whereMyPlace" 		value="b_whereMyPlace" 			<?php if($profileDone==0 || ($profileDone==1&&$b_whereMyPlace==1		))echo 'checked="checked"'; ?>> My Place (I Can Host)</input></label> <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_whereYouHost" 		value="b_whereYouHost" 			<?php if($profileDone==0 || ($profileDone==1&&$b_whereYouHost==1		))echo 'checked="checked"'; ?>> Your Place (You Host)</input></label> <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_whereCarDate" 		value="b_whereCarDate" 			<?php if($profileDone==1&&$b_whereCarDate==1				)echo 'checked="checked"'; ?>> Car Date</input></label>                           <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_whereHotelIPay" 	value="b_whereHotelIPay" 			<?php if($profileDone==1&&$b_whereHotelIPay==1				)echo 'checked="checked"'; ?>> Hotel (I Pay)</input></label>                      <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_whereHotelYouPay" 	value="b_whereHotelYouPay" 		<?php if($profileDone==1&&$b_whereHotelYouPay==1			)echo 'checked="checked"'; ?>> Hotel (You Pay)</input></label>                    <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_whereHotelSplit" 	value="b_whereHotelSplit" 		<?php if($profileDone==1&&$b_whereHotelSplit==1				)echo 'checked="checked"'; ?>> Hotel (We Split)</input></label>                   <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_whereBarClub" 		value="b_whereBarClub" 			<?php if($profileDone==1&&$b_whereBarClub==1				)echo 'checked="checked"'; ?>> Bar/Club</input></label>                           <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_whereGymSauna" 	value="b_whereGymSauna" 			<?php if($profileDone==1&&$b_whereGymSauna==1				)echo 'checked="checked"'; ?>> Gym/Sauna/Hot Tubs</input></label>                 <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_whereNudeBeach" 	value="b_whereNudeBeach" 			<?php if($profileDone==1&&$b_whereNudeBeach==1				)echo 'checked="checked"'; ?>> Nude Beach</input></label>                         <br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_whereOther" 		value="b_whereOther" 				<?php if($profileDone==1&&$b_whereOther==1					)echo 'checked="checked"'; ?>> Other</input></label>                              <br>
        </td>
    </tr>
    </tbody>
</table>
<br>
		


<?php
/*

		<!-- ---------------------------------------------------------------------------------------------- -->
		<span class="warning">
		<div class="captionText">
		Special Rules<br>Read These Carefully!
		</div>
		</span>


		<div class="smallText">
		<span class="warning">These rules are <i>mutual</i>, meaning we will only match you with others that have <i>the same options checked.</i></span>
		</div>
		<!-- ---------------------------------------------------------------------------------------------- -->
		
		<div class="rowElem">
		<label>Mutual Rules</label>
		<table border="0">
		
		<label><input type="checkbox" onclick="toggle(this)" name="b_mutualLongTerm" 			value="b_mutualLongTerm" 		<?php if($profileDone==1&&$b_mutualLongTerm==1			)echo 'checked="checked"'; ?>>I Want An Exclusive FWB (Monogamous And Long Term)</input></label>
		<label><input type="checkbox" onclick="toggle(this)" name="b_mutualNoStrings" 			value="b_mutualNoStrings" 	<?php if($profileDone==1&&$b_mutualNoStrings==1			)echo 'checked="checked"'; ?>>Absolutely No Strings (One Time Thing)</input></label>
		<label><input type="checkbox" onclick="toggle(this)" name="b_mutualWearMask" 			value="b_mutualWearMask" 		<?php if($profileDone==1&&$b_mutualWearMask==1			)echo 'checked="checked"'; ?>>I Must Be Extremely Discreet/Anonymous/Wear A Mask</input></label>
		<br>
		<label><input type="checkbox" onclick="toggle(this)" name="b_mutualOnlyPlaySafe" 		value="b_mutualOnlyPlaySafe" 	<?php if($profileDone==1&&$b_mutualOnlyPlaySafe==1			)echo 'checked="checked"'; ?>>Only Play Safe (No Exceptions For Any Play, Ever)</input></label>
		<label><input type="checkbox" onclick="toggle(this)" name="b_mutualProofOfTesting" 	value="b_mutualProofOfTesting" 	<?php if($profileDone==1&&$b_mutualProofOfTesting==1		)echo 'checked="checked"'; ?>>Match Must Have Proof Of Recent Testing</input></label>
		<br>
		</table>
		</div>
		<br>


		<!-- ---------------------------------------------------------------------------------------------- -->
		<div class="smallText">
		<span class="warning">These rules are <i>mandatory</i>, and we won't suggest users that meet these criteria in their profile.</span>
		</div>
		<!-- ---------------------------------------------------------------------------------------------- -->
*/
?>



<div class="captionText">
    Mandatory Rules
</div>
<table style="width:100%;">
    <tbody>
    <tr>
        <td style="width:30%;"></td>
        <td style="text-align:left;">
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noCigs" 				value="b_noCigs"			<?php if($profileDone==1&&$b_noCigs==1				)echo 'checked="checked"'; ?>> No Cigarette Smokers</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noLightDrink" 		value="b_noLightDrink"	<?php if($profileDone==1&&$b_noLightDrink==1		)echo 'checked="checked"'; ?>> No Light Drinking</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noHeavyDrink" 		value="b_noHeavyDrink"	<?php if($profileDone==1&&$b_noHeavyDrink==1		)echo 'checked="checked"'; ?>> No Heavy Drinking</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noMarijuana" 			value="b_noMarijuana"		<?php if($profileDone==1&&$b_noMarijuana==1			)echo 'checked="checked"'; ?>> No Marijuana</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noPsychedelics" 				value="b_noPsychedelics" 			<?php if($profileDone==0 || ($profileDone==1&&$b_noPsychedelics==1				))echo 'checked="checked"'; ?>> No Psychedelics</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noDrugs" 				value="b_noDrugs" 			<?php if($profileDone==0 || ($profileDone==1&&$b_noDrugs==1				))echo 'checked="checked"'; ?>> No Other Drugs</input></label><br>
<br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noHerpes" 				value="b_noHerpes" 		<?php if($profileDone==0 || ($profileDone==1&&$b_noHerpes==1			))echo 'checked="checked"'; ?>> Match Must Not Have Genital Herpes (HSV)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noWarts" 				value="b_noWarts" 		<?php if($profileDone==0 || ($profileDone==1&&$b_noWarts==1			))echo 'checked="checked"'; ?>> Match Must Not Have Genital Warts (HPV)</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noHepatitis" 			value="b_noHepatitis" 	<?php if($profileDone==0 || ($profileDone==1&&$b_noHepatitis==1			))echo 'checked="checked"'; ?>> Match Must Not Hepatitis</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noOtherSTIs" 			value="b_noOtherSTIs" 	<?php if($profileDone==0 || ($profileDone==1&&$b_noOtherSTIs==1			))echo 'checked="checked"'; ?>> Match Must Not Any Other STIs</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noHIV" 				value="b_noHIV" 			<?php if($profileDone==0 || ($profileDone==1&&$b_noHIV==1				))echo 'checked="checked"'; ?>> Match Must Not Have HIV</input></label><br>
<br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noMarriedSecret" 		value="b_noMarriedSecret" 		<?php if($profileDone==1&&$b_noMarriedSecret==1			)echo 'checked="checked"'; ?>> Must Not Be Married If It Is Secret From Their Spouse</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noMarriedTheyKnow" 	value="b_noMarriedTheyKnow" 		<?php if($profileDone==1&&$b_noMarriedTheyKnow==1			)echo 'checked="checked"'; ?>> Must Not Be Married Even If Their Spouse Knows</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noPoly" 				value="b_noPoly"			<?php if($profileDone==1&&$b_noPoly==1				)echo 'checked="checked"'; ?>> Match Must Not Be In Poly Relationship</input></label><br>
<label class="checkbox text-left d-sm-inline-block mb-0"><input type="checkbox" onclick="toggle(this)" name="b_noDisabled" 			value="b_noDisabled"		<?php if($profileDone==1&&$b_noDisabled==1			)echo 'checked="checked"'; ?>> Match Must Not Have Disabilities</input></label><br>
        </td>
    </tr>
    </tbody>
</table>
<br>

		<div class="captionText">
		Optional Public Information
		</div>
		<div class="smallText" style="color:#444">
		Any text here will be public to anyone, so putting personal information is at your own risk.<br><br>(2000 characters max.)
		</div>
		<label for="publicText"></label>
        <div style="text-align:center;">
		<textarea id="publicText" cols="60" rows="10" name="publicText" placeholder="Public info"><?php if($profileDone==1&&strlen($publicText)>0			)echo $publicText; ?></textarea>
		</div>
        <br>
        <div class="captionText" style="color:#660000">
            Optional Private Information
        </div>
        <div class="smallText" style="color:#444;">
            This will only be seen after you both agree to a match.<br>
            Good examples are: cell number, messenger names, other interests.<br><br>(2000 characters max.)
        </div>
        <label for="privateText"></label>
        <div style="text-align:center;">
            <textarea id="privateText" cols="60" rows="10" name="privateText" placeholder="Private info" style="color:#660000"><?php if($profileDone==1&&strlen($privateText)>0			)echo $privateText; ?></textarea>
        </div>

        <br>
        <div class="text-center">
            <button class="btn btn-lg btn-primary" type="submit" name="saveButtonName"
                    id="saveButtonID">Save
            </button>
        </div>

	</fieldset>
	</form>


</div>

<?php include("f.php");?>


<script src="/js/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="/js/jquery-validate/jquery.validate.min.js" type="text/javascript"></script>
<script src="/js/date.js" type="text/javascript"></script>

<script type="text/javascript">
    $.validator.setDefaults({});

    function toggleMaleFemale()
    {
        //if user is male, don't ask breast size
        var e = document.getElementById("gender");
        if(e.options[e.selectedIndex].id=="male" || e.options[e.selectedIndex].id=="mm")
        {
            document.getElementById("myBreastSize").style.display = "none";
        }
        else
        {
            document.getElementById("myBreastSize").style.display = "";
        }

        //if user is female, don't ask penis size
        if(e.options[e.selectedIndex].id=="female" || e.options[e.selectedIndex].id=="ff")
        {
            document.getElementById("myPenisSize").style.display = "none";
            document.getElementById("myBodyHair").style.display = "none";
        }
        else
        {
            document.getElementById("myPenisSize").style.display = "";
            document.getElementById("myBodyHair").style.display = "";
        }

        if(e.options[e.selectedIndex].id=="mtf" || e.options[e.selectedIndex].id=="cdmtf")
        {
            document.getElementById("myBodyHair").style.display = "none";
        }


        if(e.options[e.selectedIndex].id=="ftm" || e.options[e.selectedIndex].id=="cdftm")
        {
            document.getElementById("myPenisSize").style.display = "none";
        }

        var b_wantGenderMan = document.getElementById("b_wantGenderMan");
        var b_wantGenderTSWoman = document.getElementById("b_wantGenderTSWoman");
        var b_wantGenderCDWoman = document.getElementById("b_wantGenderCDWoman");
        var b_wantGenderCoupleMF = document.getElementById("b_wantGenderCoupleMF");
        var b_wantGenderCoupleMM = document.getElementById("b_wantGenderCoupleMM");
        if(b_wantGenderMan.checked||b_wantGenderTSWoman.checked||b_wantGenderCDWoman.checked||b_wantGenderCoupleMF.checked||b_wantGenderCoupleMM.checked)
        {
            document.getElementById("wantPenisSize").style.display = "";
        }
        else
        {
            document.getElementById("wantPenisSize").style.display = "none";
        }

        if(b_wantGenderMan.checked||b_wantGenderCoupleMF.checked||b_wantGenderTSWoman.checked||b_wantGenderTSMan.checked||b_wantGenderCDWoman.checked||b_wantGenderCDMan.checked||b_wantGenderCoupleMM.checked)
        {
            document.getElementById("wantBodyHair").style.display = "";
        }
        else
        {
            document.getElementById("wantBodyHair").style.display = "none";
        }

        var b_wantGenderWoman = document.getElementById("b_wantGenderWoman");
        var b_wantGenderTSWoman = document.getElementById("b_wantGenderTSWoman");
        var b_wantGenderTSMan = document.getElementById("b_wantGenderTSMan");
        var b_wantGenderCDWoman = document.getElementById("b_wantGenderCDWoman");
        var b_wantGenderCDMan = document.getElementById("b_wantGenderCDMan");
        var b_wantGenderCoupleMF = document.getElementById("b_wantGenderCoupleMF");
        var b_wantGenderCoupleFF = document.getElementById("b_wantGenderCoupleFF");
        if(b_wantGenderWoman.checked||b_wantGenderTSWoman.checked||b_wantGenderTSMan.checked||b_wantGenderCDWoman.checked||b_wantGenderCDMan.checked||b_wantGenderCoupleMF.checked||b_wantGenderCoupleFF.checked)
        {
            document.getElementById("wantBreastSize").style.display = "";

        }
        else
        {
            document.getElementById("wantBreastSize").style.display = "none";
        }
    }

    $.validator.addMethod("ofAge",function(value,element)
    {
        return Date.parse($('#birthdayMonth').val()+'/'+$('#birthdayDay').val()+'/'+$('#birthdayYear').val()) < (18).years().ago();
    }, "You must be 18 years old to join.");

    $().ready
    (
        function()
        {
            // validate the comment form when it is submitted
            $("#editProfileFormID").validate
            ({
                errorElement:"div",
                wrapper: "div",
                errorPlacement: function(error, element)
                {
                    if(	element.attr('id') == 'birthdayDay'
                        ||element.attr('id') == 'birthdayYear'
                        ||element.attr('id') == 'birthdayMonth')
                        element = element.parent();

                    element = element.parent();

                    element.before(error);
                    offset = element.offset();

                    error.insertBefore(element);
                    //error.addClass('message');
                    //error.css('position','absolute');

                    error.css('left',offset.right + element.outerWidth());
                    error.css('top', offset.top);
                },

                rules:
                    {
                        postalCode:
                            {
                                minlength:1
                            },
                        distance:
                            {
                                minlength:1
                            },
                        firstName:
                            {
                                minlength:1,
                                maxlength:12,
                                digits:false
                            },
                        wantAgeFrom:
                            {
                                min:18,
                                digits:true

                            },
                        wantAgeTo:
                            {
                                min:18,
                                digits:true
                            },
                        birthdayMonth:
                            {
                                min:1,
                                max:12,
                                minlength:1,
                                maxlength:2,
                                digits:true,
                                ofAge:true
                            },
                        birthdayDay:
                            {
                                min:1,
                                max:31,
                                minlength:1,
                                maxlength:2,
                                digits:true,
                                ofAge:true
                            },
                        birthdayYear:
                            {
                                min:1900,
                                max:2012,
                                minlength:4,
                                maxlength:4,
                                digits:true,
                                ofAge:true
                            },
                        publicText:
                            {
                                maxlength:2000
                            },
                        privateText:
                            {
                                maxlength:2000
                            }
                    }
                ,
                messages:
                    {
                        postalCode:
                            {
                                required:"Enter a Postal code.",
                                minlength:"Postal Code must be at least 1 letter or digit.",
                            },
                        birthdayMonth:
                            {
                                required: "Please enter a month 1-12."
                            },
                        birthdayDay:
                            {
                                required: "Please enter a day 1-31."
                            },
                        birthdayYear:
                            {
                                required: "Please enter a year."
                            },
                        nameCaptcha: "Wrong words, please try again so we know you're not a bot.",
                        nameAgreeLegalAge: "You must be of legal age.",
                        nameAgreeTOS: "You must agree to the Terms Of Service and Privacy Policy to join.",
                    }
            });
        }
    );
</script>
<script type="text/javascript">toggleMaleFemale();</script>
</body>
</html>

