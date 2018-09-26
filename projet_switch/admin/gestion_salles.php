<?php
  require_once('../inc/init.inc.php');
  $title = 'Gestion des salles';

   if(!userIsAdmin()) {
    header('location:' . URL . 'profil.php');
    exit(); // bloque l'exécution du code en dessous dans la page
  }

/********************************************************
*******************SUPPRESSION salle******************
*********************************************************/

  if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_salle']) && is_numeric($_GET['id_salle'])) {
    //si l'indice action exsiste dans $_GET et sa valeur est égale à "supprimer", et si l'indice id_salle existe dans $_GET, et que sa valeur est de type numérique.
      $suppression = $pdo->prepare("DELETE FROM salle WHERE id_salle = :id_salle");
      $suppression->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
      $suppression->execute();
      $msg .= '<div class="bg-success p-3 text-white mt-3" role="alert">La salle n°' . $_GET['id_salle'] . ' a bien été supprimée</div>';
      $_GET['action'] = 'afficher'; // on rebascule sur l'affichage tableau.
  }
  
  // (int) devant une valeur  permet de forcer la valeur en entier
  // trim () est une fonction prédéfinie permettant d'enlever les espaces en début et fin de chaîne.

  $id_salle =isset($_POST['id_salle']) ? $_POST['id_salle'] : '';
  $photoActuelle = isset($_POST['photoActuelle']) ? $_POST['photoActuelle'] : ''; 
  $titre = isset($_POST['titre']) ? $_POST['titre'] : '';
  $description = isset($_POST['description']) ? $_POST['description'] : '';
  $pays = isset($_POST['pays']) ? $_POST['pays'] : ''; 
  $ville = isset($_POST['ville']) ? $_POST['ville'] : '';
  $adresse = isset($_POST['adresse']) ? $_POST['adresse'] : '';
  $cp = isset($_POST['cp']) ? (int)trim($_POST['cp']) : '';
  $capacite = isset($_POST['capacite']) ? (int)trim($_POST['capacite']) : '';
  $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';
  
  
/********************************************************
*******************MODIFICATION salle******************
*********************************************************/

  if(isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['id_salle']) && is_numeric($_GET['id_salle']) && empty($_POST)) {
      $salleActuel = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
      $salleActuel->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
      $salleActuel->execute();

      $infossalleActuel = $salleActuel->fetch(PDO::FETCH_ASSOC);

      $id_salle = $infossalleActuel['id_salle'];
      $titre = $infossalleActuel['titre'];
      $description = $infossalleActuel['description'];
      $photoActuelle = $infossalleActuel['photo'];
      $pays = $infossalleActuel['pays'];
      $ville = $infossalleActuel['ville'];
      $adresse = $infossalleActuel['adresse'];
      $cp = $infossalleActuel['cp'];
      $capacite = $infossalleActuel['capacite'];
      $categorie = $infossalleActuel['categorie']; 
      
  }

