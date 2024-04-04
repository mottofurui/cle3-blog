window.addEventListener("load", init);

// global vars
let favoriteList = {};
let favoriteButton;
let favorite;

function init() {
    favoriteButton = document.getElementById("main-container");
    favoriteButton.addEventListener("click", favoriteClickHandler);

    // Load favorite exercises from localStorage
    loadFavorites();
}

//makes the button in a restaurantIndex work
function favoriteClickHandler(e) {
    if (e.target.nodeName === 'BUTTON') {
        // Navigate to the parent exercise card element
        favorite = e.target.dataset.id;//.closest('.exercise-card');
        if (!favorite) return; // If card not found, do nothing
        console.log(`${favorite}`);

        // Check if the exercise is already in the favorites list
        if (favoriteList[favorite]) {
            // Remove the exercise from the favorites list
            delete favoriteList[favorite];
            console.log(`restaurant removed from favorites.`);
            e.target.classList.remove('favorite');


        } else {
            // Add the exercise to the favorites list
            favoriteList[favorite] = favorite;
            console.log(`restaurant added to favorites.`);
            e.target.classList.add('favorite');
        }

        // Save favorite exercises to localStorage
        saveFavorites();
    }
}

function saveFavorites() {
    localStorage.setItem('favorites', JSON.stringify(favoriteList));
}

function loadFavorites() {
    const favoritesString = localStorage.getItem('favorites');
    if (favoritesString !== null) {
        favoriteList = JSON.parse(favoritesString);
    }

    // Check if the exercise is in favorites and update button text accordingly
    for (const favoriteId in favoriteList) {
        if (favoriteList.hasOwnProperty(favoriteId)) {
            const favoriteButton = document.querySelector(`[data-id="${favoriteId}"]`);
            if (favoriteButton) {
                favoriteButton.classList.add('favorite');
            }
        }
    }
}
