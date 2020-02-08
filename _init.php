<?php

include("_debug.php");

//include_once("_secrets.php");
//include_once("_names.php");
//include_once("_getProfile.php");
//include_once("_getMatches.php");
//include_once("_emailFunctions.php");


//=========================================================================================
function getSaltedPassword($password,$date)
{//=========================================================================================
    include("_secrets.php");
    return md5($password.$salt.$date);
}

//=========================================================================================
function mysqli_result($search, $row, $field)
{//=========================================================================================
    $i=0; 
	while($results=mysqli_fetch_array($search))
	{
        if ($i==$row){$result=$results[$field];}
        $i++;
	}
    return $result;
}

//=========================================================================================
function currentPageURL()
{//=========================================================================================
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

//=========================================================================================
function deleteCookiesIfInvalid()
{//=========================================================================================

    include("_secrets.php");
    include("_debug.php");

    $loggedIn = false;

    //first we'll handle validating the session
    if(!isset($_SESSION["token"])){if($debug)echo "<!-- DEBUG: No session token -->\n";}
    else if($_SESSION["token"]==null){if($debug)echo "<!-- DEBUG: Session token null -->\n";}
    else if(!isset($_SESSION["email"])){if($debug)echo "<!-- DEBUG: No session email -->\n";}
    else if($_SESSION["email"]==null){if($debug)echo "<!-- DEBUG: Session email null -->\n";}
    else if(strlen($_SESSION["email"])==0){if($debug)echo "<!-- DEBUG: Session email zero length -->\n";}
    else {

            //check salt, if doesn't match, delete cookies.
            $db = mysqli_connect($dburl,$dbuser,$dbpass);
            if(!$db)exit(mysqli_connect_error());

            $email = mysqli_real_escape_string($db,$_SESSION["email"]);

            $dbquerystring = sprintf("SELECT id, email, passwordHash, dateLastSeen, dateLastSignedIn FROM ".$dbname.".users WHERE email='%s'",$email);
            $dbquery = mysqli_query($db, $dbquerystring);
            $dbresults = mysqli_fetch_array($dbquery);
            mysqli_free_result($dbquery);

            if
			(
                $dbresults==null
                ||$dbresults['email']==null
                ||$dbresults['email']!=$email
                ||$dbresults['passwordHash']==null
                ||getSaltedPassword($dbresults['passwordHash'],$dbresults['dateLastSignedIn'])!=$_SESSION["token"]
            )
            {
                if($debug)echo "<!-- DEBUG: No email, no password, or password didn't match. Destroying session. -->\n";
                session_destroy();
                $loggedIn = false;
            }
            else
            {
                $loggedIn=true;

                //if($debug)echo "<!-- DEBUG: Logged in with session token -->\n";
                //update the last seen every 2 hours
                $time2h = 7200; //3600 per hour
                if(($dbresults['dateLastSeen']==null) || (time() > $dbresults['dateLastSeen'] + $time2h))
                {
                    mysqli_query($db, "UPDATE ".$dbname.".users SET `dateLastSeen` = '".time()."' WHERE `email` = '".$email."'");
                }
            }

            //done with the db
            mysqli_close($db);
    }


    //then we'll handle the cookies
    if(!isset($_COOKIE["token"])){}//if($debug)echo "<!-- DEBUG: No cookie token -->\n";}
    else if($_COOKIE["token"]==null){}//if($debug)echo "<!-- DEBUG: Cookie token null -->\n";}
    else if(!isset($_COOKIE["email"])){}//if($debug)echo "<!-- DEBUG: No cookie email -->\n";}
    else if($_COOKIE["email"]==null){}//if($debug)echo "<!-- DEBUG: Cookie email null -->\n";}
    else if(strlen($_COOKIE["email"])==0){}//if($debug)echo "<!-- DEBUG: Cookie email zero length -->\n";}
    else
    {
            //check salt, if doesn't match, delete cookies.
            $db = mysqli_connect($dburl,$dbuser,$dbpass);
            if(!$db)exit(mysqli_connect_error());

            $email = mysqli_real_escape_string($db, $_COOKIE["email"]);

            $dbquerystring = sprintf("SELECT id, email, passwordHash, dateLastSeen, dateLastSignedIn FROM ".$dbname.".users WHERE email='%s'",$email);
            $dbquery = mysqli_query($db, $dbquerystring);
            $dbresults = mysqli_fetch_array($dbquery);
            mysqli_free_result($dbquery);

            if(
                $dbresults==null
                ||$dbresults['passwordHash']==null
                ||md5(getSaltedPassword($dbresults['passwordHash'],$dbresults['dateLastSignedIn']))!=$_COOKIE["token"]
            )
            {
                //if($debug)echo "<!-- DEBUG: Cookie email not found in db, no passwordHash, or passwordHash didn't match cookie. Destroying cookie. -->\n";
                setcookie("email","",time()-1000,'/',".".getSiteDomain());
                setcookie("token","",time()-1000,'/',".".getSiteDomain());
            }
            else
            {
                //verified user.
                if($loggedIn==false) //we don't have a valid session but we have a valid cookie
                {
                    //if($debug)echo "<!-- DEBUG: Logged in with cookie and set session token -->\n";
                    //update the last seen every 2 hours
                    $time2h = 7200; //3600 per hour
                    if(($dbresults['dateLastSeen']==null) || (time() > $dbresults['dateLastSeen'] + $time2h))
                    {
                        mysqli_query($db, "UPDATE ".$dbname.".users SET `dateLastSeen` = '".time()."' WHERE `email` = '".$email."'");
                    }

                    //set the session email and token from the cookie
                    $_SESSION['email']=$email;
                    $_SESSION['token']=$_COOKIE["token"];
                }
                $loggedIn =  true;
            }

            //done with the db
            mysqli_close($db);
    }

    return $loggedIn;

}

//=========================================================================================
function goHomeIfCookieNotSet()
{//=========================================================================================

    // make sure signed in, otherwise go home
    if(isCookieSet()==false)
    {
        header('Location: '.getSiteURL());
        echo '<meta http-equiv="refresh" content="1;url='.getSiteURL().'"/>';
        exit();
    }
	
}

//=========================================================================================
function isProfileDone()
{//=========================================================================================

    include("_secrets.php");

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    $email = mysqli_real_escape_string($db, $_SESSION["email"]);

    $dbquerystring = sprintf("SELECT id, email, profileDone FROM ".$dbname.".users WHERE email='%s'",$email);
    $dbquery = mysqli_query($db, $dbquerystring);
    $dbresults = mysqli_fetch_array($dbquery);
    mysqli_free_result($dbquery);

    //done with the db
    mysqli_close($db);

    if(
        $dbresults!=null&&$dbresults['profileDone']==1
    )
    {
        //let's set the profileDone for below, whether to pull vars from db or not
        return 1;
    }
    else return 0;
}

//=========================================================================================
function isCookieSet()
{//=========================================================================================

    if(isset($_SESSION["email"])&&$_SESSION["email"]!=null&&strlen($_SESSION["email"])>0)
        return 1;
    else
        return 0;
}

//=========================================================================================
function getDistanceBetweenPoints($lat1,$lon1,$lat2,$lon2)
{//=========================================================================================

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;

    return round($miles,2);
}

//=========================================================================================
function getGenderString($gender)
{//=========================================================================================
    if($gender=='male')return "Man";
    if($gender=='female')return "Woman";
    if($gender=='mtf')return "TS Woman";
    if($gender=='ftm')return "TS Man";
    if($gender=='cdmtf')return "CD Woman";
    if($gender=='cdftm')return "CD Man";
    if($gender=='mf')return "Man And Woman Couple";
    if($gender=='mm')return "Man And Man Couple";
    if($gender=='ff')return "Woman And Woman Couple";
    if($gender=='group')return "Group Of People";
}

//=========================================================================================
function getAge($month,$day,$year)
{//=========================================================================================
    return (date("md", date("U", mktime(0,0,0, $month, $day, $year))) > date("md") ? ((date("Y")-$year)-1):(date("Y")-$year));
}

//=========================================================================================
function getTimeElapsedStringSinceTimestamp($time)
{//=========================================================================================
    $secs = time()-$time;
    $bit = array
    (
        ' year' => $secs / 31556926 % 12,
        ' week' => $secs / 604800 % 52,
        ' day' => $secs / 86400 % 7,
        ' hour' =>$secs / 3600 % 24
        //,
        //' minute' => $secs / 60 % 60,
        //' second' => $secs % 60
    );

    $ret = null;

    foreach($bit as $k => $v)
    {
        if($v > 1)$ret[] = $v . $k . 's';
        if($v == 1)$ret[] = $v . $k;
    }

    if($ret!=null)
    {
        array_splice($ret, count($ret)-1, 0, 'and');
        $ret[] = 'ago.';

        return join(' ', $ret);
    }
    else
    return "Online Now!";

}

//=========================================================================================
function getDateStringFromTimestamp($time)
{//=========================================================================================

    return date("F j, Y, g:i a",$time);

}

//=========================================================================================
function printGenderChar($g)
{//=========================================================================================


    if($g['gender']=="male")	echo '<span style="color:#0397ff;text-shadow:#339 1px 1px 2px;">&#x2642; </span>';
    if($g['gender']=="female")	echo '<span style="color:#ff66cb;text-shadow:#917 1px 1px 2px;">&#x2640; </span>';
    if($g['gender']=="mtf")		echo '<span style="color:#84f;text-shadow:#319 1px 1px 2px;">&#x26A5; </span>';
    if($g['gender']=="ftm")		echo '<span style="color:#84f;text-shadow:#319 1px 1px 2px;">&#x26A5; </span>';
    if($g['gender']=="cdmtf")		echo '<span style="color:#84f;text-shadow:#319 1px 1px 2px;">&#x26A5; </span>';
    if($g['gender']=="cdftm")		echo '<span style="color:#84f;text-shadow:#319 1px 1px 2px;">&#x26A5; </span>';
    if($g['gender']=="mf")		echo '<span style="color:#0397ff;text-shadow:#339 1px 1px 2px;">&#x2642;</span><span style="color:#ff66cb;text-shadow:#917 1px 1px 2px;">&#x2640;</span>';
    if($g['gender']=="mm")		echo '<span style="color:#0397ff;text-shadow:#339 1px 1px 2px;">&#x2642;&#x2642;</span>';
    if($g['gender']=="ff")		echo '<span style="color:#ff66cb;text-shadow:#917 1px 1px 2px;">&#x2640;&#x2640;</span>';
    if($g['gender']=="group")		echo '<span style="color:#0397ff;text-shadow:#339 1px 1px 2px;">&#x2642;</span><span style="color:#ff66cb;text-shadow:#917 1px 1px 2px;">&#x2640;</span><span style="color:#0397ff;text-shadow:#339 1px 1px 2px;">&#x2642;</span><span style="color:#ff66cb;text-shadow:#917 1px 1px 2px;">&#x2640;</span>';

}
//=========================================================================================
function printGenderString($g)
{//=========================================================================================

    $genderString = getGenderString($g['gender']);

    if($g['gender']=="male")	echo '<span style="color:#0397ff;text-shadow:#339 1px 1px 2px;">'.$genderString.'</span>';
    if($g['gender']=="female")	echo '<span style="color:#ff66cb;text-shadow:#917 1px 1px 2px;">'.$genderString.'</span>';
    if($g['gender']=="mtf")		echo '<span style="color:#84f;text-shadow:#319 1px 1px 2px;">'.$genderString.'</span>';
    if($g['gender']=="ftm")		echo '<span style="color:#84f;text-shadow:#319 1px 1px 2px;">'.$genderString.'</span>';
    if($g['gender']=="cdmtf")		echo '<span style="color:#84f;text-shadow:#319 1px 1px 2px;">'.$genderString.'</span>';
    if($g['gender']=="cdftm")		echo '<span style="color:#84f;text-shadow:#319 1px 1px 2px;">'.$genderString.'</span>';
    if($g['gender']=="mf")		echo '<span style="color:#0397ff;text-shadow:#339 1px 1px 2px;">'.$genderString.'</span>';
    if($g['gender']=="mm")		echo '<span style="color:#0397ff;text-shadow:#339 1px 1px 2px;">'.$genderString.'</span>';
    if($g['gender']=="ff")		echo '<span style="color:#ff66cb;text-shadow:#917 1px 1px 2px;">'.$genderString.'</span>';
    if($g['gender']=="group")		echo '<span style="color:#84f;text-shadow:#319 1px 1px 2px;">'.$genderString.'</span>';

}

//=========================================================================================
function hasPenis($g)
{//=========================================================================================
    if(
        $g['gender']=='male'||
        $g['gender']=='mtf'||
        $g['gender']=='cdmtf'||
        $g['gender']=='male'||
        $g['gender']=='mf'||
        $g['gender']=='mm'
        )
        return true;

    return false;

}
//=========================================================================================
function hasBreasts($g)
{//=========================================================================================
        if(
        $g['gender']=='female'||
        $g['gender']=='ftm'||
        $g['gender']=='mtf'||
        $g['gender']=='mf'||
        $g['gender']=='ff'
        )
        return true;

    return false;
}
//=========================================================================================
function hasBodyHair($g)
{//=========================================================================================
    if(
        $g['gender']=='male'||
        $g['gender']=='ftm'||
        $g['gender']=='mtf'||
        $g['gender']=='cdmtf'||
        $g['gender']=='mf'||
        $g['gender']=='mm'
    )
    return true;

    return false;
}


//=========================================================================================
function wantsPenis($g)
{//=========================================================================================

    if(
        $g['b_wantGenderMan']==1||
        $g['b_wantGenderTSWoman']==1||
        $g['b_wantGenderCDWoman']==1||
        $g['b_wantGenderCoupleMF']==1||
        $g['b_wantGenderCoupleMM']==1

    )
    return true;

    return false;
}
//=========================================================================================
function wantsBreasts($g)
{//=========================================================================================
    if(
        $g['b_wantGenderWoman']==1||
        $g['b_wantGenderTSWoman']==1||
        $g['b_wantGenderTSMan']==1||
        $g['b_wantGenderCoupleMF']==1||
        $g['b_wantGenderCoupleFF']==1

    )
    return true;

    return false;
}
//=========================================================================================
function wantsBodyHair($g)
{//=========================================================================================
    if(
        $g['b_wantGenderMan']==1||
        $g['b_wantGenderTSMan']==1||
        $g['b_wantGenderCDMan']==1||
        $g['b_wantGenderCoupleMF']==1||
        $g['b_wantGenderCoupleMM']==1

    )
    return true;

    return false;
}
	
//=========================================================================================
function usortByArrayKey(&$array, $key, $asc=SORT_ASC)
{//=========================================================================================
    $sort_flags = array(SORT_ASC, SORT_DESC);
    if(!in_array($asc, $sort_flags)) throw new InvalidArgumentException('sort flag only accepts SORT_ASC or SORT_DESC');
    $cmp = function(array $a, array $b) use ($key, $asc, $sort_flags)
	{
        if(!is_array($key))
		{ //just one key and sort direction
            if(!isset($a[$key]) || !isset($b[$key]))
			{
                throw new Exception('attempting to sort on non-existent keys');
            }
            if($a[$key] == $b[$key]) return 0;
            return ($asc==SORT_ASC xor $a[$key] < $b[$key]) ? 1 : -1;
        } 
		else
		{ //using multiple keys for sort and sub-sort
            foreach($key as $sub_key => $sub_asc)
			{
                //array can come as 'sort_key'=>SORT_ASC|SORT_DESC or just 'sort_key', so need to detect which
                if(!in_array($sub_asc, $sort_flags)) { $sub_key = $sub_asc; $sub_asc = $asc; }
                //just like above, except 'continue' in place of return 0
                if(!isset($a[$sub_key]) || !isset($b[$sub_key]))
				{
                    throw new Exception('attempting to sort on non-existent keys');
                }
                if($a[$sub_key] == $b[$sub_key]) continue;
                return ($sub_asc==SORT_ASC xor $a[$sub_key] < $b[$sub_key]) ? 1 : -1;
            }
            return 0;
        }
    };
    usort($array, $cmp);
}

//=========================================================================================
function rem_array(&$array,$str)
{//=========================================================================================
	foreach($array as $key => $value)
	{	
		if($array[$key] == "$str") unset($array[$key]);
	}
	return $array;
}
//=========================================================================================
function convert_line_breaks($string, $line_break="<br>")
{//=========================================================================================
    $patterns = array(
        "/(<br>|<br \/>|<br\/>)\s*/i",
        "/(\r\n|\r|\n)/"
    );
    $replacements = array(
        PHP_EOL,
        $line_break
    );
    $string = preg_replace($patterns, $replacements, $string);
    return $string;
}
		
