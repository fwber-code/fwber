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
include("_init.php");
include("_secrets.php");
include("_names.php");

include("_profileVars.php");
include("_getProfile.php");

	if(validateSessionOrCookiesReturnLoggedIn()==false){header('Location: '.getSiteURL());exit();}//full auth for actions
	
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
    <title><?php echo getSiteName(); ?> - Matches<?php echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100 matches">
	<?php include("h.php");?>
    <div id="" align="center">
		<br>
		<br>
		<br>
        <h1 class="h3 mb-3 font-weight-normal text-center"> Your Matches</h1>
        <br>
        <div class="" style="">
			<span class="blueToWhite" style="font-size:11pt; padding:6px;">
			Show:&nbsp;
			<?php if(!isset($_GET['show'])||$_GET['show']=="all")echo '<span class="sortStyle">'; ?><a href="/matches.php?show=all<?php if(isset($_GET['sort']))echo "&sort=".$_GET['sort']; ?>">All</a><?php if(!isset($_GET['show'])||$_GET['show']=="all")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['show'])&&$_GET['show']=="fwbs")echo '<span class="sortStyle">'; ?><a href="/matches.php?show=fwbs<?php if(isset($_GET['sort']))echo "&sort=".$_GET['sort']; ?>">My FWBs</a><?php if(isset($_GET['show'])&&$_GET['show']=="fwbs")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['show'])&&$_GET['show']=="waitingforme")echo '<span class="sortStyle">'; ?><a href="/matches.php?show=waitingforme<?php if(isset($_GET['sort']))echo "&sort=".$_GET['sort']; ?>">Waiting For Me</a><?php if(isset($_GET['show'])&&$_GET['show']=="waitingforme")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['show'])&&$_GET['show']=="waitingforthem")echo '<span class="sortStyle">'; ?><a href="/matches.php?show=waitingforthem<?php if(isset($_GET['sort']))echo "&sort=".$_GET['sort']; ?>">Waiting For Them</a><?php if(isset($_GET['show'])&&$_GET['show']=="waitingforthem")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['show'])&&$_GET['show']=="notmytype")echo '<span class="sortStyle">'; ?><a href="/matches.php?show=notmytype<?php if(isset($_GET['sort']))echo "&sort=".$_GET['sort']; ?>">Not My Type</a><?php if(isset($_GET['show'])&&$_GET['show']=="notmytype")echo '</span>'; ?>&nbsp;
			</span>
        </div>
        <br>
        <div class="" style="">
            &nbsp;
            <span class="blueToWhite" style="font-size:11pt; padding:6px;">
			Sort:&nbsp;
			<?php if(!isset($_GET['sort'])||$_GET['sort']=="commondesires")echo '<span class="sortStyle">'; ?><a href="/matches.php?<?php if(isset($_GET['show']))echo "show=".$_GET['show']."&"; ?>sort=commondesires">Desires</a><?php if(!isset($_GET['sort'])||$_GET['sort']=="commondesires")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['sort'])&&$_GET['sort']=="distance")echo '<span class="sortStyle">'; ?><a href="/matches.php?<?php if(isset($_GET['show']))echo "show=".$_GET['show']."&"; ?>sort=distance">Distance</a><?php if(isset($_GET['sort'])&&$_GET['sort']=="distance")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['sort'])&&$_GET['sort']=="lastseen")echo '<span class="sortStyle">'; ?><a href="/matches.php?<?php if(isset($_GET['show']))echo "show=".$_GET['show']."&"; ?>sort=lastseen">Last Seen</a><?php if(isset($_GET['sort'])&&$_GET['sort']=="lastseen")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['sort'])&&$_GET['sort']=="age")echo '<span class="sortStyle">'; ?><a href="/matches.php?<?php if(isset($_GET['show']))echo "show=".$_GET['show']."&"; ?>sort=age">Age</a><?php if(isset($_GET['sort'])&&$_GET['sort']=="age")echo '</span>'; ?>&nbsp;
			</span>
        </div>
        <br>
        <br>
