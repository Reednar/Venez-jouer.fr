<h1>Partie</h1>
<div class="row">
    <div class="col-6">
        <card class="card">
            <div class="card-body">
                <h5 class="card-title">Tour de jeu</h5>
                <p class="card-text" id="tour_de_jeu"></p>
            </div>
        </card>
    </div>
    <div class="col-6">
        <table id="morpion" class="table-morpion">
            <tr>
                <td class="td-morpion" id="case1" onclick="morpion(case1)"></td>
                <td class="td-morpion" id="case2" onclick="morpion(case2)"></td>
                <td class="td-morpion" id="case3" onclick="morpion(case3)"></td>
            </tr>
            <tr>
                <td class="td-morpion" id="case4" onclick="morpion(case4)"></td>
                <td class="td-morpion" id="case5" onclick="morpion(case5)"></td>
                <td class="td-morpion" id="case6" onclick="morpion(case6)"></td>
            </tr>
            <tr>
                <td class="td-morpion" id="case7" onclick="morpion(case7)"></td>
                <td class="td-morpion" id="case8" onclick="morpion(case8)"></td>
                <td class="td-morpion" id="case9" onclick="morpion(case9)"></td>
            </tr>
        </table>
    </div>
</div>

<script>
    var tour_de_jeu = document.getElementById('tour_de_jeu');
    var signe = null;
    var adversaire = null;
    var adversaire_1 = null;
    var adversaire_2 = null;
    var adversaire_joueur = null;
    var openModal = false;

    setInterval(function() {
        $.ajax({
            url: 'index.php?uc=jouer&action=checkEtatPartie&fluxAjax=oui&idPartie=<?= $idPartie ?>',
            success: function(data) {
                if (data == 3) {
                    if (!openModal) {
                        ouvre_modal("finie");
                    }
                }
            }
        });
    }, 500);

    setInterval(function() {
        $.ajax({
            url: 'index.php?uc=jouer&action=joueurQuiJoue&fluxAjax=oui&idPartie=<?= $idPartie ?>',
            success: function(data) {
                mettre_a_jour_joueur_qui_joue(data);
            }
        });
    }, 100);

    setInterval(function() {
        $.ajax({
            url: 'index.php?uc=jouer&action=metAJourMorpion&fluxAjax=oui&idPartie=<?= $idPartie ?>',
            success: function(json) {
                met_a_jour_morpion(json);
            }
        });
    }, 100);

    function set_joueur_gagnant(id_joueur) {
        $.ajax({
            url: 'index.php?uc=jouer&action=setJoueurGagnant&fluxAjax=oui&idPartie=<?= $idPartie ?>&idJoueur=' + id_joueur,
            success: function(data) {
                console.log(data);
                if (data != 0) {
                    var data = JSON.parse(data);
                }
                if (data[0]['gagnant'] == <?= $_SESSION['joueur']->getId() ?>) {
                    ouvre_modal("gagnant");
                } else if (data[0]['gagnant'] != <?= $_SESSION['joueur']->getId() ?> && data != 0) {
                    ouvre_modal("perdant");
                } else if (data == 0) {
                    ouvre_modal("egalite");
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    function ouvre_modal(type) {
        if (openModal == false) {
            if (type == "gagnant") {
                openModal = true;
                Swal.fire({
                    title: "Bravo !",
                    text: "Vous avez gagné !",
                    icon: "success",
                    button: "Ok",
                }).then((value) => {
                    window.location.href = "index.php?uc=accueil";
                });
            } else if (type == "perdant") {
                openModal = true;
                Swal.fire({
                    title: "Dommage !",
                    text: "Vous avez perdu !",
                    icon: "error",
                    button: "Ok",
                }).then((value) => {
                    window.location.href = "index.php?uc=accueil";
                });
            } else if (type == "finie") {
                openModal = true;
                Swal.fire({
                    title: "Un joueur a quitté la partie !",
                    text: "La partie est finie !",
                    icon: "info",
                    button: "Ok",
                }).then((value) => {
                    window.location.href = "index.php?uc=accueil";
                });
            } else if (type == "egalite") {
                openModal = true;
                Swal.fire({
                    title: "Egalité !",
                    text: "La partie est finie !",
                    icon: "info",
                    button: "Ok",
                }).then((value) => {
                    window.location.href = "index.php?uc=accueil";
                });
            }
        }
    }

    function composition_gagnante(case_cocher) {
        // Vérifie par exemple si dans le tableau case_cocher il y a la composition suivant :
        // case1, case2, cas3
        // Si c'est le cas alors le joueur qui a cette composition a gagné
        var gagnant = false;
        if (case_cocher != null) {
            if (case_cocher.length >= 3) {
                for (var i = 0; i < case_cocher.length; i++) {
                    // Tous les cas où le joueur gagne peux importe l'ordre des cases cochées
                    if (case_cocher.includes("case1") && case_cocher.includes("case2") && case_cocher.includes("case3")) {
                        gagnant = true;
                    } else if (case_cocher.includes("case4") && case_cocher.includes("case5") && case_cocher.includes("case6")) {
                        gagnant = true;
                    } else if (case_cocher.includes("case7") && case_cocher.includes("case8") && case_cocher.includes("case9")) {
                        gagnant = true;
                    } else if (case_cocher.includes("case1") && case_cocher.includes("case4") && case_cocher.includes("case7")) {
                        gagnant = true;
                    } else if (case_cocher.includes("case2") && case_cocher.includes("case5") && case_cocher.includes("case8")) {
                        gagnant = true;
                    } else if (case_cocher.includes("case3") && case_cocher.includes("case6") && case_cocher.includes("case9")) {
                        gagnant = true;
                    } else if (case_cocher.includes("case1") && case_cocher.includes("case5") && case_cocher.includes("case9")) {
                        gagnant = true;
                    } else if (case_cocher.includes("case3") && case_cocher.includes("case5") && case_cocher.includes("case7")) {
                        gagnant = true;
                    }
                }
            }
        }
        return gagnant;
    }

    function egalite(case_cocher) {
        var egalite = false;
        if (case_cocher != null) {
            if (case_cocher.length == 9) {
                egalite = true;
            }
        }
        return egalite;
    }

    function mettre_a_jour_joueur_qui_joue(data) {
        var json = JSON.parse(data);
        tour_de_jeu.innerText = "Autour de " + json.joueur_qui_joue.pseudo + " de jouer";
        adversaire_joueur = json;
        if (adversaire_joueur.joueur_qui_joue.id_joueur != <?= $_SESSION['joueur']->getId() ?>) {
            for (var i = 1; i <= 9; i++) {
                var case_morpion = document.getElementById('case' + i);
                case_morpion.removeAttribute("onclick");
                case_morpion.style.cursor = "not-allowed";
                case_morpion.style.backgroundColor = "grey";
            }
        } else {
            for (var i = 1; i <= 9; i++) {
                var case_morpion = document.getElementById('case' + i);
                if (case_morpion.innerText == "") {
                    case_morpion.setAttribute("onclick", "morpion(case" + i + ")");
                    case_morpion.style.cursor = "pointer";
                    case_morpion.style.backgroundColor = "white";
                } else {
                    case_morpion.removeAttribute("onclick");
                    case_morpion.style.cursor = "not-allowed";
                    case_morpion.style.backgroundColor = "grey";
                }
            }
        }


    }


    function met_a_jour_morpion(json) {

        adversaire = JSON.parse(json);
        adversaire_1 = adversaire[0];
        adversaire_2 = adversaire[1];

        for (var i = 0; i <= 9; i++) {
            var case_morpion = document.getElementById('case' + i);
            // Bloque le joueur qui ne joue pas

        }

        var tab_case_1 = enleve_caracteres_speciaux(adversaire_1.case, ",");
        var tab_case_2 = enleve_caracteres_speciaux(adversaire_2.case, ",");

        if (tab_case_1 != null) {
            var tab_case_1_split = tab_case_1.split(",");
        }
        if (tab_case_2 != null) {
            var tab_case_2_split = tab_case_2.split(",");
        }

        if (tab_case_1_split != null) {
            for (var i = 0; i < tab_case_1_split.length; i++) {
                var case_cocher = document.getElementById(tab_case_1_split[i]);
                if (case_cocher.innerText == "") {
                    case_cocher.innerText = adversaire_1.signe;
                    case_cocher.removeAttribute("onclick");
                }
            }
        }

        if (tab_case_2_split != null) {
            for (var i = 0; i < tab_case_2_split.length; i++) {
                var case_cocher = document.getElementById(tab_case_2_split[i]);
                if (case_cocher.innerText == "") {
                    case_cocher.innerText = adversaire_2.signe;
                    case_cocher.removeAttribute("onclick");
                }
            }
        }
        if (tab_case_1_split != null && tab_case_2_split != null) {
            var all_case_cocher = tab_case_1_split.concat(tab_case_2_split);
        }
        if (egalite(all_case_cocher)) {
            set_joueur_gagnant(0);
        }

        if (composition_gagnante(tab_case_1_split)) {
            set_joueur_gagnant(adversaire_1.id_joueur);
        }
        if (composition_gagnante(tab_case_2_split)) {
            set_joueur_gagnant(adversaire_2.id_joueur);
        }

    }


    function enleve_caracteres_speciaux(chaine, exception) {
        // On enlève les caractères spéciaux sauf les exceptions
        var regex = new RegExp("[^a-zA-Z0-9" + exception + "]", "g");
        if (chaine != null) {
            return chaine.replace(regex, "");
        }
    }

    function morpion(case_morpion) {
        // Quand le joueur qui joue clique sur une case, on change de joueur
        $.ajax({
            url: 'index.php?uc=jouer&action=changerJoueurQuiJoue&fluxAjax=oui&idPartie=<?= $idPartie ?>&case=' + case_morpion.id,
            success: function(data) {
                coche_case(case_morpion);
            }
        });
    }

    function coche_case(case_morpion) {
        if (adversaire_1.id_joueur == adversaire_joueur.joueur_qui_joue.id_joueur) {
            signe = adversaire_1.signe;
        } else {
            signe = adversaire_2.signe;
        }
        case_morpion.innerText = signe;
    }
</script>