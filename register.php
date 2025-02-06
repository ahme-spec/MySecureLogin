<?php

session_start(); 

session_regenerate_id(true);

// Ici la verifications de la soumission formulaire

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $servername = "localhost";
    $username = "root";  
    $password = "VOTRE_MOT_DE_PASSE";  
    $dbname = "users_db"; 

    try {
        // La Créeation d'une connexion securisée avec PDO
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

        // Ici je recupérer et sécurise les données du formulaire
        $user_name = trim($_POST['username']);
        $user_password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        $stmt = $pdo->prepare("SELECT username FROM users WHERE username = :username"); 
        $stmt->bindParam(':username', $user_name, PDO::PARAM_STR); 
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Identifiant invalide. Veuillez en choisir un autre.";
            header('Location: site.php');
            exit();
        }

        // Ici je verifie la complexité du mot de passe (au moins 8 caracteres, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractere spécial)
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $user_password)) { 
            $_SESSION['error'] = "8 caractères minimum : 1 maj, 1 min, 1 chiffre, 1 spé.";
            header('Location: site.php');
            exit();
        }

        if ($user_password !== $confirm_password) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
            header('Location: site.php');
            exit();
        }

        // Le hachage du mot de passe
        $hashed_password = password_hash($user_password, PASSWORD_BCRYPT);

        // Ici une requête preparée pour eviter les injections SQL
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)"); 
        $stmt->bindParam(':username', $user_name, PDO::PARAM_STR); 
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR); 

        if ($stmt->execute()) {
            $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            header('Location: site.php');
            exit();
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de la connexion. Veuillez réessayer plus tard.";
            header('Location: site.php');
            exit();
        }

    } catch (PDOException $e) {

        $_SESSION['error'] = "Erreur de connexion : " . $e->getMessage();
        header('Location: site.php');
        exit();
    }
}
$pdo = null; 
?>
