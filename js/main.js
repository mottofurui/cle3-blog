window.addEventListener("load", init);

// global vars
let restaurant;

function init() {
    restaurant = document.getElementById("restaurant");
    restaurant.addEventListener("click", restaurantInfoClickHandler);
}

//makes the button in a restaurantArticle work
function restaurantInfoClickHandler(e) {
    const clickedRestaurant = e.target;
    if (clickedRestaurant.nodeName === "BUTTON") {
        window.location.href = "restaurant.html"
    }
}