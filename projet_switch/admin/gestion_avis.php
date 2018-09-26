<?php
  require_once('../inc/init.inc.php');

   if(!userIsAdmin()) {
    header('location:' . URL . 'profil.php');
    exit(); // bloque l'exécution du code en dessous dans la page
  }

  //je déclare mes variables et je vérifie qu'elles soient remplies.
  $id_avis = isset($_POST['id_avis']) ? $_POST['id_avis'] : '';
  $id_membre = isset($_POST['id_membre']) ? $_POST['id_membre'] : '';
  $id_salle = isset($_POST['id_salle']) ? $_POST['id_salle'] : '';
  $commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : '';
  $note = isset($_POST['note']) ? $_POST['note'] : '';
  $date_enregistrement = isset($_POST['date_enregistrement']) ? $_POST['date_enregistrement'] : '';

//$listeAvis = $pdo->query("SELECT * FROM avis");// je récupère tous les avis de la BDD

/********************************************************
******************* SUPPRESSION avis ******************
*********************************************************/

if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_avis']) && is_numeric($_GET['id_avis'])){
//si l'indice action exsiste dans 	$_GET et sa valeur est égale à "supprimer", et si l'indice id_avis existe dans $_GET, et que sa valeur est de type numérique, alors je peux procédéer la requête.
	$suppression = $pdo->prepare("DELETE FROM avis WHERE id_avis = :id_avis");
	$suppression->bindParam('id_avis', $_GET['id_avis'], PDO::PARAM_STR);
	$suppression->execute();//j'exécute la requête de suppression.
	$msg .= '<div class="bg-success p-3 text white mt-3" role="alert">Votre avis a bien été supprimé</div>';
	$_GET['action'] = 'afficher';// on rebascule sur l'affichage tableau.
}


  $id_avis =isset($_POST['id_avis']) ? $_POST['id_avis'] : '';
  $id_membre = isset($_POST['id_membre']) ? $_POST['id_membre'] : ''; 
  $id_salle = isset($_POST['id_salle']) ? $_POST['id_salle'] : '';
  $commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : '';
  $note = isset($_POST['note']) ? $_POST['note'] : ''; 
  $date_enregistrement = isset($_POST['date_enregistrement']) ? $_POST['date_enregistrement'] : '';// (int) devant une valeur  permet de forcer la valeur en entier et  // trim () est une fonction prédéfinie permettant d'enlever les espaces en début et fin de chaîne.
  
  
/********************************************************
******************* MODIFICATION avis ******************
*********************************************************/

  if(isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['id_avis']) && is_numeric($_GET['id_avis']) && empty($_POST)) {
      $avisActuel = $pdo->prepare("SELECT * FROM avis WHERE id_avis = :id_avis");
      $avisActuel->bindParam(':id_avis', $_GET['id_avis'], PDO::PARAM_STR);
      $avisActuel->execute();

      $avisActuel = $avisActuel->fetch(PDO::FETCH_ASSOC);

      $id_avis = $avisActuel['id_avis'];
      $id_membre = $avisActuel['id_membre'];
      $id_salle = $avisActuel['id_salle'];
      $commentaire = $avisActuel['commentaire'];
      $note = $avisActuel['note'];
      $date_enregistrement = $avisActuel['date_enregistrement'];

      
  }

  // if(empty($msg)) {
  //   //enregistrement en BDD
  //   if(isset($_GET['action']) && $_GET['action'] == 'ajouter') {
  //   $requete = $pdo->prepare("INSERT INTO avis (id_avis, id_membre, id_salle, commentaire, note, date_enregistrement) VALUES (:id_avis, :id_membre, :id_salle, :commentaire, :note, :date_enregistrement)");
  // } else {
  //   $requete = $pdo->prepare("UPDATE avis SET id_avis = :id_avis, id_membre = :id_membre, id_salle = :id_salle, commentaire = :commentaire, note = :note, date_enregistrement = :date_enregistrement WHERE id_avis = :id_avis");
  //   $requete->bindParam(':id_avis', $id_avis, PDO::PARAM_STR);
  // }


  //     $requete->bindParam(':id_avis', $id_avis, PDO::PARAM_STR);
  //     $requete->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
  //     $requete->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
  //     $requete->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
  //     $requete->bindParam(':note', $note, PDO::PARAM_STR);
  //     $requete->bindParam(':date_enregistrement', $date_enregistrement, PDO::PARAM_STR);
  //     $requete->execute();

  //     $_GET['action'] == 'afficher';
  // }

  $avis = '';
  //requete de récupération de tous les salles en BDD
  $listeavis = $pdo->query("SELECT * FROM avis");

  require_once('../inc/header.inc.php');
  require_once('../inc/nav.inc.php');
  require_once('../inc/menu_admin.inc.php');
  
  //
