<?php

// Include DatabaseHelper.php file
require_once('DatabaseHelper.php');

// Instantiate DatabaseHelper class
$database = new DatabaseHelper();

//Fetch data from database
$totalCustomer = $database->tableRows('customer');
echo $totalCustomer;
?>

