<?php 

	session_start();

include("_debug.php");
include("_init.php");
include("_secrets.php");
include("_names.php");

include("_profileVars.php");

	if(deleteCookiesIfInvalid()==false){header('Location: '.getSiteURL());exit();}//full auth for actions
	
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
<body class="d-flex flex-column h-100">
	<?php include("h.php");?>
	<div id="mainbody" align="center" class="matches">
		<br>
		<br>
		<div>
			<span class="blueToWhite" style="font-size:11pt; padding:6px;">
			Show:&nbsp;
			<?php if(!isset($_GET['show'])||$_GET['show']=="all")echo '<span class="sortStyle">'; ?><a href="/matches.php?show=all<?php if(isset($_GET['sort']))echo "&sort=".$_GET['sort']; ?>">All</a><?php if(!isset($_GET['show'])||$_GET['show']=="all")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['show'])&&$_GET['show']=="fwbs")echo '<span class="sortStyle">'; ?><a href="/matches.php?show=fwbs<?php if(isset($_GET['sort']))echo "&sort=".$_GET['sort']; ?>">My fwbs</a><?php if(isset($_GET['show'])&&$_GET['show']=="fwbs")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['show'])&&$_GET['show']=="waitingforme")echo '<span class="sortStyle">'; ?><a href="/matches.php?show=waitingforme<?php if(isset($_GET['sort']))echo "&sort=".$_GET['sort']; ?>">Waiting For Me</a><?php if(isset($_GET['show'])&&$_GET['show']=="waitingforme")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['show'])&&$_GET['show']=="waitingforthem")echo '<span class="sortStyle">'; ?><a href="/matches.php?show=waitingforthem<?php if(isset($_GET['sort']))echo "&sort=".$_GET['sort']; ?>">Waiting For Them</a><?php if(isset($_GET['show'])&&$_GET['show']=="waitingforthem")echo '</span>'; ?>&nbsp;
			<?php if(isset($_GET['show'])&&$_GET['show']=="notmytype")echo '<span class="sortStyle">'; ?><a href="/matches.php?show=notmytype<?php if(isset($_GET['sort']))echo "&sort=".$_GET['sort']; ?>">Not My Type</a><?php if(isset($_GET['show'])&&$_GET['show']=="notmytype")echo '</span>'; ?>&nbsp;
			</span>
			
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
		<div style="width:90%;">
		    <br>
