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

// Fetching all cities
$dataQuery = "SELECT * FROM cities";
$cityResult = mysqli_query($db, $dataQuery) or die('Error ' . mysqli_error($db) . ' with query ' . $dataQuery);

$cities = [];
while ($cityRow = mysqli_fetch_assoc($cityResult)) {
    $cities[] = $cityRow;
}

//informatie uit de database ophalen op basis van Id
$query = "SELECT * FROM tags";
$result = mysqli_query($db, $query) or die('error: ' . mysqli_error($db));


// Store the $tags in an array
$taglist = [];
while ($row = mysqli_fetch_assoc($result)) {
    $taglist[] = $row;
}

//error handling
if (isset($_POST['selectedTag']) && !empty($_POST['selectedTag'])) {
    $selectedTag = $_POST['selectedTag'];
    //informatie uit de database ophalen op basis van Id
    $query = "SELECT *, t.tag_id as tag, rt.tag_id as restauranttag, rt.restaurant_id 
FROM restaurants as r
JOIN restaurant_tags as rt ON r.restaurant_id = rt.restaurant_id
JOIN tags as t ON t.tag_id = rt.tag_id
WHERE t.tag_id = '$selectedTag'";
    $result = mysqli_query($db, $query) or die('error: ' . mysqli_error($db));
}


// Store the $tags in an array
$tags = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tags[] = $row;
}

