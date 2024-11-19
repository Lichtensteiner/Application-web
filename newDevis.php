<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouveau Devis - Système de Gestion</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="./asset/newDevis.css">
  <link rel="shortcut icon" href="./img/Image1.png">
</head>
<body>

<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "facturations";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérer les clients
$sql = "SELECT id, nom FROM clients";
$result = $conn->query($sql);
$clients = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
} else {
    echo "Aucun client trouvé";
}

$conn->close();
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupérer les données du formulaire
  $client_id = $_POST['client_id'] ?? null;
  $articles = $_POST['articles'] ?? null;
  $numero_devis = $_POST['numero_devis'] ?? '';
  $createDate = $_POST['createDate'] ?? '';
  $validityDate = $_POST['validityDate'] ?? '';
  $tva = $_POST['tva'] ?? 0;
  $totalHT = $_POST['totalHT'] ?? 0;
  $totalTTC = $_POST['totalTTC'] ?? 0;
  $statut_devis = $_POST['statut_devis'] ?? '';
  $notes = $_POST['notes'] ?? '';

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Insérer les données dans la table 'devis'
    $sql = "INSERT INTO devis (client_id,  numero_devis, date_creation, date_validite, TVA, total_ht, Total_TTC, statut_devis, notes)
    VALUES ('$client_id', '$numero_devis', '$createDate', '$validityDate', '$tva', '$totalHT', '$totalTTC', '$statut_devis', '$notes')";

