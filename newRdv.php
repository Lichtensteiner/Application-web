<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";  // Remplacez par votre nom d'utilisateur MySQL
$password = "";      // Remplacez par votre mot de passe MySQL
$dbname = "facturations";  // Remplacez par le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Initialiser toutes les variables pour éviter les erreurs
$clientName = '';
$appointmentType = '';
$appointmentDate = '';
$appointmentTime = '';
$location = '';
$notes = '';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['clientName']) && !empty($_POST['clientName'])) {
        $clientName = $_POST['clientName'];
    } else {
        echo "Erreur : Le nom du client est requis.";
    }

    $appointmentType = isset($_POST['appointmentType']) ? $_POST['appointmentType'] : '';
    $appointmentDate = isset($_POST['appointmentDate']) ? $_POST['appointmentDate'] : '';
    $appointmentTime = isset($_POST['appointmentTime']) ? $_POST['appointmentTime'] : '';
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $notes = isset($_POST['notes']) ? $_POST['notes'] : '';

    // Si tout est rempli, insérer les données dans la base de données
    if (!empty($clientName) && !empty($appointmentType) && !empty($appointmentDate) && !empty($appointmentTime)) {
        $sql = "INSERT INTO newRdv (clientName, appointmentType, appointmentDate, appointmentTime, location, notes)
                VALUES ('$clientName', '$appointmentType', '$appointmentDate', '$appointmentTime', '$location', '$notes')";

        if ($conn->query($sql) === TRUE) {
            echo "Nouveau rendez-vous créé avec succès";
        } else {
            echo "Erreur : " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Erreur : Veuillez remplir tous les champs requis.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouveau Rendez-vous - Système de Gestion</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <link rel="stylesheet" href="./asset/newRdv.css">
  <link rel="shortcut icon" href="./img/Image1.png">
</head>
<body>
  <header>
    <h1><i class="fas fa-calendar-plus"></i> Nouveau Rendez-vous</h1>
  </header>
  <nav>
    <a href="./vues/dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
    <a href="./Client.php"><i class="fas fa-users"></i> Clients</a>
    <a href="./Devis.php"><i class="fas fa-file-invoice"></i> Devis</a>
    <a href="./Factures.php"><i class="fas fa-file-invoice-dollar"></i> Factures</a>
    <a href="./Rendez_vous.php"><i class="fas fa-calendar-check"></i> Rendez-vous</a>
  </nav>
  <div class="container">
    <form id="appointmentForm" action="./Devis.php" method="POST">
      <div class="form-row">
        <div>
          <label for="clientName"><i class="fas fa-user icon"></i> Nom du client :</label>
          <input type="text" id="clientName" name="clientName" required>
          <div id="clientSuggestions"></div>
        </div>
        <div>
          <label for="appointmentType"><i class="fas fa-tags icon"></i> Type de rendez-vous :</label>
          <select id="appointmentType" name="appointmentType" required>
            <option value="">Sélectionnez un type</option>
            <option value="consultation">Consultation</option>
            <option value="suivi">Suivi</option>
            <option value="presentation">Présentation</option>
            <option value="autre">Autre</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div>
          <label for="appointmentDate"><i class="far fa-calendar-alt icon"></i> Date :</label>
          <input type="text" id="appointmentDate" name="appointmentDate" required>
        </div>
        <div>
          <label for="appointmentTime"><i class="fas fa-clock icon"></i> Heure :</label>
          <input type="text" id="appointmentTime" name="appointmentTime" required>
        </div>
      </div>

      <label for="location"><i class="fas fa-map-marker-alt icon"></i> Lieu :</label>
      <input type="text" id="location" name="location" required>

      <label for="notes"><i class="fas fa-sticky-note icon"></i> Notes :</label>
      <textarea id="notes" name="notes"></textarea>

      <button type="submit"><i class="fas fa-save icon"></i> Créer le rendez-vous</button>
    </form>

    <div class="client-notes">
      <h2><i class="fas fa-clipboard-list icon"></i> Notes Client</h2>
      <div class="add-note-form">
        <select id="clientSelect">
          <option value="">Sélectionner un client</option>
        </select>
        <input type="text" id="newNoteText" placeholder="Saisir une note">
        <button id="addNoteBtn" class="add-note-btn"><i class="fas fa-plus icon"></i> Ajouter une note</button>
      </div>
      <table class="client-notes-table">
        <thead>
          <tr>
            <th><i class="fas fa-user icon"></i> Client</th>
            <th><i class="fas fa-comment icon"></i> Note</th>
            <th><i class="fas fa-cogs icon"></i> Actions</th>
          </tr>
        </thead>
        <tbody id="clientNotesBody">
        </tbody>
      </table>
    </div>
  </div>

  <script>
    const clients = [
      { id: 1, name: "Client 1" },
      { id: 2, name: "Client 2" },
      { id: 3, name: "Client 3" },
      { id: 4, name: "Client 4" },
      { id: 5, name: "Client 5" }
    ];

    let clientNotes = [
      { id: 1, clientId: 1, note: "Préfère les rendez-vous en fin de journée" },
      { id: 2, clientId: 2, note: "A des allergies, vérifier avant chaque rendez-vous" },
      { id: 3, clientId: 3, note: "Toujours ponctuelle, apprécie la précision" },
      { id: 4, clientId: 4, note: "Demande souvent des devis détaillés" },
      { id: 5, clientId: 5, note: "Préfère la communication par email" }
    ];

    const clientNameInput = document.getElementById('clientName');
    const clientSuggestions = document.getElementById('clientSuggestions');

    clientNameInput.addEventListener('input', function() {
      const input = this.value.toLowerCase();
      const filteredClients = clients.filter(client => 
        client.name.toLowerCase().includes(input)
      );

      clientSuggestions.innerHTML = '';
      clientSuggestions.style.display = filteredClients.length > 0 ? 'block' : 'none';

      filteredClients.forEach(client => {
        const div = document.createElement('div');
        div.textContent = client.name;
        div.addEventListener('click', function() {
          clientNameInput.value = client.name;
          clientSuggestions.style.display = 'none';
          updateClientNotes(client.id);
        });
        clientSuggestions.appendChild(div);
      });
    });

    document.addEventListener('click', function(e) {
      if (e.target !== clientNameInput && e.target !== clientSuggestions) {
        clientSuggestions.style.display = 'none';
      }
    });

    function updateClientNotes(clientId) {
      const clientNotesBody = document.getElementById('clientNotesBody');
      clientNotesBody.innerHTML = '';

      const clientNotesList = clientNotes.filter(note => note.clientId === clientId);
      const client = clients.find(c => c.id === clientId);

      clientNotesList.forEach(note => {
        addNoteToTable(client, note.note);
      });
    }

    function addNoteToTable(client, note) {
      const clientNotesBody = document.getElementById('clientNotesBody');
      const tr = document.createElement('tr');
      const clientNameTd = document.createElement('td');
      clientNameTd.textContent = client.name;
      const noteTd = document.createElement('td');
      noteTd.textContent = note;
      const actionTd = document.createElement('td');
      const deleteBtn = document.createElement('button');
      deleteBtn.classList.add('delete-btn');
      deleteBtn.textContent = 'Supprimer';
      actionTd.appendChild(deleteBtn);
      tr.appendChild(clientNameTd);
      tr.appendChild(noteTd);
      tr.appendChild(actionTd);
      clientNotesBody.appendChild(tr);

      deleteBtn.addEventListener('click', function() {
        clientNotes = clientNotes.filter(n => n.note !== note);
        updateClientNotes(client.id);
      });
    }

    document.getElementById('addNoteBtn').addEventListener('click', function() {
      const clientId = document.getElementById('clientSelect').value;
      const newNoteText = document.getElementById('newNoteText').value;

      if (clientId && newNoteText) {
        const client = clients.find(c => c.id == clientId);
        const newNote = { id: clientNotes.length + 1, clientId: parseInt(clientId), note: newNoteText };
        clientNotes.push(newNote);
        addNoteToTable(client, newNoteText);
        document.getElementById('newNoteText').value = '';
      } else {
        alert('Sélectionnez un client et entrez une note.');
      }
    });

    flatpickr("#appointmentDate", { dateFormat: "Y-m-d" });
    flatpickr("#appointmentTime", { enableTime: true, noCalendar: true, dateFormat: "H:i" });
  </script>
</body>
</html>
