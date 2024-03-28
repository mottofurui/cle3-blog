<?php
/** @var mysqli $db */
session_start();
//verbinding van database
require_once 'includes/database.php';

//ID uit de url ophalen
//zo niet stuur gebruiker terug naar index
if (!isset($_GET['restaurant_id']) || $_GET['restaurant_id'] === '') {
    header('Location: index.php');
    exit;
}
//controleer of ID echt is mee gegeven
$restaurantId = $_GET['restaurant_id'];

//informatie uit de database ophalen op basis van Id
$query = "SELECT * FROM restaurants WHERE restaurant_id = '$restaurantId'";
$result = mysqli_query($db, $query) or die('error: ' . mysqli_error($db));

//gebruiker terug sturen als er geen resultaten uit query komen
if (mysqli_num_rows($result) != 1){
    header('Location: index.php');
    exit;
}

//informatie uit de database omzetten naar php array
$restaurant = mysqli_fetch_assoc($result);

//connectie met database afsluiten
mysqli_close($db);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="column is-narrow">
    <h2 class="title mt-4 has-text-warning"><?= htmlentities($restaurant['name'])?> details</h2>
    <section class="content">
        <ul>
            <li class="has-text-warning">adress: <?= htmlentities($restaurant['info'])?></li>
<!--            <li class="has-text-warning">Threat: --><?php //= htmlentities($restaurant[''])?><!--</li>-->
        </ul>
    </section>
    <div>
        <a class="button has-text-warning has-background-dark" href="index.php">Go back to the list</a>
    </div>
</div>
</body>
</html>
