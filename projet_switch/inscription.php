<?php
  require_once('inc/init.inc.php');

  $pseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : '';
  $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';
  $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
  $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
  $email = isset($_POST['email']) ? $_POST['email'] : '';
  $civilite = isset($_POST['civilite']) ? $_POST['civilite'] : '';

  if($_POST) { //est-ce que le formulaire a été validé.

    // contrôle sur la taille du pseudo
    //iconv_strlen() pour la prise en compte de l'utf-8
    if(iconv_strlen($pseudo) < 4 || iconv_strlen($pseudo) > 14){
        $msg .= '<div class="alert alert-danger" role="alert">Le pseudo doit contenir entre 4 et 14 caratctères ! </div>';
    }

    // controle des caractères selon un expression régulière
    /*
        EXPRESSION REGULIERE
        --------------------
        - les # délimitent l'expression régulière
        - le ^ indique le début de la chaîne, on ne peut pas avoir d'autre  caractère que ceux précisés en début de chaîne !
        - le $ indique la fin de la chaîne, on ne peut pas avoir d'autre carcatère que ceux précisés en fin de chaîne !
        - le + permet d'avoir plusieurs fois le même caractère !

        preg_match() renvoi false si dans la chaîne passée en deuxième argument se trouve un caractère différent que ceux dans l'expression sinin true.

    */

    if(!preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo)){
      $msg .= '<div class="bg-danger p-3 text-white mt-3" role="alert">Attention, <br> Caractères acceptés : a-z, 0-9<br> Veuillez recommencer </div>';
    }

    //controle sur l'existence du pseudo (unique en BDD)
    $checkPseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $checkPseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $checkPseudo->execute();

    if($checkPseudo->rowCount() > 0) {
      $msg .= '<div class="bg-danger p-3 text-white mt-3" role="alert">Attention, <br> Pseudo indisponible<br> Veuillez recommencer </div>';
    } 

    //controle sur la validité du mail
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $msg .= '<div class="bg-danger p-3 text-white mt-3" role="alert">Attention, <br> Le format du mail n\'est pas valide<br> Veuillez recommencer </div>';
    }
    //enregistrement de l'utilisateur en BDD
    if(empty($msg)) { // si $msg est vide => pas d'erreur, on peut enregistrer l'inscription.
      // cryptage (hashage)
      $mdp = password_hash($mdp, PASSWORD_DEFAULT);


       $inscription = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, 1, NOW())");
       $inscription->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
       $inscription->bindParam(':mdp', $mdp, PDO::PARAM_STR);
       $inscription->bindParam(':nom', $nom, PDO::PARAM_STR);
       $inscription->bindParam(':prenom', $prenom, PDO::PARAM_STR);
       $inscription->bindParam(':email', $email, PDO::PARAM_STR);
       $inscription->bindParam(':civilite', $civilite, PDO::PARAM_STR);
       $inscription->execute(); 

       // si tout est ok, on redirige l'utilisateur sur connexion.php
       header('location:connexion.php');
    }



  }

  require_once('inc/header.inc.php');
  require_once('inc/nav.inc.php');
  //var_dump($_POST);
//phpinfo();
?>


    <main role="main"  style="background-image:url('https://hdwallsource.com/img/2014/9/blur-26347-27038-hd-wallpapers.jpg');">

      <div class="starter-template text-center">
        <h1 class="pt-4"><i class="far fa-arrow-alt-circle-right"></i> Inscription</h1>
        <p class="lead">Inscrivez-vous pour pouvoir réserver votre salle</p>
        <?php echo $msg; ?>
      </div>
      
      <div  class="row" >

          <div class="col-sm-6 mx-auto">
            <form method="post" action="" class="form">
              <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo..." value="<?php echo $pseudo; ?>"> 
              </div>
              <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="text" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe...">
              </div>
              <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom..." value="<?php echo $nom; ?>">
              </div>
              <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom..." value="<?php echo $prenom; ?>">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Email..." value="<?php echo $email; ?>">
              </div>
               <div class="form-group">
                <label for="civilite">Civilite</label>
                <select name="civilite" id="civilite" class="form-control">
                    <option value="f">Femme </option> 
                    <option value="m" <?php if ($civilite == 'h') { echo 'selected';}?> >Homme </option>                 
                </select>
              </div>
              <hr>
              <input style="margin-bottom: 30px;" type="submit" class="btn btn-secondary w-100" name="inscription" value="Inscription">

            </form> 
          </div>  

      </div>

    </main><!-- /.container -->
	

<?php
  require_once('inc/footer.inc.php');

	
	
	
	
