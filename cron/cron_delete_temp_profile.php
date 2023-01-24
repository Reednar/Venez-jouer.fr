<?php

// Si le cookie tempProfile a expiré, on supprime le profil de la base de données
if (!empty($_COOKIE['tempProfile'])) {
    $tempProfile = $_COOKIE['tempProfile'];
    $tempProfile = secure_data($tempProfile);
    $tempProfile = Joueur::getJoueurById($tempProfile);
    if ($tempProfile != null) {
        // If date aujourdhui = date expiration cookie on delete le profil
        if (date('Y-m-d') == date('Y-m-d', strtotime($tempProfile['date_expiration']))) {
            Joueur::delete($tempProfile);
        } 
    }
}