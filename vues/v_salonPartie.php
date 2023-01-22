<h1>Salon de la partie</h1>
<?php
if ($partie->getIdJoueur() == $_SESSION['joueur']->getId()) {
?><button id="commencer" class="btn btn-primary" disabled>Commencer la partie</button><?php }                                                                                                                                                                                 ?>
<a class="btn btn-danger quitter-btn" href="index.php?uc=jouer&action=quitterPartie&idPartie=<?= $partie->getIdPartieByNom($partie->getNom()) ?>">Quitter la partie</a>
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Nombre de victoire</th>
            <th>Nombre de défaites</th>
            <th>Nombre de match nul</th>
        </tr>
    </thead>
    <tbody id="tableau_des_joueurs">
        <?php foreach ($getJoueurInPartie as $joueur) { ?>
            <tr>
                <td class="align-middle"><?php echo $joueur['pseudo']; ?></td>
                <td class="align-middle"><?php echo $joueur['nbVictoire']; ?></td>
                <td class="align-middle"><?php echo $joueur['nbDefaite']; ?></td>
                <td class="align-middle"><?php echo $joueur['nbNul']; ?></td>
            </tr>
        <?php } ?>
</table>

<script>
    var tableau_des_joueurs = document.getElementById('tableau_des_joueurs');
    var commencer = document.getElementById('commencer');
    var modalOuvert = false;

    setInterval(function() {
        $.ajax({
            url: 'index.php?uc=jouer&action=listeJoueursDansPartie&idPartie=<?= $partie->getIdPartieByNom($partie->getNom()) ?>',
            success: function(data) {
                met_a_jour_tableau(data);
            }
        });
    }, 1000);
    if (commencer) {
        commencer.addEventListener('click', function() {
            $.ajax({
                type: "GET",
                url: "index.php?&uc=jouer&action=lancementPartie&idPartie=<?= $partie->getIdPartieByNom($partie->getNom()) ?>",
                timeout: 2000,
                error: function(xhr, status, error) {
                    if (status === "timeout") {
                        console.log("request timed out");
                    }
                }
            });
        });
    }

    setInterval(function() {
        $.ajax({
            type: "GET",
            url: "index.php?&uc=jouer&action=pileOuface&fluxAjax=oui&idPartie=<?= $partie->getIdPartieByNom($partie->getNom()) ?>",
            timeout: 2000,
            success: function(response) {
                if (response == 1) {
                    declenche_modal_pile_ou_face();
                }
            },
            error: function(xhr, status, error) {
                if (status === "timeout") {
                    console.log("request timed out");
                }
            }
        });
    }, 100);

    function met_a_jour_tableau(data) {
        var $data = $(data);
        var $elements = $data.find("#tableau_des_joueurs");
        var $elements = $elements.children().map(function() {
            return $(this).prop('outerHTML');
        }).get();
        document.getElementById("tableau_des_joueurs").innerHTML = "";
        for (let index = 0; index < $elements.length; index++) {
            document.getElementById("tableau_des_joueurs").innerHTML += $elements[index];
        }
        if (commencer) {
            if (document.getElementById("tableau_des_joueurs").children.length == 2) {
                commencer.removeAttribute("disabled");
            } else {
                commencer.setAttribute("disabled", "disabled");
            }
        }
    }

    function declenche_modal_pile_ou_face() {
        if (modalOuvert == false) {
            var pile_ou_face = new bootstrap.Modal(document.getElementById('pile_ou_face'), {
                keyboard: false
            });
            pile_ou_face.show();
            modalOuvert = true;
        }
    }
    setInterval(function() {
        $.ajax({
            type: "GET",
            url: "index.php?&uc=jouer&action=verifChoix&fluxAjax=oui&idPartie=<?= $partie->getIdPartieByNom($partie->getNom()) ?>",
            timeout: 2000,
            success: function(response) {
                met_a_jour_choix(response);
            },
            error: function(xhr, status, error) {
                if (status === "timeout") {
                    console.log("request timed out");
                }
            }
        });
    }, 500);

    function choix_du_joueur(cote) {
        $.ajax({
            type: "GET",
            url: "index.php?&uc=jouer&action=choixDuJoueur&fluxAjax=oui&idPartie=<?= $partie->getIdPartieByNom($partie->getNom()) ?>&cote=" + cote,
            timeout: 2000,
            error: function(xhr, status, error) {
                if (status === "timeout") {
                    console.log("request timed out");
                }
            }
        });
    }

    function met_a_jour_choix(data) {
        var data = JSON.parse(data);
        for (let index = 0; index < data.length; index++) {
            const element = data[index];
            if ("<?= $_SESSION['joueur']->getPseudo() ?>" == element.pseudo && element.choix != null) {
                document.getElementById("choix_joueur_" + element.choix).innerHTML = element.pseudo + " (vous)";
                document.getElementById("div-pile").removeAttribute("onclick");
                document.getElementById("div-face").removeAttribute("onclick");

            } else if (element.choix != null && "<?= $_SESSION['joueur']->getPseudo() ?>" != element.pseudo) {
                document.getElementById("choix_joueur_" + element.choix).innerHTML = element.pseudo;
            }
            
            if (document.getElementById("choix_joueur_pile").innerHTML != "") {
                document.getElementById("div-pile").removeAttribute("onclick");
            }

            if (document.getElementById("choix_joueur_face").innerHTML != "") {
                document.getElementById("div-face").removeAttribute("onclick");
            }
        }

        if (document.getElementById("choix_joueur_pile").innerHTML != "" && document.getElementById("choix_joueur_face").innerHTML != "") {
            if (document.getElementById("lancement")) {
                document.getElementById("lancement").removeAttribute("disabled");
            }
        }
    }

    function lancer_la_partie() {
        $.ajax({
            type: "GET",
            url: "index.php?&uc=jouer&action=commencerPartie&fluxAjax=oui&idPartie=<?= $partie->getIdPartieByNom($partie->getNom()) ?>",
            timeout: 2000,
            error: function(xhr, status, error) {
                if (status === "timeout") {
                    console.log("request timed out");
                }
            }
        });
    }

    setInterval(function() {
        $.ajax({
            type: "GET",
            url: "index.php?&uc=jouer&action=redirectionVersPartie&fluxAjax=oui&idPartie=<?= $partie->getIdPartieByNom($partie->getNom()) ?>",
            timeout: 2000,
            success: function(response) {
                if (response == 2) {
                    window.location.href = "index.php?&uc=jouer&action=partieEnCours&idPartie=<?= $partie->getIdPartieByNom($partie->getNom()) ?>";
                }
            },
            error: function(xhr, status, error) {
                if (status === "timeout") {
                    console.log("request timed out");
                }
            }
        });
    }, 1000);
</script>

<div class="modal" tabindex="-1" id="pile_ou_face">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pile ou face ?</h5>
                <?php if ($partie->getIdJoueur() == $_SESSION['joueur']->getId()) { ?>
                    <button class="btn btn-primary" onclick="lancer_la_partie()" id="lancement" disabled>Lancer la partie</button>
                <?php } ?>
                </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col pile" onclick="choix_du_joueur('pile')" id="div-pile">
                        <p>
                            <span class="p-pile">Pile</span> <br>
                            <span id="choix_joueur_pile"></span>
                        </p>
                    </div>
                    <div class="col face" onclick="choix_du_joueur('face')" id="div-face">
                        <p>
                            <span class="p-face">Face</span> <br>
                            <span id="choix_joueur_face"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>