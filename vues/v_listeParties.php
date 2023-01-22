<h1>Liste des parties</h1>
<a class="btn btn-primary" href="index.php?uc=jouer&action=creerPartie">Créer une partie</a>
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>Partie</th>
            <th>Créateur</th>
            <th>Nombre de joueurs</th>
            <th>Nombre de manches</th>
            <th class="th-mobile">Etat</th>
            <th class="th-mobile">Actions</th>
        </tr>
    </thead>
    <tbody id="tableau_partie">
        <?php if (!empty($getAllPartie)) { ?>
            <?php foreach ($getAllPartie as $partie) { ?>
                <tr>
                    <td><?php echo $partie->getNom(); ?></td>
                    <td><?php echo $partie->getPseudoJoueurById($partie->getIdJoueur()); ?></td>
                    <td><?php echo $partie->getNbJoueur() . "/2"; ?></td>
                    <td class="td-mobile"><?php echo $partie->getNbManche(); ?></td>
                    <td class="td-mobile"><?php echo check_etat_partie($partie); ?></td>
                    <td>
                        <a class="btn btn-primary" href="index.php?uc=jouer&action=joinSalon&idPartie=<?= $partie->getIdPartieByNom($partie->getNom()) ?>">Rejoindre</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6" style="text-align: center;">Aucune partie n'est disponible</td>
            </tr>
        <?php } ?>
</table>
<script>
    var tableau_des_joueurs = document.getElementById('tableau_partie');
    setInterval(function() {
        $.ajax({
            url: 'index.php?uc=jouer&action=listeParties',
            success: function(data) {
                met_a_jour_tableau(data);
            }
        });
    }, 1000);

    function met_a_jour_tableau(data) {
        var $data = $(data);
        var $elements = $data.find("#tableau_partie");
        var $elements = $elements.children().map(function() {
            return $(this).prop('outerHTML');
        }).get();
        document.getElementById("tableau_partie").innerHTML = "";
        for (let index = 0; index < $elements.length; index++) {
            document.getElementById("tableau_partie").innerHTML += $elements[index];

        }
    }
</script>