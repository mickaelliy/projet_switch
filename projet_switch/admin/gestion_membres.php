<?php
  require_once('../inc/init.inc.php');
  $title = 'Gestion des membres';

  if(!userIsAdmin()) {
    header('location:' . URL . 'profil.php');
    exit(); // bloque l'exécution du code en dessous dans la page
  }

/********************************************************
*******************SUPPRESSION membre******************
*********************************************************/

  if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['id_membre']) && is_numeric($_GET['id_membre'])) {
    //si l'indice action exsiste dans $_GET et sa valeur est égale à "supprimer", et si l'indice id_membre existe dans $_GET, et que sa valeur est de type numérique.
      $suppression = $pdo->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
      $suppression->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
      $suppression->execute();
      $msg .= '<div class="bg-success p-3 text-white mt-3" role="alert">La membre n°' . $_GET['id_membre'] . ' a bien été supprimée</div>';
      $_GET['action'] = 'afficher'; // on rebascule sur l'affichage tableau.
  }
  
  // (int) devant une valeur  permet de forcer la valeur en entier
  // trim () est une fonction prédéfinie permettant d'enlever les espaces en début et fin de chaîne.

  $id_membre =isset($_POST['id_membre']) ? $_POST['id_membre'] : '';
  $pseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : '';
  $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';
  $nom = isset($_POST['nom']) ? $_POST['nom'] : ''; 
  $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
  $email = isset($_POST['email']) ? $_POST['email'] : '';
  $civilite = isset($_POST['civilite']) ? $_POST['civilite'] : '';
  $statut = isset($_POST['statut']) ? $_POST['statut'] : '';
  $date_enregistrement = isset($_POST['date_enregistrement']) ? $_POST['date_enregistrement'] : '';
  
  
/********************************************************
*******************MODIFICATION membre******************
*********************************************************/

  if(isset($_GET['action']) && $_GET['action'] == 'modifier' && isset($_GET['id_membre']) && is_numeric($_GET['id_membre']) && empty($_POST)) {
      $membreActuel = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
      $membreActuel->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
      $membreActuel->execute();

      $infosmembreActuel = $membreActuel->fetch(PDO::FETCH_ASSOC);

      $id_membre = $infosmembreActuel['id_membre'];
      $pseudo = $infosmembreActuel['pseudo'];
      $mdp = $infosmembreActuel['mdp'];
      $nom = $infosmembreActuel['nom'];
      $prenom = $infosmembreActuel['prenom'];
      $email = $infosmembreActuel['email'];
      $civilite = $infosmembreActuel['civilite'];
      $statut = $infosmembreActuel['statut'];
      $date_enregistrement = $infosmembreActuel['date_enregistrement']; 
      
  }

/********************************************************
*******************CONSULTATION membre******************
*********************************************************/

  if(isset($_GET['action']) && $_GET['action'] == 'consulter' && isset($_GET['id_membre']) && is_numeric($_GET['id_membre']) && empty($_POST)) {
      $membreActuel = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
      $membreActuel->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
      $membreActuel->execute();

      $infosmembreActuel = $membreActuel->fetch(PDO::FETCH_ASSOC);

      $id_membre = $infosmembreActuel['id_membre'];
      $pseudo = $infosmembreActuel['pseudo'];
      $mdp = $infosmembreActuel['mdp'];
      $nom = $infosmembreActuel['nom'];
      $prenom = $infosmembreActuel['prenom'];
      $email = $infosmembreActuel['email'];
      $civilite = $infosmembreActuel['civilite'];
      $statut = $infosmembreActuel['statut'];
      $date_enregistrement = $infosmembreActuel['date_enregistrement']; 
      
  }


  if($_POST) { // equivalent à if (!empty($_POST))



    //Controle sur l'existence de la référence (unique en BDD)
    $checkid_membre = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $checkid_membre->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
    $checkid_membre->execute();

    if($checkid_membre->rowCount() > 0 && isset($_GET['action']) && $_GET['action'] != 'modifier') {
       $msg .= '<div class="alert alert-danger" role="alert">Attention, <br> la référence existe déjà et doit être unique<br>Veuillez recommencer </div>';
    }


  if(empty($msg)) {
    //enregistrement en BDD

    $mdp = password_hash($mdp, PASSWORD_DEFAULT);

    if(isset($_GET['action']) && $_GET['action'] == 'ajouter') {
    $requete = $pdo->prepare("INSERT INTO membre (date_enregistrement, pseudo, mdp, nom, prenom, civilite, statut, email) VALUES (NOW(), :pseudo, :mdp, :nom, :prenom, :civilite, :statut, :email)");
      echo '<div style="background: green; padding: 5px;">Le membre a bien été ajouté</div>';
  } else {
    $requete = $pdo->prepare("UPDATE membre SET date_enregistrement = NOW(), pseudo = :pseudo, mdp = :mdp, nom = :nom, prenom = :prenom, civilite = :civilite, statut = :statut, email = :email WHERE id_membre = :id_membre");
    echo '<div style="background: green; padding: 5px;">Le membre a bien été modifié</div>';
    $requete->bindParam(':id_membre', $id_membre, PDO::PARAM_STR);
  }

      //$requete->bindParam(':date_enregistrement', $date_enregistrement, PDO::PARAM_STR);
      $requete->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
      $requete->bindParam(':mdp', $mdp, PDO::PARAM_STR);
      $requete->bindParam(':nom', $nom, PDO::PARAM_STR);
      $requete->bindParam(':prenom', $prenom, PDO::PARAM_STR);
      $requete->bindParam(':civilite', $civilite, PDO::PARAM_STR);
      $requete->bindParam(':statut', $statut, PDO::PARAM_STR);
      $requete->bindParam(':email', $email, PDO::PARAM_STR);
      $requete->execute();

      $_GET['action'] == 'afficher';
  }
  }


  //requete de récupération de tous les membres en BDD
  $listemembre = $pdo->query("SELECT * FROM membre");



  require_once('../inc/header.inc.php');
  require_once('../inc/nav.inc.php');
  require_once('../inc/menu_admin.inc.php');
