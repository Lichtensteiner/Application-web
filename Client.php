<?php
session_start();
include './config/db.php'; // Inclure le fichier de connexion


// Récupérer tous les clients de la base de données
$clients = [];
$sql = "SELECT * FROM clients";

try {
    $stmt = $pdo->query($sql);
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des clients : " . $e->getMessage());
}

// Vérifiez si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $telephone = $_POST['phone'] ?? null;
    $adresse = $_POST['adresse'] ?? null; // Utilisez le bon nom ici
    $entreprise = $_POST['company'] ?? null;

    // Vérifiez que les champs ne sont pas vides
    if (empty($nom) || empty($email) || empty($telephone) || empty($adresse) || empty($entreprise)) {
        die("Tous les champs sont requis.");
    }

    // Insérer les données dans la base de données
    $sql = "INSERT INTO clients (nom, email, telephone, adresse, entreprise) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$nom, $email, $telephone, $adresse, $entreprise]);
        // Redirection vers la page Client.html après succès
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
  <title>Gestion des Clients - Système de Facturation</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="shortcut icon" href="./img/Image1.png">
  
  <link rel="stylesheet" href="./asset/bouton.css">
  <link rel="stylesheet" href="./asset/CLient.css">
</head>
<style>
   #searchBar {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

    
footer {
  padding: 20px;
  margin-top: 20px;
  color: #ffffff;
  background-color: #3498db;
  text-align: center;
}

</style>
<body>
  <header>
    <h1><i class="fas fa-users"></i> Gestion des Clients</h1>
  </header>
  <?php
  include 'composant/nav.php';
  ?>
        <div>
          <table id="clientsTable">
          <div class="container mx-auto mt-4">
          <input type="text" id="searchBar" placeholder="Rechercher un client...">
            <thead>
              <tr>
                <th><i class="fas fa-user"></i> Nom</th>
                <th><i class="fas fa-envelope"></i> Email</th>
                <th><i class="fas fa-phone"></i> Téléphone</th>
                <th><i class="fas fa-building"></i> Entreprise</th>
                <th><i class="fas fa-map-marker-alt"></i> Adresse</th> <!-- Nouvelle colonne pour l'adresse -->
                <th><i class="fas fa-cogs"></i> Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($clients as $client): ?>
                <tr>
                  <td><i class="fas fa-user"></i> <?= htmlspecialchars($client['nom']) ?></td>
                  <td><i class="fas fa-envelope"></i> <?= htmlspecialchars($client['email']) ?></td>
                  <td><i class="fas fa-phone"></i> <?= htmlspecialchars($client['telephone']) ?></td>
                  <td><i class="fas fa-building"></i> <?= htmlspecialchars($client['entreprise']) ?></td>
                  <td><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($client['adresse']) ?></td> <!-- Affichage de l'adresse -->
                  <td class="action-buttons">

                    <!-- Bouton pour voir les détails du client -->
                      <a href="./views_clients.php?id=<?= $client['id'] ?>" class="views-button"><i class="fas fa-eye"></i> views</a>
                      
                      <!-- Bouton pour modifier le client -->
                      <a href="./edit_clients.php?id=<?= $client['id'] ?>" class="edit-button"><i class="fas fa-edit"></i> edit</a>
                      
                      <!-- Bouton pour supprimer le client -->
                      <button class="delete-button" onclick="confirmDelete(<?= $client['id'] ?>)"><i class="fas fa-trash-alt"></i>delete</button>
                    
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <footer>
        <p>Systeme de Gestion Commerciale et Facturation, Copyright Ludo_Consulting &copy; 2024</p>
    </footer>

  <script>

document.getElementById("searchBar").addEventListener("input", function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll("#clientsTable tbody tr");
      rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchTerm));
        row.style.display = match ? "" : "none";
      });
    });
    function viewClient(clientId) {
      // Redirection vers une page avec les détails du client, incluant ses factures, devis, et suivi de paiement
      window.location.href = 'client_details.php?id=' + clientId;
    }
    
    // Fonction pour confirmer la suppression d'un client
    function confirmDelete(clientId) {
      if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
        window.location.href = 'delete_client.php?id=' + clientId; // Remplacez par le chemin de votre script de suppression
      }
    }
    
  </script>
</body>
</html>
