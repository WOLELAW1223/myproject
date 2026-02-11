const email = document.getElementById("forgotEmail");
const error = document.getElementById("forgotError");

email.addEventListener("input", function () {
    const pattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

    if (email.value === "") {
        error.textContent = "";
        return;
    }

    if (!pattern.test(email.value)) {
        error.textContent = "Please enter a valid Gmail address (example@gmail.com)";
    } else {
        error.textContent = "";
    }
});

document.getElementById("forgotForm").addEventListener("submit", function(event){
    if (!email.checkValidity()) {
        event.preventDefault();
        error.textContent = " Invalid Gmail address!";
    } else {
        alert("A reset link has been sent to your Gmail!");
    }
});
