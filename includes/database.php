<?php
$host       = "localhost";
$user       = "root";
$password   = "";
$database   = "cle3";

$mapsApiKey = "AIzaSyAZnWejld5cB6YkHZuIMHDRanLBDmCD8sU";



$db = mysqli_connect($host, $user, $password, $database)
or die("Error: " . mysqli_connect_error());;
