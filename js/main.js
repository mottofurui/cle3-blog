window.addEventListener("load", init);

// global vars
let restaurant;

let modal;
let dropdownButton;
let modalCloseButton;

function init() {
    restaurant = document.getElementById("restaurant");
    restaurant.addEventListener("click", restaurantInfoClickHandler);

    modal = document.getElementById("modal");
    dropdownButton = document.getElementById("modal-open");
    modalCloseButton = document.getElementById("close");

    dropdownButton.addEventListener("click", showModal);
}

//makes the button in a restaurantArticle work
function restaurantInfoClickHandler(e) {
    const clickedRestaurant = e.target;
    if (clickedRestaurant.nodeName === "BUTTON") {
        window.location.href = "restaurant.html"
    }
}

function showModal(e) {
    modal.style.display = "block";
}