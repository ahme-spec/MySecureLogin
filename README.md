                                                       Authentification sécurisée en PHP :


----------------------------------------------------------------> Arborescence du projet <----------------------------------------------------

|
│── /assets
│   ├── logo.png          # Logo du site
|         
│── /css
│   ├── style.css         # Fichier de styles CSS
|
│── /js
│   ├── script.js         # Scripts JavaScript pour interactions dynamiques
|
│── /views
│   ├── register.php      # Page d'inscription
│   ├── login.php         # Page de connexion
|
│── site.php              # Page d'accueil
|
│── README.md             # Documentation du projet



------------------------------------------------------------------> Description du projet : <----------------------------------------------------


Ce projet est une implémentation sécurisée d'un système d'authentification en PHP avec :

1) Protection contre les injections SQL avec requêtes préparées (PDO).

2) Hachage sécurisé des mots de passe avec password_hash().

3) Protection contre les attaques CSRF avec un token sécurisé.

4) Limitation des tentatives de connexion pour éviter les attaques par force brute.

5) Regénération de l'ID de session après connexion pour éviter le vol de session.


-------------------------------------------------------------> Les Technologies utilisées : <----------------------------------------------------

1) PHP 7.4 ou supérieur

2) JavaScript

3) HTML5 et CSS3

2) Serveur local (XAMPP)

3) MySQL

4) phpMyAdmin


----------------------------------------------------------> Configuration de la base de données : <----------------------------------------------------

CREATE DATABASE users_db;

USE users_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) NOT NULL
);

-------------------------------------------------------------> Configurer les fichier register.php et login.php : <------------------------------------------

<?php
$host = "localhost";
$dbname = "users_db";
$username = "root";
$password = "TON_MOT_DE_PASSE"; // Mets ton mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : Accès refusé");
}
?>

------------------------------------------------------------------> Lancer le serveur local <------------------------------------------------------------------

1) Si tu utilises XAMPP, démarre Apache et MySQL :

$ sudo /opt/lampp/lampp start

$ sudo /opt/lampp/lampp status

    Version: XAMPP for Linux 8.2.4-0
    Apache is running.
    MySQL is running.
    ProFTPD is running.

2) Puis accède à : http://localhost/phpmyadmin/


--------------------------------------------------------------------> Sécurité <-------------------------------------------------------------------------------

1) Hachage des mots de passe (password_hash())

2) Requêtes préparées pour éviter les injections SQL

3) Session sécurisée (session_regenerate_id(true))

4) Protection contre le brute force (limitation des tentatives)

5) Protection du formulaire contre les attaques XSS et CSRF : htmlspecialchars() | $_SESSION['csrf_token'] = bin2hex(random_bytes(32)) .....


--------------------------------------------------------------------> Auteur <--------------------------------------------------------------------------------

IZEKKI Ahmed

contact : ahmedizekki@hotmail.com

--------------------------------------------------------------------------------------------------------------------------------------------------------------