<?php
    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    $email = mysqli_escape_string($db,$_SESSION["email"]);

    $result_array = getMatches($email);

    if(count($result_array)<6)
    {
?>	

	<div class="outerOutlineContainer" style="width:80%;">
	<div class="normalContainer">
	<div class="innerOutlineContainer whiteToGray" style="
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#eeeeee'); /* for IE */
	background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#eeeeee)); /* for webkit browsers */
	background: -moz-linear-gradient(top,  #ffffff,  #eeeeee); /* for firefox 3.6+ */
	">
	<br>
    <br>
		<div  style="width:80%;">
		<div class="innerOutlineContainer whiteToGray" style="
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#c6e8ff'); /* for IE */
			background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#c6e8ff)); /* for webkit browsers */
			background: -moz-linear-gradient(top,  #ffffff,  #c6e8ff); /* for firefox 3.6+ */
        ">
        <br>
        <div style="font-size:18pt;font-weight:bold;margin:-32px;">
        <img src="/images/fwber_logo_icon.png" alt="fwber" id="fwber_logo_img" align="middle"/>
            &nbsp;is different than other match sites.
        </div>
		</div>
		</div>
		<br>
		<br>
		<div style="font-size:13pt; color:#000;">
            We only match you with people into the same things as you.<br>
			It may take a while for <?php echo getSiteName();?> to find you the perfect matches.<br>
			When it does, we'll email you right away to let you know.<br>
			<br>
		</div>
	</div>
	</div>
	</div>
	<br>
	<br>
<?php
    }

    if(count($result_array)>0)
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
			foreach($result_array as &$g){if($g['status']=="alltheway")getProfile($g,$g['status']);}unset($g);
		}
		else
		if(isset($_GET['show']) && !empty($_GET['show']) && $_GET['show']=="waitingforme")
		{
			//all the way they are waiting for me (authorize)
			foreach($result_array as &$g){if($g['status']=="authforalltheway")getProfile($g,$g['status']);}unset($g);

			//first base they are waiting for me
			foreach($result_array as &$g){if($g['status']=="authforfirstbase")getProfile($g,$g['status']);}unset($g);
		}
		else
		if(isset($_GET['show']) && !empty($_GET['show']) && $_GET['show']=="waitingforthem")
		{
			//waiting for them to auth all the way
			foreach($result_array as &$g){if($g['status']=="waitingforalltheway")getProfile($g,$g['status']);}unset($g);
			
			//waiting for them to auth first base
			foreach($result_array as &$g){if($g['status']=="waitingforfirstbase")getProfile($g,$g['status']);}unset($g);
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
		
			//all the way they are waiting for me (authorize)
			foreach($result_array as &$g){if($g['status']=="authforalltheway")getProfile($g,$g['status']);}unset($g);

			//first base they are waiting for me
			foreach($result_array as &$g){if($g['status']=="authforfirstbase")getProfile($g,$g['status']);}unset($g);
			
			//fwbs
			foreach($result_array as &$g){if($g['status']=="alltheway")getProfile($g,$g['status']);}unset($g);
			
			//waiting for them to auth all the way
			foreach($result_array as &$g){if($g['status']=="waitingforalltheway")getProfile($g,$g['status']);}unset($g);
			
			//first base
			foreach($result_array as &$g){if($g['status']=="firstbase")getProfile($g,$g['status']);}unset($g);
			
			//waiting for them to auth first base
			foreach($result_array as &$g){if($g['status']=="waitingforfirstbase")getProfile($g,$g['status']);}unset($g);
			
			//public by join date
			foreach($result_array as &$g){if($g['status']=="public")getProfile($g,$g['status']);}unset($g);
			
			//not my type
			foreach($result_array as &$g){if($g['status']=="notmytype")getProfile($g,$g['status']);}unset($g);

			//dont show hidden
		}

    } // end if($results)
?>
        <br>
        <div class="outerOutlineContainer" style="width:80%;">
        <div class="normalContainer">
        <div class="innerOutlineContainer whiteToGray" style="
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#eeeeee', endColorstr='#ffffff'); /* for IE */
        background: -webkit-gradient(linear, left top, left bottom, from(#eeeeee), to(#ffffff)); /* for webkit browsers */
        background: -moz-linear-gradient(top,  #eeeeee,  #ffffff); /* for firefox 3.6+ */
        ">
            <br>
            <div  style="width:80%;">
            <div class="">
            <div class="innerOutlineContainer whiteToGray" style="
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#eeeeee'); /* for IE */
                background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#eeeeee)); /* for webkit browsers */
                background: -moz-linear-gradient(top,  #ffffff,  #eeeeee); /* for firefox 3.6+ */
                ">
                <br>
                <div style="font-size:13pt;">
                    It's important that you <a href="/uploadpics.php">upload pictures.</a><br>
                </div>
                <div style="font-size:8pt;">
                    <br>
                    Nobody can see them until you let them. The more pictures you have, the more other people want to unlock them.<br>
                    <br>
                </div>
            </div>
            </div>
            </div>
            <br>
            <div style="width:80%;">
            <div class="">
            <div class="innerOutlineContainer whiteToGray" style="
                filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#eeeeee'); /* for IE */
                background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#eeeeee)); /* for webkit browsers */
                background: -moz-linear-gradient(top,  #ffffff,  #eeeeee); /* for firefox 3.6+ */
                ">
                <br>
                <br>
            </div>
            </div>
            </div>
            <br>
            <br>
            <br>
        </div>
        </div>
        </div>
        <br>
        <br>
	</div> <!-- 96% -->
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

        //AJAX
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
                            if(action=="askfirstbase")statusString = "Email was sent asking for face pics!";
                            if(action=="authorizefirstbase")statusString = "You've taken it to first base! Quick, refresh your match list and see if you're interested!";
                            if(action=="askalltheway")statusString = "An email was sent to them offering to trade private pictures and contact information.";
                            if(action=="notmytype")statusString = "They weren't what you were looking for. We'll hide you from their matches.";
                            if(action=="authorizealltheway")statusString = "You've agreed to share your private pictures and contact information with them. You can see theirs now.";
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