?>


    <main role="main" class="container">

      <div class="starter-template text-center mt-4">
        <h1>Gestion des membres</h1>
        <?php echo $msg; ?>
        <?php //var_dump ($_SERVER); ?>
        <hr>
        <a href="?action=ajouter" class="btn btn-primary">Ajouter un membre</a>
        <a href="?action=afficher" class="btn btn-info">Afficher les membres</a>
         <hr>
      </div>
      

      

      <?php if(isset($_GET['action']) && ($_GET['action'] == 'ajouter')) { ?>  
      <form method="post" action="" enctype="multipart/form-data">
        <div  class="row">
          <div class="col-sm-6 mx-auto">

              <input type="hidden" class="form-control" id="datepicker" name="date_enregistrement" placeholder="" value="<?php echo $date_enregistrement; ?>"> 
            
              <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <div class="row">
                  <div class="col-sm-12">
                    <label class="sr-only" for="inlineFormInputGroup">Pseudo</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                      </div>
                      <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="pseudo" value="<?php echo $pseudo; ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <div class="row">
                  <div class="col-sm-12 ">
                    <label class="sr-only" for="inlineFormInputGroup">mdp</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                      </div>
                      <input type="text" class="form-control" id="mdp" name="mdp" placeholder="mdp" value="<?php echo $mdp; ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="nom">Nom</label>
                <div class="row">
                  <div class="col-sm-12">
                    <label class="sr-only" for="inlineFormInputGroup">nom</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                      </div>
                      <input type="text" class="form-control" id="nom" name="nom" placeholder="nom" value="<?php echo $nom; ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="prenom">Prénom</label>
                <div class="row">
                  <div class="col-sm-12">
                    <label class="sr-only" for="inlineFormInputGroup">prenom</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                      </div>
                      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="prenom" value="<?php echo $prenom; ?>">
                    </div>
                  </div>
                </div>
              </div>
           </div>   
         

         <div class="col-sm-6 mx-auto">
            
              
              <div class="form-group">
                <label for="email">Email</label>
                <div class="row">
                  <div class="col-sm-12">
                    <label class="sr-only" for="inlineFormInputGroup">email</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                      </div>
                      <input type="text" class="form-control" id="email" name="email" placeholder="email" value="<?php echo $email; ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="civilite">Civilité</label>
                <div class="row">
                  <div class="col-sm-12">
                      <select name="civilite" id="civilite" class="form-control">
                        <option value="f">Femme </option> 
                        <option value="m" <?php if ($civilite == 'h') { echo 'selected';}?> >Homme </option>                 
                      </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="statut">Statut</label>
                <div class="row">
                  <div class="col-sm-12">
                      <select name="statut" id="statut" class="form-control">
                        <option value="2">Admin </option> 
                        <option value="1" <?php if ($statut == 'm') { echo 'selected';}?> >Membre </option>                 
                      </select>
                  </div>
                </div>
              </div>
              <input type="submit" class="btn btn-info w-25 mb-4 float-right" name="enregistrer" value="Enregistrer"
              <?php if(isset($_GET['action'])) {echo 'value="' . ucfirst($_GET['action']) . '"';} ?> >
          </div> 
        </div>
      </form> 
      
        <?php } ?>

      <!-- Affichage de la page modification -->  
      <?php if(isset($_GET['action']) && ($_GET['action'] == 'modifier')) { ?>


      <form method="post" action="" enctype="multipart/form-data">
        <div  class="row">
          <div class="col-sm-6 mx-auto">
            
              
              

              <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <div class="row">
                  <div class="col-sm-12">
                    <label class="sr-only" for="inlineFormInputGroup">Pseudo</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                      </div>
                      <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="pseudo" value="<?php echo $pseudo; ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <div class="row">
                  <div class="col-sm-12 ">
                    <label class="sr-only" for="inlineFormInputGroup">mdp</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                      </div>
                      <input type="text" class="form-control" id="mdp" name="mdp" placeholder="mdp" value="<?php echo $mdp; ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="nom">Nom</label>
                <div class="row">
                  <div class="col-sm-12">
                    <label class="sr-only" for="inlineFormInputGroup">nom</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                      </div>
                      <input type="text" class="form-control" id="nom" name="nom" placeholder="nom" value="<?php echo $nom; ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="prenom">Prénom</label>
                <div class="row">
                  <div class="col-sm-12">
                    <label class="sr-only" for="inlineFormInputGroup">prenom</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-pen"></i></div>
                      </div>
                      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="prenom" value="<?php echo $pseudo; ?>">
                    </div>
                  </div>
                </div>
              </div>
           </div>   
         

         <div class="col-sm-6 mx-auto">
            
              
              <div class="form-group">
                <label for="email">Email</label>
                <div class="row">
                  <div class="col-sm-12">
                    <label class="sr-only" for="inlineFormInputGroup">email</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                      </div>
                      <input type="text" class="form-control" id="email" name="email" placeholder="email" value="<?php echo $email; ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="civilite">Civilité</label>
                <div class="row">
                  <div class="col-sm-12">
                      <select name="civilite" id="civilite" class="form-control">
                        <option value="f">Femme </option> 
                        <option value="m" <?php if ($civilite == 'h') { echo 'selected';}?> >Homme </option>                 
                      </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="statut">Statut</label>
                <div class="row">
                  <div class="col-sm-12">
                      <select name="statut" id="statut" class="form-control">
                        <option value="2">Admin </option> 
                        <option value="1" <?php if ($statut == 'm') { echo 'selected';}?> >Membre </option>                 
                      </select>
                  </div>
                </div>
              </div>
              <input type="submit" class="btn btn-info w-25 mb-4 float-right" name="modifier" value="Modifier"
              <?php if(isset($_GET['action'])) {echo 'value="' . ucfirst($_GET['action']) . '"';} ?> >
          </div> 
        </div>
      </form> 
      
      <?php } ?>
      <!-- fin de l'affichage de la page modification -->

      <?php 
          // affichage du tableau
          //if(isset($_GET['action']) && $_GET['action'] == 'afficher') {
            //affiche tous les membres de la BDD et les affiche dans un tableau html ! 
             //var_dump($listemembre);


        
              // Tableau
              $listemembre = $pdo->query("SELECT * FROM membre");

              echo '<div class="table-responsive table-bordered">';
              echo '<table class="table table-bordered">';
              echo '<thead class="thead-dark">';
              echo '<tr>';
              // affichage des colonnes en récupérant les noms dans l'objet
              //columnCount()
              $nbColonnes = $listemembre->columnCount(); // le nombre de colonnes
              for($i = 0; $i<$nbColonnes;$i++) {
                $colonne = $listemembre->getColumnMeta($i);
                //print "<pre>"; var_dump($colonne); print "</pre>";
                echo '<th style="padding:10px">' .$colonne['name'] . '</th>';

              }
              echo '<th style="padding:10px">consulter</th>';
              echo '<th style="padding:10px">modifier</th>';
              echo '<th style="padding:10px">supprimer</th>';
              echo '</tr>';

              // ou faire un tableau manuel
              /*echo '<tr>'
              echo '<th>id_membre</th>';
              echo '<th>id_membre</th>';
              echo '<th>date_enregistrement</th>';
              echo '<th>pseudo</th>';
              echo '<th>mdp</th>';
              etc...
              */


                //affichage des données
              while($membre = $listemembre->fetch(PDO::FETCH_ASSOC)) {
                echo'<tr>';

                foreach ($membre AS $indice => $valeur) {

                  if($indice == 'photo') {
                      echo'<td><img src="' . URL . $valeur . '" alt="membre" width="140"></td>';
                  } elseif ($indice == 'mdp') {
                      echo'<td><a href="" title="' . $valeur . '">' . substr($valeur, 0, 20) . '...</a></td>';
                  } else {
                      echo '<td>' . $valeur . '</td>';
                  }
                  
                }
                echo '<td class="text-center align-middle"> <a href="?action=consulter&id_membre=' . $membre['id_membre'] . '" class="btn btn-light";"><i class="far fa-eye" aria-hidden="true"></i></a></td>';
                echo '<td class="text-center align-middle"> <a href="?action=modifier&id_membre=' . $membre['id_membre'] . '" class="btn btn-light";"><i class="fas fa-edit" aria-hidden="true"></i></a></td>';
                echo '<td class="text-center align-middle"><a href="?action=supprimer&id_membre=' . $membre['id_membre'] . '" class="btn btn-light" onclick="return(confirm(\' Etes vous sûr ? \'));"><i class="fas fa-trash-alt" aria-hidden="true"></i></a></td>';
                echo '<tr>';
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

	
	
	
	
