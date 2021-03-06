<?php
  require_once('../inc/init.inc.php');

   if(!userIsAdmin()) {
    header('location:' . URL . 'profil.php');
    exit(); // bloque l'exécution du code en dessous dans la page
  }



$listeSalle = $pdo->query("SELECT * FROM salle");


/********************************************************
*******************SUPPRESSION produit ******************
*********************************************************/

  if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_produit']) && is_numeric($_GET['id_produit'])) {
    //si l'indice action exsiste dans $_GET et sa valeur est égale à "supprimer", et si l'indice id_produit existe dans $_GET, et que sa valeur est de type numérique.
      $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
      $suppression->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
      $suppression->execute();
      $msg .= '<div class="bg-success p-3 text-white mt-3" role="alert">Le produit n°' . $_GET['id_produit'] . ' a bien été supprimée</div>';
      $_GET['action'] = 'afficher'; // on rebascule sur l'affichage tableau.
  }
  
  // (int) devant une valeur  permet de forcer la valeur en entier
  // trim () est une fonction prédéfinie permettant d'enlever les espaces en début et fin de chaîne.

  $id_produit =isset($_POST['id_produit']) ? $_POST['id_produit'] : '';
  $photoActuelle = isset($_POST['photoActuelle']) ? $_POST['photoActuelle'] : ''; 
  $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
  $date_arrivee = isset($_POST['date_arrivee']) ? $_POST['date_arrivee'] : '';
  $date_depart = isset($_POST['date_depart']) ? $_POST['date_depart'] : ''; 
  $prix = isset($_POST['prix']) ? (int)trim($_POST['prix']) : '';
  $etat = isset($_POST['etat']) ? $_POST['etat'] : '';
  $id_salle = isset($_POST['id_salle']) ? $_POST['id_salle'] : '';
  
  
  
