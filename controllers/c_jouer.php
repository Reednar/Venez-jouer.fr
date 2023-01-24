<?php
$action = $_GET['action'];

if (!check_session()) {
    header('Location: index.php?uc=authentification&action=tempProfile');
} else {
    switch ($action) {
        case 'listeParties':
            $getAllPartie = Partie::getAllPartie();
            if (!empty($getAllPartie)) {
                foreach ($getAllPartie as $partie) {
                    $idPartie = Partie::getIdPartieByNom($partie->getNom());
                    $partie->setId($idPartie);
                    if ($partie->getEtat() == 2 || $partie->getEtat() == 3 || $partie->getNbJoueur() == 0) {
                        Partie::delete($idPartie);
                    }
                }
            }
            include('vues/v_listeParties.php');
            break;
        case 'creerPartie':
            include('vues/v_creerPartie.php');
            break;
        case 'validerCreationPartie':
            $joueur = $_SESSION['joueur'];
            $nom = secure_data($_POST['nom']);
            $nbManche = secure_data($_POST['nbManche']);
            $msg = "";
            if (check_dispo_nom_partie($nom) == false) {
                $msg .= "Le nom de la partie est déjà utilisé. ";
            }
            if ($nbManche < 1 || $nbManche > 10) {
                $msg .= "Le nombre de manche doit être compris entre 1 et 10. ";
            }
            if ($nom == "") {
                $msg .= "Le nom de la partie ne peut pas être vide. ";
            }
            if ($nbManche == "") {
                $msg .= "Le nombre de manche ne peut pas être vide. ";
            }
            if ($msg != "") {
                set_flash($msg, 'danger');
                header('Location: index.php?uc=jouer&action=creerPartie');
                break;
            } else {
                $partie = Partie::new_instance_partie($joueur, $nom, $nbManche);
                Partie::add($partie);
                Partie::addJoueurDansPartie($partie->getId(), $joueur->getId());
                set_flash("La partie a été créée.", 'success');
                header('Location: index.php?uc=jouer&action=joinSalon&idPartie=' . $partie->getId());
                break;
            }
            break;
        case 'joinSalon':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            if ($partie->getNbJoueur() == 2) {
                set_flash("La partie est déjà pleine.", 'danger');
                header('Location: index.php?uc=jouer&action=listeParties');
            } else {
                $getJoueurInPartie = Partie::getJoueurInPartie($idPartie);
                $lesIdJoueurs = Partie::getIdJoueurInPartie($idPartie);
                if (!Partie::checkJoueurDansPartie($idPartie, $_SESSION['joueur']->getId())) {
                    Partie::addJoueurDansPartie($idPartie, $_SESSION['joueur']->getId());
                }
                $partie->setNbJoueur(Partie::countNbJoueurInPartie($idPartie));
                $partie->setId($idPartie);
                Partie::updateNbJoueur($idPartie, $partie->getNbJoueur());
                include('vues/v_salonPartie.php');
            }
            break;
        case 'listeJoueursDansPartie':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            $getJoueurInPartie = Partie::getJoueurInPartie($idPartie);
            include('vues/v_salonPartie.php');
            break;
        case 'quitterPartie':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            $partie->setNbJoueur(Partie::countNbJoueurInPartie($idPartie));
            $partie->setId($idPartie);
            Partie::updateNbJoueur($idPartie, $partie->getNbJoueur());
            Partie::deleteJoueurDansPartie($idPartie, $_SESSION['joueur']->getId());
            set_flash("Vous avez quitté la partie.", 'success');
            header('Location: index.php?uc=jouer&action=listeParties');
            break;
        case 'commencerPartie':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            if ($partie->getEtat() == 1) {
                $partie->setEtat(2);
                Partie::updateEtat($idPartie, $partie->getEtat());
            }
            break;
        case 'redirectionVersPartie':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            if ($partie->getEtat() == 2) {
                echo json_encode($partie->getEtat());
            }
            break;
        case 'partieEnCours':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            $getJoueurInPartie = Partie::getJoueurInPartie($idPartie);
            $getChoixJoueur = Partie::getChoixJoueurInPartie($idPartie);
            $getNbManche = $partie->getNbManche();
            $tour_de_jeu = random_int(0, 1); // Celui qui commence
            $joueur_qui_joue = null;
            if ($joueur_qui_joue == null) {
                foreach ($getJoueurInPartie as $joueur) {
                    for ($i = 0; $i < count($getChoixJoueur); $i++) {
                        if (check_cote_pile_ou_face($getChoixJoueur[$i]['pile_ou_face']) == $tour_de_jeu) {
                            $joueur_qui_joue = $joueur;
                            Partie::updateJoueurQuiJoue($idPartie, $getChoixJoueur[$i]['id_joueur']);
                        }
                    }
                }
            }
            $signe = Partie::getSigne($idPartie, $joueur_qui_joue['id_joueur'])[0];
            include('vues/v_partieEnCours.php');
            break;
        case 'joueurQuiJoue':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            $getJoueurInPartie = Partie::getJoueurInPartie($idPartie);
            $getJoueurQuiJoue = Partie::getJoueurQuiJoue($idPartie);
            $joueur_qui_joue = null;
            $signe = null;
            if (!in_array($getJoueurQuiJoue[0]['id_joueur'], array_column($getJoueurInPartie, 'id_joueur')) || count($getJoueurInPartie) <= 1) {
                Partie::updateEtat($idPartie, 3);
                Partie::deleteAllJoueurDansPartie($idPartie);
                Partie::updateNbJoueur($idPartie, 0);
            }
            
            foreach ($getJoueurInPartie as $joueur) {
                if ($joueur['id_joueur'] == $getJoueurQuiJoue[0]['id_joueur']) {
                    $joueur_qui_joue = $joueur;
                    $signe = Partie::getSigne($idPartie, $joueur['id_joueur'])[0];
                }
            }
            $dataSend = array(
                'joueur_qui_joue' => $joueur_qui_joue,
                'signe' => $signe
            );
            echo json_encode($dataSend);
            break;
        case 'changerJoueurQuiJoue':
            $idPartie = $_GET['idPartie'];
            $case = $_GET['case'];
            $partie = Partie::getPartieById($idPartie);
            $getJoueurInPartie = Partie::getJoueurInPartie($idPartie);
            $getJoueurQuiJoue = Partie::getJoueurQuiJoue($idPartie);
            foreach ($getJoueurInPartie as $joueur) {
                $getCaseCocher = Partie::getCaseCocher($idPartie, $joueur['id_joueur']);
                if ($joueur['id_joueur'] != $getJoueurQuiJoue[0]['tour']) {
                    Partie::updateJoueurQuiJoue($idPartie, $joueur['id_joueur']);
                } else {
                    Partie::updateCaseCocher($idPartie, $joueur['id_joueur'], $case);
                }
            }
            break;
        case 'metAJourMorpion':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            $getJoueurInPartie = Partie::getJoueurInPartie($idPartie);
            $caseCocher = array();
            foreach ($getJoueurInPartie as $joueur) {
                $getCaseCocher = Partie::getCaseCocher($idPartie, $joueur['id_joueur']);
                $caseCocher[] = [
                    'id_joueur' => $joueur['id_joueur'],
                    'case' => $getCaseCocher[0]['case_cocher'],
                    'signe' => $getCaseCocher[0]['signe']
                ];
            }
            echo json_encode($caseCocher);
            break;
        case 'setJoueurGagnant':
            $idPartie = $_GET['idPartie'];
            $idJoueur = $_GET['idJoueur'];
            if ($idJoueur == 0) {
                $joueur_gagnant = 0;
            } else {
                Partie::setJoueurGagnant($idPartie, $idJoueur);
                $joueur_gagnant = Partie::getJoueurGagnant($idPartie);
            }
            echo json_encode($joueur_gagnant);
            break;
        case 'lancementPartie':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            if ($partie->getEtat() == 0) {
                $partie->setEtat(1);
                Partie::updateEtat($idPartie, $partie->getEtat());
            }
            break;
        case 'pileOuface':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            if ($partie->getEtat() == 1) {
                echo json_encode($partie->getEtat());
            }
            break;
        case 'verifChoix':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            $getChoixJoueur = Partie::getChoixJoueurInPartie($idPartie);
            $dataSend = array();
            foreach ($getChoixJoueur as $joueur) {
                $dataSend[] = array(
                    'pseudo' => $joueur['pseudo'],
                    'choix' => $joueur['pile_ou_face']
                );
            }
            echo json_encode($dataSend);
            break;
        case 'choixDuJoueur':
            $idPartie = $_GET['idPartie'];
            $choix = $_GET['cote'];
            $partie = Partie::getPartieById($idPartie);
            if ($choix == 'pile') {
                $signe = 0;
            } else {
                $signe = 1;
            }
            $partie->updateChoix($idPartie, $_SESSION['joueur']->getId(), $choix, check_X_ou_O($signe));
            break;
        case 'checkEtatPartie':
            $idPartie = $_GET['idPartie'];
            $partie = Partie::getPartieById($idPartie);
            echo json_encode($partie->getEtat());
            break;
    }
}
