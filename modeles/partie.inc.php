<?php

class Partie {

    private int $id;
    private int $id_joueur;
    private string $nom;
    private int $nbJoueur;
    private int $nbManche;
    private int $etat;

    public function getId(): int {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function getNbJoueur(): int {
        return $this->nbJoueur;
    }

    public function setNbJoueur(int $nbJoueur): void {
        $this->nbJoueur = $nbJoueur;
    }

    public function getIdJoueur(): int {
        return $this->id_joueur;
    }

    public function setIdJoueur(int $id_joueur): void {
        $this->id_joueur = $id_joueur;
    }

    public function getNbManche(): int {
        return $this->nbManche;
    }

    public function setNbManche(int $nbManche): void {
        $this->nbManche = $nbManche;
    }

    public function getEtat(): int {
        return $this->etat;
    }

    public function setEtat(int $etat): void {
        $this->etat = $etat;
    }

    public static function new_instance_partie(Joueur $createur, $nom, $nbManche): Partie {
        $instance = new self();
        $instance->id_joueur = $createur->getId();
        $instance->nom = $nom;
        $instance->nbJoueur = 0;
        $instance->nbManche = $nbManche;
        $instance->etat = 0;
        return $instance;
    }

    public static function getAllPartie(): array {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT * FROM partie");
        $req->execute();
        $lesParties = $req->fetchAll(PDO::FETCH_CLASS, 'Partie');
        return $lesParties;
    }

    public static function getPartieById(int $id): Partie {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT * FROM partie WHERE id_partie = :id");
        $req->bindParam(':id', $id);
        $req->execute();
        $laPartie = $req->fetchObject('Partie');
        return $laPartie;
    }

    public static function getPseudoJoueurById(int $id): string {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT pseudo FROM joueur WHERE id_joueur = :id");
        $req->bindParam(':id', $id);
        $req->execute();
        $lePseudo = $req->fetchColumn();
        return $lePseudo;
    }

    public static function getIdPartieByNom(string $nom): int {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT id_partie FROM partie WHERE nom = :nom");
        $req->bindParam(':nom', $nom);
        $req->execute();
        $idPartie = $req->fetchColumn();
        return $idPartie;
    }

    public static function addJoueurDansPartie(int $idPartie, int $idJoueur): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("INSERT INTO joueur_dans_partie(id_joueur, id_partie) VALUES(:id_joueur, :id_partie)");
        $req->bindParam(':id_joueur', $idJoueur);
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
        $_SESSION['id_partie'] = $idPartie;
    }

    public static function checkJoueurDansPartie(int $idPartie, int $idJoueur): bool {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT * FROM joueur_dans_partie WHERE id_joueur = :id_joueur AND id_partie = :id_partie");
        $req->bindParam(':id_joueur', $idJoueur);
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
        $result = $req->fetch();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public static function getJoueurInPartie(int $idPartie): array {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT jdp.id_joueur, j.pseudo, j.mail, j.nbVictoire, j.nbDefaite, j.nbNul FROM joueur_dans_partie jdp INNER JOIN joueur j ON jdp.id_joueur = j.id_joueur WHERE jdp.id_partie = :id_partie");
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
        $lesJoueurs = $req->fetchAll();
        return $lesJoueurs;
    }

    public static function getIdJoueurInPartie(int $idPartie): array {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT id_joueur FROM joueur_dans_partie WHERE id_partie = :id_partie");
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
        $lesIdJoueurs = $req->fetchAll(PDO::FETCH_COLUMN);
        return $lesIdJoueurs;
    }

    public static function countNbJoueurInPartie(int $idPartie): int {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT COUNT(id_joueur) FROM joueur_dans_partie WHERE id_partie = :id_partie");
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
        $nbJoueur = $req->fetchColumn();
        return $nbJoueur;
    }

    public static function getChoixJoueurInPartie(int $idPartie): array {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT j.pseudo, j.id_joueur, jdp.pile_ou_face FROM joueur_dans_partie jdp INNER JOIN joueur j ON jdp.id_joueur = j.id_joueur WHERE jdp.id_partie = :id_partie");
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
        $lesChoix = $req->fetchAll();
        return $lesChoix;
    }

    public static function getJoueurQuiJoue(int $idPartie): array {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT jdp.tour, jdp.id_joueur, j.pseudo, j.id_joueur FROM joueur_dans_partie jdp INNER JOIN joueur j ON jdp.id_joueur = j.id_joueur WHERE jdp.id_partie = :id_partie AND jdp.tour = jdp.id_joueur");
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
        $idJoueurQuiJoue = $req->fetchAll();
        return $idJoueurQuiJoue;
    }

    public static function getCaseCocher(int $idPartie, int $idJoueur): array {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT jdp.id_joueur, jdp.case_cocher, jdp.signe FROM joueur_dans_partie jdp WHERE jdp.id_partie = :id_partie AND jdp.id_joueur = :id_joueur");
        $req->bindParam(':id_partie', $idPartie);
        $req->bindParam(':id_joueur', $idJoueur);
        $req->execute();
        $lesCasesCocher = $req->fetchAll();
        return $lesCasesCocher;
    }

    public static function getSigne(int $idPartie, int $idJoueur): array {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT jdp.signe FROM joueur_dans_partie jdp WHERE jdp.id_partie = :id_partie AND jdp.id_joueur = :id_joueur");
        $req->bindParam(':id_partie', $idPartie);
        $req->bindParam(':id_joueur', $idJoueur);
        $req->execute();
        $lesSignes = $req->fetchAll(PDO::FETCH_COLUMN);
        return $lesSignes;
    }

    public static function getJoueurGagnant(int $idPartie): array {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT jdp.gagnant, jdp.id_joueur, j.pseudo, j.nbVictoire, j.nbDefaite, j.nbNul FROM joueur_dans_partie jdp INNER JOIN joueur j ON jdp.id_joueur = j.id_joueur WHERE jdp.id_partie = :id_partie AND jdp.gagnant = jdp.id_joueur");
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
        $lesJoueurs = $req->fetchAll();
        return $lesJoueurs;
    }

    public static function setJoueurGagnant(int $idPartie, int $idJoueur) {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("UPDATE joueur_dans_partie SET gagnant = :id_joueur WHERE id_partie = :id_partie AND id_joueur = :id_joueur");
        $req->bindParam(':id_joueur', $idJoueur);
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
    }

    public static function add(Partie $partie) {
        $sql = "INSERT INTO partie(nom, id_joueur, nbJoueur, nbManche, etat) VALUES(:nom, :id_joueur, :nbJoueur, :nbManche, :etat)";
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare($sql);
        $nom = $partie->getNom();
        $id_joueur = $partie->getIdJoueur();
        $nbJoueur = $partie->getNbJoueur();
        $nbManche = $partie->getNbManche();
        $etat = $partie->getEtat();
        $req->bindParam(':nom', $nom);
        $req->bindParam(':id_joueur', $id_joueur);
        $req->bindParam(':nbJoueur', $nbJoueur);
        $req->bindParam(':nbManche', $nbManche);
        $req->bindParam(':etat', $etat);

        $req->execute();
        $partie->setId($pdo->lastInsertId());
        return $req;
    }

    public static function updateNbJoueur($idPartie, $nbJoueur): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("UPDATE partie SET nbJoueur = :nbJoueur WHERE id_partie = :id");
        $req->bindParam(':nbJoueur', $nbJoueur);
        $req->bindParam(':id', $idPartie);
        $req->execute();
    }

