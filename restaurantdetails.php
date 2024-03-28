<?php
/** @var mysqli $db */
session_start();
//verbinding van database
require_once 'includes/database.php';
require_once 'includes/reviews-database.php';

$ratingNumbers = [];

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

//START REVIEW PHP
//informatie uit de database omzetten naar php array
$restaurant = mysqli_fetch_assoc($result);

// Select all the reviews from the database
$query = "SELECT * FROM reviews WHERE restaurant_id = $restaurantId";
$result = mysqli_query($db, $query) or die('Error ' . mysqli_error($db) . ' with query ' . $query);

// Store the reviews in an array
$reviews = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}

// push de rating in array ratingnumbers
foreach ($reviews as $reviewRating) {
    $ratingNumbers[] = $reviewRating['rating'];
}

$maxCount = 0;
$count = 0;
foreach ($ratingNumbers as $ratingNumber) {
    $maxCount += $ratingNumber;
    $count++;
}

$roundedGrade = round($maxCount / $count, 1);

//connectie met database afsluiten
mysqli_close($db);
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/main-styles.css">
    <link rel="stylesheet" href="css/restoview.css">
    <script src="js/global.js"></script>
    <script src="js/review.js"></script>
    <script src="https://kit.fontawesome.com/915daa22f2.js" crossorigin="anonymous"></script>
    <title>Restaurant naam</title>
</head>
<body>
    <a href="#main" class="skip">Ga naar hoofdcontent</a>
    <nav>
        <img class="logo" src="./img/restoramalogo.png" alt="logo">
        <i id="modal-open" class="fa-solid fa-bars"></i>
    </nav>
    <header>
        <div role="navigation" id="modal">
            <div class="modal-content">
                <span id="close">close</span>
                <a href="index.php">reviews</a>
                <a href="#">reviews</a>
                <a href="#">reviews</a>
                <a href="#">reviews</a>
            </div>
        </div>
        <h1><?= htmlentities($restaurant['name'])?></h1>
        <h2><?= htmlentities($restaurant['adress'])?></h2>
        <div class="panorama">
            <img src="./img/restopanorama.jpg" alt="restaurant-banner">
        </div>
    </header>
    <main id="main">
        <section id="information">
            <h3>Omschrijving</h3>
            <p><?= htmlentities($restaurant['info'])?></p>
            <h3>Tags</h3>
            <ul>
                <li>Craft Beer</li>
                <li>Burgers</li>
                <li>Accessible</li>
            </ul>
        </section>
        <section >
                <p>4,5</p>
                <div class="stars">
                    <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>
                </div>

            <button class="button" type="submit">Laat uw ervaring achter</button>
            <div class="review">
                <h4>Naam</h4>
                <div class="stars">
                    <i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>
                </div>
                <p>
                    Dit restaurant heeft erg behulpzaam personeel! Ik werd aan de deur begroet en begeleid naar de tafel.
                    Voor mijn geleide hond hadden ze een bakje water en de tafel met dimbare lichten kon ik van te voren
                    speciaal reserveren!
                </p>
            </div>
        </section>
        <section id="reviews">
            <h3>Ervaringen</h3>
            <p><?= $roundedGrade ?> van de 5</p>
            <div id="rating-stars-container">
                <div class="rating-stars" style="background-color: yellow; height: 10vh; width: <?= ($roundedGrade * 2) * 10 ?>%;"></div>
                <img src="img/sterren.png" class="rating-stars">
            </div>
            <?php foreach ($reviews as $index => $review) { ?>
                <h4><?= htmlentities($review['name']) ?></h4>
                <div class="stars">
                    <?php
                    // laat sterren zien met de hoeveelheid rating
                    $rating = $review['rating'];
                    for ($i = 0; $i < $rating; $i++) {
                        echo '★';
                    }
                    ?>
                </div>
                <h4><?= htmlentities($review['title']) ?></h4>
                <p><?= htmlentities($review['review']) ?></p>
            <?php } ?>
        </section>
    </main>
    <footer>
        <img class="logo" src="./img/restoramalogo.png" alt="logo">
    </footer>
</body>
</html>
