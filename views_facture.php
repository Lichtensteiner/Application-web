<?php
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'facturations';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Erreur de connexion : ' . $conn->connect_error);
}


// Récupérer l'ID de la facture depuis l'URL
$factureId = $_GET['id'] ?? null;

if (!$factureId) {
    die("Facture non spécifiée.");
}

// Récupérer les informations de la facture
$queryFacture = "
    SELECT f.*, c.nom AS client_nom, c.entreprise AS entreprise_nom
    FROM factures f
    JOIN clients c ON f.client_id = c.id
    WHERE f.id = ?";

$stmt = $conn->prepare($queryFacture);
$stmt->bind_param("i", $factureId);
$stmt->execute();
$result = $stmt->get_result();

$facture = $result->fetch_assoc();

if (!$facture) {
    die("Facture non trouvée.");
}
// Récupérer les articles associés à la facture
$queryArticles = "
    SELECT af.description, af.quantite, af.prix_unitaire
    FROM articles_facture af
    WHERE af.facture_id = ?
";

$stmtArticles = $conn->prepare($queryArticles);
$stmtArticles->bind_param("i", $factureId);
$stmtArticles->execute();
$resultArticles = $stmtArticles->get_result();


// Inclure FPDF
require('./fpdf/fpdf186/fpdf.php'); // Assurez-vous que ce chemin est correct

// Fonction pour générer le PDF
function generatePDF($facture) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Détails de la Facture', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Ajout des détails de la facture
    foreach ($facture as $key => $value) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $key)) . ': ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, htmlspecialchars($value), 19, 1);
    }

    $pdf->Output('D', 'facture_' . $facture['id'] . '.pdf');
}

// Gestion des actions
if (isset($_POST['download_pdf'])) {
    generatePDF($facture);
}

if (isset($_POST['send_email'])) {
    // Fonctionnalité d'envoi par email
    // Vous devez configurer les paramètres SMTP pour cela
    $to = "client@example.com"; // Remplacez par l'email du client
    $subject = "Détails de la Facture #" . $facture['numero_facture'];
    $message = "Veuillez trouver ci-joint la facture.\n\nDétails : " . json_encode($facture);
    // Envoyer l'email
    mail($to, $subject, $message);
    echo "Email envoyé avec succès.";
}



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Facture</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <link rel="stylesheet" href="./asset/factures.css">
    <link rel="shortcut icon" href="./img/Image1.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .header-info {
            text-align: right;
            font-size: 14px;
        }

        .header-info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f9f9f9;
            color: #333;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            background: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background: #0056b3;
        }

        .section-header {
            font-size: 18px;
            font-weight: bold;
            margin-top: 30px;
            color: #333;
        }

        .note {
            font-size: 14px;
            color: #555;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container mx-auto mt-10">
    <div id="invoice" class="bg-white p-8 rounded shadow-md">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Facture #<?= htmlspecialchars($facture['numero_facture']) ?></h1>
            </div>
            <div class="header-info">
                <h2 class="font-semibold">Ludo_Consulting</h2>
                <p>Adresse: 123 Rue de Centre Ville</p>
                <p>Téléphone: +241 077 02 23 06</p>
                <p>Email: ludo.consulting3@gmail.com</p>
            </div>
        </div>

        <!-- Client Info Section -->
        <div class="section-header">Informations du Client</div>
        <table>
            <tr>
                <th>Client</th>
                <td><?= htmlspecialchars($facture['client_nom']) ?></td>
            </tr>
            <tr>
                <th>Entreprise</th>
                <td><?= htmlspecialchars($facture['entreprise_nom']) ?></td>
            </tr>
        </table>

        <!-- Facture Info Section -->
        <div class="section-header">Détails de la Facture</div>
        <table>
            <tr>
                <th>Date de Facturation</th>
                <td><?= htmlspecialchars($facture['date_facturation'] ? date("d/m/Y", strtotime($facture['date_facturation'])) : '') ?></td>
            </tr>
            <tr>
                <th>Date d'Échéance</th>
                <td><?= htmlspecialchars($facture['date_echeance'] ? date("d/m/Y", strtotime($facture['date_echeance'])) : '') ?></td>
            </tr>
            <tr>
                <th>Mode de Paiement</th>
                <td><?= htmlspecialchars($facture['mode_paiement']) ?></td>
            </tr>
            <tr>
                <th>Statut de Facture</th>
                <td><?= htmlspecialchars($facture['statut_facture']) ?></td>
            </tr>
            
            <tr>
                <th>Type de Facture</th>
                <td><?= htmlspecialchars($facture['type_facture']) ?></td>
            </tr>
        </table>

        <!-- Articles Section -->
<div class="section-header">Articles de la Facture</div>
<table>
    <thead>
        <tr>
            <th>Description</th>
            <th>Quantité</th>
            <th>Prix Unitaire (XAF)</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($article = $resultArticles->fetch_assoc()) : ?>
            <tr>
            <td><?= htmlspecialchars($article['description']) ?></td>
                <td><?= htmlspecialchars($article['quantite']) ?></td>
                <td><?= number_format($article['prix_unitaire'], 2, ',', ' ') ?> XAF</td>
                <td><?= number_format($article['quantite'] * $article['prix_unitaire'], 2, ',', ' ') ?> XAF</td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


        <!-- Totals Section -->
        <div class="section-header">Totaux de la Facture</div>
        <table>
            <tr>
                <th>Total HT</th>
                <td><?= number_format($facture['total_ht'], 2, ',', ' ') ?> XAF</td>
            </tr>
            <tr>
                <th>TVA</th>
                <td><?= number_format($facture['tva'], 2, ',', ' ') ?> XAF</td>
            </tr>
            <tr>
                <th>Total TTC</th>
                <td><?= number_format($facture['total_ttc'], 2, ',', ' ') ?> XAF</td>
            </tr>
        </table>

        <!-- Notes Section -->
        <div class="note">
            <p><strong>Notes :</strong> <?= htmlspecialchars($facture['notes']) ?></p>
        </div>

        <!-- Buttons -->
        <div class="btn-container">
            <button onclick="printInvoice()" class="btn">Impression</button>
            <button onclick="downloadPDF()" class="btn">Télécharger PDF</button>
            <button onclick="openEmailForm()" class="btn">Envoyer par Email</button>
        </div>
    </div>
</div>
<br>
<br> <br>
<br>

<script>
    function printInvoice() {
        window.print();
    }

    function downloadPDF() {
        var element = document.getElementById("invoice");
        html2pdf(element);
    }

    function openEmailForm() {
        document.getElementById('emailForm').style.display = 'block';
    }
</script>

</body>
</html>
