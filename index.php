<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion / Inscription - Système de Gestion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./asset/login.css">
    <link rel="shortcut icon" href="./img/Image1.png">
    <style>
        /* Style pour le message de confirmation */
        .confirmation-message {
            font-size: 2.5em;
            color: #4CAF50; /* Vert pour succès */
            text-align: center;
            padding: 20px;
            margin-top: 20px;
            background-color: #e0ffe0;
            border: 2px solid #4CAF50;
            border-radius: 10px;
            animation: fadeIn 2s ease-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .error-message {
            font-size: 1.5em;
            color: red; /* Rouge pour erreur */
            text-align: center;
            padding: 10px;
            margin-top: 10px;
            background-color: #ffe0e0;
            border: 2px solid red;
            border-radius: 10px;
            animation: fadeIn 2s ease-out;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1><i class="fas fa-user-circle"></i> Authentification </h1>
        </div>
        <div class="auth-tabs">
            <div class="auth-tab active" id="loginTab">Connexion</div>
            <div class="auth-tab" id="registerTab">Inscription</div>
        </div>

        <!-- Formulaire de Connexion -->
        <form id="loginForm" class="auth-form active" action="./vues/login.php" method="POST">
            <div class="form-group">
                <label for="loginUsername"><i class="fas fa-user"></i> Nom d'utilisateur</label>
                <input type="text" id="loginUsername" name="loginUsername" required>
            </div>
            <div class="form-group">
                <label for="loginPassword"><i class="fas fa-key"></i> Mot de passe</label>
                <input type="password" id="loginPassword" name="loginPassword" required>
            </div>
            <button type="submit" class="auth-button"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
        </form>

        <!-- Formulaire d'Inscription -->
        <form id="registerForm" class="auth-form" action="./vues/register.php" method="POST">
            <div class="form-group">
                <label for="registerUsername"><i class="fas fa-user"></i> Nom d'utilisateur</label>
                <input type="text" id="registerUsername" name="registerUsername" required>
            </div>
            <div class="form-group">
                <label for="registerEmail"><i class="fas fa-envelope"></i> Adresse e-mail</label>
                <input type="email" id="registerEmail" name="registerEmail" required>
            </div>
            <div class="form-group">
                <label for="registerPassword"><i class="fas fa-key"></i> Mot de passe</label>
                <input type="password" id="registerPassword" name="registerPassword" required>
            </div>
            <div class="form-group">
                <label for="registerConfirmPassword"><i class="fas fa-lock"></i> Confirmer le mot de passe</label>
                <input type="password" id="registerConfirmPassword" name="registerConfirmPassword" required>
            </div>
            <div class="form-group">
                <label for="registerRole"><i class="fas fa-user-tag"></i> Rôle</label>
                <select id="registerRole" name="registerRole" required>
                    <option value="">Sélectionnez un rôle</option>
                    <option value="secretaire">Secrétaire</option>
                    <option value="comptable">Comptable</option>
                    <option value="direction">Direction</option>
                    <option value="administrateur">Administrateur</option>
                </select>
            </div>
            <button type="submit" class="auth-button"><i class="fas fa-user-plus"></i> S'inscrire</button>
        </form>

        <!-- Affichage des messages -->
        <div id="confirmationMessage" class="confirmation-message" style="display: none;"></div>
        <div id="errorMessage" class="error-message" style="display: none;"></div>

    </div>

    <script>
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const errorMessage = document.getElementById('errorMessage');
    const confirmationMessage = document.getElementById('confirmationMessage');

    loginTab.addEventListener('click', showLogin);
    registerTab.addEventListener('click', showSignup);

    function showLogin() {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
    }

    function showSignup() {
        registerTab.classList.add('active');
        loginTab.classList.remove('active');
        registerForm.classList.add('active');
        loginForm.classList.remove('active');
    }

    function displayError(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
    }

    function hideError() {
        errorMessage.style.display = 'none';
    }

    function displayConfirmation(message) {
        confirmationMessage.textContent = message;
        confirmationMessage.style.display = 'block';
    }

    function hideConfirmation() {
        confirmationMessage.style.display = 'none';
    }

    function submitRegisterForm() {
        const username = document.getElementById('registerUsername').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const confirmPassword = document.getElementById('registerConfirmPassword').value;
        const role = document.getElementById('registerRole').value;

        if (password !== confirmPassword) {
            displayError("Les mots de passe ne correspondent pas.");
        } else if (role === "") {
            displayError("Veuillez sélectionner un rôle.");
        } else {
            fetch('./vues/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    registerUsername: username,
                    registerEmail: email,
                    registerPassword: password,
                    registerRole: role
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayConfirmation(data.message);
                    setTimeout(() => {
                        hideConfirmation();
                        loginForm.querySelector('input[name="loginUsername"]').value = username;
                        loginForm.querySelector('input[name="loginPassword"]').value = password;
                        loginForm.submit(); // Soumettre automatiquement le formulaire de connexion
                    }, 3000);
                } else {
                    displayError(data.message);
                }
            })
            .catch(error => {
                displayError("Erreur de connexion au serveur.");
            });
        }
    }

    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        hideError();
        submitRegisterForm();
    });
</script>
</body>
</html>
