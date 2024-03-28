window.addEventListener("load", init);

// global vars
let reviewAdd;

function init() {
    reviewAdd = document.getElementById("reviews");
    reviewAdd.addEventListener("click", reviewClickHandler);
}

function reviewClickHandler(e) {
    const clickedReview = e.target;
    if (clickedReview.nodeName === "BUTTON") {
        window.location.href = "reviews.php"
    }
}