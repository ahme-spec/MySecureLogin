<?php
session_start(); 


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // pour generer un token CSRF
}

if (empty($_SESSION['nonce'])) {
    $_SESSION['nonce'] = bin2hex(random_bytes(16)); // POur generer un nonce de 16 octets
}

// la fonction pour securiser les affichages
function secureOutput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Sécurisée</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="logo-container">
        <img src="logo.png" alt="Logo du site" class="logo">
    </div>
    
    <div class="login-container">
        
        <div id="message">
            <?php
            if (isset($_SESSION['error'])) {
                echo "<p class='error'>" . secureOutput($_SESSION['error']) . "</p>";
                unset($_SESSION['error']); 
            }

            if (isset($_SESSION['success'])) {
                echo "<p class='success'>" . secureOutput($_SESSION['success']) . "</p>";
                unset($_SESSION['success']); 
            }
            ?>
        </div>
        
        <!-- mon formulaire de connexion -->
        <div id="login-form">
            <h2>Connexion</h2>
            <form action="login.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <label for="username">Identifiant :</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <div class="button-group">
                    <button type="reset">Réinitialiser</button>
                    <button type="submit">Se connecter</button>
                    <button type="button" id="signupButton">S'inscrire</button>
                </div>
            </form>
        </div>
        
        <!-- mon formulaire d'inscription -->
        <div id="register-form">
            <h2>Inscription</h2>
            <form action="register.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <label for="username">Identifiant :</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <div class="button-group">
                    <button type="reset">Réinitialiser</button>
                    <button type="submit">S'inscrire</button>
                    <button type="button" id="backButton">Retour</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js" nonce="<?php echo $_SESSION['nonce']; ?>"></script>
</body>
</html>
