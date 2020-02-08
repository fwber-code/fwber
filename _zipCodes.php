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

/*

CREATE TABLE  `zipgeoworld` (
  `isoCountryCode` varchar(2) NOT NULL,
  `postalCode` varchar(20) NOT NULL,
  `city` varchar(180) NOT NULL,
  `state` varchar(100) NOT NULL,
  `stateCode` varchar(20) NOT NULL,
  `county1` varchar(100) NOT NULL,
  `county2` varchar(20) NOT NULL,
  `community1` varchar(100) NOT NULL,
  `community2` varchar(20) NOT NULL,
  `lat` double NOT NULL,
  `lon` double NOT NULL,
  `accuracy` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

set_time_limit(0);
$mysqlhost = 'localhost';
$mysqluser = 'root';
$mysqlpass = '';
$mysqldb = 'fwber';
$mysqltable = 'zipgeoworld';
mysql_connect($mysqlhost, $mysqluser, $mysqlpass) or exit(mysqli_connect_error());
mysql_select_db($mysqldb) or exit(mysqli_connect_error());
$fields = array('isoCountryCode', 'postalCode', 'city', 'state', 'stateCode', 'county1', 'county2', 'community1', 'community2', 'lat', 'lon', 'accuracy');
$contents = file('world.csv');
$buffer = 100;
$basestatement = "insert into {$mysqltable} (`" . implode("`, `", $fields) . "`) VALUES ";
$counter = 0;
$inserts = array();
foreach ($contents as $line) {
	$linefields = explode(',', $line);
	$linefields = array_map('trim', $linefields);
	$linefields = array_map('mysql_real_escape_string', $linefields);
	$inserts[] = "('" . implode("', '", $linefields) . "')";
	$counter++;
	if ($counter == $buffer) {
		$query = $basestatement . implode(',', $inserts);
		mysql_query($query) or exit(mysqli_connect_error());
		$counter = 0;
		$inserts = array();
	}
}
if (count($inserts)) {
	$query = $basestatement . implode(',', $inserts);
	mysql_query($query);
}
print 'done';

*/

function get_zips_within($db,$zip, $miles)
{
	$milesperdegree = 69;
	$degreesdiff = $miles / $milesperdegree;
	$query = "select lat, lon from zipgeo where zip5={$zip}";
	$result = mysqli_query($db,$query);
	$latlong = mysqli_fetch_assoc($result);
	$lat1 = $latlong['lat'] - $degreesdiff;
	$lat2 = $latlong['lat'] + $degreesdiff;
	$lon1 = $latlong['lon'] - $degreesdiff;
	$lon2 = $latlong['lon'] + $degreesdiff;
	$query = "select * from zipgeo where lat between {$lat1} and {$lat2} and lon between {$lon1} and {$lon2}";
	$result = mysqli_query($db,$query);
	$zips = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$zips[] = $row;
	}
	return $zips;
}

function get_lat_long_for_zip($db,$zip)
{
	$query = "select lat, lon from zipgeo where zip5={$zip}";
	$result = mysqli_query($db,$query);
	$latlong = mysqli_fetch_assoc($result);

	return $latlong;
}

function get_lat_long_for_zip_world($db,$zip)
{
	$query = "select lat, lon from geolookup.zipgeoworld where postalCode={$zip}";
	$result = mysqli_query($db,$query);
	$latlong = mysqli_fetch_assoc($result);

	return $latlong;
}

/*

CREATE TABLE  `zipgeo` (
  `zip5` char(5) NOT NULL,
  `city` varchar(250) NOT NULL,
  `state` varchar(250) NOT NULL,
  `lat` double NOT NULL,
  `lon` double NOT NULL,
  `county` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

set_time_limit(0);
$mysqlhost = 'localhost';
$mysqluser = 'root';
$mysqlpass = '';
$mysqldb = 'fwber';
$mysqltable = 'zipgeo';
mysql_connect($mysqlhost, $mysqluser, $mysqlpass) or exit(mysqli_connect_error());
mysql_select_db($mysqldb) or exit(mysqli_connect_error());
$fields = array('zip5', 'city', 'state', 'lat', 'lon', 'county');
$contents = file('zip5.csv');
$buffer = 100;
$basestatement = "insert into {$mysqltable} (`" . implode("`, `", $fields) . "`) VALUES ";
$counter = 0;
$inserts = array();
foreach ($contents as $line) {
	$linefields = explode(',', $line);
	$linefields = array_map('trim', $linefields);
	$linefields = array_map('mysql_real_escape_string', $linefields);
	$inserts[] = "('" . implode("', '", $linefields) . "')";
	$counter++;
	if ($counter == $buffer) {
		$query = $basestatement . implode(',', $inserts);
		mysql_query($query) or exit(mysqli_connect_error());
		$counter = 0;
		$inserts = array();
	}
}
if (count($inserts)) {
	$query = $basestatement . implode(',', $inserts);
	mysql_query($query);
}
print 'done';

*/

/*
CREATE TABLE  `countrycodes` (
  `isoCountryCode` varchar(2) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

/*
set_time_limit(0);
$mysqlhost = 'localhost';
$mysqluser = 'root';
$mysqlpass = '';
$mysqldb = 'fwber';
$mysqltable = 'countrycodes';
mysql_connect($mysqlhost, $mysqluser, $mysqlpass) or exit(mysqli_connect_error());
mysql_select_db($mysqldb) or exit(mysqli_connect_error());
$fields = array('isoCountryCode', 'country');
$contents = file('countryCodes.csv');
$buffer = 100;
$basestatement = "insert into {$mysqltable} (`" . implode("`, `", $fields) . "`) VALUES ";
$counter = 0;
$inserts = array();
foreach ($contents as $line) {
	$linefields = explode(',', $line);
	$linefields = array_map('trim', $linefields);
	$linefields = array_map('mysql_real_escape_string', $linefields);
	$inserts[] = "('" . implode("', '", $linefields) . "')";
	$counter++;
	if ($counter == $buffer) {
		$query = $basestatement . implode(',', $inserts);
		mysql_query($query) or exit(mysqli_connect_error());
		$counter = 0;
		$inserts = array();
	}
}
if (count($inserts)) {
	$query = $basestatement . implode(',', $inserts);
	mysql_query($query);
}
print 'done';
*/

