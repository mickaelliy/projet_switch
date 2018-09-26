<?php
require_once('inc/init.inc.php');
if(isset($_GET['action']) && $_GET['action']  == 'deconnexion'){
  //si l'utilisateur a cliqué sur "Se déconnecter"
  session_destroy();
  //unset($_SESSION);
  //session_start();
  //unset($_SESSION['membre']);
}


if(userIsConnect()) {
  //si l'utilisateur est connecté, on le renvoi sur profil.
  header('location:profil.php');
}

if(userIsAdmin()) {
  header('location:admin.php');
}


$pseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : '';
$mdp = isset($_POST['mdp']) ? $_POST['mdp'] : '';

if($_POST) {
  $checkPseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
  $checkPseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
  $checkPseudo->execute();

  if($checkPseudo->rowCount() != 0){
    $infosMembre = $checkPseudo->fetch(PDO::FETCH_ASSOC);
    //Fetch traitement de la réponse de la bdd pour l'exploiter en php

    //controle du mdp 
    if(password_verify($mdp, $infosMembre['mdp'])){
      //on enregistre les informations dans la session, pour cela on crée un nouvel indice utilisateur dans la session
      $_SESSION['membre'] = array();
      $_SESSION['membre']['id_membre'] = $infosMembre['id_membre'];
      $_SESSION['membre']['pseudo'] = $infosMembre['pseudo'];
      $_SESSION['membre']['mdp'] = $infosMembre['mdp'];
      $_SESSION['membre']['nom'] = $infosMembre['nom'];
      $_SESSION['membre']['prenom'] = $infosMembre['prenom'];
      $_SESSION['membre']['email'] = $infosMembre['email'];
      $_SESSION['membre']['statut'] = $infosMembre['statut'];
      $_SESSION['membre']['civilite'] = $infosMembre['civilite'];

      header("location:profil.php");
    
    } else {
        $msg .='<div class="bg-danger 3 text-white mt-3" role="alert">
      Attention, <br> Erreur sur le pseudo ou le mot de passe ! <br> Veuillez recommencer !</div>';
    }
  } else {
      $msg .='<div class="bg-danger 3 text-white mt-3" role="alert">
    Attention, <br> Erreur sur le pseudo ou le mot de passe ! <br> Veuillez recommencer !</div>';
  } 

}


require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
//var_dump($_SESSION);
?>
    <main role="main" class="container">



      <div class="starter-template text-center m-4">
        <h1><i class="fab fa-connectdevelop" style="color: #00FFBF"></i> Connexion</h1>
        <hr>
        <?php echo $msg; ?>
      </div>

      <div class="row">
        <div class="col-sm-6 mx-auto">
          <form method="post" action="" class="form">
          <div class="form-group">
            <label for="pseudo">Pseudo</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo..." value="<?php echo $pseudo; ?>">
          </div>
          <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input type="text" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe..."value="">
          </div>
          <hr>
          <input type="submit" class="btn w-100 mb-4" name="inscription" value="Se Connecter" style="background-color: #00FFBF">
        </form>
      </div>
    </div>

    </main><!-- /.container -->

<?php
require_once('inc/footer.inc.php');