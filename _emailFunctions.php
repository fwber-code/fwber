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
    //=========================================================================================
    function doMail($mailFromAddress,$mailFromName,$mailToAddress,$mailSubject,$mailHTMLBody,$mailTextBody)
    {//=========================================================================================

        include('_secrets.php');

        // sendgrid swift mailer
        /*
        require_once("./sendgrid/SendGrid_loader.php");
        $sendgrid = new SendGrid($mailUsername, $mailPassword);
        $sgmail = new SendGrid\Mail();
        $sgmail->addTo($mailToAddress);
        $sgmail->setFrom($mailFromAddress,$mailFromName);
        $sgmail->setSubject($mailSubject);
        $sgmail->setText($mailTextBody);
        $sgmail->setHtml($mailHTMLBody);
        //$sgmail->addAttachment("");
        if($sendgrid->smtp->send($sgmail)==false)return false;
        return true;
        */

        //phpmailer
        include('js/PHPMailer-6.1.4/src/PHPMailer.php');
        $mail             = new PHPMailer();
        //$mailHTMLBody   = file_get_contents('contents.html');
        //$mailHTMLBody   = eregi_replace("[\]",'',$mailHTMLBody);
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->SMTPDebug  = $mailSMTPDebug;// enables SMTP debug information (for testing)// 1 = errors and messages // 2 = messages only
        $mail->SMTPAuth   = $mailSMTPAuth;// enable SMTP authentication
        $mail->SMTPSecure = $mailSMTPSecurityType;// sets the prefix to the servier
        $mail->Host       = $mailHost;// sets GMAIL as the SMTP server
        $mail->Port       = $mailPort;// set the SMTP port for the GMAIL server
        $mail->Username   = $mailUsername;// GMAIL username
        $mail->Password   = $mailPassword;// GMAIL password
        $mail->SetFrom($mailFromAddress,$mailFromName);
        $mail->AddReplyTo($mailFromAddress,$mailFromName);
        $mail->Subject    = $mailSubject;
        $mail->AltBody    =  $mailTextBody;
        $mail->MsgHTML($mailHTMLBody);
        $mail->AddAddress($mailToAddress);
        //$mail->AddAttachment("images/phpmailer.gif");      // attachment
        //$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
        if(!$mail->Send()){echo "Mailer Error: ".$mail->ErrorInfo;return false;}
        //else echo "Message sent!";
        return true;
    }

	//=========================================================================================
	function sendContactEmail($subject,$message,$theirEmail)
	{//=========================================================================================

		$mailSubject=$subject;

        $mailHTMLBody = "";
		if($theirEmail!="")$mailHTMLBody="
		    Email: ".$theirEmail."<br>";

        $mailHTMLBody=$mailHTMLBody.
		"
			Subject: ".$subject."<br>
			Message:<br>
			".$message."
		";
		$mailTextBody = "Contact Form";

        $success = doMail(getEmailAddress(),getSiteDomain(),getEmailAddress(),$mailSubject,$mailHTMLBody,$mailTextBody);
        return $success;
	}

	//=========================================================================================
	function sendMatchNoticeEmail($myFirstName,$theirEmail,$verifyHash)
	{//=========================================================================================

		//should store this in database instead, and batch it.
		//i should send in newUser and existingUser, store both.
			
		$mailToAddress=$theirEmail;
		$mailSubject=getSiteDomain()." - You got a new match";
		$mailHTMLBody=	
		"
		<b>".$myFirstName."</b> just signed up for ".getSiteName().", and we've discovered they have compatible interests.<br>
		<br>
		Go take a look and see if they are a good match for you.<br>
		<br>
		Check them out here:
		<a href='".getSiteURL()."/matches'>My Matches</a>
		<br>
		<br>
		<br>
		To immediately unsubscribe from <b>new match</b> notifications, please visit this link:<br>
		<a href='".getSiteURL()."/unsubscribe?type=matches&email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=matches&email=".$theirEmail."&verifyHash=".$verifyHash."</a><br>
		<br>
		To immediately unsubscribe from <b>ALL</b> notifications, please visit this link:<br>
		<a href='".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."</a><br>
		<br>
		";
		$mailTextBody = $myFirstName." just signed up for ".getSiteName().", and we've discovered they have compatible interests. Check them out here: ".getSiteURL()."/matches";

        $success = doMail(getEmailAddress(),getSiteDomain(),$mailToAddress,$mailSubject,$mailHTMLBody,$mailTextBody);
        return $success;
	}

	
	//=========================================================================================
	function sendMatchActionEmail($myFirstName,$theirEmail,$action,$verifyHash)
	{//=========================================================================================
	
		$mailToAddress=$theirEmail;
		$mailTextBody = "Check them out here: ".getSiteURL()."/matches";
		

		if($action=='askprivate')
		{
			$mailSubject=getSiteDomain()." - ".$myFirstName." wants to see your private profile";
			$mailHTMLBody=	
			"
			".$myFirstName." wants to see your private profile on ".getSiteName()."<br>
			<br>
			This will show them your full profile including contact information and private pictures, and you'll be able to see theirs.<br>
			<br>
			Check them out here:
			<a href='".getSiteURL()."/matches?show=waitingforme'>My Matches</a>
			<br>
			<br>
			<br>
			To immediately unsubscribe from request notifications, please visit this link:<br>
			<a href='".getSiteURL()."/unsubscribe?type=interested&email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=interested&email=".$theirEmail."&verifyHash=".$verifyHash."</a><br>
			<br>
			To immediately unsubscribe from ALL notifications, please visit this link:<br>
			<a href='".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."</a><br>
			<br>
			";
		}
	
		
		if($action=='authorizeprivate')
		{
			$mailSubject=getSiteDomain()." - You've been approved to see ".$myFirstName."'s private profile!";
			$mailHTMLBody=	
			"
			".$myFirstName." has agreed to share their private profile with you on ".getSiteName()."<br>
			<br>
			Quick, go check them out and take it to the next step!<br>
			<br>
			Check them out here:
			<a href='".getSiteURL()."/matches?show=fwbs'>My fwbs</a>
			<br>
			<br>
			<br>
			To immediately unsubscribe from approval notifications, please visit this link: <br>
			<a href='".getSiteURL()."/unsubscribe?type=approval&email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=approval&email=".$theirEmail."&verifyHash=".$verifyHash."</a><br>
			<br>
			To immediately unsubscribe from ALL notifications, please visit this link: <br>
			<a href='".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."</a><br>
			<br>
			";
		}

        $success = doMail(getEmailAddress(),getSiteDomain(),$mailToAddress,$mailSubject,$mailHTMLBody,$mailTextBody);
        return $success;
	}
	
	//=========================================================================================
	function sendPasswordResetVerificationEmail($theirEmail,$verifyHash)
	{//=========================================================================================

		//send verification email to new email
		$mailToAddress=$theirEmail;
		$mailSubject=getSiteDomain()." - Password Reset Request";
		$mailHTMLBody=	
		"
            Someone recently requested a password reset for your account at ".getSiteDomain()."<br>
            If this wasn't you, please ignore this email.<br>
            <br>
            To get a new password sent to you, click on or paste this link into your browser:<br>
            <a href='".getSiteURL()."/_mailNewPassword?email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/_mailNewPassword?email=".$theirEmail."&verifyHash=".$verifyHash."</a>
            <br>
            <br>
			<br>
			To immediately unsubscribe from ALL notifications, please visit this link: <br>
			<a href='".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."</a><br>
			<br>
		";
		$mailTextBody = "To get a new password sent to you, please copy and paste this link into your browser. \n\n ".getSiteURL()."/_mailNewPassword?email=".$theirEmail."&verifyHash=".$verifyHash;

        $success = doMail(getEmailAddress(),getSiteDomain(),$mailToAddress,$mailSubject,$mailHTMLBody,$mailTextBody);
        return $success;
	}
	
	//=========================================================================================
	function sendNewPasswordEmail($theirEmail, $newPass,$verifyHash)
	{//=========================================================================================
	
		$success=true;
	
		//send verification email to new email
		$mailToAddress=$theirEmail;
		$mailSubject=getSiteDomain()." - Your New Password";
		$mailHTMLBody=
		"
            Here is your new password for your ".getSiteDomain()." account:<br>
            \"".$newPass."\"
            <br><br>
            Sign in and change it if you'd like under My Profile | Settings.
            <br>
            <br>
			<br>
			To immediately unsubscribe from ALL notifications, please visit this link: <br>
			<a href='".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."</a><br>
			<br>
		";
		$mailTextBody = "Here is your new password for your account at ".getSiteDomain()." (not including quotes): \"".$newPass."\"";

        $success = doMail(getEmailAddress(),getSiteDomain(),$mailToAddress,$mailSubject,$mailHTMLBody,$mailTextBody);
		return $success;
	}
	
	//=========================================================================================
	function sendJoinVerificationEmail($theirEmail,$verifyHash,$emailExists)
	{//=========================================================================================

		//if email is in use, send email asking if they wanted to change their password.
		//if email isn't in use, send verification email.

		$mailToAddress=$theirEmail;
		
		if($emailExists==false)
		{
			//send verification email
			$mailSubject=getSiteDomain()." - Verify your new ".getSiteName()." account";
			$mailHTMLBody=
			"
			Your email address was recently signed up for an account at ".getSiteDomain()."<br>
			<br>
			Click on or paste this link into your browser to verify your account and sign in:<br>
			<a href='".getSiteURL()."/verify?email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/verify?email=".$theirEmail."&verifyHash=".$verifyHash."</a>
			<br>
			<br>
			<br>
			To immediately unsubscribe from ALL notifications, please visit this link: <br>
			<a href='".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."</a><br>
			<br>
			";
			$mailTextBody = "To verify your account, please copy and paste this link into your browser. \n\n ".getSiteURL()."/verify?email=".$theirEmail."&verifyHash=".$verifyHash;
		}
		else
		{
			//send warning email
			$mailSubject=getSiteDomain()." - You already have a ".getSiteName()." account";
			$mailHTMLBody=
			"
			Your email address was recently signed up for an account at ".getSiteDomain().", but your email address is already in our system.<br>
			If this wasn't you, just ignore this email.<br>
			<br>
			If this was on purpose and you can't remember your password, go here instead:<br>
			<a href='".getSiteURL()."/forgotpassword'>".getSiteURL()."/forgotpassword</a>
			<br>
			<br>
			<br>
			If you needed your verification link again, here it is:<br>
			<a href='".getSiteURL()."/verify?email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/verify?email=".$theirEmail."&verifyHash=".$verifyHash."</a>
			<br>
			<br>
			<br>
			To immediately unsubscribe from ALL notifications, please visit this link: <br>
			<a href='".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=all&email=".$theirEmail."&verifyHash=".$verifyHash."</a><br>
			<br>
			";
			
			$mailTextBody = "Your email address was recently signed up for an account at ".getSiteDomain().", but your email address is already in our system. If this was on purpose and you can't remember your password, go here instead: ".getSiteURL()."/forgotpassword \n\n If you lost your verification email and wanted a new one, use this: ".getSiteURL()."/verify?email=".$theirEmail."&verifyHash=".$verifyHash;
		}

        $success = doMail(getEmailAddress(),getSiteDomain(),$mailToAddress,$mailSubject,$mailHTMLBody,$mailTextBody);
        return $success;
	}
	
	//=========================================================================================
	function sendNewEmailAddressVerificationEmail($myNewEmail,$verifyHash)
	{//=========================================================================================

		//send verification email to new email

		$mailToAddress=$myNewEmail;

		$mailSubject=getSiteDomain()." - Verify your new email address";
		$mailHTMLBody=	
		"
		Your email address was recently changed for your account at ".getSiteDomain().".<br>
		Click on or paste this link into your browser to verify your new email address and sign in:<br>
		<br>
		<a href='".getSiteURL()."/verify?email=".$myNewEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/verify?email=".$myNewEmail."&verifyHash=".$verifyHash."</a>
		<br>
		<br>
		Thanks for signing up for ".getSiteName()."!<br>
		<br>
		<br>
			<br>
			To immediately unsubscribe from ALL notifications, please visit this link: <br>
			<a href='".getSiteURL()."/unsubscribe?type=all&email=".$myNewEmail."&verifyHash=".$verifyHash."'>".getSiteURL()."/unsubscribe?type=all&email=".$myNewEmail."&verifyHash=".$verifyHash."</a><br>
			<br>
		";
				
		$mailTextBody= "To verify your new email address, please copy and paste this link into your browser. \n\n ".getSiteURL()."/verify?email=".$myNewEmail."&verifyHash=".$verifyHash;

        $success = doMail(getEmailAddress(),getSiteDomain(),$mailToAddress,$mailSubject,$mailHTMLBody,$mailTextBody);
        return $success;
	}
	
	