<?php

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    $email = mysqli_escape_string($db,$_SESSION["email"]);

    include("_getMatches.php");
    $result_array = getMatches($email);

    if(empty($result_array) || count($result_array)<1)
    {
?>
        <div class="card p-5 m-2 shadow-sm" style="display:inline-block;">
            <h4 class="h3 mb-3 font-weight-normal text-center">
                No matches found yet.
            </h4>
            <br>
            <div class="smallText">
                <img src="/images/fwber_logo_icon.png" alt="fwber" id="fwber_logo_img" align="middle" width="80px" style="vertical-align:bottom;"/> is different than other match sites.<br>
                We only match you with people into the same things as you.<br>
                <br>
                We will email you when you match with someone.<br>
                Meanwhile, it's important that you <a href="/managepics">upload pictures.</a><br>
            </div>
        </div>
<?php
    }

    if(!empty($result_array) && count($result_array)>0)
    {
		//default sort:
		
		if(isset($_GET['sort']) && !empty($_GET['sort']))
		{
			if($_GET['sort']=="lastseen")usortByArrayKey($result_array, 'dateLastSeen', SORT_DESC); 
			else if($_GET['sort']=="commondesires")usortByArrayKey($result_array, 'commonDesires', SORT_DESC); 
			else if($_GET['sort']=="distance")usortByArrayKey($result_array, 'calculatedDistance'); 
			else if($_GET['sort']=="age")usortByArrayKey($result_array, 'age'); 
		}
		else
		{
			usortByArrayKey($result_array, 'commonDesires', SORT_DESC);
		}

		if(isset($_GET['show']) && !empty($_GET['show']) && $_GET['show']=="fwbs")
		{
			//fwbs
			foreach($result_array as &$g){if($g['status']=="private")getProfile($g,$g['status']);}unset($g);
		}
		else
		if(isset($_GET['show']) && !empty($_GET['show']) && $_GET['show']=="waitingforme")
		{
			//private they are waiting for me (authorize)
			foreach($result_array as &$g){if($g['status']=="authforprivate")getProfile($g,$g['status']);}unset($g);

		}
		else
		if(isset($_GET['show']) && !empty($_GET['show']) && $_GET['show']=="waitingforthem")
		{
			//waiting for them to auth private
			foreach($result_array as &$g){if($g['status']=="waitingforprivate")getProfile($g,$g['status']);}unset($g);
			
		}
		else
		if(isset($_GET['show']) && !empty($_GET['show']) && $_GET['show']=="notmytype")
		{
			//not my type
			foreach($result_array as &$g){if($g['status']=="notmytype")getProfile($g,$g['status']);}unset($g);
		}
		else	
		{
			//show all in correct order
		
			//private they are waiting for me (authorize)
			foreach($result_array as &$g){if($g['status']=="authforprivate")getProfile($g,$g['status']);}unset($g);

			//fwbs
			foreach($result_array as &$g){if($g['status']=="private")getProfile($g,$g['status']);}unset($g);
			
			//waiting for them to auth private
			foreach($result_array as &$g){if($g['status']=="waitingforprivate")getProfile($g,$g['status']);}unset($g);

			//public by join date
			foreach($result_array as &$g){if($g['status']=="public")getProfile($g,$g['status']);}unset($g);
			
			//not my type
			foreach($result_array as &$g){if($g['status']=="notmytype")getProfile($g,$g['status']);}unset($g);

			//dont show hidden
		}

    } // end if($results)
?>
        <br>
	</div>
    <?php include("f.php");?>

    <script src="/js/jquery-3.4.1.min.js" type="text/javascript"></script>
    <script src="/js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>
    <script type="text/javascript">

        function toggleExpandProfile(profileBoxID)
        {
            var e = document.getElementById(profileBoxID+"details");
            $(e).toggle("blind","",1000);
        }

        function talkToServer(obj, userid)
        {
            var action = obj.name;

            if(action=="notmytype")
            {
                var sure = confirm("Are you sure you want to remove them?");
                if(sure==false)
                {
                    return;
                }
            }

            $(obj).hide("explode","",200);

            $.ajax(
                {
                    type: "GET",
                    url: "_matchAction.php",
                    data: "action=" + escape(action) + "&d=" + escape(userid),
                    dataType: "html",
                    success: function(text)
                    {
                        if(text=="done")
                        {
                            $(document.getElementById("div"+userid)).hide("puff","",1000);

                            var statusString = "";

                            if(action=="askprivate")statusString = "An email was sent to them offering to trade private pictures and contact information.";
                            if(action=="notmytype")statusString = "They weren't what you were looking for. We'll hide you from their matches.";
                            if(action=="authorizeprivate")statusString = "You've agreed to trade your private pictures and contact information with them. You can see theirs now.";
                            if(action=="undonotmytype")statusString = "You've given them another chance. Refresh your match list to see them.";

                            document.getElementById("status"+userid).innerHTML=statusString;
                            $(document.getElementById("status"+userid)).show("explode","",1000);
                        }
                        else
                        {
                            $(document.getElementById("div"+userid)).hide("puff","",1000);

                            document.getElementById("status"+userid).innerHTML="Something went wrong. Please try again later.<br>Error: " + text;
                            $(document.getElementById("status"+userid)).show("explode","",1000);
                        }
                    }
                });
        }
    </script>

</body>
</html>

