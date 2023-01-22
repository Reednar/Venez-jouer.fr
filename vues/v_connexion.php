<h1>Connexion</h1>
<form method="POST" action="index.php?uc=authentification&action=validerConnexion">
   
    <label for="email" class="form-label mt-3">Adresse email</label>
    <input type="email" name="email" id="email" class="form-control" placeholder="Votre adresse email">

    <label for="password" class="form-label mt-3">Mot de passe</label>
    <input type="password" name="password" id="password" class="form-control" placeholder="Votre mot de passe">

    <button type="submit" class="btn btn-primary mt-3">Se connecter</button>
    <button type="reset" class="btn btn-secondary mt-3">Annuler</button>
</form>