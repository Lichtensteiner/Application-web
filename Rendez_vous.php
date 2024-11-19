<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = ""; // Remplacez par votre mot de passe
$dbname = "facturations"; // Remplacez par le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Traitement du formulaire pour enregistrer un rendez-vous
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'addAppointment') {
    $client_id = $_POST['client'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $note = $_POST['notes'];
    
    $sql = "INSERT INTO rendez_vous (client_id, date, time, location, note) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $client_id, $date, $time, $location,  $note);
    $stmt->execute();
    $stmt->close();

    // Redirection pour éviter la répétition de la requête POST
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

// Récupérer tous les rendez-vous
$appointments = [];
$sql = "SELECT rv.*, c.nom AS client_name FROM rendez_vous rv JOIN clients c ON rv.client_id = c.id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./asset/rdv.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="./img/Image1.png">
    <title>Rendez-vous et Notes Clients - Gestion de Facturation</title>
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
        <h1><i class="fas fa-calendar-alt"></i> Rendez-vous et Notes Clients</h1>
    </header>
    <?php
  include 'composant/nav.php';
  ?>

    <div class="container">
        <div class="content">
            <div class="grid">
                <div>
                    <h2><i class="fas fa-plus-circle"></i> Planifier un rendez-vous</h2>
                    <form id="appointmentForm" method="POST" action="./Rendez_vous.php">
                        <input type="hidden" name="action" value="addAppointment">
                        <label for="client"><i class="fas fa-user"></i> Client</label>
                        <select id="client" name="client" required>
                            <option value="">Sélectionnez un client</option>
                            <!-- Les options des clients seront ajoutées ici -->
                        </select>

                        <label for="date"><i class="fas fa-calendar-day"></i> Date</label>
                        <input type="date" id="date" name="date" required>

                        <label for="time"><i class="fas fa-clock"></i> Heure</label>
                        <input type="time" id="time" name="time" required>

                        <label for="location"><i class="fas fa-map-marker-alt"></i> Lieu</label>
                        <input type="text" id="location" name="location" required>

                        <label for="notes"><i class="fas fa-sticky-note"></i> Notes</label>
                        <textarea id="notes" name="notes" rows="4"></textarea>

                        <button type="submit"><i class="fas fa-save"></i> Planifier le rendez-vous</button>
                    </form>
                </div>

                <div>
                    <h2><i class="fas fa-calendar"></i> Prochains rendez-vous</h2>
                    <table id="appointmentsTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Client</th>
                                <th><i class="fas fa-calendar-day"></i> Date</th>
                                <th><i class="fas fa-clock"></i> Heure</th>
                                <th><i class="fas fa-map-marker-alt"></i> Lieu</th>
                                <th><i class="fas fa-sticky-note"></i> Note</th>
                            </tr>
                        </thead>
                        <tbody id="appointmentsBody">
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($appointment['client_name']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['location']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['note'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h2><i class="fas fa-sticky-note"></i> Notes Clients</h2>
                <form id="notesForm" method="POST" action="./Rendez_vous.php">
                    <input type="hidden" name="action" value="addNote">
                    <label for="clientNotes"><i class="fas fa-user"></i> Client</label>
                    <select id="clientNotes" name="clientNotes" required>
                        <option value="">Sélectionnez un client</option>
                        <!-- Les options des clients seront ajoutées ici -->
                    </select>

                    <label for="noteContent"><i class="fas fa-pen"></i> Note</label>
                    <textarea id="noteContent" name="noteContent" rows="4" required></textarea>

                    <button type="submit"><i class="fas fa-plus"></i> Ajouter une note</button>
                </form>

                <div id="clientNotesList">
                    <!-- Les notes clients seront ajoutées ici dynamiquement -->
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>Systeme de Gestion Commerciale et Facturation, Copyright Ludo_Consulting &copy; 2024</p>
    </footer>

    <script>
      // Fonction pour récupérer les clients
      async function fetchClients() {
        try {
          const response = await fetch('get_clients.php');
          const clients = await response.json();
          const clientSelect = document.getElementById('client');
          const clientNotesSelect = document.getElementById('clientNotes');

          // Remplir le select des clients
          clients.forEach(client => {
            const option = document.createElement('option');
            option.value = client.id;
            option.textContent = client.nom; // Remplacez 'nom' par la colonne que vous avez dans votre table
            clientSelect.appendChild(option);

            const optionNotes = document.createElement('option');
            optionNotes.value = client.id;
            optionNotes.textContent = client.nom;
            clientNotesSelect.appendChild(optionNotes);
          });
        } catch (error) {
          console.error('Erreur lors de la récupération des clients:', error);
        }
      }

      // Appeler la fonction pour récupérer les clients
      fetchClients();
    </script>
</body>
</html>
