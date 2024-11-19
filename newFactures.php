<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'facturations');

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérer les clients depuis la base de données
$clients = [];
$result = $conn->query("SELECT id, nom FROM clients");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}

// Traitement du formulaire lors de la soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];
    $type_facture = $_POST['type_facture'];
    $description = $_POST['description'];
    $numero_facture = $_POST['invoice_number'];
    $date_facturation = $_POST['invoice_date'];
    $date_echeance = $_POST['due_date'];
    $mode_paiement = $_POST['payment_mode'];
    $statut_facture = $_POST['invoice_status'];
    $total_ht = $_POST['total_ht'];
    $tva = $_POST['tva'];
    $total_ttc = $_POST['total_ttc'];
    $notes = $_POST['notes'];

    // Insertion des informations de la facture dans la base de données
    $stmt = $conn->prepare("INSERT INTO factures (client_id, type_facture, description, numero_facture, date_facturation, date_echeance, mode_paiement, statut_facture, total_ht, tva, total_ttc, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssss", $client_id, $type_facture, $description, $numero_facture, $date_facturation, $date_echeance, $mode_paiement, $statut_facture, $total_ht, $tva, $total_ttc, $notes);

    if ($stmt->execute()) {
        $facture_id = $stmt->insert_id;
// Insertion des articles dans la table articles_facture
if (isset($_POST['items']) && is_array($_POST['items'])) {
    foreach ($_POST['items'] as $item) {
        $description_article = $item['description'];  // Description de l'article
        $quantite = $item['quantity'];  // Quantité de l'article
        $prix_unitaire = $item['unitPrice'];  // Prix unitaire de l'article
        $total_article = $quantite * $prix_unitaire;  // Calcul du total pour cet article

        // Insertion de chaque article dans la table 'articles_facture'
        $stmt_item = $conn->prepare("INSERT INTO articles_facture (facture_id, description, quantite, prix_unitaire, total) VALUES (?, ?, ?, ?, ?)");
        $stmt_item->bind_param("isidd", $facture_id, $description_article, $quantite, $prix_unitaire, $total_article);
        $stmt_item->execute();
    }
}



        // Redirection vers la page des factures après l'enregistrement
        header("Location: Factures.php");
        exit();
    } else {
        echo "Erreur lors de l'enregistrement de la facture : " . $stmt->error;
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Facture - Système de Gestion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./asset/newFacture.css">
    <link rel="shortcut icon" href="./img/Image1.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background: #3498db;
            color: #fff;
            padding: 10px 20px;
            text-align: left;
        }

        nav {
            background: #46637f;
            padding: 10px 0;
        }

        nav a {
            color: #fff;
            padding: 15px;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-section {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .two-column {
            display: flex;
            justify-content: space-between;
        }

        .two-column .form-group {
            width: 48%; /* Chaque groupe occupe environ la moitié de l'espace */
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .add-item-btn {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }

        .submit-btn {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }

        .submit-btn, .add-item-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<header>
    <h1><i class="fas fa-file-invoice-dollar"></i> Nouvelle Facture</h1>
</header>
<nav>
    <a href="./vues/dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
    <a href="./Client.php"><i class="fas fa-users"></i> Clients</a>
    <a href="./Devis.php"><i class="fas fa-file-invoice"></i> Devis</a>
    <a href="./Factures.php"><i class="fas fa-file-invoice-dollar"></i> Factures</a>
    <a href="./Rendez_vous.php"><i class="fas fa-calendar-check"></i> Rendez-vous</a>
</nav>

<div class="container">
    <form id="invoiceForm" class="invoice-form" method="POST" action="./newFactures.php">
        
        <!-- Section Client -->
        <section class="form-section">
            <h2><i class="fas fa-user"></i> Informations Client</h2>
            <div class="two-column">
                <div class="form-group">
                    <label for="clientSelect">Client :</label>
                    <select id="clientSelect" name="client_id" required>
                        <option value="">Sélectionnez un client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?php echo $client['id']; ?>"><?php echo htmlspecialchars($client['nom']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="invoiceNumber">Numéro de facture :</label>
                    <input type="text" id="invoiceNumber" name="invoice_number" required>
                </div>
            </div>
            <div class="two-column">
                <div class="form-group">
                    <label for="invoiceDate">Date de facturation :</label>
                    <input type="date" id="invoiceDate" name="invoice_date" required>
                </div>
                <div class="form-group">
                    <label for="dueDate">Date d'échéance :</label>
                    <input type="date" id="dueDate" name="due_date" required>
                </div>
            </div>
        </section>

        <!-- Type de Facture -->
        <div class="form-group">
                    <label for="typeFacture">Type de facture :</label>
                    <select id="typeFacture" name="type_facture" required>
                        <option value="">Sélectionnez un type de facture</option>
                        <option value="classique">Facture classique</option>
                        <option value="acompte">Facture d'acompte</option>
                        <option value="solde">Facture de solde</option>
                        <option value="avoir">Facture d'avoir</option>
                        <option value="cloture">Facture cloture</option>
                        <option value="pro forma">Facture pro forma</option>
                        <option value="rectificative">Facture rectificative</option>
                    </select>
                </div>
            </section>

        <!-- Section Paiement -->
        <section class="form-section">
            <h2><i class="fas fa-money-bill-alt"></i> Informations de Paiement</h2>
            <div class="two-column">
                <div class="form-group">
                    <label for="paymentMode">Mode de paiement :</label>
                    <select id="paymentMode" name="payment_mode" required>
                        <option value="">Sélectionnez un mode de paiement</option>
                        <option value="Virement bank">Virement bank</option>
                        <option value="Espèce">Espèce</option>
                        <option value="Chèque">Chèque</option>
                        <option value="Portefeuille nume">Portefeuille numé</option>
                        <option value="Airtel Money">Airtel Money</option>
                        <option value="MOOv Africa">MOOV Africa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="invoice_status">Statut de la facture :</label>
                    <select id="invoice_status" name="invoice_status" required>
                        <option value="">Sélectionnez un statut</option>
                        <option value="Payée">Payée</option>
                        <option value="Attente">Attente</option>
                        <option value="En retard">Retard</option>
                        <option value="En cours">En cours</option>
                        <option value="Annulée">Annulée</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Section Articles -->
        <section class="form-section">
            <h2><i class="fas fa-list"></i> Articles</h2>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="itemsContainer">
                    <tr>
                        <td><input type="text" name="description[]" required></td>
                        <td><input type="number" name="quantity[]" class="quantity" oninput="calculateRowTotal(this)" required></td>
                        <td><input type="number" name="unitPrice[]" class="unit-price" step="0.01" oninput="calculateRowTotal(this)" required></td>
                        <td><input type="text" name="total[]" class="row-total" readonly></td>
                    </tr>
                </tbody>
            </table>

            <button type="button" id="addItemBtn" class="add-item-btn"><i class="fas fa-plus"></i> Ajouter un article</button>
        </section>
        <section>
            <h2>Informations de Paiement</h2>
            <div class="form-group">
                <label for="total_ht">Total HT :</label>
                <input type="number" id="total_ht" name="total_ht" step="0.01" readonly>
            </div>
            <div class="form-group">
            <label for="tva">TVA (20%) :</label>
            <input type="text" id="tva" name="tva" readonly>
            </div>
            <div class="form-group">
            <label for="total_ttc">Total TTC :</label>
            <input type="text" id="total_ttc" name="total_ttc" readonly>
            </div>
        </section>


        <!-- Section Notes -->
        <section class="form-section">
            <h2><i class="fas fa-sticky-note"></i> Notes</h2>
            <div class="form-group">
                <label for="notes">Notes :</label>
                <textarea id="notes" name="notes"></textarea>
            </div>
        </section>

        <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Enregistrer la facture</button>
    </form>
</div>

<script>
    
    // Fonction pour calculer le total d'une ligne
    function calculateRowTotal(inputElement) {
        const row = inputElement.closest('tr');
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const rowTotal = quantity * unitPrice;

        row.querySelector('.row-total').value = rowTotal.toFixed(2);

        calculateTotalHT();
    }

    // Fonction pour ajouter un nouvel article
    document.getElementById('addItemBtn').addEventListener('click', function () {
        const itemsContainer = document.getElementById('itemsContainer');
        const newRow = `
            <tr>
                <td><input type="text" name="description[]" required></td>
                <td><input type="number" name="quantity[]" class="quantity" oninput="calculateRowTotal(this)" required></td>
                <td><input type="number" name="unitPrice[]" class="unit-price" step="0.01" oninput="calculateRowTotal(this)" required></td>
                <td><input type="text" name="total[]" class="row-total" readonly></td>
            </tr>`;
        itemsContainer.insertAdjacentHTML('beforeend', newRow);
    });

    // Fonction pour calculer le total HT, TVA et TTC
    function calculateTotalHT() {
        let totalHT = 0;
        const rowTotals = document.querySelectorAll('.row-total');
        
        rowTotals.forEach(function (totalInput) {
            totalHT += parseFloat(totalInput.value) || 0;
        });

        const tva = totalHT * 0.2; // 20% TVA
        const totalTTC = totalHT + tva;

        document.getElementById('total_ht').value = totalHT.toFixed(2);
        document.getElementById('tva').value = tva.toFixed(2);
        document.getElementById('total_ttc').value = totalTTC.toFixed(2);
    }
</script>
</body>
</html>
