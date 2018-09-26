<?php
  require_once('inc/init.inc.php');
  /*la ligne ci-dessous sert à rediriger une personne sur la page connexion si elle veut venir sur l'URL profil alors qu'elle n'est pas connectée.*/
  if(!userIsConnect()) {
    header('location:connexion.php');
  }




  require_once('inc/header.inc.php');
  require_once('inc/nav.inc.php');
  //var_dump($_SESSION);
?>


    <main role="main" style="background-image:url('assets/img/motion-blur-wallpapers-37066-37909-hd-wallpapers.jpg');">

      <div class="starter-template text-center">
        <h1><img src="https://png.icons8.com/color/50/000000/morty-smith.png"> Votre profil</h1>
        <p class="lead">Retrouvez toutes vos informations personnelles</p>
        <?php echo $msg; ?>
      </div> 

      <div class="row">
         <div class="col-sm-12 my-1">
          
            <div class="card-body">
               <div class="row">
                <div class="col-sm-6 my-1">
                  <p class="card-title">Statut : <?php if($_SESSION['membre'] ['statut'] == 2) {echo'administration';} else {echo 'membre'; } ?></p>
                  <hr>
                  <p class="card-title">Pseudo : <?= $_SESSION['membre'] ['pseudo'] ; ?></p>
                  <hr>
                </div>  
                <div class="col-sm-6 my-1">
                  
                  <p class="card-title">Nom : <?= $_SESSION['membre'] ['nom'] ; ?></p>
                  <hr>
                  <p class="card-title">Prénom : <?= $_SESSION['membre'] ['prenom'] ; ?></p>
                  <hr>
                </div>
                <div class="col-sm-6 my-1">
                  
                  <p class="card-title">Email : <?= $_SESSION['membre'] ['email'] ; ?></p>
                  <hr>
                </div>
                <div class="col-sm-6 my-1">
                  <p class="card-title">Civilite : <?php if($_SESSION['membre']['civilite'] == 'm') {echo 'Homme';} else {echo 'Femme';} ?></p>
              
                  <hr>
                </div>

              </div>
              
            </div>
            <a href="#" class="myBtn btn btn-dark mt-2 mb-2 p-2">Modifier</a>
          
        </div>
        
      </div>
      

    </main><!-- /.container -->
	

<?php
  require_once('inc/footer.inc.php');

	
	
	
	
