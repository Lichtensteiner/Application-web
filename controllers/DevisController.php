<?php
// Inclure le modèle pour accéder aux fonctions de base de données
require_once '../models/Devis';

// Fonction pour afficher la page de création de devis
function newDevis() {
    // Récupérer tous les clients pour remplir la liste déroulante
    $clients = getAllClients();

    // Charger la vue pour la création de devis avec la liste des clients
    include './Devis.php';
}

// Fonction pour gérer la création d'un devis
function createDevisController() {
    // Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données du formulaire
        $clientId = $_POST['client_id'];
        $quoteDate = $_POST['quote_date'];
        $validityDate = $_POST['validity_date'];
        $tva = $_POST['tva'];
        $notes = $_POST['notes'];

        // Articles envoyés dans un tableau (description, quantité, prix)
        $items = [];
        foreach ($_POST['articles'] as $article) {
            $items[] = [
                'description' => $article['description'],
                'quantite' => $article['quantite'],
                'prix' => $article['prix'],
            ];
        }

        // Appeler la fonction du modèle pour créer un devis
        $success = createDevis($clientId, $quoteDate, $validityDate, $items, $notes, $tva);

        // Vérifier si la création a réussi et rediriger ou afficher un message
        if ($success) {
            header('Location: Devis.php?success=true');
            exit;
        } else {
            // Gestion des erreurs
            echo "Erreur lors de la création du devis.";
        }
    }
}
?>
