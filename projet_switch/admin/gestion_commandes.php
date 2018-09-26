<?php
  require_once('../inc/init.inc.php');
  $title = 'Gestion des commandes';

  if(!userIsAdmin()) {
    header('location:' . URL . 'profil.php');
    exit(); // bloque l'exécution du code en dessous dans la page
  }





  require_once('../inc/header.inc.php');
  require_once('../inc/nav.inc.php');
  require_once('../inc/menu_admin.inc.php');
?>


    <main role="main" class="container">

      <div class="starter-template text-center mt-4">
     
        <hr>
        <?php echo $msg; ?>
      </div>

      <?php 
          // affichage du tableau
          //if(isset($_GET['action']) && $_GET['action'] == 'afficher') {
           
             //var_dump($listeCommande);


            echo '<div class="col-12">';
              
              // Tableau
              //$listeCommande = $pdo->query("SELECT c.id_commande, c.id_membre, c.id_produit, c.date_enregistrement FROM commande c, produit p, membre m WHERE c.id_membre = m.id_membre AND c.id_membre = c.id_produit");

              $listeCommande = $pdo->query("SELECT * FROM commande ");


              echo '<div class="table-responsive table-bordered mb-4">';
              echo '<table class="table table-bordered">';

              echo '<thead>';
              echo '<tr>';
              // affichage des colonnes en récupérant les noms dans l'objet
              //columnCount()
              $nbColonnes = $listeCommande->columnCount(); // le nombre de colonnes
              for($i = 0; $i<$nbColonnes;$i++) {
                $colonne = $listeCommande->getColumnMeta($i);
                //print "<pre>"; var_dump($colonne); print "</pre>";
                echo '<th style="padding:10px" class="bg-success text-white">' .$colonne['name'] . '</th>';

              }
              echo '<th class="bg-success text-white">consulter</th>';
              echo '<th class="bg-success text-white">modifier</th>';
              echo '<th class="bg-success text-white">supprimer</th>';
              echo '</tr>';

              // ou faire un tableau manuel
              /*echo '<tr>'
              echo '<th>id_commande</th>';
              echo '<th>id_commande</th>';
              echo '<th>categorie</th>';
              echo '<th>titre</th>';
              echo '<th>description</th>';
              etc...
              */


                //affichage des données
              while($commande = $listeCommande->fetch(PDO::FETCH_ASSOC)) {
                echo'<tr>';

                foreach ($commande AS $indice => $valeur) {

                  if($indice == 'photo') {
                      echo'<td><img src="' . URL . $valeur . '" alt="commande" width="140"></td>';
                  } elseif ($indice == 'description') {
                      echo'<td><a href="" title="' . $valeur . '">' . substr($valeur, 0, 20) . '...</a></td>';
                  } else {
                      echo '<td>' . $valeur . '</td>';
                  }
                  
                }
                echo '<td class="text-center align-middle"> <a href="?action=consulter&id_commande=' . $commande['id_commande'] . '" class="btn btn-light";"><i class="far fa-eye" aria-hidden="true"></i></a></td>';
                echo '<td class="text-center align-middle"> <a href="?action=modifier&id_commande=' . $commande['id_commande'] . '" class="btn btn-light";"><i class="fas fa-edit" aria-hidden="true"></i></a></td>';
                echo '<td class="text-center align-middle"><a href="?action=supprimer&id_commande=' . $commande['id_commande'] . '" class="btn btn-light" onclick="return(confirm(\' Etes vous sûr ? \'));"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></td>';
                echo '<tr>';
              }
            echo '</thead>';
            echo '</table>';  
            echo '</div>';  

          //}  

        ?> 

    </main><!-- /.container -->
	

<?php
  require_once('../inc/footer.inc.php');

	
	
	
	
