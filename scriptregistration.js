document.addEventListener("DOMContentLoaded", function() {
    const password = document.getElementById("password");
    const toggle = document.getElementById("togglePassword");
    const form = document.getElementById("registerForm");
    const error = document.getElementById("error");

    // Show/hide password
    toggle.addEventListener("click", function () {
        if(password.type === "password"){
            password.type = "text";
            toggle.textContent = "🙈";
        } else {
            password.type = "password";
            toggle.textContent = "👁";
        }
    });

    // Simple form validation
    form.addEventListener("submit", function(e){
        let firstName = document.getElementById("firstName").value.trim();
        let lastName = document.getElementById("lastName").value.trim();
        let pass = password.value.trim();

        if(firstName.length < 2 || lastName.length < 2){
            e.preventDefault();
            error.style.display = "block";
            error.textContent = "First and Last name must be at least 2 characters.";
        }
        else if(pass.length < 4){
            e.preventDefault();
            error.style.display = "block";
            error.textContent = "Password must be at least 8 characters.";
        }
        else{
            error.style.display = "none";
        }
    });
});

const error = document.getElementById("passwordError");

password.addEventListener("input", function () {
    const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/;

    if (password.value === "") {
        error.textContent = "";
        return;
    }

    if (!pattern.test(password.value)) {
        error.textContent = " Password must include small letter, capital letter, number, and special character. minimum 8 charactaer";
    } else {
        error.textContent = "✔ Strong password";
        error.style.color = "green";
    }
});
