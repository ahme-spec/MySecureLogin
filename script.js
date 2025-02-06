// Ici j'attache l'evenement 'click' aux boutons
document.addEventListener('DOMContentLoaded', function() {
    const backButton = document.getElementById('backButton');
    const signupButton = document.getElementById('signupButton');
    
    backButton.addEventListener('click', toggleRegisterForm);
    signupButton.addEventListener('click', toggleRegisterForm);
});

// La fonction pour afficher ou masquer le formulaire d'inscription
function toggleRegisterForm() {
    var registerForm = document.getElementById("register-form");
    var loginForm = document.getElementById("login-form");

    if (registerForm.style.display === "none") {
        registerForm.style.display = "block";
        loginForm.style.display = "none";
    } else {
        registerForm.style.display = "none";
        loginForm.style.display = "block";
    }
}