// Select all the reviews from the database
for ($i = 1; $i < sizeof($restaurants) + 1; $i++) {
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

    $ratingNumbers = [];
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
    <script src="js/index.js"></script>
    <script src="js/global.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>Restorama</title>
</head>
<body id="body">
<a href="#main" class="skip">Ga naar Hoofdcontent</a>
<nav>
    <p role="navigation" id="modal-open">Menu</p>
    <img class="logo" src="./img/restoramalogo.png" alt="Restorama logo">
</nav>
<header>
</header>
<main id="main">
    <!--        <section class="searchbar">-->
    <!--            <form role="search">-->
    <!--                <label for="searchbar">Zoek op tags</label>-->
    <!--                <div>-->
    <!--                    <input id="searchbar" name="searchbar" type="text" placeholder="Bijv. dimbaar licht">-->
    <!--                    <button class="button" type="submit">Zoeken</button>-->
    <!--                </div>-->
    <!--            </form>-->
    <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
        <label for="selectedTag">Zoek op tags</label>
        <div>
            <select name="selectedTag" id="selectedTag">
                <option value="" selected>Alle tags</option>
                <?php foreach ($taglist as $tag) { ?>
                    <option value="<?= $tag['tag_id'] ?>"><?= $tag['tag_name'] ?></option>
                <?php } ?>
            </select>
            <input class="button" type="submit" name="Submit" value="Zoeken"/>
        </div>
    </form>
    <!--        </section>-->
    <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
        <label for="selectedCity">Zoek op stad</label>
        <div>
            <select name="selectedCity" id="selectedCity">
                <option value="" selected>Alle steden</option>
                <?php foreach ($cities as $city) { ?>
                    <option value="<?= $city['city'] ?>"><?= $city['city'] ?></option>
                <?php } ?>
            </select>
            <input class="button" type="submit" name="Submit" value="Zoeken"/>
        </div>
    </form>
    <section id="main-container">
            <?php
            //als is gevuld word $selectedTag aangemaakt met de tag name als value
            if (isset($_POST['selectedTag']) && !empty($_POST['selectedTag'])) {
                $selectedTag = $_POST['selectedTag']; ?>
                <h1>Restaurants met <?php foreach ($taglist as $tag) {
                    if($selectedTag == $tag['tag_id']) {
                            echo htmlentities($tag['tag_name']);
                        }
                    }?></h1>
               <?php foreach ($tags as $tag) { ?>
                   <?php if ($selectedTag == $tag['tag_id']) {
                        ?>
                        <section class="border">
                            <div class="restaurant">
                                <h2><?= htmlentities($tag['restaurant_name']) ?></h2>
                                <div id="rating-stars-container">
                                    <?php if ($roundedById[$tag['restaurant_id'] - 1] === 0) { ?>
                                        <div class="rating-stars"
                                             style="background-color: #fff4e3; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 1.96) * 10 ?>%;"></div>
                                    <?php } else { ?>
                                        <div class="rating-stars"
                                             style="background-color: black; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 1.96) * 10 ?>%;"></div>
                                    <?php } ?>
                                    <div class="rating-stars-div"></div>
                                    <img src="img/sterren.png" class="rating-stars-image" alt="sterren-rating">
                                </div>
                                <p><?= htmlentities($tag['adress']) ?>, <?= htmlentities($tag['city']) ?></p>
                                <div class="link">
                                    <a href="restaurantdetails.php?restaurant_id=<?= $tag['restaurant_id'] ?>">Meer
                                        informatie</a>
                                </div>
                                <div class="buttonContainer">
                                <button class="fav-button" data-id=<?= $roundedById[$tag['restaurant_id']] ?>> Dit restaurant is geen favoriet</button>
                            </div>
                        </div>
                    </section>

                        <?php
                    }
                }
            } // Handle selectedCity case
            elseif (isset($_POST['selectedCity']) && !empty($_POST['selectedCity'])) { ?>
                <h1>Restaurants in <?= htmlentities($_POST['selectedCity']) ?></h1>
               <?php foreach ($restaurants as $restaurant) {
                    $selectedCity = $_POST['selectedCity'];
                    if ($selectedCity == $restaurant['city']) {
                        ?>
                        <section class="border">
                            <div class="restaurant">
                                <h2><?= htmlentities($restaurant['restaurant_name']) ?></h2>
                                <div id="rating-stars-container">
                                    <?php if ($roundedById[$restaurant['restaurant_id'] - 1] === 0) { ?>
                                        <div class="rating-stars"
                                             style="background-color: #fff4e3; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 1.96) * 10 ?>%;"></div>
                                    <?php } else { ?>
                                        <div class="rating-stars"
                                             style="background-color: black; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 1.96) * 10 ?>%;"></div>
                                    <?php } ?>
                                    <div class="rating-stars-div"></div>
                                    <img src="img/sterren.png" class="rating-stars-image" alt="sterren-rating">
                                </div>
                                <p><?= htmlentities($restaurant['adress']) ?>, <?= htmlentities($restaurant['city']) ?></p>
                                <div class="link">
                                    <a href="restaurantdetails.php?restaurant_id=<?= $restaurant['restaurant_id'] ?>">Meer
                                        informatie</a>
                                </div>
                                <div class="buttonContainer">
                                <button class="fav-button" data-id=<?= $restaurant['restaurant_id'] ?>>Dit restaurant is
                                    geen favoriet
                                </button>
                            </div>
                        </div>
                    </section>

                        <?php
                    }
                }
            } // Handle the case when neither selectedTag nor selectedCity is set
            else {
                foreach ($restaurants as $restaurant) {
                    ?>
                    <section class="border">
                        <div class="restaurant">
                            <h2><?= htmlentities($restaurant['restaurant_name']) ?></h2>
                            <p><?= htmlentities($restaurant['adress']) ?>, <?= htmlentities($restaurant['city']) ?></p>
                            <div id="rating-stars-container">
                                <?php if ($roundedById[$restaurant['restaurant_id'] - 1] === 0) { ?>
                                    <div class="rating-stars"
                                         style="background-color: #fff4e3; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 2) * 10 ?>%;"></div>
                                <?php } else if ($roundedById[$restaurant['restaurant_id'] - 1] < 2.5){ ?>
                                    <div class="rating-stars"
                                         style="background-color: black; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 2) * 10.3 ?>%;"></div>
                                <?php } else {?>
                                <div class="rating-stars"
                                     style="background-color: black; height: 10vh; width: <?= ($roundedById[$restaurant['restaurant_id'] - 1] * 2) * 10 ?>%;"></div>
                                <?php } ?>
                                <div class="rating-stars-div"></div>
                                <img src="img/sterren.png" class="rating-stars-image" alt="sterren-rating">
                            </div>
                            <div class="link">
                                <a href="restaurantdetails.php?restaurant_id=<?= $restaurant['restaurant_id'] ?>">Meer
                                    informatie</a>
                            </div>
                            <button class="fav-button" data-id=<?= $restaurant['restaurant_id'] ?>>Dit restaurant is
                                geen favoriet
                            </button>
                        </div>
                    </section>
                    <?php
                }
            }
        ?>
    </section>
</main>
<footer>
    <img class="logo" src="./img/restoramalogo.png" alt="Restorama logo">
    <a href="api.php" class="api">naar de google api!!</a>
</footer>
<dialog id="modal">
    <div id="modal-content">
        <div class="modallogo">
            <h2>Menu</h2>
            <img src="./img/restoramalogo.png" alt="Restorama logo" class="modlogo">
        </div>
        <a href="index.php">Homepagina</a>
        <a href="eduplaza.html">EduPlaza</a>
        <a href="favourites.html">Favorieten</a>
        <button id="close">Terug</button>
    </div>
</dialog>
</body>
</html>