/********************************************************
*******************CONSULTATION salle******************
*********************************************************/

  if(isset($_GET['action']) && $_GET['action'] == 'consulter' && isset($_GET['id_salle']) && is_numeric($_GET['id_salle']) && empty($_POST)) {
      $salleActuel = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
      $salleActuel->bindParam(':id_salle', $_GET['id_salle'], PDO::PARAM_STR);
      $salleActuel->execute();

      $infossalleActuel = $salleActuel->fetch(PDO::FETCH_ASSOC);

      $id_salle = $infossalleActuel['id_salle'];
      $titre = $infossalleActuel['titre'];
      $description = $infossalleActuel['description'];
      $photoActuelle = $infossalleActuel['photo'];
      $pays = $infossalleActuel['pays'];
      $ville = $infossalleActuel['ville'];
      $adresse = $infossalleActuel['adresse'];
      $cp = $infossalleActuel['cp'];
      $capacite = $infossalleActuel['capacite'];
      $categorie = $infossalleActuel['categorie']; 
      
  }


  if($_POST) { // equivalent à if (!empty($_POST))

    $photo_bdd = $photoActuelle; //déclaration de cette variable vide car si l'utilisateur ne charge pas de photo, la variable existe, on évite une erreur sql au cas où.


    //Controle sur l'existence de la référence (unique en BDD)
    $checkid_salle = $pdo->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
    $checkid_salle->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
    $checkid_salle->execute();

    if($checkid_salle->rowCount() > 0 && isset($_GET['action']) && $_GET['action'] != 'modifier') {
       $msg .= '<div class="alert alert-danger" role="alert">Attention, <br> la référence existe déjà et doit être unique<br>Veuillez recommencer </div>';
    }

    //traitement de la photo
    if(!empty($_FILES['photo']['name']) && empty($msg)) {

      //pour ne pas écraser un image du même nom, on ajoute la référence (qui est unique) sur son nom
      $nom_photo = $id_salle . '_' . $_FILES['photo']['name'];
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
    $requete = $pdo->prepare("INSERT INTO salle (categorie, titre, description, pays, ville, cp, photo, capacite, adresse) VALUES (:categorie, :titre, :description, :pays, :ville, :cp, :photo, :capacite, :adresse)");
      echo '<div style="background: green; padding: 5px; margin: 20px;color:white;">La salle a bien été ajoutée</div>';
  } else {
    $requete = $pdo->prepare("UPDATE salle SET categorie = :categorie, titre = :titre, description = :description, pays = :pays, ville = :ville, cp = :cp, photo = :photo, capacite = :capacite, adresse = :adresse WHERE id_salle = :id_salle");
    $requete->bindParam(':id_salle', $id_salle, PDO::PARAM_STR);
    echo '<div style="background: green; padding: 5px; margin: 20px; color:white;">La salle a bien été modifiée</div>';
  }

      $requete->bindParam(':categorie', $categorie, PDO::PARAM_STR);
      $requete->bindParam(':titre', $titre, PDO::PARAM_STR);
      $requete->bindParam(':description', $description, PDO::PARAM_STR);
      $requete->bindParam(':pays', $pays, PDO::PARAM_STR);
      $requete->bindParam(':ville', $ville, PDO::PARAM_STR);
      $requete->bindParam(':cp', $cp, PDO::PARAM_STR);
      $requete->bindParam(':photo', $photo_bdd, PDO::PARAM_STR);
      $requete->bindParam(':capacite', $capacite, PDO::PARAM_STR);
      $requete->bindParam(':adresse', $adresse, PDO::PARAM_STR);
      $requete->execute();

      $_GET['action'] == 'afficher';
  }

  }


  //requete de récupération de tous les salles en BDD
  $listesalle = $pdo->query("SELECT * FROM salle");

  require_once('../inc/header.inc.php');
  require_once('../inc/nav.inc.php');
  //var_dump($_POST);
  //var_dump($_FILES);
  require_once('../inc/menu_admin.inc.php');
