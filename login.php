<?php

session_start();
session_regenerate_id(true);


$servername = "localhost";
$username = "root";  
$password = "VOTRE_MOT_DE_PASSE";  
$dbname = "users_db";  

$max_attempts = 3; 
$time_frame = 3; 

try {
   
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $user_name = trim($_POST['username']); 
        $user_password = $_POST['password'];

        // Verifier les tentatives echouées dans la base de donnee
        $stmt = $pdo->prepare("SELECT COUNT(*) AS attempt_count FROM login_attempts WHERE username = :username AND attempt_time > NOW() - INTERVAL :time_frame MINUTE");
        $stmt->bindParam(':username', $user_name, PDO::PARAM_STR);
        $stmt->bindParam(':time_frame', $time_frame, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si trop de tentatives, on bloque la connexion
        if ($result['attempt_count'] >= $max_attempts) {
            $_SESSION['error'] = "Trop de tentatives échouées. Veuillez réessayer dans $time_frame minutes.";
            header('Location: site.php');
            exit();
        }

        
        $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $user_name, PDO::PARAM_STR);
        $stmt->execute();

        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password_from_db = $user['password'];

            // Ici on verifie le mot de passe avec password_verify
            if (password_verify($user_password, $hashed_password_from_db)) {
                $_SESSION['success'] = "Vous êtes connecté avec succès !";
                header('Location: site.php'); 
                exit();
            } else {
                // Ici on enregistre les tentative de connection
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $stmt = $pdo->prepare("INSERT INTO login_attempts (username, ip_address) VALUES (:username, :ip_address)");
                $stmt->bindParam(':username', $user_name, PDO::PARAM_STR);
                $stmt->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
                $stmt->execute();

                $_SESSION['error'] = "Identifiant ou mot de passe incorrect. Veuillez réessayer.";
                header('Location: site.php'); 
                exit();
            }
        } else {
            $_SESSION['error'] = "Identifiant ou mot de passe incorrect. Veuillez réessayer.";
            header('Location: site.php'); 
            exit();
        }
    }

} catch (PDOException $e) {
    
    $_SESSION['error'] = "Une erreur est survenue lors de la connexion. Veuillez réessayer plus tard.";
    header('Location: site.php'); 
    exit();
}

$pdo = null; 
?>
