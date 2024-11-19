<?php
include './config/db.php'; // Inclure le fichier de connexion

// Vérifiez si un ID est passé en paramètre
if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    // Récupérer les informations du client depuis la base de données
    $sql = "SELECT * FROM clients WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$clientId]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        die("Client introuvable.");
    }
} else {
    die("ID de client manquant.");
}

// Vérifiez si le formulaire de mise à jour est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $telephone = $_POST['phone'] ?? null;
    $adresse = $_POST['adresse'] ?? null;
    $entreprise = $_POST['company'] ?? null;

    // Vérifiez que les champs ne sont pas vides
    if (empty($nom) || empty($email) || empty($telephone) || empty($adresse) || empty($entreprise)) {
        die("Tous les champs sont requis.");
    }

    // Mettre à jour les informations du client dans la base de données
    $sql = "UPDATE clients SET nom = ?, email = ?, telephone = ?, adresse = ?, entreprise = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([$nom, $email, $telephone, $adresse, $entreprise, $clientId]);
        // Redirection vers la page Client.php après succès
        header('Location: Client.php');
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Client - Système de Facturation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="./img/Image1.png">
    <link rel="stylesheet" href="./asset/Client.css">
</head>
<body>
    <header>
        <h1><i class="fas fa-user-edit"></i> Modifier le Client</h1>
    </header>
    
    <div class="container">
        <div class="form-container">
            <h2><i class="fas fa-edit"></i> Modifier les informations du client</h2>
            <form method="POST" action="">
                <label for="name"><i class="fas fa-user"></i> Nom</label>
                <input type="text" name="name" value="<?= htmlspecialchars($client['nom']) ?>" required>

                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>

                <label for="phone"><i class="fas fa-phone"></i> Téléphone</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($client['telephone']) ?>" required>

                <label for="company"><i class="fas fa-building"></i> Entreprise</label>
                <input type="text" name="company" value="<?= htmlspecialchars($client['entreprise']) ?>" required>

                <label for="adresse"><i class="fas fa-map-marker-alt"></i> Adresse</label>
                <input type="text" name="adresse" value="<?= htmlspecialchars($client['adresse']) ?>" required>

                <button type="submit"><i class="fas fa-save"></i> Sauvegarder</button>
            </form>
        </div>
    </div>

    <script>
        // Ajoutez une confirmation avant la soumission du formulaire
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir modifier les informations du client ?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
