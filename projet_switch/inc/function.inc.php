<?php

 
function debug($var, $mode = 1)
{
    echo '<div style="background: orange; padding: 5px; float: right; clear: both; ">';
    $trace = debug_backtrace();
    $trace = array_shift($trace);
    echo 'Debug demandé dans le fichier : $trace[file] à la ligne $trace[line].';
    if($mode === 1)
    {
        echo '<pre>'; print_r($var); echo '</pre>';
    }
    else
    {
        echo '<pre>'; var_dump($var); echo '</pre>';
    }
    echo '</div>';
}

//utilisateur est connecté
function userIsConnect() {
	// si l'indice membre existe dans session alors, l'utilisateur est forcément passé par connexion et donc nous a donné un pseudo et un mdp correct.
	if(isset($_SESSION['membre'])) {
		return true;
	}
	return false;
}

// utilisateur est connecté et est admin !
function userisAdmin () {
	if(userIsConnect() && $_SESSION['membre']['statut'] == 2) {
		return true;
	}
	return false;
}

// fonction pour créer le panier
function creationDuPanier()
{ // si la session n'existe pas, nous la créons
   if(!isset($_SESSION['panier']))
   {
      $_SESSION['panier'] = array();
      $_SESSION['panier']['titre'] = array();
      $_SESSION['panier']['id_produit'] = array();
      $_SESSION['panier']['quantite'] = array();
      $_SESSION['panier']['prix'] = array();
   }
}
//------------------------------------
//Ajout du produit dans le panier
function ajouterProduitDansPanier($titre, $id_produit, $quantite, $prix)
{
    creationDuPanier(); 
    $position_produit = array_search($id_produit,  $_SESSION['panier']['id_produit']);
    if($position_produit !== false)
    {							//+=permet de ne pas perdre l'ajout du produit précédent
         $_SESSION['panier']['quantite'][$position_produit] += $quantite ;
    }
    else
    {
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;
    }
}
//------------------------------------
//Calculer le montant total du panier
function montantTotal()
{
   $total=0;
   for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
   {
      $total += $_SESSION['panier']['prix'][$i];
   }
   return round($total,2); 

   /*$total = array($_SESSION['panier']['prix']);
   echo "sum(total) =" . array_sum($total) . "\n" ;*/
}
//------------------------------------
// retirer le produit du panier
function retirerProduitDuPanier($id_produit_a_supprimer)
{
    $position_produit = array_search($id_produit_a_supprimer,  $_SESSION['panier']['id_produit']);
    if ($position_produit !== false)
    {
        array_splice($_SESSION['panier']['titre'], $position_produit, 1);
        array_splice($_SESSION['panier']['id_produit'], $position_produit, 1);
        array_splice($_SESSION['panier']['quantite'], $position_produit, 1);
        array_splice($_SESSION['panier']['prix'], $position_produit, 1);
    }
}
//------------------------------------