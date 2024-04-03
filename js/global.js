window.addEventListener("load", init);

// global vars
let modal;
let dropdownButton;
let modalCloseButton;
let dialogContent;

function init() {
    modal = document.getElementById("modal");
    dropdownButton = document.getElementById("modal-open");
    modalCloseButton = document.getElementById("close");
    dialogContent = document.getElementById('modal-content')

    dropdownButton.addEventListener("click", openDialog);
    modalCloseButton.addEventListener("click", closeModal);
}

function openDialog(e) {
    modal.showModal();
}

function closeModal(e) {
    modal.close();
}