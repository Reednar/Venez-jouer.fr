<h1>Création de la partie</h1>
<form action="index.php?uc=jouer&action=validerCreationPartie" method="post">
    <div class="form-group mt-3">
        <label for="nom">Nom de la partie</label>
        <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom de la partie">
    </div>
    <div class="form-group mt-3">
        <label for="nbManche">Nombre de manche</label>
        <input type="number" class="form-control" id="nbManche" name="nbManche" placeholder="Nombre de manche" min="1" max="10">
    </div>
    <button type="submit" class="btn btn-primary mt-3">Créer la partie</button>
</form>