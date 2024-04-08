<?php
/** @var mysqli $db */

require_once 'includes/database.php';

//informatie uit de database ophalen op basis van Id
$query = "SELECT * FROM restaurants";
$result = mysqli_query($db, $query) or die('error: ' . mysqli_error($db));


// Store the $restaurants in an array
$restaurants = [];
while ($row = mysqli_fetch_assoc($result)) {
    $restaurants[] = $row;
}

foreach ($restaurants as $restaurant) {
    $restoData[] = $restaurants[$restaurant['restaurant_id'] - 1];
}

header("Content-Type: application/json");
echo json_encode($restoData);
exit;

