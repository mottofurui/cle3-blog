<?php
/** @var mysqli $db */
//voeg database toe
session_start();

if (!isset($_GET['restaurant_id']) || $_GET['restaurant_id'] === '') {
    header('Location: index.php');
    exit;
}

$restaurantId = $_GET['restaurant_id'];

require_once 'includes/reviews-database.php';

$ratingNumbers = [];

// als er op submit is gedrukt
if (isset($_POST['submit'])) {

    $errors = array();

    //als de data valide is
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $title = mysqli_real_escape_string($db, $_POST['title']);
    $review = mysqli_real_escape_string($db, $_POST['review']);
    $rating = isset($db, $_POST['rating']) ? intval($_POST['rating']) : 0;


    //server-side validation
    if ($name === "") {
        $errors['name'] = "Naam mag niet leeg zijn.";
    }
    if ($title === "") {
        $errors['title'] = "Titel mag niet leeg zijn.";
    }
    if ($review === "") {
        $errors['review'] = "Review mag niet leeg zijn.";
    }


    //als er geen fouten zijn
    if (empty($errors)) {

        //insert query opbouwen
        $insertQuery = "INSERT INTO `reviews`(`id`, `restaurant_id`, `name`, `title`, `review`, `rating`) VALUES ('', '$restaurantId', '$name', '$title', '$review', '$rating')";
        // Als de query correct uitgevoerd wordt
        if (mysqli_query($db, $insertQuery)) {
            // Redirect naar reviews.php
            header("location: https://localhost/cle3-blog/restaurantdetails.php?restaurant_id=$restaurantId");
            exit;
        } else {
            // Niet correct uitgevoerd
            // Foutmelding tonen
            echo "Error: " . mysqli_error($db);
        }
    }
}

// Select all the reviews from the database
$query = "SELECT * FROM reviews";
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

// Close the connection
mysqli_close($db)

?>

<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/main-styles.css">
    <link rel="stylesheet" href="css/review.css">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/915daa22f2.js" crossorigin="anonymous"></script>
    <script src="js/global.js"></script>
    <title>Deel Ervaring</title>
</head>
<body>
    <a href="#main" class="skip">Ga naar hoofdcontent</a>
    <nav>
        <img class="logo" src="./img/restoramalogo.png" alt="logo">
        <i id="modal-open" class="fa-solid fa-bars"></i>
    </nav>
    <main>
        <div role="navigation" id="modal">
            <div id="modal-content">
                <span id="close">close</span>
                <a href="index.php">reviews</a>
                <a href="#">reviews</a>
                <a href="#">reviews</a>
                <a href="#">reviews</a>
            </div>
        </div>
        <h1>Laat uw ervaring achter</h1>
        <form action="" method="post">

            <div class="formfield">
                <label for="name">Naam</label>
                <input id="name" name="name" placeholder="Vul hier uw naam in" value="<?= isset($name) ? $name : '' ?>">
                <?= isset($errors['name']) ? $errors['name'] : '' ?>
            </div>
            <div class="formfield">
                <label for="rating">Rating</label>
                <select class="input" id="rating" name="rating">
                    <option disabled="" selected="">Hoeveel sterren?</option>
                    <option value="5">5 ster</option>
                    <option value="4">4 ster</option>
                    <option value="3">3 ster</option>
                    <option value="2">2 ster</option>
                    <option value="1">1 ster</option>
                </select>
            </div>
            <div class="formfield">
                <label for="title">Title</label>
                <input id="title" name="title" placeholder="Geef uw review een titel" value="<?= isset($title) ? $title : '' ?>">
                <?= isset($errors['title']) ? $errors['title'] : '' ?>
            </div>
            <div class="formfield">
                <label for="review">Ervaring</label>
                <textarea id="review" name="review" placeholder="Noteer hier uw ervaring" "></textarea>
                <?= isset($errors['review']) ? $errors['review'] : '' ?>
<!--                value="--><?php //= isset($review) ? $review : '' ?>
            </div>
            <button type="submit" name="submit">Verzend</button>
        </form>
    </main>
<!-- <section>
    <div id="rating-stars-container">
        <p>Gemiddeld cijfer: <?= $roundedGrade ?></p>
        <div class="rating-stars" style="background-color: yellow; height: 5vh; width: <?= ($roundedGrade * 2) * 10 ?>%;"></div>
        <img src="img/sterren.png" class="rating-stars">
    </div>
    <?php foreach ($reviews as $index => $review) { ?>
        <p><?= htmlentities($review['name']) ?></p>
        <?php
        // laat sterren zien met de hoeveelheid rating
        $rating = $review['rating'];
        for ($i = 0; $i < $rating; $i++) {
            echo '★';
        }
        ?>
        <p><?= htmlentities($review['title']) ?></p>
        <p><?= htmlentities($review['review']) ?></p>
    <?php } ?>
</section> -->
    <footer>
        <img class="logo" src="./img/restoramalogo.png" alt="logo">
    </footer>
</body>
</html>
