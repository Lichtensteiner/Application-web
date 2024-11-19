<?php
class Client {
    private $nom;
    private $email;
    private $telephone;
    private $adresse;
    private $entreprise;
    private $db;

    public function __construct() {
        // Connexion à la base de données (adapter les informations de connexion)
        $this->db = new PDO('mysql:host=localhost;dbname=facturations', 'root', '');
    }

    // Getters et Setters
    public function setNom($nom) { $this->nom = $nom; }
    public function setEmail($email) { $this->email = $email; }
    public function setTelephone($telephone) { $this->telephone = $telephone; }
    public function setAdresse($adresse) { $this->adresse = $adresse; }
    public function setEntreprise($entreprise) { $this->entreprise = $entreprise; }

    // Méthode pour enregistrer le client dans la base de données
    public function enregistrer() {
        // Préparer la requête SQL
        $query = $this->db->prepare('INSERT INTO clients (nom, email, telephone, adresse, entreprise) VALUES (?, ?, ?, ?, ?)');
        
        // Exécuter la requête avec les données du client
        return $query->execute([$this->nom, $this->email, $this->telephone, $this->adresse, $this->entreprise]);
    }
}
?>
