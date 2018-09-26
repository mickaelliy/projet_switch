<?php
  require_once('inc/init.inc.php');
  $title = 'Accueil';

  //récupération des catégories en BDD
  $listeCategorie = $pdo->query("SELECT categorie, COUNT(*) AS nb FROM salle GROUP BY categorie");
  //récupération des villes en BDD
  $listeVille = $pdo->query("SELECT ville, COUNT(*) AS nb FROM salle GROUP BY ville");
  //récupération des capacités des salles en BDD
  $listeCapacite = $pdo->query("SELECT capacite, COUNT(*) AS nb FROM salle GROUP BY capacite");

  $order = " ORDER BY categorie, titre";
  $where = "";

  // Pour afficher les produits dans les bonnes catégories
  if(!empty($_GET['cat'])) {
  $where = " AND categorie=:cat ";
  }

  ///////////////////// Affichage dispo des salles ///////////////////////
  $produit = $pdo->prepare("SELECT * FROM salle, produit, avis WHERE produit.id_produit = :id AND produit.id_salle = salle.id_salle AND produit.id_salle = avis.id_salle");
  $produit->bindParam(':id', $_GET['id'], PDO::PARAM_STR);
  $produit->execute();

  $infoProduit = $produit->fetch(PDO::FETCH_ASSOC);
  ///////////////////// Fin - Affichage dispo des salles ///////////////////////



  // A remettre ---- test en cours---
  /*if(!empty($_GET['v'])) {
  $where = " AND ville=:v ";
  }
  */

  //ATTENTION, LE CODE CI-DESSOUS EST ENCORE EN COURS DE MODIF, essayer de mettre en place une seule requete avec toutes les conditions
if(isset($_GET['v']) && !empty($_GET['v']))
{
   $listeSalles = $pdo->prepare("SELECT * FROM salle s, produit p WHERE ville = '$_GET[v]'");

}
else
{
      $listeSalles = $pdo->prepare("SELECT * FROM salle s, produit p WHERE s.id_salle = p.id_salle " . $where . $order);
}


  
  if(!empty($where)) {

      if(!empty($_GET['cat'])) {
    $listeSalles->bindParam(':cat', $_GET['cat'], PDO::PARAM_STR);
    }
     if(!empty($_GET['v'])) {
    $listeVille->bindParam(':v', $_GET['v'], PDO::PARAM_STR);
    }
  }

  $listeSalles->execute();






  require_once('inc/header.inc.php');
  require_once('inc/nav.inc.php');
