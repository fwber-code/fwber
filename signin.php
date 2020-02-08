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

    $show_html=false;
    $error_string = "";

    if(isset($_SESSION["email"])&&$_SESSION["email"]!=null&&strlen($_SESSION["email"])>0)
    {
        header('Location: '.getSiteURL().'/matches.php');
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] != "POST")
    {
        $show_html=true;
    }
    else
    {

        if(isset($_POST['nameEmail']) &&!empty($_POST['nameEmail'])
           &&isset($_POST['namePassword']) &&!empty($_POST['namePassword']))
        {

            $db = mysqli_connect($dburl,$dbuser,$dbpass);
            if(!$db)exit(mysqli_connect_error());

            $email = mysqli_escape_string($db,$_POST['nameEmail']);
            $password = mysqli_escape_string($db,$_POST['namePassword']);
            $staySignedIn = 0;
            if(isset($_POST['staySignedIn'])&&!empty($_POST['staySignedIn']))$staySignedIn = 1;

            //validate email using php filter_var
            if(!filter_var($email,FILTER_VALIDATE_EMAIL))
            {
                exit("not valid.");
            }

            //connect to the database to see if the email is already registered.
            $dbquerystring = sprintf("SELECT id, email, passwordHash, verified, dateJoined FROM ".$dbname.".users WHERE email='%s'",$email);
            $dbquery = mysqli_query($db,$dbquerystring);
            $dbresults = mysqli_fetch_array($dbquery);
            mysqli_free_result($dbquery);

            //done with the db
            mysqli_close($db);

            if(
                $dbresults==null
                ||$dbresults['email']==null
                ||$dbresults['email']!=$email
                ||$dbresults['passwordHash']==null
                ||$dbresults['passwordHash']!=getSaltedPassword($password,$dbresults['dateJoined'])
                ||$dbresults['verified']==null
                ||$dbresults['verified']==0
            )
            {
                $show_html=true;

                if($dbresults!=null
                    &&$dbresults['email']==$email
                    &&$dbresults['passwordHash']==getSaltedPassword($password,$dbresults['dateJoined'])
                    &&$dbresults['verified']==0)
                {
                    $error_string =
                        "<div style='width:100%;text-align:center;font-size:14px;padding-bottom:16px;'>
                            Your email address is not yet verified.
                            <br>
                            <br>
                            <div style='color:#000;font-size:14px;font-weight:normal;text-shadow:#aaa 0px 0px 1px;'>
                                Please verify your email address by clicking the link in your email. Check your spam folder if you can't find it, and mark it not spam.
                            </div>
                        </div>";
                }
                else
                {
                    $error_string =
                        "<div style='width:100%;text-align:center;font-size:14px;padding-bottom:16px;'>
                            Either you typed the wrong password or your email address is not yet signed up.
                            <br>
                            <br>

                        </div>";
                }
            }
            else
            {
                $dateLastSignedIn = time();

                //connect to database and set dateLastSignedIn

                $db = mysqli_connect($dburl,$dbuser,$dbpass);
                if(!$db)exit(mysqli_connect_error());


                mysqli_query($db,"UPDATE ".$dbname.".users SET `dateLastSignedIn` = '".$dateLastSignedIn."' WHERE `email` = '".$email."'");

                //done with the db
                mysqli_close($db);

                $_SESSION['email']=$email;
                $_SESSION['token']=getSaltedPassword($dbresults['passwordHash'],$dateLastSignedIn);

                if($staySignedIn==1)
                {
                    setcookie("email",$email,time()+(3600*24*30),'/',".".getSiteDomain());
                    setcookie("token",getSaltedPassword($dbresults['passwordHash'],$dateLastSignedIn),time()+(3600*24*30),'/',".".getSiteDomain());
                }

                header('Location: '.getSiteURL().'/matches');
                exit();
            }
        }
    }

    if($show_html==false)exit();

?>
<!doctype html>
<html lang="en">
<head>
	<title><?php require_once("_names.php"); echo getSiteName(); ?> - Sign In<?php require_once("_names.php"); echo getTitleTagline(); ?></title>
	<?php include("head.php");?>	
</head>
<body class="d-flex flex-column h-100">
<?php include("h.php");?>

    <div class="col-sm-12 my-auto text-center">
        <form class="form-signin" action="signin" method="POST" enctype="multipart/form-data"
              name="signinFormName" id="signinFormID">
            <fieldset style="text-align:left;">

                <h1 class="h3 mb-3 font-weight-normal text-center"> Sign In</h1>

                <label for="idEmail" class="sr-only">Email address</label>
                <input type="email" id="idEmail" class="form-control required" placeholder="Email address" name="nameEmail" required autofocus>

                <label for="idPassword" class="sr-only">Password</label>
                <input type="password" id="idPassword" class="form-control required" placeholder="Password" name="namePassword" required>

                <div class="text-center" align="center">

                    <div>
                        <label class="checkbox text-left" style="display:inline-block;float:none;">
                            <input type="checkbox" onclick="toggle(this)" name="idStaySignedIn" checked="checked" class="required" required> Stay signed in
                        </label>
                    </div>
                    <br>

<?php
    if($error_string!="")
    {
        echo '
            <br>
            <br>
            <br>
            <div style="color:#0096ff;font-size:12pt;text-shadow:#aad 1px 1px 1px;">
                    '.$error_string.'
            </div>
            <br>
            <br>
';
    }
?>


                    <button class="btn btn-lg btn-primary" type="submit" name="signinButtonName"
                            id="signinButtonID">Sign In
                    </button>
                </div>
                <br>
                <br>

                <div style="width:100%;text-align:center;font-size:14px;padding-bottom:16px;">
                    <a href="/forgotpassword">Forgot Password?</a>
                </div>

            </fieldset>
        </form>
    </div>



<?php include("f.php");?>

<script src="/js/jquery/jquery-1.7.2.min.js" type="text/javascript" ></script>
<script src="/js/jquery-validate/jquery.validate.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $.validator.setDefaults({});
    $().ready
    (
        function()
        {
            $("#signinFormID").validate
            ({
                errorElement:"div",

                rules:
                    {
                        nameEmail:
                            {
                                email:true
                            },
                        namePassword:
                            {
                                required:true,
                                minlength:5
                            },
                    }
                ,
                messages:
                    {
                        namePassword:
                            {
                                required:"Enter a password.",
                                minlength:"Your password must be at least 5 characters long"
                            },
                        nameEmail:
                            {
                                required: "Enter your email address."
                            }
                    }
            });
        }
    );


</script>
</body>
</html>


