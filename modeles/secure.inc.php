<?php

function secure_data($donnees) {
    $donnees = trim($donnees);
    $donnees = stripslashes($donnees);
    $donnees = htmlspecialchars($donnees);
    return $donnees;
}

function secure_all_data($donnees) {
    foreach ($donnees as $key => $value) {
        $donnees[$key] = secure_data($value);
    }
    return $donnees;
}

function secure_password($password) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    return $password;
}

function check_email($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function check_password($password) {
    if (preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#", $password)) {
        return true;
    } else {
        return false;
    }
}