?>


    <!-- Page Content -->
    <div class="container">

      <div class="row">

        <div class="col-lg-3">

          <!--<h1 class="my-4 text-center">Location de salles profesionnelles</h1>-->
          <ul class="list-group my-4">
                <li class="list-group-item">
                  <h4>Catégories</h4>
                </li>
              <?php
                // Exercice : récupérer toutes les catégories des articles de la BDD et les afficher ici dans liste en format <a href>, majuscule pour la première lettre, Attention den a pas avoir de répétitions dans les catégories(affichagfe par ordre alphabétique) 
                //bonus : afficher entre parenthèse à côté de la catégorie, le nombre d'article présent en BDD de cette catégorie. 
                while($categorie = $listeCategorie->fetch(PDO::FETCH_ASSOC)){
                  echo '<li class="list-group-item"><a href="?cat=' . $categorie['categorie'] . '">' . ucfirst($categorie['categorie']) . '</a> (' . $categorie['nb'] . ')</li>';
                }
              ?>
          </ul>

          <!--bloc ville-->
          <ul class="list-group my-4">
                <li class="list-group-item">
                  <h4>Ville</h4>
                </li>
              <?php
                while($ville = $listeVille->fetch(PDO::FETCH_ASSOC)){
                  echo '<li class="list-group-item"><a href="?v=' . $ville['ville'] . '">' . ucfirst($ville['ville']) . '</a> (' . $ville['nb'] . " salles" . ')</li>';
                }
              ?>
          </ul>

          <!--Capacité-->
          <ul class="list-group my-4">
                <li class="list-group-item">
                  <h4>Capacité</h4>

                    <div class="form-group">
                      <select class="form-control" id="exampleFormControlSelect1">
                        <?php
                          while($capacite = $listeCapacite->fetch(PDO::FETCH_ASSOC)){
                            echo '<option>
                                    <a href="?c=' . $capacite['capacite'] . '">' . ucfirst($capacite['capacite'] . " personnes") . '
                                        </a> (' . $capacite['nb'] . ')
                                  </option> ';
                          }
                        ?>
                       </select>    
                    </div>
                </li>
          </ul>

          <!--bloc période-->
          <ul class="list-group my-4">
                <li class="list-group-item">
                  <h4>Période</h4>
                    
                    <div class="form-group">
                      <label for="">Date d'arrivée&nbsp;</label>
                      <div class="row">
                        <div class="col-sm-12">
                          <label class="sr-only" for="inlineFormInputGroup"></label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                            <input type="text" class="form-control" id="datepicker" name="date_arrivee" placeholder="" ">
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="">Date de départ&nbsp;</label>
                      <div class="row">
                        <div class="col-sm-12">
                          <label class="sr-only" for="inlineFormInputGroup"></label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                            </div>
                            <input type="text" class="form-control" id="datepicker2" name="date_depart" placeholder="" "> 
                          </div>
                        </div>
                      </div>
                    </div>

                </li>              
          </ul>


        </div>
        <!-- /.col-lg-3 -->

        <div class="col-lg-9">

            <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
              <div class="carousel-inner" role="listbox">
                <?php
                  $categoryPhoto = !empty($_GET['cat']) ?  $_GET['cat'] : '';
                  $condition = '';
                  if(!empty($categoryPhoto)) {
                    $condition = " WHERE categorie = '$categoryPhoto'";
                  }
                  $listePhotoSalle = $pdo->query("SELECT photo FROM salle $condition LIMIT 3");
                  $active = 'active';
                  while($photoEnCours = $listePhotoSalle->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="carousel-item ' . $active . '">
                          <img class="d-block img-fluid" src="' . $photoEnCours['photo'] . '" alt="salle" class="img-responsive"" alt="First slide">
                      </div>';
                      $active = '';
                  }
                 ?>

                 <!--
                <div class="carousel-item active">
                    <img class="d-block img-fluid" src="assets/img/salle_rick" alt="salle" class="img-responsive"" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block img-fluid" src="assets/img/salle_morty" alt="salle" class="img-responsive"" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block img-fluid" src="assets/img/salle_beth" alt="salle" class="img-responsive"" alt="Third slide">
                </div>
              -->
              </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Précedent</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Suivant</span>
                    </a>
            </div>

          <div class="row">  
          <?php 
                    
                   //affichage des salles sous le carrousel
                    while ($salle = $listeSalles->fetch(PDO::FETCH_ASSOC)) {
                      //var_dump($salles);
                      echo '
                              <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100">
                                  <a href="' . URL . 'fiche_produit.php?id=' . $salle['id_produit'] .  '"><img class="card-img-top" src="' . URL . $salle['photo'] . '" alt=""></a>
                                  <div class="card-body">
                                    <h4 class="card-title text-center">
                                      <a href="' . URL . 'fiche_produit.php?id=' . $salle['id_produit'] .  '">' . $salle['titre'] . '</a>
                                    </h4>
                                    <h5 class="card-title text-center"> ' . $salle['prix'] . ' €' . ' </h5>
                                    <p class="card-text text-justify">' . $salle['description'] . '</p>' ?>

                                    <?php
                                      if ($infoProduit['etat'] == 'reservation') { 

                                       // la condition if ne fonctionne pas... A creuser 

                                        echo '<p class="text-danger text-center">Indisponible</p>';
                                        echo '<a href="' . URL .'index.php?cat=' . $infoProduit['etat'] . '" class="text-info"> Retour vers la page d\'accueil </a>';
                                        } else {
                                      
                                          echo '<input type="hidden" name="id_produit" value=" ' . $infoProduit['id_salle'] . '">';
                                          echo '<h6 class="card-text text-center" style="color:limegreen;">' . "Disponible du ". '<br>' . $salle['date_arrivee'] . " au " . $salle['date_depart'] . '</h6>';

                                        } ?>
                                   <?php echo '
                                  </div>
                                  <div class="text-center mb-3">
                                      <a href="' . URL . 'fiche_produit.php?id=' . $salle['id_produit'] .  '" class="btn btn-primary">Voir la fiche</a>
                                    </div> 
                                  <div class="card-footer">
                                    <small class="text-muted">&#9733; &#9733; &#9733; &#9733; &#9734;</small>
                                  </div>
                                </div>
                              </div>';       
                  }     
          ?> 
          
          </div> 
           <!-- row -->
          
          
        </div>
        <!-- /.col-lg-9 -->

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

<?php
  require_once('inc/footer.inc.php');
  ?>



    