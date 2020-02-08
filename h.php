<?php
    require_once("_init.php");

	validateSessionOrCookiesReturnLoggedIn();
	$logged_in=false;
	if(isCookieSet())$logged_in=true;

    if($logged_in==false) {
?>

<main role="main" class="flex-shrink-0 my-auto">
    <div class="">

<header>
    <nav class="site-header navbar navbar-expand-sm fixed-top py-1">
        <a class="navbar-brand mr-auto" href="<?php echo getSiteURL();?>"><img src="/images/fwber_logo_icon.png" alt="fwber" id="fwber_logo_img" height="50" border="0" style="border:0px;position:absolute;left:10px;top:4px; z-index:1;"></a>
        <div class="" id="">
            <ul class="navbar-nav" style="-ms-flex-direction:row;flex-direction:row;">
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-secondary my-0 px-3 mx-1" href="/signin">Sign In</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-secondary my-0 px-3 mx-1" href="/join">Join Now</a>
                </li>
            </ul>
        </div>

    </nav>
</header>
<?php } else {?>
        <header>
            <nav class="site-header navbar navbar-expand-sm fixed-top py-1">
                <a class="navbar-brand mr-auto" href="<?php echo getSiteURL();?>"><img src="/images/fwber_logo_icon.png" alt="fwber" id="fwber_logo_img" height="50" border="0" style="border:0px;position:absolute;left:30px;top:0px; z-index:1;"></a>
                <div class="" id="">
                    <ul class="navbar-nav" style="-ms-flex-direction:row;flex-direction:row;">
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-secondary my-0 px-3 mx-1" href="/matches">Matches</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-secondary my-0 px-3 mx-1" href="/profile">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-secondary my-0 px-3 mx-1" href="/signout">Sign Out</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
<?php } ?>