?>


    <main role="main" class="container">

      <div class="starter-template text-center">
        <?php echo $msg; ?>
        <?php // ?>
        <hr>
        <a href="?action=afficher" class="btn btn-info">Afficher les avis</a>
         <hr>
      </div>
      

      <div  class="row">

      <?php if(isset($_GET['action']) && ($_GET['action'] == 'ajouter' || $_GET['action'] == 'modifier')) { ?>  

          <div class="col-sm-6 mx-auto">
            <form method="post" action="" enctype="multipart/form-data">
              
              <div class="form-group">
                  <label for="">id_membre</label>
                  <!--<input type="date" class="form-control" id="date_arrivee" name="date_arrivee" placeholder="" value="<?php echo $date_arrivee; ?>"> -->
                  <input type="text" class="form-control" id="id_membre" name="id_membre" placeholder="" value="<?php echo $id_membre; ?>"> 
              </div>
              <div class="form-group">
                  <label for="">id_salle</label>
                  <!--<input type="date" class="form-control" id="date_arrivee" name="date_arrivee" placeholder="" value="<?php echo $date_arrivee; ?>"> -->
                  <input type="text" class="form-control" id="id_salle" name="id_salle" placeholder="" value="<?php echo $id_salle; ?>"> 
              </div>
              <div class="form-group">
                  <label for="">commentaire</label>
                  <!--<input type="date" class="form-control" id="date_depart" name="date_depart" placeholder="" value="<?php echo $date_depart; ?>"> --> 
                  <input type="text" class="form-control" id=commentaire" name="commentaire" placeholder="" value="<?php echo $commentaire; ?>">             
              </div>
              <div class="form-group">
                <label for="note">note</label>
                <input type="text" class="form-control" id="note" name="note" placeholder="" value="<?php echo $note; ?>">
              </div>
              <div class="form-group">
                  <label for="">Date d'enregistrement</label>
                  <input type="text" class="form-control" id="date_enregistrement" name="date_enregistrement" placeholder="" value="<?php echo $date_enregistrement; ?>"> 
              </div>
  
              <hr>
              <input type="submit" class="btn btn-success w-100" name="enregistrer" 
              <?php if(isset($_GET['action'])) {echo 'value="' . ucfirst($_GET['action']) . '"';} ?> >

            </form> 
          </div> 

        <?php } ?>

        <?php 
          // affichage du tableau
          if(isset($_GET['action']) && $_GET['action'] == 'afficher') {
            //exercice: récupérer tous les salles de la BDD et les afficher ici dans un tableau html !
            // afficher l'image dans un <img src=""> 
             


            echo '<div class="col-12">';
              
              // Tableau
              $listeavis = $pdo->query("SELECT a.id_avis, a.id_membre, a.id_salle, s.titre, s.photo, a.commentaire, a.note, a.date_enregistrement FROM avis a, salle s WHERE a.id_salle = s.id_salle");

              echo '<div class="table-responsive table-bordered mb-4">';
              echo '<table class="table table-bordered">';

              echo '<thead>';
              echo '<tr>';
              // affichage des colonnes en récupérant les noms dans l'objet
              //columnCount()
              $nbColonnes = $listeavis->columnCount(); // le nombre de colonnes
              for($i = 0; $i<$nbColonnes;$i++) {
                $colonne = $listeavis->getColumnMeta($i);
                //print "<pre>";  print "</pre>";
                echo '<th style="padding:10px" class="bg-warning text-white">' .$colonne['name'] . '</th>';

              }
              echo '<th class="bg-warning text-white">Consulter</th>';
              echo '<th class="bg-warning text-white">Modifier</th>';
              echo '<th class="bg-warning text-white">Supprimer</th>';
              echo '</tr>';

              //affichage des données
              while($avis = $listeavis->fetch(PDO::FETCH_ASSOC)) {
                echo'<tr>';

                foreach ($avis AS $indice => $valeur) {


                  if($indice == 'note'){

                    $compteurEtoiles = 0;
                    $noteFinale = '';

                    while($compteurEtoiles < $valeur){                         
                            $noteFinale = $noteFinale  . '⭐';
                            $compteurEtoiles++;
                    }

                    echo '<td>' . $noteFinale . '</td>';

                  }
                  else{
                    echo '<td>' . $valeur . '</td>';
                  }

                }
                echo '<td class="text-center align-middle"> <a href="?action=consulter&id_avis=' . $avis['id_avis'] . '" class="btn btn-light";"><i class="far fa-eye" aria-hidden="true"></i></a></td>';
                echo '<td class="text-center align-middle"> <a href="?action=modifier&id_avis=' . $avis['id_avis'] . '" class="btn btn-light";"><i class="fas fa-edit" aria-hidden="true"></i></a></td>';
                echo '<td class="text-center align-middle"><a href="?action=supprimer&id_avis=' . $avis['id_avis'] . '" class="btn btn-light" onclick="return(confirm(\' Etes vous sûr ? \'));"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></td>';
                echo '<tr>';
              }
              echo '</thead>';
            echo '</table>';  
            echo '</div>';   
          }  
        ?> 
      </div>
    </main><!-- /.container -->
	<?php
  require_once('../inc/footer.inc.php');