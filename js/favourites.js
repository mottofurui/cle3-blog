window.addEventListener('load', init);

const apiUrl = "./favourites.php";
let favoriteList = {};
let favoriteButton;
let favorite;

function init()
{
    favoriteButton = document.getElementById("main-container");
    favoriteButton.addEventListener("click", favoriteClickHandler);

    getData(apiUrl, succesHandler);
}

function getData(url, succesFunction){
    fetch(url)
        .then((response) => {
            if(!response.ok){
                throw new Error(response.statusText)
            }
            return response.json()
        })
        .then(succesFunction)
        .catch(ajaxErrorHandler);
}

function succesHandler(data) {
    for(const result of data) {
        const favoritesString = localStorage.getItem('favorites');
        if (favoritesString !== null) {
            favoriteList = JSON.parse(favoritesString);
        }

        // Check if the exercise is in favorites and update button text accordingly
        if (result.restaurant_id in favoriteList) {
            const mainSection = document.getElementById("main-container")

            const borderSection = document.createElement("section")
            borderSection.classList.add("border")
            mainSection.append(borderSection)

            const borderDiv = document.createElement("div")
            borderDiv.classList.add("restaurant")
            borderSection.append(borderDiv)

            const h2 = document.createElement("h2")
            h2.innerText = result.restaurant_name
            borderDiv.append(h2)

            const p = document.createElement("p")
            p.innerText = `${result.adress}, ${result.city}`
            borderDiv.append(p)

            const linkDiv = document.createElement("div")
            linkDiv.classList.add("link")
            borderDiv.append(linkDiv)

            const a = document.createElement("a")
            a.innerText = "Meer informatie";
            a.href = `restaurantdetails.php?restaurant_id=${result.restaurant_id}`;
            linkDiv.append(a);

            const button = document.createElement("button");
            button.innerText = "Dit restaurant is uw favoriet"
            button.classList.add("fav-button")
            button.classList.add("favorite")
            button.dataset.id = result.restaurant_id
            borderDiv.append(button)

        }
    }
}

function favoriteClickHandler(e) {
    if (e.target.nodeName === 'BUTTON') {
        console.log(e.target)
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
            e.target.innerText = "Dit restaurant is geen favoriet";

        } else {
            // Add the exercise to the favorites list
            favoriteList[favorite] = favorite;
            console.log(`restaurant added to favorites.`);
            e.target.classList.add('favorite');
            e.target.innerText = "Dit restaurant is uw favoriet"
        }

        // Save favorite exercises to localStorage
        saveFavorites();
    }
}

function saveFavorites() {
    localStorage.setItem('favorites', JSON.stringify(favoriteList));
}

function ajaxErrorHandler(error){
    console.log(error);
}