<?php
session_start();

// Connexion à la base de données
$host = 'localhost'; // ou votre hôte
$user = 'root'; // votre nom d'utilisateur
$password = ''; // votre mot de passe
$database = 'facturations'; // nom de votre base de données

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Erreur de connexion : ' . $conn->connect_error);
}

// Récupérer la liste des clients
$sqlClients = "SELECT id, nom FROM clients";
$resultClients = $conn->query($sqlClients);
$clients = [];
if ($resultClients->num_rows > 0) {
    while ($row = $resultClients->fetch_assoc()) {
        $clients[] = $row;
    }
}

// Récupérer la liste des devis avec les articles associés
$sqlDevis = "SELECT d.id, c.nom AS client, d.date_creation, d.date_validite, d.notes, d.statut_devis, d.tva, d.total_ht, d.total_ttc,
             GROUP_CONCAT(a.nom SEPARATOR ', ') AS articles
             FROM devis d
             JOIN clients c ON d.client_id = c.id
             LEFT JOIN articles a ON d.id = a.devis_id
             GROUP BY d.id";

$resultDevis = $conn->query($sqlDevis);
$devis = [];
if ($resultDevis->num_rows > 0) {
    while ($row = $resultDevis->fetch_assoc()) {
        $devis[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Devis - Système de Facturation</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="shortcut icon" href="./img/Image1.png">
  <link rel="stylesheet" href="./asset/devis.css">
</head>
<style>
  .button-container {
    display: flex;
    gap: 10px;
}

.action-button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.action-button:hover {
    background-color: #0056b3;
}

.edit-button {
    background-color: #28a745;
}

.edit-button:hover {
    background-color: #218838;
}

.delete-button {
    background-color: #dc3545;
}

.delete-button:hover {
    background-color: #c82333;
}

.convert-button {
    background-color: #17a2b8;
}

.convert-button:hover {
    background-color: #138496;
}
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
    <h1><i class="fas fa-file-invoice"></i> Gestion des Devis</h1>
  </header>
  <?php
  include 'composant/nav.php';
  ?>
 <div>
      <table id="DevisTable">
          <div class="container mx-auto mt-4">
          <input type="text" id="searchBar" placeholder="Rechercher un devis...">
            <thead>
              <tr>
                <th><i class="fas fa-hashtag"></i> N° Devis</th>
                <th><i class="fas fa-user"></i> Client</th>
                <th><i class="fas fa-calendar-alt"></i> Date creation</th>
                <th><i class="fas fa-calendar-check"></i> Date de validité</th>
                <th><i class="fas fa-money-bill-wave"></i> Montant HT</th>
                <th><i class="fas fa-percent"></i> TVA</th>
                <th><i class="fas fa-money-bill-wave"></i> Montant TTC</th>
                <th><i class="fas fa-clipboard-list"></i> Statuts devis</th>
                <th><i class="fas fa-cogs"></i> Actions</th>
              </tr>
            </thead>
            <tbody>
  <?php if (!empty($devis)): ?>
    <?php foreach ($devis as $devi): ?>
      <tr>
        <td><?= htmlspecialchars($devi['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($devi['client'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($devi['date_creation'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($devi['date_validite'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($devi['total_ht'] ?? '', ENT_QUOTES, 'UTF-8') ?> XAF</td>
        <td><?= htmlspecialchars($devi['tva'] ?? '', ENT_QUOTES, 'UTF-8') ?> %</td>
        <td><?= htmlspecialchars($devi['total_ttc'] ?? '', ENT_QUOTES, 'UTF-8') ?> XAF</td>
        <td><?= htmlspecialchars($devi['statut_devis'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
        <td>
          <div class="button-container">
              <a href="edit_devis.php?id=<?= htmlspecialchars($devi['id'], ENT_QUOTES, 'UTF-8') ?>" class="action-button edit-button">
                  <i class="fas fa-edit"></i> Édit
              </a>
              <a href="delete_devis.php?id=<?= htmlspecialchars($devi['id'], ENT_QUOTES, 'UTF-8') ?>" class="action-button delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce devis ?');">
                  <i class="fas fa-trash-alt"></i> Delete
              </a>
              <a href="views_devis.php?id=<?= htmlspecialchars($devi['id'], ENT_QUOTES, 'UTF-8') ?>" class="action-button convert-button">
                  <i class="fas fa-eye"></i> View
              </a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="9">Aucun devis enregistré</td>
    </tr>
  <?php endif; ?>
</tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <footer>
        <p>Systeme de Gestion Commerciale et Facturation, Copyright Ludo_Consulting &copy; 2024</p>
    </footer>
</body>
<script>

      // Fonction de recherche
      document.getElementById("searchBar").addEventListener("input", function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const rows = document.querySelectorAll("#DevisTable tbody tr");
      rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchTerm));
        row.style.display = match ? "" : "none";
      });
    });

    // Afficher les alertes au chargement de la page
    displayAlerts();
</script>
</html>
