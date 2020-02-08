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

	//force https
	if (empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on") {
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		exit();
	}

	//check cookie, see if user is logged in. if not, tell them to register.
	if (
		(isset($_COOKIE["email"]) && $_COOKIE["email"] != null && strlen($_COOKIE["email"]) > 0)
		||
		(isset($_SESSION["email"]) && $_SESSION["email"] != null && strlen($_SESSION["email"]) > 0)
		) 
		{
			require_once("_names.php");
			header('Location: '.getSiteURL().'/matches');
			exit();
		}
?>
<!doctype html>
<html lang="en">
<head>
    <title><?php require_once("_names.php");echo getSiteName(); ?><?php require_once("_names.php");echo getTitleTagline(); ?></title>
    <?php include("head.php"); ?>
</head>
<body class="d-flex flex-column h-100">
    <?php include("h.php"); ?>
    <div class="col-sm-12 my-auto text-center">
    <form class="form-signin" action="_makeAccount" method="POST" enctype="multipart/form-data"
          name="joinFormName" id="joinFormID">
        <fieldset style="text-align:left;">

            <h1 class="h3 mb-3 font-weight-normal text-center"> Join Now</h1>

            <label for="idEmail" class="sr-only">Email address</label>
            <input type="email" id="idEmail" class="form-control required" placeholder="Email address" name="nameEmail" required autofocus>

            <br>
            <label for="idPassword" class="sr-only">Password</label>
            <input type="password" id="idPassword" class="form-control required" placeholder="Password" name="namePassword" required>

            <label for="idVerify" class="sr-only">Verify Password</label>
            <input type="password" id="idVerify" class="form-control required" placeholder="Verify Password" name="nameVerify" required>

            <div class="text-center">
                <label class="section" for="captchaDiv"></label>
                <div id="captchaDiv">
                    <div class="g-recaptcha" data-sitekey="6LfUldISAAAAAJjP3rj8cCd1CEmBrfdEMVE_51eZ" data-callback="reCaptchaCallback"></div>
                </div>
                <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">
                <br>
                <div>
                    <label class="checkbox text-left">
                        <input type="checkbox" onclick="toggle(this)" name="nameAgreeLegalAge" class="required" required> I certify that I am of legal adult age in my country
                    </label>
                </div>
                <br>
                <div>
                    <label class="checkbox text-left">
                        <input type="checkbox" onclick="toggle(this)" name="nameAgreeTOS" class="required" required> I agree to <?php echo getSiteName(); ?> <a href="/tos" target="_blank">Terms Of Service</a> and <a href="/privacy" target="_blank">Privacy Policy</a>
                    </label>
                </div>
                <br>
                <br>
                <br>
                <br>
                <button class="btn btn-lg btn-primary" type="submit" name="createAccountButtonName"
                        id="createAccountButtonID">Join Now
                </button>
            </div>
            <br>

            <div class="smallText">
                <span style="color:#ff00a8; font-size:10pt; font-weight:normal;">You can completely delete your profile at any time.</span><br>
            </div>
        </fieldset>
    </form>
</div>


<?php include("f.php");?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="/bootstrap-4.3.1-dist/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>

<script src="/js/jquery-validate/jquery.validate.min.js" type="text/javascript"></script>

<script src='https://kit.fontawesome.com/a076d05399.js'></script>

<script type="text/javascript">

    var doSubmit = false;

    $.validator.setDefaults({});
    $().ready
    (
        function () {
            // validate the comment form when it is submitted
            $("#joinFormID").validate
            ({
                errorElement: "div",
                ignore: ".ignore", //needed for hiddenCaptcha input to validate captcha
                rules:
                    {
                        nameEmail:
                            {
                                email: true
                            },
                        namePassword:
                            {
                                required: true,
                                minlength: 5
                            },
                        nameVerify:
                            {
                                equalTo: "#idPassword"
                            },
                        hiddenRecaptcha:
                            {
                                required: function () {
                                    if (doSubmit == false) {
                                        return true;
                                    } else {
                                        return false;
                                    }
                                }
                            },
                    }
                ,
                messages:
                    {
                        namePassword:
                            {
                                required: "Enter a password.",
                                minlength: "Your password must be at least 5 characters long"
                            },
                        nameEmail:
                            {
                                required: "We need your email address to send you matches."
                            },
                        nameVerify:
                            {
                                required: "Verify your password."
                            },
                        nameAgreeLegalAge: "You must be of legal age.",
                        nameAgreeTOS: "You must agree to the Terms Of Service and Privacy Policy to sign up.",
                        hiddenRecaptcha: "Please complete the captcha to continue."
                    }
                ,
            });
        }
    );

    function reCaptchaCallback(response)
    {
        if (response === document.querySelector('.g-recaptcha-response').value)doSubmit = true;

        $('#hiddenRecaptcha').valid();
    }



</script>

<script src='https://www.google.com/recaptcha/api.js' defer></script>

</body>
</html>
