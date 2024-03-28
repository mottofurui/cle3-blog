<?php
/** @var mysqli $db */
//voeg database toe
session_start();

require_once 'includes/database.php';

//informatie uit de database ophalen op basis van Id
$query = "SELECT * FROM restaurants";
$result = mysqli_query($db, $query) or die('error: ' . mysqli_error($db));

// Store the $restaurants in an array
$restaurants = [];
while ($row = mysqli_fetch_assoc($result)) {
    $restaurants[] = $row;
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
<!--        <p>Insert menu logo to go to the menu</p>-->
    </nav>
    <header>
    </header>
    <main id="main">
        <div id="modal">
            <div role="navigation" class="modal-content">
                <span id="close">close</span>
                <a href="index.php">reviews</a>
                <a href="#">reviews</a>
                <a href="#">reviews</a>
                <a href="#">reviews</a>
            </div>
        </div>
        <section class="searchbar">
            <form role="search">
                <label for="searchbar">Zoek op tags</label>
                <div>
                    <input id="searchbar" name="searchbar" type="text" placeholder="Bijv. dimbaar licht">
                    <button class="button" type="submit">Zoeken</button>
                </div>
            </form>
        </section>
        <section id="main-container">
            <?php foreach ($restaurants as $index => $restaurant) { ?>
                <section class="border">
                    <div class="restaurant">
                        <section>
                            <h2><?= htmlentities($restaurant['name']) ?></h2>
                            <p><?= htmlentities($restaurant['adress']) ?></p>
                            <p class="stars">
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                            </p>
                            <div>
                                <a class="link" href="restaurantdetails.php?restaurant_id=<?= $restaurant['restaurant_id'] ?>">Meer informatie</a>
                            </div>
                        </section>
                        <section>
                            <img src="">
                        </section>
                    </div>
                </section>
            <?php } ?>
        </section>
    </main>
    <footer>
        <img class="logo" src="./img/restoramalogo.png" alt="logo van de restorama app">
    </footer>
</body>
</html>