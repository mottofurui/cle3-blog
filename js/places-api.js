//global vars
let autocomplete;
// let geocoder;


if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition((position) => {
        geolocationHandler(position.coords.latitude, position.coords.longitude)
    });
} else {

}

//functions
function initMap() {
    initAutoComplete();
}

function geolocationHandler(latitude, longitude) {
    console.log(latitude, longitude);
}

// function geocodeHandler(locationName) {
//     geocoder = new google.maps.Geocoder(
//         let locationCoordinates = Geocoder.geocode(locationName),  {
//         address: [JSON.stringify(locationName)]
//     });
//     console.log(locationCoordinates)
// }

function initAutoComplete() {
    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById("autocomplete"), {
            componentRestrictions: {'country': ["NL"]},
            fields: ["geometry", "name"]
        }
    );
    autocomplete.addListener("place_changed", autocompleteHandler)
}

function autocompleteHandler() {
    const place = autocomplete.getPlace();
    if (!place.geometry) {
        document.getElementById("autocomplete").placeholder = "zoek op Restaurant"
    } else {
        let locationName = place.name;
        geocodeHandler(locationName)
    }
}