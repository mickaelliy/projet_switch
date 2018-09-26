<?php
  require_once('inc/init.inc.php');

  //--- AJOUT PANIER ---//
  if(isset($_POST['ajout_panier'])) 
  {   // debug($_POST);
      $resultat = $pdo->query("SELECT * FROM produit, salle WHERE produit.id_salle = salle.id_salle AND produit.id_produit='$_POST[id_produit]'");

      $produit = $resultat->fetch(PDO::FETCH_ASSOC);
      ajouterProduitDansPanier($produit['titre'],$_POST['id_produit'],$_POST['quantite'],$produit['prix']);
  }

  //--- VIDER PANIER ---//
  if(isset($_GET['action']) && $_GET['action'] == "vider")
  {
      unset($_SESSION['panier']);
  }
  //--- PAIEMENT ---//
  if(isset($_POST['payer']))
  {
      if(!isset($erreur))
      {
          $resultat = $pdo->exec("INSERT INTO commande (id_membre, montant, date_enregistrement,id_produit) VALUES (" . $_SESSION['membre']['id_membre'] . "," . montantTotal() . ", NOW(), id_produit)");
          //$id_commande = $pdo->insert_id;
          
          unset($_SESSION['panier']);
         // mail($_SESSION['membre']['email'], "confirmation de la commande", "Merci votre n° de suivi est le $id_commande", "From:vendeur@dp_site.com");
         
          //$msg .= "<div class='validation'>Merci pour votre commande. votre n° de suivi est le $id_commande</div>";
      }
  }




  require_once('inc/header.inc.php');
  require_once('inc/nav.inc.php');
  //var_dump ($_POST);
  //var_dump ($_SESSION);

?>

  <main role="main" class="container">
    <div class="ba mx-auto text-center">
        <?php
              echo "<table border='1' style='border-collapse: collapse;width:100%;' cellpadding='7'>";
              echo "<tr><td colspan='7' class='text-center' >Votre Panier</td></tr>";
              echo "<tr><th>Titre</th><th>Produit</th><th>Quantité</th><th>Prix Unitaire</th></tr>";
              if(empty($_SESSION['panier']['id_produit'])) // panier vide
              {
                  echo "<tr><td colspan='5'>Votre panier est vide</td></tr>";
              }
              else
              {
                  for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) 
                  {
                      echo "<tr>";
                      echo "<td>" . $_SESSION['panier']['titre'][$i] . "</td>";
                      echo "<td>" . $_SESSION['panier']['id_produit'][$i] . "</td>";
                      echo "<td>" . $_SESSION['panier']['quantite'][$i] . "</td>";
                      echo "<td>" . $_SESSION['panier']['prix'][$i] . "</td>";
                      echo "</tr>";
                  }
                  echo "<tr><th colspan='3'>Total</th><td colspan='2'>" . montantTotal() . " euros</td></tr>";
                  if(userIsConnect()) 
                  {
                      echo '<form method="post" action="">';
                      echo '<tr><td colspan="5" class="text-center"><input type="submit" class="btn" style="background-color: #00FFBF" name="payer" value="Valider votre commande"></td></tr>';
                      echo '</form>';   
                  }
                  else
                  {
                      echo '<tr><td colspan="3">Veuillez vous <a href="inscription.php">inscrire</a> ou vous <a href="connexion.php">connecter</a> afin de pouvoir payer</td></tr>';
                  }
                  echo "<tr><td colspan='5'><a href='?action=vider'>Vider mon panier</a></td></tr>";
              }


              echo "</table><br>";
              echo "<i>Réglement par CHÈQUE uniquement à l'adresse suivante : 300 rue de vaugirard 75015 PARIS</i><br>";
              //echo "<hr>session panier:<br>"; //debug($_SESSION);

        ?>
    </div>

  </main><!-- /.container -->
	

<?php
  require_once('inc/footer.inc.php');

	
	
	
	