    public static function updateEtat($idPartie, $etat): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("UPDATE partie SET etat = :etat WHERE id_partie = :id");
        $req->bindParam(':etat', $etat);
        $req->bindParam(':id', $idPartie);
        $req->execute();
    }

    public static function updateJoueurQuiJoue($idPartie, $tour): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("UPDATE joueur_dans_partie SET tour = :tour WHERE id_partie = :id");
        $req->bindParam(':tour', $tour);
        $req->bindParam(':id', $idPartie);
        $req->execute();
    }

    public static function updateCaseCocher($idPartie, $idJoueur, $caseCocher): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("SELECT case_cocher FROM joueur_dans_partie WHERE id_partie = :id_partie AND id_joueur = :id_joueur");
        $req->bindParam(':id_partie', $idPartie);
        $req->bindParam(':id_joueur', $idJoueur);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        $currentJson = json_decode($result['case_cocher'], true);
        $currentJson[] = $caseCocher;
        $jsonString = json_encode($currentJson);
        $updateReq = $pdo->prepare("UPDATE joueur_dans_partie SET case_cocher = :case_cocher WHERE id_partie = :id_partie AND id_joueur = :id_joueur");
        $updateReq->bindParam(':case_cocher', $jsonString);
        $updateReq->bindParam(':id_partie', $idPartie);
        $updateReq->bindParam(':id_joueur', $idJoueur);
        $updateReq->execute();
    }
    

    public static function updateChoix($idPartie, $idJoueur, $choix, $signe): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("UPDATE joueur_dans_partie SET pile_ou_face = :choix, signe = :signe WHERE id_partie = :id_partie AND id_joueur = :id_joueur");
        $req->bindParam(':choix', $choix);
        $req->bindParam(':id_partie', $idPartie);
        $req->bindParam(':id_joueur', $idJoueur);
        $req->bindParam(':signe', $signe);
        $req->execute();
    }

    public static function update(Partie $partie): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("UPDATE partie SET nom = :nom, id_joueur = :id_joueur, nbJoueur = :nbJoueur, nbManche = :nbManche, etat = :etat WHERE id_partie = :id");
        $req->bindParam(':nom', $partie->getNom());
        $req->bindParam(':id_joueur', $partie->getIdJoueur());
        $req->bindParam(':nbJoueur', $partie->getNbJoueur());
        $req->bindParam(':nbManche', $partie->getNbManche());
        $req->bindParam(':etat', $partie->getEtat());
        $req->bindParam(':id', $partie->getId());
        $req->execute();
    }

    public static function deleteJoueurDansPartie(int $idPartie, int $idJoueur): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("DELETE FROM joueur_dans_partie WHERE id_joueur = :id_joueur AND id_partie = :id_partie");
        $req->bindParam(':id_joueur', $idJoueur);
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
        Partie::updateNbJoueur($idPartie, Partie::countNbJoueurInPartie($idPartie));
    }

    public static function deleteAllJoueurDansPartie(int $idPartie): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("DELETE FROM joueur_dans_partie WHERE id_partie = :id_partie");
        $req->bindParam(':id_partie', $idPartie);
        $req->execute();
    }

    public static function delete(int $id): void {
        $pdo = monPDO::getInstance();
        $req = $pdo->prepare("DELETE FROM partie WHERE id_partie = :id");
        $req->bindParam(':id', $id);
        $req->execute();
    }
}