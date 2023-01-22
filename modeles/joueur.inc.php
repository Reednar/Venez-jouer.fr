<?php

class Joueur {

    private int $id;
    private string $pseudo;
    private string $mail;
    private string $password;
    private ?int $nbVictoire;
    private ?int $nbDefaite;
    private ?int $nbNul;

    public function getId(): int {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function getPseudo(): string {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): void {
        $this->pseudo = $pseudo;
    }

    public function getMail(): string {
        return $this->mail;
    }

    public function setMail(string $mail): void {
        $this->mail = $mail;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getNbVictoire(): int {
        return $this->nbVictoire;
    }

    public function setNbVictoire(int $nbVictoire): void {
        $this->nbVictoire = $nbVictoire;
    }

    public function getNbDefaite(): int {
        return $this->nbDefaite;
    }

    public function setNbDefaite(int $nbDefaite): void {
        $this->nbDefaite = $nbDefaite;
    }

    public function getNbNul(): int {
        return $this->nbNul;
    }

    public function setNbNul(int $nbNul): void {
        $this->nbNul = $nbNul;
    }

    public static function new_instance_joueur($id, $pseudo, $mail, $password): Joueur {
        $instance = new self();
        $instance->id = $id;
        $instance->pseudo = $pseudo;
        $instance->mail = $mail;
        $instance->password = $password;
        $instance->nbVictoire = 0;
        $instance->nbDefaite = 0;
        $instance->nbNul = 0;
        return $instance;
    }

    public static function getAllJoueur(): array {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT * FROM joueur");
        $req->execute();
        $lesJoueurs = $req->fetchAll(PDO::FETCH_CLASS, 'Joueur');
        return $lesJoueurs;
    }

    public static function getJoueurById(int $id): Joueur {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT * FROM joueur WHERE id_joueur = :id");
        $req->bindParam(':id', $id);
        $req->execute();
        $leJoueur = $req->fetchObject('Joueur');
        return $leJoueur;
    }

    public static function getEmailExistDeja(string $mail): bool {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT * FROM joueur WHERE mail = :mail");
        $req->bindParam(':mail', $mail);
        $leJoueur = $req->fetchObject('Joueur');
        if($leJoueur == null) {
            return false;
        } else {
            return true;
        }
    }

    public static function getPseudoExistDeja(string $pseudo): bool {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT * FROM joueur WHERE pseudo = :pseudo");
        $req->bindParam(':pseudo', $pseudo);
        $leJoueur = $req->fetchObject('Joueur');
        if($leJoueur == null) {
            return false;
        } else {
            return true;
        }
    }

    public static function connexion(string $mail, string $password): array {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT id_joueur, pseudo, mail, password FROM joueur WHERE mail = :mail");
        $req->bindParam(':mail', $mail);
        $req->execute();
        $leJoueur = $req->fetch();
        if($leJoueur == null) {
            return [];
        } else {
            if(password_verify($password, $leJoueur['password'])) {
                return $leJoueur;
            } else {
                return [];
            }
        }
    }

    public static function inscription(string $pseudo, string $mail, string $password): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("INSERT INTO joueur (pseudo, mail, password) VALUES (:pseudo, :mail, :password)");
        $req->bindParam(':pseudo', $pseudo);
        $req->bindParam(':mail', $mail);
        $req->bindParam(':password', $password);
        $req->execute();
    }

    public static function add(Joueur $joueur): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("INSERT INTO joueur (pseudo, mail, password, nbVictoire, nbDefaite, nbNul) VALUES (:pseudo, :mail, :password, :nbVictoire, :nbDefaite, :nbNul)");
        $pseudo = $joueur->getPseudo();
        $mail = $joueur->getMail();
        $password = $joueur->getPassword();
        $nbVictoire = $joueur->getNbVictoire();
        $nbDefaite = $joueur->getNbDefaite();
        $nbNul = $joueur->getNbNul();
        $req->bindParam(':pseudo', $pseudo);
        $req->bindParam(':mail', $mail);
        $req->bindParam(':password', $password);
        $req->bindParam(':nbVictoire', $nbVictoire);
        $req->bindParam(':nbDefaite', $nbDefaite);
        $req->bindParam(':nbNul', $nbNul);
        if(!$req->execute()) {
            die("Error: " . $req->errorInfo()[2]);
        }
    }

    public static function update(Joueur $joueur): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("UPDATE joueur SET pseudo = :pseudo, mail = :mail, password = :password, nbVictoire = :nbVictoire, nbDefaite = :nbDefaite, nbNul = :nbNul WHERE id = :id");
        $pseudo = $joueur->getPseudo();
        $mail = $joueur->getMail();
        $password = $joueur->getPassword();
        $nbVictoire = $joueur->getNbVictoire();
        $nbDefaite = $joueur->getNbDefaite();
        $nbNul = $joueur->getNbNul();
        $req->bindParam(':pseudo', $pseudo);
        $req->bindParam(':mail', $mail);
        $req->bindParam(':password', $password);
        $req->bindParam(':nbVictoire', $nbVictoire);
        $req->bindParam(':nbDefaite', $nbDefaite);
        $req->bindParam(':nbNul', $nbNul);
        if(!$req->execute()) {
            die("Error: " . $req->errorInfo()[2]);
        }
    }

    public static function delete(Joueur $joueur): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("DELETE FROM joueur WHERE id = :id");
        $id = $joueur->getId();
        $req->bindParam(':id', $id);
        
    }

}