if ($conn->query($sql) === TRUE) {
        // Récupérer l'ID du devis inséré
        $devis_id = $conn->insert_id;

        // Traiter les données des articles
        if (isset($_POST['articles']) && is_array($_POST['articles'])) {
            foreach ($_POST['articles'] as $article) {
                $description = $article['description'] ?? '';
                $quantity = $article['quantity'] ?? 0;
                $unit_price = $article['unit_price'] ?? 0;
                $total = $quantity * $unit_price;

                // Insérer chaque article dans la table 'devis_articles'
                $sql_article = "INSERT INTO devis_articles (devis_id, description, quantity, unit_price, total)
                VALUES ('$devis_id', '$description', '$quantity', '$unit_price', '$total')";

                if (!$conn->query($sql_article)) {
                    echo "Erreur lors de l'ajout de l'article : " . $conn->error;
                }
            }
        }

        echo "Nouveau devis et articles ajoutés avec succès !";
        header("Location: Devis.php");
        exit();
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<header>
  <h1><i class="fas fa-file-invoice"></i> Nouveau Devis</h1>
</header>

<nav>
  <a href="/vues/dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
  <a href="./Client.php"><i class="fas fa-users"></i> Clients</a>
  <a href="./Devis.php"><i class="fas fa-file-invoice"></i> Devis</a>
  <a href="./Factures.php"><i class="fas fa-file-invoice-dollar"></i> Factures</a>
  <a href="./Rendez_vous.php"><i class="fas fa-calendar-check"></i> Rendez-vous</a>
</nav>

<div class="container">
  <div class="form-container">
    <form id="newQuoteForm" method="POST" action="./newDevis.php">
      <div class="form-row">
        <div class="form-column">
          <div class="form-group">
            <label for="clientSelect"><i class="fas fa-user"></i> Client :</label>
            <div class="icon-input">
              <i class="fas fa-building"></i>
              <select id="clientSelect" name="client_id" required>
                <option value="">Sélectionnez un client</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?php echo $client['id']; ?>"><?php echo $client['nom']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="form-column">
          <div class="form-group">
            <label for="createDate"><i class="fas fa-calendar-alt"></i> Date de création :</label>
            <div class="icon-input">
              <i class="fas fa-calendar"></i>
              <input type="date" id="createDate" name="createDate" required>
            </div>
          </div>
        </div>
        
        <div class="form-column">
          <div class="form-group">
            <label for="validityDate"><i class="fas fa-hourglass-half"></i> Date de validité :</label>
            <div class="icon-input">
              <i class="fas fa-calendar"></i>
              <input type="date" id="validityDate" name="validityDate" required>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
    <label for="numero_devis">Numéro de Devis</label>
    <div style="position: relative;">
        <i class="fas fa-file-alt" style="position: absolute; left: 10px; top: 10px; color: #aaa;"></i>
        <input type="text" name="numero_devis" id="numero_devis" value="<?= htmlspecialchars($devis['numero_devis'] ?? '') ?>" required style="padding-left: 30px;">
    </div>
</div>

      <div class="form-group">
        <label><i class="fas fa-shopping-cart"></i> Articles :</label>
        <div id="itemsContainer" class="items-container">
            <div class="item">
                <input type="text" name="articles[0][description]" placeholder="Description" required>
                <input type="number" name="articles[0][quantity]" placeholder="Qté" min="1" required>
                <input type="number" name="articles[0][unit_price]" placeholder="Prix" min="0" step="0.01" required>
                <input type="text" name="articles[0][total]" placeholder="Total" readonly>
                <button type="button" onclick="removeItem(this)"><i class="fas fa-trash"></i></button>
            </div>
        </div>
        <button type="button" id="addItemBtn" class="add-item">
          <i class="fas fa-plus"></i> Ajouter un article
        </button>
      </div>
      <div class="form-row">
        <div class="form-column">
          <div class="form-group">
            <label for="totalHT"><i class="fas fa-calculator"></i> Total HT :</label>
            <div class="icon-input">
              <i class="fas fa-money-bill-alt"></i>
              <input type="text" id="totalHT" name="totalHT" readonly>
            </div>
          </div>
        </div>
        <div class="form-column">
          <div class="form-group">
            <label for="tva"><i class="fas fa-percent"></i> TVA (%) :</label>
            <div class="icon-input">
              <i class="fas fa-tag"></i>
              <input type="number" id="tva" name="tva" min="0" max="100" value="20" required>
            </div>
          </div>
        </div>
        <div class="form-column">
          <div class="form-group">
            <label for="totalTTC"><i class="fas fa-money-bill-wave"></i> Total TTC :</label>
            <div class="icon-input">
              <i class="fas fa-money-bill-alt"></i>
              <input type="text" id="totalTTC" name="totalTTC" readonly>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group">
    <label for="statut"><i class="fas fa-clipboard-list"></i> Statut :</label>
    <select id="statut" name="statut" required>
        <option value="">Sélectionnez un statut</option>
        <option value="En_attente">En Attente</option>
        <option value="approuve">Approuvé</option>
        <option value="Expiré">Expiré</option>
        <option value="Rejetee">Rejeté</option>
        <option value="Facturé">Facturé</option>
        <option value="Envoyé">Envoyer</option>
        <option value="En_cours">En cours</option>
        <option value="Accepté">Accepté</option>
    </select>
</div>
      <div class="form-group">
        <label for="notes"><i class="fas fa-sticky-note"></i> Notes :</label>
        <textarea id="notes" name="notes" rows="3"></textarea>
      </div>
      <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Créer le devis</button>
    </form>
  </div>
</div>

<script>
// Gestion des articles
const itemsContainer = document.getElementById('itemsContainer');
const addItemBtn = document.getElementById('addItemBtn');
let itemCount = 0;

function addItem() {
  const item = document.createElement('div');
  item.className = 'item';
  item.innerHTML = `
    <input type="text" name="articles[0][description]" placeholder="Description" required>
    <input type="number" name="articles[0][quantity]" placeholder="Qté" min="1" required>
    <input type="number" name="articles[0][unit_price]" placeholder="Prix" min="0" step="0.01" required>
    <input type="text" name="articles[0][total]" placeholder="Total" readonly>
    <button type="button" onclick="removeItem(this)"><i class="fas fa-trash"></i></button>
  `;
  itemsContainer.appendChild(item);
  itemCount++;
  updateTotals();
}

function removeItem(button) {
  button.parentElement.remove();
  itemCount--;
  updateTotals();
}

addItemBtn.addEventListener('click', addItem);

// Mise à jour des totaux
function updateTotals() {
  let totalHT = 0;
  const items = itemsContainer.querySelectorAll('.item');
  items.forEach(item => {
    const quantity = parseFloat(item.children[1].value) || 0;
    const unitPrice = parseFloat(item.children[2].value) || 0;
    const total = quantity * unitPrice;
    item.children[3].value = total.toFixed(2) + ' XAF';
    totalHT += total;
  });

  document.getElementById('totalHT').value = totalHT.toFixed(2) + ' XAF';
  const tva = parseFloat(document.getElementById('tva').value) || 0;
  const totalTTC = totalHT + (totalHT * tva / 100);
  document.getElementById('totalTTC').value = totalTTC.toFixed(2) + ' XAF';
}

itemsContainer.addEventListener('input', updateTotals);
document.getElementById('tva').addEventListener('input', updateTotals);
</script>

</body>
</html>
