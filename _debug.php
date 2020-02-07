<?php

//global $debug;
$debug = true;

if($debug)
{
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}
else
{
    error_reporting(0);
    ini_set("display_errors", 0);
}
