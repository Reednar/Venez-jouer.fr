<?php

function check_session() {
    if (!empty($_SESSION['joueur'])) {
        return true;
    } else {
        return false;
    }
}

function check_id_partie() {
    if (!empty($_SESSION['id_partie'])) {
        return true;
    } else {
        return false;
    }
}