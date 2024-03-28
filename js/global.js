window.addEventListener("load", init);

// global vars
let modal;
let dropdownButton;
let modalCloseButton;

function init() {
    modal = document.getElementById("modal");
    dropdownButton = document.getElementById("modal-open");
    modalCloseButton = document.getElementById("close");

    dropdownButton.addEventListener("click", showModal);
    modalCloseButton.addEventListener("click", closeModal);
}

function showModal(e) {
    modal.style.display = "block";
}

function closeModal(e) {
    modal.style.display = "none";
}