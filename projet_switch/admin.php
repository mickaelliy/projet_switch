<?php
  require_once('inc/init.inc.php');
  /*la ligne ci-dessous sert à rediriger une personne sur la page connexion si elle veut venir sur l'URL profil alors qu'elle n'est pas connectée.*/
  if(!userIsConnect()) {
    header('location:connexion.php');
  }

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


  

  require_once('inc/header.inc.php');
  require_once('inc/nav.inc.php');

?>


    <div id="wrapper">
      <div id="content-wrapper">
        <div class="container-fluid">

          <!-- Icon Cards-->
          <div class="row mt-3">
            <div class="col-xl-2 col-sm-6 mb-3">
              <div class="card text-white text-center bg-primary o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-building"></i>
                  </div>
                  <div class="text-center">Gestion des salles</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="?action=voir_salles">
                  <span class="float-left">Voir le détails</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-2 col-sm-6 mb-3">
              <div class="card text-white text-center bg-secondary o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-comments"></i>
                  </div>
                  <div class="text-center">Gestion des produits</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-2 col-sm-6 mb-3">
              <div class="card text-white text-center bg-dark o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-comments"></i>
                  </div>
                  <div class="text-center">Gestion des membres</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-2 col-sm-6 mb-3">
              <div class="card text-white text-center bg-warning o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-list"></i>
                  </div>
                  <div class="text-center">Gestion des avis</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-2 col-sm-6 mb-3">
              <div class="card text-white text-center bg-success o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                  </div>
                  <div class="text-center">Gestion des commandes</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-2 col-sm-6 mb-3">
              <div class="card text-white bg-danger o-hidden h-100">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-life-ring"></i>
                  </div>
                  <div class="mr-5">13 New Tickets!</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="#">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
          </div>


<!--Page gestion des salles -->
<?php if(isset($_GET['action']) && ($_GET['action'] == 'voir_salles' )) { 
echo '<a href="admin/gestion_salles.php" class="text-info"> aller gs </a>';
 } ?>


        </div>
        <!-- /.container-fluid -->

       

      </div>
      <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

   <?php
  require_once('inc/footer.inc.php');