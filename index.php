<?php
/** @var mysqli $db */
//voeg database toe
session_start();

require_once 'includes/database.php';

$ratingNumbers = [];
$roundedById = [];

//informatie uit de database ophalen op basis van Id
$query = "SELECT * FROM restaurants";
$result = mysqli_query($db, $query) or die('error: ' . mysqli_error($db));

// Store the $restaurants in an array
$restaurants = [];
while ($row = mysqli_fetch_assoc($result)) {
    $restaurants[] = $row;
}

//informatie uit de database omzetten naar php array
$restaurant = mysqli_fetch_assoc($result);

// Select all the reviews from the database
for ($i = 1; $i < sizeof($restaurants) +1; $i++) {
    $query = "SELECT * FROM reviews WHERE restaurant_id = $i";
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

    $roundedGrade = 0;
    if (!$reviews == []) {
        $roundedGrade = round($maxCount / $count, 1);
        $roundedById[] = $roundedGrade;
    } else {
        $roundedById[] = 0;
    }
}

//connectie met database afsluiten
mysqli_close($db);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/main-styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/915daa22f2.js" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
    <script src="js/global.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>Homepage</title>
</head>
<body>
<a href="#main" class="skip">Ga naar Hoofdcontent</a>
<nav>
    <img class="logo" src="./img/restoramalogo.png" alt="logo van de restorama app">
    <i id="modal-open" class="fa-solid fa-bars"></i>
</nav>
<header>
    <div id="modal">
        <div role="navigation" class="modal-content">
            <button role="close" id="close">close</button>
            <a href="index.php">reviews</a>
            <a href="#">reviews</a>
            <a href="#">reviews</a>
            <a href="#">reviews</a>
        </div>
    </div>
</header>
<main id="main">
    <section class="searchbar">
        <form role="search">
            <label for="searchbar">Zoek op tags</label>
            <div>
                <input id="searchbar" name="searchbar" type="text" placeholder="Bijv. dimbaar licht">
                <button class="button" type="submit">Zoeken</button>
            </div>
        </form>
    </section>
    <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
        <label for="selectedCity">Zoek op stad</label>
        <div>
            <select name="selectedCity" id="selectedCity">
                <option value="" selected>Alle steden</option>
                <?php foreach ($restaurants as $city) { ?>
                    <option value="<?= $city['city'] ?>"><?= $city['city'] ?></option>
                <?php } ?>
            </select>
            <input class="button" type="submit" name="Submit" value="Zoeken"/>
        </div>
    </form>
    <section id="main-container">
        <?php foreach ($restaurants as $restaurant) { ?>
            <?php
            if (isset($_POST['selectedCity']) && !empty($_POST['selectedCity'])) {
                $selectedCity = $_POST['selectedCity'];
                if ($selectedCity == $restaurant['city']) {
                    ?>
                    <h1>Restaurants in <?= htmlentities($_POST['selectedCity']) ?></h1>
                    <section class="border">
                        <div class="restaurant">
                            <h2><?= htmlentities($restaurant['name']) ?></h2>
                            <div id="rating-stars-container">
                                <?php if ($roundedById[$restaurant['restaurant_id'] -1] === 0) { ?>
                                    <div class="rating-stars" style="background-color: #fff4e3; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 2) * 10 ?>%;"></div>
                                <?php } else {?>
                                    <div class="rating-stars" style="background-color: black; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 2) * 10 ?>%;"></div>
                                <?php } ?>
                                <div class="rating-stars-div"></div>
                                <img src="img/sterren.png" class="rating-stars-image" alt="sterren-rating">
                            </div>
                            <p><?= htmlentities($restaurant['adress']) ?></p>
                            <div class="link">
                                <a href="restaurantdetails.php?restaurant_id=<?= $restaurant['restaurant_id'] ?>">Meer informatie</a>
                            </div>
                        </div>
                    </section>
                    <?php
                }
            } else {
                ?>
                <section class="border">
                    <div class="restaurant">
                        <h2><?= htmlentities($restaurant['name']) ?></h2>
                        <p><?= htmlentities($restaurant['adress']) ?>, <?= htmlentities($restaurant['city']) ?></p>
                        <div id="rating-stars-container">
                            <?php if ($roundedById[$restaurant['restaurant_id'] -1] === 0) { ?>
                                <div class="rating-stars" style="background-color: #fff4e3; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 1.96) * 10 ?>%;"></div>
                            <?php } else {?>
                                <div class="rating-stars" style="background-color: black; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 1.96) * 10 ?>%;"></div>
                            <?php } ?>
                            <div class="rating-stars-div"></div>
                            <img src="img/sterren.png" class="rating-stars-image" alt="sterren-rating">
                        </div>
                        <div class="link">
                            <a href="restaurantdetails.php?restaurant_id=<?= $restaurant['restaurant_id'] ?>">Meer informatie</a>
                        </div>
                    </div>
                </section>
                <?php
            } ?>

        <?php } ?>

    </section>
</main>
<footer>
    <img class="logo" src="./img/restoramalogo.png" alt="logo van de restorama app">
</footer>
</body>
</html>