/********************************************************
*******************MODIFICATION produit ******************
*********************************************************/

  if(isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['id_produit']) && is_numeric($_GET['id_produit']) && empty($_POST)) {
      $produitActuel = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
      $produitActuel->bindParam(':id_produit', $_GET['id_produit'], PDO::PARAM_STR);
      $produitActuel->execute();

      $produitActuel = $produitActuel->fetch(PDO::FETCH_ASSOC);

      $id_produit = $produitActuel['id_produit'];
      $date_arrivee = $produitActuel['date_arrivee'];
      $date_depart = $produitActuel['date_depart'];
      $prix = $produitActuel['prix'];
      $etat = $produitActuel['etat'];

      
  }

  if($_POST) { // equivalent à if (!empty($_POST))

    $photo_bdd = $photoActuelle; //déclaration de cette variable vide car si l'utilisateur ne charge pas de photo, la variable existe, on évite une erreur sql au cas où.


    //Controle sur l'existence de la référence (unique en BDD)
    $checkid_produit = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $checkid_produit->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
    $checkid_produit->execute();

    if($checkid_produit->rowCount() > 0 && isset($_GET['action']) && $_GET['action'] != 'modifier') {
       $msg .= '<div class="alert alert-danger" role="alert">Attention, <br> la référence existe déjà et doit être unique<br>Veuillez recommencer </div>';
    }

    //traitement de la photo
    if(!empty($_FILES['photo']['name']) && empty($msg)) {

      //pour ne pas écraser un image du même nom, on ajoute la référence (qui est unique) sur son nom
      $nom_photo = $id_produit . '_' . $_FILES['photo']['name'];
      $photo_bdd  = "assets/img/" . $nom_photo; // valeur qui sera enregistrée dans la BDD (représente le src d'une image)

      //déclaration des formats acceptés
      $extensionValide = array("png", "jpg","jpeg", "gif");

      //on récupère l'extension du fichier
      $extension = strrchr($_FILES['photo']['name'], '.');
      //strrchr découpe une chaîne en partant de la fin. Premier argument :la chaîne, deuxième argument le caractère où on coupe. Nous renvoie le tout avec le caractère concerné.
      //exemple: pantalon.jpg => on récupère .jpg

      $extension = strtolower(substr($extension, 1));

      $verifExtension = in_array($extension, $extensionValide);
      // in_array cherche la valeur fournie en premier argument dans les valeurs d'un tableau array fourni en deuxième argument. 

      if($verifExtension){
      //Si l'extention est correcte, on enregistre l'image de son emplacement temporaire ($_FILES['photo']['tmp_name']) dans notre dossier img
      //pour copier, nous avons besoin de la racine serveur (voir sur init.inc.php)

      $photo_dossier = ROOT . $photo_bdd ;

      // on copie:
      copy($_FILES['photo']['tmp_name'], $photo_dossier);
      //copy(emplacement_de_base, amplacement_cible)

    } else {
      $msg .= '<div class="alert alert-danger" role="alert">Attention <br> l\'extension du fichier n\'est pas valide <br>Extentions acceptées : .jpg .gif .jpeg .png</div>';

    } 
    }

  if(empty($msg)) {
    //enregistrement en BDD
    if(isset($_GET['action']) && $_GET['action'] == 'ajouter') {
    $requete = $pdo->prepare("INSERT INTO produit (date_arrivee, date_depart, prix, etat, id_salle) VALUES (:date_arrivee, :date_depart, :prix, :etat, :id_salle)");
  } else {
    $requete = $pdo->prepare("UPDATE produit SET date_arrivee = :date_arrivee, date_depart = :date_depart, prix = :prix, etat = :etat, id_salle = :id_salle WHERE id_produit = :id_produit");
    $requete->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
  }


      //$requete->bindParam(':id_produit', $id_produit, PDO::PARAM_STR);
      $requete->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
      $requete->bindParam(':date_arrivee', $date_arrivee, PDO::PARAM_STR);
      $requete->bindParam(':date_depart', $date_depart, PDO::PARAM_STR);
      $requete->bindParam(':prix', $prix, PDO::PARAM_STR);
      $requete->bindParam(':etat', $etat, PDO::PARAM_STR);
      $requete->execute();

      $_GET['action'] == 'afficher';
  }

  }


  //requete de récupération de tous les salles en BDD
  $listeproduit = $pdo->query("SELECT * FROM produit");

  require_once('../inc/header.inc.php');
  require_once('../inc/nav.inc.php');
  //var_dump($_POST);
  //var_dump($_FILES);
  require_once('../inc/menu_admin.inc.php');
