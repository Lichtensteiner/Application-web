<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "facturations";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Requête pour récupérer les utilisateurs
$sql = "SELECT id, username, email, role FROM utilisateurs";
$result = $conn->query($sql);

// Stockez les utilisateurs dans un tableau
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Utilisateurs - Système de Gestion</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="shortcut icon" href="./img/Image1.png">
  <link rel="stylesheet" href="./asset/users.css">
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
    <h1><i class="fas fa-users-cog"></i> Gestion des Utilisateurs</h1>
  </header>
  
  <?php include 'composant/nav.php'; ?>

  <div class="container">
    <div class="content">
      <h2>Liste des Utilisateurs</h2>
      <button id="addUserBtn" class="add-user-btn"><i class="fas fa-user-plus"></i> Ajouter un Utilisateur</button>
      <table id="usersTable">
        <thead>
          <tr>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Les utilisateurs seront ajoutés ici dynamiquement -->
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal pour ajouter un utilisateur -->
  <div id="addUserModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Ajouter un Utilisateur</h2>
      <form id="addUserForm">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" required>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
        <label for="role">Rôle</label>
        <select id="role" name="role" required>
          <option value="secretaire">Secrétaire</option>
          <option value="comptable">Comptable</option>
          <option value="direction">Direction</option>
          <option value="administrateur">Administrateur</option>
        </select>
        <button type="submit">Mettre à jours</button>
      </form>
    </div>
  </div>

  <footer>
      <p>Systeme de Gestion Commerciale et Facturation, Copyright Ludo_Consulting &copy; 2024</p>
  </footer>

  <script>
    // Passez les utilisateurs de PHP à JavaScript
    let users = <?php echo json_encode($users); ?>;

    function displayUsers() {
      const tableBody = document.querySelector("#usersTable tbody");
      tableBody.innerHTML = "";
      users.forEach(user => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${user.username}</td>
          <td>${user.email}</td>
          <td>${user.role}</td>
          <td class="action-buttons">
            <button class="edit-btn" data-id="${user.id}" data-username="${user.username}" data-email="${user.email}" data-role="${user.role}"><i class="fas fa-edit"></i> Modifier</button>
            <button class="delete-btn" data-id="${user.id}"><i class="fas fa-trash-alt"></i> Supprimer</button>
          </td>
        `;
        tableBody.appendChild(tr);
      });
    }

    // Afficher les utilisateurs au chargement de la page
    displayUsers();

    // Ouvrir le modal
    document.getElementById('addUserBtn').addEventListener('click', function() {
      document.getElementById('addUserModal').style.display = 'block';
    });

    // Fermer le modal
    document.querySelector('.close').addEventListener('click', function() {
      document.getElementById('addUserModal').style.display = 'none';
    });

    // Fermer le modal lorsque l'utilisateur clique en dehors de la modale
    window.addEventListener('click', function(event) {
      if (event.target == document.getElementById('addUserModal')) {
        document.getElementById('addUserModal').style.display = 'none';
      }
    });

    // Soumettre le formulaire d'ajout d'utilisateur
    document.getElementById('addUserForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(this);
      fetch('./new_user.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Mettre à jour la liste des utilisateurs
          users.push(data.user);
          displayUsers();
          document.getElementById('addUserModal').style.display = 'none';  // Fermer la modale
        } else {
          alert('Erreur lors de l\'ajout de l\'utilisateur');
        }
      });
    });

    // Modifier un utilisateur
    document.querySelector('#usersTable').addEventListener('click', function(event) {
      if (event.target.classList.contains('edit-btn')) {
        const id = event.target.getAttribute('data-id');
        const username = event.target.getAttribute('data-username');
        const email = event.target.getAttribute('data-email');
        const role = event.target.getAttribute('data-role');

        // Remplir le formulaire du modal avec les informations de l'utilisateur
        document.getElementById('username').value = username;
        document.getElementById('email').value = email;
        document.getElementById('role').value = role;

        // Ouvrir le modal
        document.getElementById('addUserModal').style.display = 'block';

        // Modifier l'action du formulaire pour l'édition
        document.getElementById('addUserForm').onsubmit = function(event) {
          event.preventDefault();
          const formData = new FormData(this);
          formData.append('id', id);  // Ajouter l'ID de l'utilisateur à modifier
          formData.append('edit', true); // Indiquer qu'il s'agit d'une modification

          fetch('./edit_user.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Mettre à jour la liste des utilisateurs
              const userIndex = users.findIndex(user => user.id === id);
              users[userIndex].username = formData.get('username');
              users[userIndex].email = formData.get('email');
              users[userIndex].role = formData.get('role');
              displayUsers();
              document.getElementById('addUserModal').style.display = 'none';  // Fermer la modale
            } else {
              alert('Erreur lors de la modification');
            }
          });
        };
      }
    });

    // Supprimer un utilisateur
    document.querySelector('#usersTable').addEventListener('click', function(event) {
      if (event.target.classList.contains('delete-btn')) {
        const id = event.target.getAttribute('data-id');
        if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
          fetch('./delete_user.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              users = users.filter(user => user.id !== id);
              displayUsers();
            } else {
              alert('Erreur lors de la suppression');
            }
          });
        }
      }
    });
  </script>
</body>
</html>
