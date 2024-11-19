<?php
// Connexion à la base de données
function getDbConnection() {
    $host = 'localhost';  // Remplacez par vos informations de connexion
    $dbname = 'facturations';  // Nom de votre base de données
    $username = 'root';  // Nom d'utilisateur MySQL
    $password = '';  // Mot de passe MySQL

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

// Récupérer tous les clients
function getAllClients() {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT id, name FROM clients");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer tous les devis
function getAllDevis() {
    $pdo = getDbConnection();
    $stmt = $pdo->prepare("SELECT d.id, c.name as client, d.date, d.montant_total 
                           FROM devis d
                           JOIN clients c ON d.client_id = c.id");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Créer un devis
function createDevis($clientId, $quoteDate, $validityDate, $items, $notes, $tva) {
    $pdo = getDbConnection();
    
    // Calcul du total HT et TTC
    $totalHT = calculateTotalHT($items);  // Fonction que vous devez implémenter pour calculer le total
    $totalTTC = $totalHT + ($totalHT * $tva / 100);

    try {
        $pdo->beginTransaction();

        // Insertion dans la table devis
        $stmt = $pdo->prepare("INSERT INTO devis (client_id, date, date_validite, montant_total, notes, tva)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$clientId, $quoteDate, $validityDate, $totalTTC, $notes, $tva]);

        // Récupérer l'ID du devis nouvellement créé
        $devisId = $pdo->lastInsertId();

        // Insertion des articles du devis
        foreach ($items as $item) {
            $stmtItem = $pdo->prepare("INSERT INTO devis_items (devis_id, description, quantite, prix_unitaire)
                                       VALUES (?, ?, ?, ?)");
            $stmtItem->execute([$devisId, $item['description'], $item['quantite'], $item['prix']]);
        }

        // Valider la transaction
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

// Calculer le total HT
function calculateTotalHT($items) {
    $totalHT = 0;
    foreach ($items as $item) {
        $totalHT += $item['quantite'] * $item['prix'];
    }
    return $totalHT;
}
