window.addEventListener("load", init);

// global vars
let restaurant;

function init() {
    restaurant = document.getElementById("restaurant");
    restaurant.addEventListener("click", restaurantClickHandler);
}

function restaurantClickHandler(e) {
    window.location.href = "restaurant.html";
}