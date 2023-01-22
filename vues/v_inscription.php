<h1>Inscription</h1>
<form method="POST" action="index.php?uc=authentification&action=validerInscription">
    <label for="pseudo" class="form-label mt-3">Pseudonyme</label>
    <input onInput="check_form()" type="text" name="pseudo" id="pseudo" class="form-control" placeholder="Votre pseudonyme">

    <label for="email" class="form-label mt-3">Adresse email</label>
    <input onInput="check_form()" type="email" name="email" id="email" class="form-control" placeholder="Votre adresse email">

    <label for="password" class="form-label mt-3">Mot de passe</label>
    <input onInput="check_strong_password(); check_form()" type="password" name="password" id="password" class="form-control" placeholder="Votre mot de passe">
    <div id="errorPassword"></div>

    <label for="passwordConfirm" class="form-label mt-3">Confirmation du mot de passe</label>
    <input onInput="check_confirm_password(); check_form()" type="password" name="passwordConfirm" id="passwordConfirm" class="form-control" placeholder="Confirmez votre mot de passe">
    <div id="errorConfirmPassword"></div>

    <input type="checkbox" class="mt-3" name="conditionsOfuse" id="conditionsOfuse">
    <label for="checkbox">J'accepte les conditions d'utilisation</label> <br>

    <div id="errorConditionsOfuse" style="color: red">Veuillez accepter les conditions d'utilisation</div>
    <div id="errorForm"></div>

    <button type="submit" class="btn btn-primary mt-3">S'inscrire</button>
    <button type="reset" class="btn btn-secondary mt-3">Annuler</button>
</form>