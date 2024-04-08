<?php
$host       = "stud.hosted.hr.nl";
$user       = "1066431";
$password   = "queifede";
$database   = "1066431";

//$host       = "localhost";
//$user       = "root";
//$password   = "";
//$database   = "cle_33";

$mapsApiKey = "AIzaSyAZnWejld5cB6YkHZuIMHDRanLBDmCD8sU";

$db = mysqli_connect($host, $user, $password, $database)
or die("Error: " . mysqli_connect_error());;
