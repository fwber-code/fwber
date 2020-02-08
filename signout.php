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
	
	require_once("_names.php");
	
	//delete cookies
	setcookie("email","",time()-1000,'/',".".getSiteDomain());
	setcookie("token","",time()-1000,'/',".".getSiteDomain());
	
	session_destroy();

	header('Location: '.getSiteURL());
    exit();
?>