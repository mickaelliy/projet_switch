<?php

header("Content-Type: json/application");

$resultat = array();

if(isset($_POST['id_article']) && is_numeric($_POST['id_article'])){

	$conn = new PDO("mysql:host=localhost;dbname=api","api","azerty1234!");

	$r = $conn->prepare("INSERT INTO rating (valeur,id_article) VALUES (:valeur,:id_article) ");


	$valeur = htmlspecialchars($_POST['rating']);
	$id_article = htmlspecialchars($_POST['id_article']);

	$r->bindParam(":valeur",$valeur);
	$r->bindParam(":id_article",$id_article);

	$r->execute();

	$resultat = array("status"=>"note ajoutée en BDD");
}


echo json_encode($resultat);
