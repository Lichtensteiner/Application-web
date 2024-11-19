<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="./img/Image1.png">
    <link rel="stylesheet" href="./asset/parametres.css">
    <title>Paramètres - Gestion Commerciale et Facturation</title>
    <style>
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
    </style>
</head>
<body>
    
    <header>
     <h1><i class="fas fa-cog"></i> Gestion des paramètres</h1>
    </header>
    <div class="nav-links">
    <nav>
    <a href="./vues/dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
    <a href="./Client.php"><i class="fas fa-users"></i> Clients</a>
    <a href="./Devis.php"><i class="fas fa-file-invoice"></i> Devis</a>
    <a href="./Factures.php"><i class="fas fa-file-invoice-dollar"></i> Factures</a>
    <a href="./Rendez_vous.php"><i class="fas fa-calendar-check"></i> Rendez-vous</a>
    <a href="/alertes.php"><i class="fas fa-bell"></i> Alertes</a>
    <a href="/Rapports.php"><i class="fas fa-chart-bar"></i> Rapports</a>
    <a href="/Parametre.php"><i class="fas fa-cog"></i> Paramètres</a>
    <a href="/users.php"><i class="fas fa-users-cog"></i> Utilisateurs</a>
    </div>

    
</nav>

    <div class="container">
        <h2><i class="fas fa-cog"></i> Paramètres</h2>
        <div class="settings-grid">
            <div class="settings-card">
                <h3><i class="fas fa-user"></i> Informations du compte</h3>
                <form>
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" value="Ludovic Lichtensteiner" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="ludo.consulting@gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirmer le mot de passe</label>
                        <input type="password" id="confirm-password" name="confirm-password">
                    </div>
                    <button type="submit" class="btn">Mettre à jour</button>
                </form>
            </div>
            <div class="settings-card">
                <h3><i class="fas fa-building"></i> Informations de l'entreprise</h3>
                <form>
                    <div class="form-group">
                        <label for="company-name">Nom de l'entreprise</label>
                        <input type="text" id="company-name" name="company-name" value="Ludo_Consulting" required>
                    </div>
                    <div class="form-group">
                        <label for="company-address">Adresse</label>
                        <input type="text" id="company-address" name="company-address" value="123 Rue de la Paix, 75000 Libreville" required>
                    </div>
                    <div class="form-group">
                        <label for="company-phone">Téléphone</label>
                        <input type="text" id="company-phone" name="company-phone" value="+241 077 02 23 06" required>
                    </div>
                    <div class="form-group">
                        <label for="company-siret">Numéro SIRET</label>
                        <input type="text" id="company-siret" name="company-siret" value="123 456 789 00012" required>
                    </div>
                    <button type="submit" class="btn">Enregistrer</button>
                </form>
            </div>
            <div class="settings-card">
                <h3><i class="fas fa-file-invoice"></i> Paramètres de facturation</h3>
                <form>
                    <div class="form-group">
                        <label for="invoice-prefix">Préfixe des factures</label>
                        <input type="text" id="invoice-prefix" name="invoice-prefix" value="FACT-" required>
                    </div>
                    <div class="form-group">
                        <label for="quote-prefix">Préfixe des devis</label>
                        <input type="text" id="quote-prefix" name="quote-prefix" value="DEV-" required>
                    </div>
                    <div class="form-group">
                        <label for="payment-terms">Conditions de paiement par défaut</label>
                        <select id="payment-terms" name="payment-terms">
                            <option value="15">15 jours</option>
                            <option value="30" selected>30 jours</option>
                            <option value="45">45 jours</option>
                            <option value="60">60 jours</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="default-tax-rate">Taux de TVA par défaut</label>
                        <select id="default-tax-rate" name="default-tax-rate" required>
                            <option value="5%">5%</option>
                            <option value="10%">10%</option>
                            <option value="20%" selected>20%</option>
                            <option value="0%">Exonéré (0%)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Appliquer</button>
                    
                </form>
            </div>
            <div class="settings-card">
                <h3><i class="fas fa-bell"></i> Paramètres des notifications</h3>
                <form>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="notify-invoice-due" checked> Notifier pour les échéances de factures
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="notify-quote-expiry" checked> Notifier pour l'expiration des devis
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="notify-low-stock"> Notifier pour le stock bas
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="notification-email">Email pour les notifications</label>
                        <input type="email" id="notification-email" name="notification-email" value="notifications@ludoconsulting.com" required>
                    </div>
                    <button type="submit" class="btn">Sauvegarder les préférences</button>
                </form>
            </div>
            <div class="settings-card">
                <h3><i class="fas fa-palette"></i> Préférences d'affichage</h3>
                <form>
                    <div class="form-group">
                        <label for="dark-mode-toggle">Mode sombre</label>
                        <label class="switch">
                            <input type="checkbox" id="dark-mode-toggle">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="font-size">Taille de police</label>
                        <select id="font-size" name="font-size">
                            <option value="small">Petite</option>
                            <option value="medium" selected>Moyenne</option>
                            <option value="large">Grande</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="language">Langue</label>
                        <select id="language" name="language">
                            <option value="fr" selected>Français</option>
                            <option value="en">English</option>
                            <option value="es">Español</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Appliquer les préférences</button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <p>Systeme de Gestion Commerciale et Facturation, Copyright Ludo_Consulting &copy; 2024</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Paramètres mis à jour avec succès !');
                });
            });

            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const body = document.body;

            darkModeToggle.addEventListener('change', function() {
                if (this.checked) {
                    body.classList.add('dark-mode');
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    body.classList.remove('dark-mode');
                    localStorage.setItem('darkMode', 'disabled');
                }
            });

            // Check for saved dark mode preference
            if (localStorage.getItem('darkMode') === 'enabled') {
                darkModeToggle.checked = true;
                body.classList.add('dark-mode');
            }

            const fontSizeSelect = document.getElementById('font-size');
            fontSizeSelect.addEventListener('change', function() {
                document.body.style.fontSize = this.value === 'small' ? '14px' : this.value === 'medium' ? '16px' : '18px';
            });

            const languageSelect = document.getElementById('language');
            languageSelect.addEventListener('change', function() {
                alert('Changement de langue vers : ' + this.options[this.selectedIndex].text);
                // Ici, vous implémenteriez la logique réelle de changement de langue
            });
        });
    </script>
</body></html>