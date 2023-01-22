var errorPassword = document.getElementById('errorPassword');
var errorConfirmPassword = document.getElementById('errorConfirmPassword');
var errorForm = document.getElementById('errorForm');
var conditionsOfuse = document.getElementById('conditionsOfuse');
var errorConditionsOfuse = document.getElementById('errorConditionsOfuse');
function check_strong_password() {
    var password = document.getElementById('password').value;
    var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
    if (strongRegex.test(password)) {
        errorPassword.innerHTML = "Le mot de passe est fort";
        errorPassword.style.color = "green";
        return true;
    } else {
        errorPassword.innerHTML = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial";
        errorPassword.style.color = "red";
        return false;
    }
}

function check_confirm_password() {
    if (document.getElementById('password').value == document.getElementById('passwordConfirm').value) {
        errorConfirmPassword.innerHTML = "Les mots de passe correspondent";
        errorConfirmPassword.style.color = "green";
        return true;
    } else {
        errorConfirmPassword.innerHTML = "Les mots de passe ne correspondent pas";
        errorConfirmPassword.style.color = "red";
        return false;
    }
}

function check_form() {
    var pseudo = document.getElementById('pseudo');
    var email = document.getElementById('email');
    var password = document.getElementById('password');
    var passwordConfirm = document.getElementById('passwordConfirm');

    if (pseudo.value == "" || email.value == "" || password.value == "" || passwordConfirm.value == "") {
        errorForm.innerHTML = "Veuillez remplir tous les champs";
        errorForm.style.color = "red";
        return false;
    } else {
        errorForm.innerHTML = "";
        return true;
    }
}

if (conditionsOfuse) {
    conditionsOfuse.addEventListener("change", function () {
        if (this.checked) {
            errorConditionsOfuse.innerHTML = "Conditions d'utilisation acceptées";
            errorConditionsOfuse.style.color = "green";
        } else {
            errorConditionsOfuse.innerHTML = "Veuillez accepter les conditions d'utilisation";
            errorConditionsOfuse.style.color = "red";
        }
    });
}
