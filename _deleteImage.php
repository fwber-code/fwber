<?php 

	session_start();

	require_once("_init.php");
	require_once("_profileVars.php");
	require_once("_secrets.php");
	require_once("_globals.php");
	
	//first make sure we are a legit user.
	if(deleteCookiesIfInvalid()==false){header('Location: '.getSiteURL());return;}//full auth for actions
	
	goHomeIfCookieNotSet();
	
	//var statusString = "";
	//if(action=="askfirstbase")statusString = "Email was sent asking for face pics!";
	//if(action=="authorizefirstbase")statusString = "You've taken it to first base! Quick, refresh your match list and see if you're interested!";
	//if(action=="askalltheway")statusString = "Email was sent asking to trade all info!";
	//if(action=="notmytype")statusString = "They weren't what you were looking for. We'll hide you from their matches to soften the blow. Out of sight, out of mind!";
	//if(action=="authorizealltheway")statusString = "You've taken it all the way! Quick, refresh your match list and take a look!";
	//if(action=="undonotmytype")statusString = "You've given them another chance! Refresh your match list to see them in your normal matches.";
	//if(action=="rejection")statusString = "You've rejected them. We'll hide you from their matches to soften the blow. Out of sight, out of mind!";

	//make sure we've got an action.
	if(!isset($_GET['img'])||empty($_GET['img']))exit('no img');

	$db = mysqli_connect($dburl,$dbuser,$dbpass);
	if(!$db)exit(mysqli_connect_error());
	
	$email = mysqli_escape_string($db,$_SESSION["email"]);
	$img = mysqli_escape_string($db,$_GET['img']);

	//get my userid
	$dbquerystring = sprintf("SELECT id, publicPics, privatePics FROM ".$dbname.".users WHERE email='%s'",$email);
	$dbquery = mysqli_query($db,$dbquerystring);
	$dbresults = mysqli_fetch_array($dbquery);
	
	$userid=$dbresults['id'];
	
	$publicPics=explode(",",trim(trim($dbresults['publicPics']),","));
	$privatePics=explode(",",trim(trim($dbresults['privatePics']),","));
	
	mysqli_free_result($dbquery);
	
	//figure out which database list it's in.
	$foundWhere="";
	foreach($publicPics as $s)if($s==$img)$foundWhere="firstBase";
	
	if($foundWhere=="")foreach($privatePics as $s)if($s==$img)$foundWhere="allTheWay";
	
	if($foundWhere=="")exit("Image not found.");

	//remove image filename in type database.
	if($foundWhere=="firstBase")rem_array($publicPics,$img);
	if($foundWhere=="allTheWay")rem_array($privatePics,$img);
	
	$dbquerystring = 
	sprintf("UPDATE ".$dbname.".users SET publicPics = '%s',privatePics = '%s' WHERE id='%s'",
	trim(trim(implode(",",$publicPics)),","),
	trim(trim(implode(",",$privatePics)),","),
	$userid
	);
	if(!mysqli_query($db,$dbquerystring))exit("didn't work");
	
	//done
	mysqli_close($db);

	//send back done
	echo "done";

