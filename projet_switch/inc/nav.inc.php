 <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="<?php echo URL; ?>index.php">Switch</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="<?php echo URL; ?>index.php"">Accueil
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo URL; ?>qsn.php">Qui sommes-nous</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Besoin d'aide ?</a>
            </li>

            <?php
              if(!userIsConnect()) {
              ?>
              <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="<?php echo URL; ?>https://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Espace membre</a>
              <div class="dropdown-menu" aria-labelledby="dropdown01">
                <a class="dropdown-item" href="<?php echo URL; ?>inscription.php">Inscription</a>
                <a class="dropdown-item" href="<?php echo URL; ?>connexion.php">Connexion</a>
              </div>
            </li>
              
              <?php
              } else {  
              ?>
              <li class="nav-item">
                <a class="nav-link disabled" href="<?php echo URL; ?>profil.php">Profil</a>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" href="<?php echo URL; ?>connexion.php?action=deconnexion">Se déconnecter</a>
              </li>
              <?php
              }

              if(userIsAdmin()) { 
              ?>
              <li class="nav-item dropdown bg-warning">
                <a class="nav-link" href="<?php echo URL; ?>admin/gestion_salles.php" id="dropdown01" >Administration</a>
                <!--<div class="dropdown-menu" aria-labelledby="dropdown01">
                  <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_salles.php">Gestion des salles</a>
                  <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_produits.php">Gestion des produits</a>
                  <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_membres.php">Gestion des membres</a>
                  <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_avis.php">Gestion des avis</a>
                  <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_commandes.php">Gestion des commandes</a>
                  <a class="dropdown-item" href="<?php echo URL; ?>admin/statistiques.php">statistiques</a>
                </div>-->
              </li>
            <?php
            }
            ?>

          </ul>
        </div>
      </div>
    </nav>