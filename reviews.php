<?php
/** @var mysqli $db */
//voeg database toe
session_start();

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
        $insertQuery = "INSERT INTO `reviews`(`id`, `name`, `title`, `review`, `rating`) VALUES ('', '$name', '$title', '$review', '$rating')";
        // Als de query correct uitgevoerd wordt
        if (mysqli_query($db, $insertQuery)) {
            // Redirect naar reviews.php
            header('location: reviews.php');
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

<html lang="nl" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<form action="" method="post">

    <label for="name">Naam</label>
    <input id="name" name="name" value="<?= isset($name) ? $name : '' ?>">
    <?= isset($errors['name']) ? $errors['name'] : '' ?>

    <label for="title">Title</label>
    <input id="title" name="title" value="<?= isset($title) ? $title : '' ?>">
    <?= isset($errors['title']) ? $errors['title'] : '' ?>

    <label for="review">Review</label>
    <input id="review" name="review" value="<?= isset($review) ? $review : '' ?>">
    <?= isset($errors['review']) ? $errors['review'] : '' ?>

    <label for="rating">Rating</label>
    <select class="input" id="rating" name="rating">
        <option value="5">5 ster</option>
        <option value="4">4 ster</option>
        <option value="3">3 ster</option>
        <option value="2">2 ster</option>
        <option value="1">1 ster</option>
    </select>


    <button type="submit" name="submit">Save</button>
</form>
<section>
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
</section>
</body>
</html>
