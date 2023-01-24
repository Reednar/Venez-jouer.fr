<?php

/**
 * Convertit les états des parties en string
 * 0 = Salon de la partie
 * 1 = Pile ou face
 * 2 = En cours
 * 3 = Finie
 * 
 * @param Partie $partie
 * @return string
 */
function check_etat_partie(Partie $partie) : string {
    $etat = $partie->getEtat();
    switch ($etat) {
        case 0:
            return "Salon de la partie";
        case 1:
            return "Pile ou face";
        case 2:
            return "En cours";
        case 3:
            return "Finie";
        default:
            return "Erreur";
    }
}

/**
 * Vérifie si le nom de la partie est disponible
 *
 * @param string $nom
 * @return boolean
 */
function check_dispo_nom_partie(string $nom) : bool {
    $pdo = monPDO::getInstance();
    $req = $pdo->prepare("SELECT * FROM partie WHERE nom = :nom");
    $req->execute(array(
        'nom' => $nom
    ));
    $resultat = $req->fetch();
    if ($resultat) {
        return false;
    } else {
        return true;
    }
}

/**
 * Pile ou face
 * pile = 0
 * face = 1
 *
 * @param string $cote
 * @return integer
 */
function check_cote_pile_ou_face(string $cote) : int {
    if ($cote == "pile") {
        return 0;
    } else if ($cote == "face") {
        return 1;
    } else {
        return -1;
    }
}

/**
 * Signe du joueur
 * 0 = X
 * 1 = O
 *
 * @param integer $signe
 * @return string
 */
function check_X_ou_O(int $signe) : string {
    if ($signe == 0) {
        return "X";
    } else if ($signe == 1) {
        return "O";
    } else {
        return "Erreur";
    }
}