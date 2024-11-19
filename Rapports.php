<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de Bord Amélioré - Système de Gestion</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="./asset/statistiques.css">
  <link rel="shortcut icon" href="./img/Image1.png">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
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
    <h1><i class="fas fa-chart-bar"></i> Rapports</h1>
  </header>
  <?php
  include 'composant/nav.php';
  ?>

  <div class="container">
    <div class="dashboard-grid">
      <div class="dashboard-card">
        <h3>Statistiques Générales</h3>
        <div class="stat">
          <span>Clients actifs</span>
          <span class="stat-value" id="activeClients">7</span>
        </div>
        <div class="stat">
          <span>Devis en cours</span>
          <span class="stat-value" id="activeQuotes">4</span>
        </div>
        <div class="stat">
          <span>Factures impayées</span>
          <span class="stat-value" id="unpaidInvoices">3</span>
        </div>
        <div class="stat">
          <span>Rendez-vous à venir</span>
          <span class="stat-value" id="upcomingAppointments">3</span>
        </div>
      </div>

      <div class="dashboard-card">
        <h3>Chiffre d'Affaires Mensuel</h3>
        <div class="chart-container">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>

      <div class="dashboard-card">
        <h3>Suivi des Factures</h3>
        <div class="document-list">
          <div class="document-item">
            <span>Fact_001</span>
            <span class="document-status status-paid">Payée</span>
          </div>
          <div class="document-item">
            <span>Fact_002</span>
            <span class="document-status status-pending">En attente</span>
          </div>
          <div class="document-item">
            <span>Fact_003</span>
            <span class="document-status status-overdue">En retard</span>
          </div>
          <div class="document-item">
            <span>Fact_004</span>
            <span class="document-status status-pending">En attente</span>
          </div>
          <div class="document-item">
            <span>Fact_005</span>
            <span class="document-status status-paid">Payée</span>
          </div>
        </div>
      </div>

      <div class="dashboard-card">
        <h3>Suivi des Devis</h3>
        <div class="document-list">
          <div class="document-item">
            <span>Devis #Dev_001</span>
            <span class="document-status status-pending">En attente</span>
          </div>
          <div class="document-item">
            <span>Devis #Dev_002</span>
            <span class="document-status status-paid">Accepté</span>
          </div>
          <div class="document-item">
            <span>Devis #Dev_003</span>
            <span class="document-status status-overdue">Expiré</span>
          </div>
          <div class="document-item">
            <span>Devis #Dev_004</span>
            <span class="document-status status-pending">En attente</span>
          </div>
          <div class="document-item">
            <span>Devis #Dev_005</span>
            <span class="document-status status-paid">Accepté</span>
          </div>
        </div>
      </div>

      <div class="dashboard-card">
        <h3>Suivi des Paiements</h3>
        <div class="document-list">
          <div class="document-item">
            <span>Paiement #Paie_001</span>
            <span class="document-status status-paid">Reçu</span>
          </div>
          <div class="document-item">
            <span>Paiement #Paie_002</span>
            <span class="document-status status-pending">En cours</span>
          </div>
          <div class="document-item">
            <span>Paiement #Pai_003</span>
            <span class="document-status status-overdue">Retard</span>
          </div>
          <div class="document-item">
            <span>Paiement #Paie_004</span>
            <span class="document-status status-paid">Reçu</span>
          </div>
          <div class="document-item">
            <span>Paiement #Paie_005</span>
            <span class="document-status status-pending">En cours</span>
          </div>
        </div>
      </div>

      <div class="dashboard-card">
        <h3>Emails Envoyés</h3>
        <div class="email-list">
          <div class="email-item">
            <div class="email-subject">Rappel de paiement - Facture </div>
            <div class="email-date">Envoyé le: 15/05/2024</div>
          </div>
          <div class="email-item">
            <div class="email-subject">Confirmation de rendez-vous - Client</div>
            <div class="email-date">Envoyé le: 14/05/2024</div>
          </div>
          <div class="email-item">
            <div class="email-subject">Nouveau devis </div>
            <div class="email-date">Envoyé le: 13/05/2024</div>
          </div>
          <div class="email-item">
            <div class="email-subject">Remerciement pour paiement - Facture </div>
            <div class="email-date">Envoyé le: 12/05/2024</div>
          </div>
          <div class="email-item">
            <div class="email-subject">Offre promotionnelle - Mai 2024</div>
            <div class="email-date">Envoyé le: 11/05/2024</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <footer>
    <p>Systeme de Gestion Commerciale et Facturation, Copyright Ludo_Consulting &copy; 2024</p>
  </footer>
  
  <script>
    // Graphique du chiffre d'affaires mensuel
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'juil', 'Aout', 'Sept', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Chiffre d\'affaires (XAF)',
          data: [20000, 30000, 25000, 40000, 32000, 50000, 60000, 55000, 45000, 62000, 70000, 75000],
          borderColor: 'rgb(75, 192, 192)',
          tension: 0.1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    });

    // Fonction pour mettre à jour les statistiques
    function updateStats() {
      document.getElementById('activeClients').textContent = Math.floor(Math.random() * 6) + 1;
      document.getElementById('activeQuotes').textContent = Math.floor(Math.random() * 10) + 2;
      document.getElementById('unpaidInvoices').textContent = Math.floor(Math.random() * 5) + 1;
      document.getElementById('upcomingAppointments').textContent = Math.floor(Math.random() * 6) + 1;
    }

    // Mettre à jour les statistiques toutes les 5 secondes
    setInterval(updateStats, 5000);

    // Appel initial pour afficher les statistiques
    updateStats();
  </script>
</body>
</html>
