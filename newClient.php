<?php
include './config/db.php'; // Inclure le fichier de connexion


// Vérifiez si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'] ?? null;
    $email = $_POST['email'] ?? null;
    $telephone = $_POST['telephone'] ?? null;
    $adresse = $_POST['adresse'] ?? null;
    $entreprise = $_POST['entreprise'] ?? null;

    // Vérifiez que les champs ne sont pas vides
    if (empty($nom) || empty($email) || empty($telephone) || empty($adresse)) {
        die("Tous les champs sauf l'entreprise sont requis.");
    }

    // Insérer les données dans la base de données
    $sql = "INSERT INTO clients (nom, email, telephone, adresse, entreprise) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$nom, $email, $telephone, $adresse, $entreprise]);
        // Redirection vers la page Client.php après succès
        header('Location: Client.php');
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de l'insertion : " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouveau Client - Système de Gestion</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="./asset/addClient.css">
  <link rel="shortcut icon" href="./img/Image1.png">
  <style>
    h1 {
    color: #f5f6fa;
    margin-bottom: 1.5rem;
}
  </style>
</head>
<body>
  <header>
    <h1><i class="fas fa-user-plus"></i> Nouveau Client</h1>
  </header>

  <nav>
    <a href="/vues/dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
    <a href="/Client.php"><i class="fas fa-users"></i> Clients</a>
    <a href="/Devis.php"><i class="fas fa-file-invoice"></i> Devis</a>
    <a href="/Factures.php"><i class="fas fa-file-invoice-dollar"></i> Factures</a>
    <a href="/Rendez_vous.php"><i class="fas fa-calendar-check"></i> Rendez-vous</a>
  </nav>

  <div class="container">
    <form id="newClientForm" method="POST" action="/newClient.php">
      <div class="form-group">
        <label for="nom">Nom:</label>
        <i class="fas fa-user"></i>
        <input type="text" id="nom" name="nom" required>
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <i class="fas fa-envelope"></i>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="telephone">Téléphone:</label>
        <i class="fas fa-phone"></i>
        <input type="tel" id="telephone" name="telephone" required>
      </div>

      <div class="form-group">
        <label for="adresse">Adresse:</label>
        <i class="fas fa-map-marker-alt"></i>
        <textarea id="adresse" name="adresse" required></textarea>
      </div>

      <div class="form-group">
        <label for="entreprise">Entreprise:</label>
        <i class="fas fa-building"></i>
        <input type="text" id="entreprise" name="entreprise">
      </div>

      <div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer le client</button>
        <a href="/vues/dashboard.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
      </div>
    </form>
  </div>
</body>
</html>
