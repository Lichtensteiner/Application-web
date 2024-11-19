<?php
// Démarre la session
session_start();

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isset($_SESSION['user_name'])) {
    header("Location: ../login.php");
    exit();
}

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "facturations");

if ($mysqli->connect_error) {
    die("Connexion échouée : " . $mysqli->connect_error);
}

// -------------------- SECTION RÉPARTITION DES CLIENTS --------------------
$sqlRepartitionClients = "
    SELECT c.nom, COUNT(DISTINCT f.id) AS nb_factures, COUNT(DISTINCT d.id) AS nb_devis
    FROM clients c
    LEFT JOIN factures f ON c.id = f.client_id
    LEFT JOIN devis d ON c.id = d.client_id
    GROUP BY c.id
    HAVING nb_factures > 2 AND nb_devis > 0";
$resultRepartitionClients = $mysqli->query($sqlRepartitionClients);

$repartitionClients = [];
if ($resultRepartitionClients && $resultRepartitionClients->num_rows > 0) {
    while ($row = $resultRepartitionClients->fetch_assoc()) {
        $repartitionClients[] = $row;
    }
}

// -------------------- SECTION ÉVOLUTION DU CA --------------------
$sqlEvolutionCA = "
    SELECT 'facture' AS type, total_ttc, total_ht
    FROM factures
    UNION ALL
    SELECT 'devis' AS type, total_ttc, total_ht
    FROM devis";
$resultEvolutionCA = $mysqli->query($sqlEvolutionCA);

$totalTTC = 0;
$totalHT = 0;
if ($resultEvolutionCA && $resultEvolutionCA->num_rows > 0) {
    while ($row = $resultEvolutionCA->fetch_assoc()) {
        $totalTTC += $row['total_ttc'];
        $totalHT += $row['total_ht'];
    }
}

// -------------------- SECTION ÉVÉNEMENTS EN COURS --------------------
$sqlEvenementsEnCours = "
    SELECT numero_facture, date_facturation, statut_facture, total_ttc
    FROM factures
    WHERE statut_facture IN ('en cours', 'en attente')";
$resultEvenementsEnCours = $mysqli->query($sqlEvenementsEnCours);

$evenementsEnCours = [];
if ($resultEvenementsEnCours && $resultEvenementsEnCours->num_rows > 0) {
    while ($row = $resultEvenementsEnCours->fetch_assoc()) {
        $evenementsEnCours[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="shortcut icon" href="/img/Image1.png">
  <link rel="stylesheet" href="/asset/dashboard.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>Tableau de Bord - Système de Gestion</title>
  <style>
    
footer {
  padding: 20px;
  margin-top: 20px;
  color: #ffffff;
  background-color: #3498db;
  text-align: center;
}
  </style>
</head>
<body>
  <header>
    <h1><i class="fas fa-tachometer-alt"></i> Tableau de Bord</h1>
  </header>
  <?php
  include '../composant/nav.php';
  ?>

  <div class="container">
    <div class="dashboard">
      <div class="card">
        <h3><i class="fas fa-users"></i> Clients</h3>
        <div class="stat" id="clientCount">4</div>
        <p>Total des clients</p>
      </div>
      <div class="card">
        <h3><i class="fas fa-file-invoice-dollar"></i> Chiffre d'affaires</h3>
        <div class="stat" id="revenue">550.000 XAF</div>
        <p>Ce mois-ci</p>
      </div>
      <div class="card">
        <h3><i class="fas fa-tasks"></i> Evenements en cours</h3>
        <div class="stat" id="ongoingProjects">5</div>
        <p>Clients actifs</p>
      </div>
      <div class="card">
        <h3><i class="fas fa-calendar-check"></i> Rendez-vous à venir</h3>
        <div class="stat" id="upcomingAppointments">2</div>
        <p>Cette semaine</p>
      </div>
    </div>

    <div class="dashboard">
      <div class="card">
        <h3><i class="fas fa-chart-line"></i> Évolution du CA</h3>
        <div class="chart-container">
          <canvas id="revenueChart">350.000</canvas>
        </div>
      </div>
      <div class="card">
        <h3><i class="fas fa-chart-pie"></i> Répartition des clients</h3>
        <div class="chart-container">
          <canvas id="clientDistributionChart"></canvas>
        </div>
      </div>
    </div>

    <div class="recent-activity">
      <h3><i class="fas fa-history"></i> Activité récente</h3>
      <div id="activityList">
      <ul class="mt-4 space-y-2">
                <li class="flex items-center space-x-2">
                    <i class="fas fa-file-invoice text-blue-600"></i>
                    <span>Facture Fact_001 créée pour 50.000 XAF</span>
                </li>
                <li class="flex items-center space-x-2">
                    <i class="fas fa-file-alt text-blue-600"></i>
                    <span>Devis Dev_001 approuvé pour 100.000 XAF</span>
                </li>
                <li class="flex items-center space-x-2">
                    <i class="fas fa-user-plus text-blue-600"></i>
                    <span>Nouveau client ajouté: Ossima Stael</span>
                </li>
                <li class="flex items-center space-x-2">
                    <i class="fas fa-calendar-check text-blue-600"></i>
                    <span>Rendez-vous avec Ludovic Mve Zogo confirmé</span>
                </li>
            </ul>
      </div>
    </div>

    <div class="quick-actions">
      <a href="/newClient.php" class="action-button">
        <i class="fas fa-user-plus"></i>
        Nouveau client
      </a>
      <a href="/newDevis.php" class="action-button">
        <i class="fas fa-file-invoice"></i>
        Nouveau devis
      </a>
      <a href="/newFactures.php" class="action-button">
        <i class="fas fa-file-invoice-dollar"></i>
        Nouvelle facture
      </a>
      <a href="/newRdv.php" class="action-button">
        <i class="fas fa-calendar-plus"></i>
        Nouveau rendez-vous
      </a>
    </div>
  </div>
  <footer>
        <p>Systeme de Gestion Commerciale et Facturation, Copyright Ludo_Consulting &copy; 2024</p>
    </footer>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   const caCtx = document.getElementById('caChart').getContext('2d');
        const caChart = new Chart(caCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Chiffre d\'affaires',
                    data: [120000, 150000, 180000, 200000, 220000, 250000, 270000, 300000, 320000, 350000, 370000, 400000],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const clientCtx = document.getElementById('clientChart').getContext('2d');
        const clientChart = new Chart(clientCtx, {
            type: 'pie',
            data: {
                labels: ['SEEG Gabon', 'BGFI Banque', 'GabonTelecom', 'Ludo_Consulting'],
                datasets: [{
                    label: 'Répartition des clients',
                    data: [30, 20, 25, 25],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });

     
</script>
</body>
</html>
