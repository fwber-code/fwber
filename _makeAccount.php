<?php

    include("_init.php");
    include("_secrets.php");
    include("_names.php");
    include("_debug.php");

    if($_SERVER["REQUEST_METHOD"] != "POST") {header("Location: ".getSiteURL()."/join");exit();}

    if(
        (
            isset($_POST['nameEmail']) &&!empty($_POST['nameEmail'])
            &&isset($_POST['namePassword']) &&!empty($_POST['namePassword'])
            &&isset($_POST['nameVerify']) &&!empty($_POST['nameVerify'])
            &&isset($_POST['nameAgreeLegalAge']) &&!empty($_POST['nameAgreeLegalAge'])
            &&isset($_POST['nameAgreeTOS']) &&!empty($_POST['nameAgreeTOS'])
        )==false
    )
    {
        header("Location: ".getSiteURL()."/join");
        exit();
    }

    $db = mysqli_connect($dburl,$dbuser,$dbpass);
    if(!$db)exit(mysqli_connect_error());

    //escape the values
    $email = mysqli_real_escape_string($db,$_POST['nameEmail']);
    $password = mysqli_real_escape_string($db,$_POST['namePassword']);
    $verify = mysqli_real_escape_string($db,$_POST['nameVerify']);

    if(
            strlen($password)<5 ||
            strcmp($password,$verify)!=0 ||
            $_POST['nameAgreeLegalAge']!=true ||
            $_POST['nameAgreeTOS']!=true ||
            !filter_var($email,FILTER_VALIDATE_EMAIL)
    )
    {
        header("Location: ".getSiteURL()."/join");
        exit();
    }

    if(isset($_POST['g-recaptcha-response'])){
        $captcha=$_POST['g-recaptcha-response'];
    }
    if(!$captcha)
    {
        header("Location: ".getSiteURL()."/join");
        exit();
    }

    $ip = $_SERVER['REMOTE_ADDR'];
    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($reCaptchaSecretKey) .  '&response=' . urlencode($captcha);
    $response = file_get_contents($url);
    $responseKeys = json_decode($response,true);
    // should return JSON with success as true
    if($responseKeys["success"]==false)
    {
        header("Location: ".getSiteURL()."/join");
        exit();
    }

    //connect to the database to see if the email is already registered.
    $dbquerystring = sprintf("SELECT email, verifyHash FROM ".$dbname.".users WHERE email='%s'",$email);
    $dbquery = mysqli_query($db, $dbquerystring);
    $dbresult = mysqli_fetch_array($dbquery);
    mysqli_free_result($dbquery);

    $emailExists = true;

    if($dbresult==null || $dbresult['email']==null || $dbresult['email']!=$email)
    {
        $emailExists=false;
    }
?>
<!doctype html>
<html lang="en">
<head>
	<title><?php require_once("_names.php"); echo getSiteName(); ?> - New Account Confirmation<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
	<?php include("head.php");?>
</head>
<body class="d-flex flex-column h-100">
    <?php include("h.php");?>
    <div class="col-sm-12 my-auto text-center">
            <div style="color:#0096ff;font-size:14pt;text-shadow:#aad 1px 1px 1px;">
                Your account has been created.<br><br>
                <div style="color:#000;font-size:12pt;text-shadow:#aaa 1px 1px 1px;">
                    Please check your email for a verification link.<br>
                    If it is in the spam folder please mark it as not spam.<br>
                    <br><br>
                </div>
            </div>
<?php

    if($emailExists==false)
    {
        $dateJoined = time();
        $verifyHash = md5(rand(0,1000));
        $dbquerystring = "INSERT INTO ".$dbname.".users (email, passwordHash, verifyHash, dateJoined) VALUES(
        '". mysqli_real_escape_string($db,$email) ."',
        '". mysqli_real_escape_string($db,getSaltedPassword($password,$dateJoined)) ."',
        '". mysqli_real_escape_string($db,$verifyHash) ."',
        '". mysqli_real_escape_string($db,$dateJoined) ."') ";

        $dbquery = mysqli_query($db, $dbquerystring);
    }
    else
    {
        $verifyHash = $dbresult['verifyHash'];
    }

    mysqli_close($db);

    include("_emailFunctions.php");
    sendJoinVerificationEmail($email,$verifyHash,$emailExists);

?>
</div>
<?php include("f.php");?>
</body>
</html>

