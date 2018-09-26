<?php
// connexion à ma BDD
$pdo = new PDO('mysql:host=localhost;dbname=switch', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// appel de nos fonctions
require_once('function.inc.php');

// création de la session
session_start();

//Déclarer les constantes 
//Racine serveur
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/projet_switch/');
//Racine site
define('URL', 'http://localhost/projet_switch/');

// création d'une variable destinée à afficher des messages utilisateurs
$msg = '';