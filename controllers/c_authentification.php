<?php
$action = $_GET['action'];

switch ($action) {
    case 'connexion':
        include('vues/v_connexion.php');
        break;
    case 'validerConnexion':
        $msg = "";
        $donneesPost = array(
            'email' => $_POST['email'],
            'password' => $_POST['password']
        );
        $donneesPost = secure_all_data($donneesPost);

        if (!check_email($donneesPost['email'])) {
            $msg .= "L'adresse email n'est pas valide. ";
        }

        if (!check_password($donneesPost['password'])) {
            $msg .= "Le mot de passe n'est pas valide. ";
        }

        if ($msg != "") {
            set_flash($msg, 'danger');
            header('Location: index.php?uc=authentification&action=connexion');
        } else {
            $joueur = Joueur::connexion($donneesPost['email'], $donneesPost['password']);
            if (!empty($joueur)) {
                $_SESSION['joueur'] = Joueur::new_instance_joueur($joueur['id_joueur'], $joueur['pseudo'], $joueur['mail'], $joueur['password']);
                set_flash("Vous êtes connecté.", 'success');
                header('Location: index.php?uc=accueil&action=accueil');
            } else {
                set_flash("L'adresse email ou le mot de passe est incorrect.", 'danger');
                header('Location: index.php?uc=authentification&action=connexion');
            }
        }
        break;
    case 'inscription':
        include('vues/v_inscription.php');
        break;
    case 'validerInscription':
        $msg = "";
        $donneesPost = array(
            'pseudo' => $_POST['pseudo'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'passwordConfirm' => $_POST['passwordConfirm']
        );
        $donneesPost = secure_all_data($donneesPost);

        if (!check_email($donneesPost['email'])) {
            $msg .= "L'adresse email n'est pas valide. ";
        }

        if (!check_password($donneesPost['password'])) {
            $msg .= "Le mot de passe n'est pas valide. ";
        }

        if ($donneesPost['password'] != $donneesPost['passwordConfirm']) {
            $msg .= "Les mots de passe ne correspondent pas. ";
        }

        if (!isset($_POST['conditionsOfuse'])) {
            $msg .= "Vous devez accepter les conditions d'utilisation. ";
        }

        if (Joueur::getEmailExistDeja($donneesPost['email'])) {
            $msg .= "L'adresse email est déjà utilisée. ";
        }

        if (Joueur::getPseudoExistDeja($donneesPost['pseudo'])) {
            $msg .= "Le pseudonyme est déjà utilisé. ";
        }

        if ($msg != "") {
            set_flash($msg, 'danger');
            header('Location: index.php?uc=authentification&action=inscription');
        } else {
            $password_hash = secure_password($donneesPost['password']);
            Joueur::inscription($donneesPost['pseudo'], $donneesPost['email'], $password_hash);
            set_flash("Votre compte a bien été créé. Vous pouvez vous connecter.", 'success');
            header('Location: index.php?uc=authentification&action=connexion');
        }
        break;
    case 'tempProfile':
        include('vues/v_tempProfile.php');
        break;
    case 'validationProfileTemp':
        $pseudo = secure_data($_POST['pseudo']);
        if ($pseudo == "") {
            $pseudo = "Joueur";
        } else {
            $pseudo = preg_replace('/[^A-Za-z0-9\-]/', '', $pseudo);
        }
        $email = $pseudo . "@temp.com";
        $template_password = $pseudo . "tempo*7cE]{4Q3x4yWq";
        $password = secure_password($template_password);
        $email_secure = secure_data($email);
        $password_secure = secure_data($password);
        Joueur::inscription($pseudo, $email_secure, $password_secure);
        $joueur = Joueur::connexion($email_secure, $template_password);
        if (!empty($joueur)) {
            $_SESSION['joueur'] = Joueur::new_instance_joueur($joueur['id_joueur'], $joueur['pseudo'], $joueur['mail'], $joueur['password']);
            // Créer un cookie qui expire au bout de 30 minutes
            setcookie('tempProfile', $joueur['id_joueur'], time() + 60, '/');
            set_flash("Vous êtes connecté avec un profil temporaire.", 'success');
            header('Location: index.php?uc=jouer&action=listeParties');
        } else {
            set_flash("Une erreur est survenue.", 'danger');
            header('Location: index.php?uc=accueil');
        }
        break;
    case 'deconnexion':
        session_destroy();
        header('Location: index.php?uc=accueil&action=accueil');
        break;
}