?>


    <main role="main" class="container">

      <div class="starter-template text-center">
        <?php echo $msg; ?>
        <?php //var_dump ($_SERVER); ?>
        <hr>
        <a href="?action=ajouter" class="btn btn-primary" style="background-color: #00FFBF">Ajouter une salle</a>
        <!--<a href="?action=afficher" class="btn btn-info">Afficher les salles</a>-->
         <hr>
      </div>
      

      <div  class="row">

      <?php if(isset($_GET['action']) && ($_GET['action'] == 'ajouter' || $_GET['action'] == 'modifier' || $_GET['action'] == 'consulter')) { ?>  

          <div class="col-sm-6 mx-auto">
            <form method="post" action="" enctype="multipart/form-data">
              
             <input type="hidden" name="id_salle" id="id_salle" value="<?php echo $id_salle; ?>" <?php if(!empty($salleActuel)) {echo 'readonly'; } ?>>
              <!--
              <div class="form-group">
                <label for="id_salle">ID</label>
                <input type="hidden" class="form-control" id="id_salle" name="id_salle" placeholder="Réference..." value="<?php echo $id_salle; ?>" <?php if(!empty($salleActuel)) {echo 'readonly'; } ?>> 
              </div>
            -->
              <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre de la salle" value="<?php echo $titre; ?>">
              </div>
              <div class="form-group">
                <label for="description">Description</label>
                <textarea type="text" class="form-control" id="description" name="description" placeholder="Description de la salle"><?php echo $description; ?></textarea>
              </div>
               <div class="form-group">
                <label for="photo">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo">
              </div>
              <?php if(!empty($photoActuelle)) { ?>
              <div class="col-12">
                <img src="<?php echo URL . $photoActuelle; ?>" alt="photo salle" class="img-thumbnail" width="210">
              </div> 
              <?php } ?>
              <input type="hidden" name="photoActuelle" value="<?php echo $photoActuelle; ?>">
              <div class="form-group">
                <label for="capacite">Capacite</label>
                <input type="text" name="capacite" id="capacite" class="form-control" value="<?php echo $capacite; ?>">
              </div>
              <div class="form-group">
                <label for="categorie">Catégorie</label>
                <select name="categorie" id="categorie" class="form-control">
                    <option>Réunion</option>
                    <option <?php if($categorie == 'Bureau') { echo 'selected';} ?> >Bureau</option>
                    <option <?php if($categorie == 'Formation') { echo 'selected';} ?> >Formation</option>          
                </select>
              </div>
              <div class="form-group">
                <label for="pays">pays</label>
                <select name="pays" id="pays" class="form-control">
                    <option>France</option>
                    <option <?php if($pays == 'Autre') { echo 'selected';} ?> >Autre</option>               
                </select>
              </div> 
              <div class="form-group">
                <label for="ville">Ville</label>
                <select name="ville" id="ville" class="form-control">
                    <option value="paris">Paris</option> 
                    <option <?php if($ville == 'Lyon') { echo 'selected';} ?> >Lyon</option>
                    <option <?php if($ville == 'Marseille') { echo 'selected';} ?> >Marseille</option>
                </select>
              </div>  
              <div class="form-group">
                <label for="adresse">adresse</label>
                <input type="text" class="form-control" id="adresse" name="adresse" placeholder="adresse..." value="<?php echo $adresse; ?>">
              </div>  
              <div class="form-group">
                <label for="cp">Code Postal</label>
                <input type="text" name="cp" id="cp" class="form-control" value="<?php echo $cp; ?>">
              </div>  
              <hr>
              <input type="submit" class="btn btn-success w-100 mb-4" name="enregistrer" value="Enregitrer"
              <?php if(isset($_GET['action'])) {echo 'value="' . ucfirst($_GET['action']) . '"';} ?> >
            </form> 
          </div> 

        <?php } ?>

        <?php 
          // affichage du tableau
          //if(isset($_GET['action']) && $_GET['action'] == 'afficher') {
           
             //var_dump($listesalle);


            echo '<div class="col-12">';
              
              // Tableau
              $listesalle = $pdo->query("SELECT * FROM salle");
      
              echo '<div class="table-responsive table-bordered mb-4">';
              echo '<table class="table table-bordered">';

              echo '<thead>';
              echo '<tr>';
              // affichage des colonnes en récupérant les noms dans l'objet
              //columnCount()
              $nbColonnes = $listesalle->columnCount(); // le nombre de colonnes
              for($i = 0; $i<$nbColonnes;$i++) {
                $colonne = $listesalle->getColumnMeta($i);
                //print "<pre>"; var_dump($colonne); print "</pre>";
                echo '<th style="padding:10px" class="bg-primary text-white">' .$colonne['name'] . '</th>';

              }
              echo '<th class="bg-primary text-white">consulter</th>';
              echo '<th class="bg-primary text-white">modifier</th>';
              echo '<th class="bg-primary text-white">supprimer</th>';
              echo '</tr>';

              // ou faire un tableau manuel
              /*echo '<tr>'
              echo '<th>id_salle</th>';
              echo '<th>id_salle</th>';
              echo '<th>categorie</th>';
              echo '<th>titre</th>';
              echo '<th>description</th>';
              etc...
              */


                //affichage des données
              
              // Ajouter un ROw COUNT pour afficher le nombre de lignes...

              while($salle = $listesalle->fetch(PDO::FETCH_ASSOC)) {
                echo'<tr>';

                foreach ($salle AS $indice => $valeur) {

                  if($indice == 'photo') {
                      echo'<td><img src="' . URL . $valeur . '" alt="salle" width="140"></td>';
                  } elseif ($indice == 'description') {
                      echo'<td><a href="" title="' . $valeur . '">' . substr($valeur, 0, 20) . '...</a></td>';
                  } else {
                      echo '<td>' . $valeur . '</td>';
                  }
                  
                }
                echo '<td class="text-center align-middle"> <a href="?action=consulter&id_salle=' . $salle['id_salle'] . '" class="btn btn-light";"><i class="far fa-eye" aria-hidden="true"></i></a></td>';
                echo '<td class="text-center align-middle"> <a href="?action=modifier&id_salle=' . $salle['id_salle'] . '" class="btn btn-light";"><i class="fas fa-edit" aria-hidden="true"></i></a></td>';
                echo '<td class="text-center align-middle"><a href="?action=supprimer&id_salle=' . $salle['id_salle'] . '" class="btn btn-light" onclick="return(confirm(\' Etes vous sûr ? \'));"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></td>';
                echo '</tr>';
              }
            echo '</thead>';
            echo '</table>';  
            echo '</div>';  

          //}  

        ?> 

      </div>

    </main><!-- /.container -->
	

<?php
  require_once('../inc/footer.inc.php');