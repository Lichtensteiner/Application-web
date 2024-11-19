
<style>
  .badge {
    position: relative;
    top: -10px; /* Ajustez selon la hauteur de votre icône */
    left: 5px;  /* Ajustez selon l'espace désiré */
    font-size: 12px; /* Taille du texte */
    padding: 5px 10px; /* Marges internes pour un look arrondi */
    border-radius: 50%; /* Arrondi du badge */
    color: white; /* Couleur du texte */
    background-color: red; /* Couleur du badge */
    display: none; /* Masqué par défaut */
}

</style>

<nav>
    <div class="nav-links">
      <a href="../vues/dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
      <a href="/Client.php"><i class="fas fa-users"></i> Clients</a>
      <a href="/Devis.php"><i class="fas fa-file-invoice"></i> Devis</a>
      <a href="/Factures.php"><i class="fas fa-file-invoice-dollar"></i> Factures</a>
      <a href="/Rapports.php"><i class="fas fa-chart-bar"></i> Rapports</a>
      <a href="/Rendez_vous.php"><i class="fas fa-calendar-check"></i> Rendez-vous</a>
      <a href="/alertes.php">
            <i class="fas fa-bell"></i> Alertes 
            <span id="notifications-badge" class="badge bg-danger">0</span>
      </a>
      <a href="/Parametre.php"><i class="fas fa-cog"></i> Paramètres</a>
      <a href="/users.php"><i class="fas fa-users-cog"></i> Utilisateurs</a>
    </div>
    <div class="user-actions">
      <div class="user-info" id="userInfo">
        <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
      </div>
      <button id="logoutButton" class="logout-button">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
      </button>
      <script>
           document.getElementById("logoutButton").addEventListener("click", () => {
      if (confirm("Êtes-vous sûr de vouloir vous déconnecter ?")) {
        // Redirection vers la page de connexion
        window.location.href = "/index.php";
      }
    });

       

    // Gestion de la déconnexion
    document.getElementById('logoutButton').addEventListener('click', () => {
      // Logique de déconnexion
      alert('Déconnexion effectuée');
    });
    </script>
    </div>
  </nav>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateNotifications() {
        $.ajax({
            url: '/get_notifications.php',
            method: 'GET',
            success: function (data) {
                const result = JSON.parse(data);
                const badge = document.getElementById('notifications-badge');
                badge.textContent = result.total;
                badge.style.display = result.total > 0 ? 'inline-block' : 'none';
            },
            error: function () {
                console.error("Erreur lors de la récupération des notifications.");
            }
        });
    }

    // Mettre à jour toutes les 30 secondes
    setInterval(updateNotifications, 30000);
    updateNotifications(); // Mise à jour immédiate au chargement
</script>