?>


    <main role="main" class="container">

      <div class="starter-template text-center">
        <h1>Gestion des Produits&nbsp;</h1>
        <?php echo $msg; ?>
        <?php //var_dump ($_SERVER); ?>
        <hr>
        <a href="?action=ajouter" class="btn btn-primary">Ajouter un produit&nbsp;</a>
        <a href="?action=afficher" class="btn btn-info">Afficher les produits&nbsp;</a>
         <hr>
      </div>
      

      <div  class="row">

      <?php if(isset($_GET['action']) && ($_GET['action'] == 'ajouter' || $_GET['action'] == 'modifier')) { ?>  

          <div class="col-sm-6 mx-auto">
            <form method="post" action="" enctype="multipart/form-data">
              
              <input type="hidden" name="id_produit" id="id_produit" value="<?php echo $id_produit; ?>">

              <!--<div class="form-group">
                <label for="id_produit">Référence&nbsp;</label>
                <input type="text" class="form-control" id="id_produit" name="id_produit" placeholder="" value="<?php echo $id_produit; ?>" <?php if(!empty($produitActuel)) {echo 'readonly'; } ?>> 
              </div>-->                            
              <div class="form-group">
                  <label for="date_arrivee">Date d'arrivée&nbsp;</label>
                  <!--<input type="date" class="form-control" id="date_arrivee" name="date_arrivee" placeholder="" value="<?php echo $date_arrivee; ?>"> -->
                  <input type="text" class="form-control" id="datepicker" name="date_arrivee" placeholder="" value="<?php echo $date_arrivee; ?>"> 
              </div>
              <div class="form-group">
                  <label for="date_depart">Date de départ&nbsp;</label>
                  <!--<input type="date" class="form-control" id="date_depart" name="date_depart" placeholder="" value="<?php echo $date_depart; ?>"> --> 
                  <input type="text" class="form-control" id="datepicker2" name="date_depart" placeholder="" value="<?php echo $date_depart; ?>">             
              </div>
              <div class="form-group">
                <label for="prix">Prix&nbsp;</label>
                <input type="text" class="form-control" id="prix" name="prix" placeholder="" value="<?php echo $titre; ?>">
              </div>
              <div class="form-group">
                <label for="etat">Etat&nbsp;</label>
                <select name="etat" id="etat" class="form-control">
                    <option>libre</option>
                    <option <?php if($etat == 'Réservation') { echo 'selected';} ?> >Réservation</option>           
                </select>
              </div>  
              <div class="form-group">
                <label for="id_salle">Salle&nbsp;</label>
                <select name="id_salle" id="id_salle" class="form-control">
                         <?php 
                            while($salle = $listeSalle->fetch(PDO::FETCH_ASSOC)) {
                              echo '<option value="' . $salle['id_salle'] . '">' . $salle['titre'] . '</option>';
                            }
                         ?>
                </select>
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
             var_dump($listeproduit);


            echo '<div class="col-12">';
              
              // Tableau
              $listeproduit = $pdo->query("SELECT p.id_produit, date_format(p.date_arrivee, '%d/%m/%Y') AS 'Date arrivée', date_format(p.date_depart, '%d/%m/%Y') AS 'Date départ', p.id_salle,s.titre, s.photo, p.prix, p.etat FROM produit p, salle s WHERE p.id_salle = s.id_salle");//Requête méthode jointure

              echo '<table class="table table-bordered">';
              echo '<tr>';
              // affichage des colonnes en récupérant les noms dans l'objet
              //columnCount()
              $nbColonnes = $listeproduit->columnCount(); // le nombre de colonnes
              for($i = 0; $i<$nbColonnes;$i++) {
                $colonne = $listeproduit->getColumnMeta($i);
                //print "<pre>"; var_dump($colonne); print "</pre>";
                echo '<th style="padding:10px">' .$colonne['name'] . '</th>';

              }
              
              echo '<th>Consulter</th>';
              echo '<th>Modifier</th>';
              echo '<th>Supprimer</th>';
              echo '</tr>';

              // ou faire un tableau manuel
              /*echo '<tr>'
              echo '<th>id_produit</th>';
              echo '<th>id_produit</th>';
              echo '<th>categorie</th>';
              echo '<th>titre</th>';
              echo '<th>date_arrivee</th>';
              etc...
              */
               //affichage des données
              while($produit = $listeproduit->fetch(PDO::FETCH_ASSOC)) {
                echo'<tr>';

                foreach ($produit AS $indice => $valeur) {

                  if($indice == 'photo') {
                      echo'<td><img src="' . URL . $valeur . '" alt="produit" width="140"></td>';
                  } elseif ($indice == 'description') {
                      echo'<td><a href="" title="' . $valeur . '">' . substr($valeur, 0, 20) . '...</a></td>';
                  } else {
                      echo '<td>' . $valeur . '</td>';
                  }
                  
                }
                echo '<td class="text-center align-middle"> <a href="?action=consulter&id_produit=' . $produit['id_produit'] . '" class="btn btn-light";"><i class="far fa-eye" aria-hidden="true"></i></a></td>';
                echo '<td class="text-center align-middle"> <a href="?action=modifier&id_produit=' . $produit['id_produit'] . '" class="btn btn-light";"><i class="fas fa-edit" aria-hidden="true"></i></a></td>';
                echo '<td class="text-center align-middle"><a href="?action=supprimer&id_produit=' . $produit['id_produit'] . '" class="btn btn-light" onclick="return(confirm(\' Etes vous sûr ? \'));"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></td>';
                echo '<tr>';
              }
              echo '</table>';  

            echo '</div>';  
          }  
        ?> 
      </div>
    </main><!-- /.container -->
	<?php
  require_once('../inc/footer.inc.php');