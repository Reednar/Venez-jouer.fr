<?php

require_once('modeles/fnc.inc.php');
require_once('modeles/joueur.inc.php');
require_once('modeles/partie.inc.php');
require_once('modeles/pdo.inc.php');
require_once('modeles/secure.inc.php');
require_once('modeles/session.inc.php');
require_once('modeles/msgflash.inc.php');

session_start();
ob_start();

if (empty($_GET['fluxAjax'])) {
    include('vues/v_entete.php');
}

$uc = empty($_GET['uc']) ? 'accueil' : $_GET['uc'];
$action = empty($_GET['action']) ? '' : $_GET['action'];
$idPartie = empty($_GET['idPartie']) ? '' : $_GET['idPartie'];
if ($uc != 'jouer' || $idPartie == '') {
    if (!empty($_SESSION['joueur']) && !empty($_SESSION['id_partie'])) {
        Partie::deleteJoueurDansPartie($_SESSION['id_partie'], $_SESSION['joueur']->getId());
        unset($_SESSION['id_partie']);
    }
}

switch($uc)
{
    case 'accueil':
        include('controllers/c_accueil.php');
        break;
    case 'authentification':
        include('controllers/c_authentification.php');
        break;
    case 'jouer':
        include('controllers/c_jouer.php');
        break;
}

if (empty($_GET['fluxAjax'])) {
    include('vues/v_pied.php');
}