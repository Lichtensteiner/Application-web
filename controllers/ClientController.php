<?php
require_once 'models/Client.php';

class ClientController {

    public function ajouterClient() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $telephone = $_POST['telephone'];
            $adresse = $_POST['adresse'];
            $entreprise = $_POST['entreprise'];

            // Créer un nouvel objet Client
            $client = new Client();
            $client->setNom($nom);
            $client->setEmail($email);
            $client->setTelephone($telephone);
            $client->setAdresse($adresse);
            $client->setEntreprise($entreprise);

            // Appeler la méthode pour enregistrer le client dans la base de données
            if ($client->enregistrer()) {
                // Redirection ou message de succès
                header('Location: clients.php');
            } else {
                // Gestion des erreurs
                echo "Erreur lors de l'ajout du client.";
            }
        }
    }
}
?>
