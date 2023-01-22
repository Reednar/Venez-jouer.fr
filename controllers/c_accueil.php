<?php
$action = empty($_GET['action']) ? 'accueil' : $_GET['action'];
include('vues/v_accueil.php');