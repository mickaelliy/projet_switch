<?php 
require_once('inc/init.inc.php');
/*
- verfifier si l'id est bien présent dans l'url sinon on redirige sur index
- si l'id existe, déclencher une requete qui recupere en bdd les informations produit
- si 0 ligne obtenu => on redirige sur index
- un seul produit ? => 1 fetch (pas besoin de boucle)
- piocher dans le tableau array obtenu après le fetch pour afficher les informations produit dans cette page
*/
$note = isset($_POST['note']) ? $_POST['note'] : '';
$commentaire = isset($_POST['commentaire']) ? $_POST['commentaire'] : '';
$date_enregistrement = isset($_POST['date_enregistrement']) ? $_POST['date_enregistrement'] : '';

if($_POST) {
  if(empty($msg)) { // si $msg est vide => pas d'erreur, on peut enregistrer l'inscription.
    if(isset($_GET['action']) && $_GET['action'] == 'ajouter') {
      
       $ajoutAvis = $pdo->prepare("INSERT INTO avis (commentaire, note, date_enregistrement) VALUES (:commentaire, :note, NOW())");
      echo '<div style="background: green; padding: 5px; margin: 20px;color:white;">Le commentaire a bien été ajouté</div>';

       $ajoutAvis->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
       $ajoutAvis->bindParam(':note', $note, PDO::PARAM_STR);
       $ajoutAvis->execute(); 
    }
  }
}


if(!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$produit = $pdo->prepare("SELECT */*, date_format(produit.date_arrivee, '%d/%m/%Y')*/ FROM salle, produit, avis, membre WHERE produit.id_produit = :id AND produit.id_salle = salle.id_salle AND avis.id_membre = membre.id_membre");
	$produit->bindParam(':id', $_GET['id'], PDO::PARAM_STR);
	$produit->execute();

	if($produit->rowCount() < 1) {
		// si nous n'avons pas récupéré de produit (0 ligne)
		//header("location:" . URL);
		//exit();
	}

	$infoProduit = $produit->fetch(PDO::FETCH_ASSOC);
	//echo '<pre>'; var_dump($infoProduit); echo '</pre>';

} else {
	// si $_GET['id'] n'existe pas ou est vide ou n'est pas numérique
	header("location:" . URL);
	exit();
}

$avis ='';
$listeAvis =$pdo->query("SELECT a.note, a.commentaire, s.titre, p.id_produit, s.id_salle FROM avis a, salle s, produit p WHERE a.id_salle = p.id_salle AND p.id_salle = s.id_salle AND p.id_produit = $_GET[id]");
//$listeAvis = $pdo->query("SELECT note, COUNT(*) AS nb FROM avis WHERE id_avis = $_GET[id] ");
//$listeAvis = $pdo->query("SELECT note FROM avis WHERE id_avis = $_GET[id] ");

//voir pour faire les modifs
/*$listeAvis = $pdo->query("SELECT p.*, a.*, s.*
                          FROM produit p, avis a, salle s
                          WHERE p.id_salle = a.id_salle;
                          AND s.id_salle = a.id_salle
                          AND p.id_produit = $_GET[id]  
                          ");
*/



$listeCategorie = $pdo->query("SELECT categorie, COUNT(*) AS nb FROM salle GROUP BY categorie");

  $order = " ORDER BY categorie, titre";
  $where = "";

  if(!empty($_GET['cat'])) {
  $where = " AND categorie=:cat";
  }
  //$listeSalles = $pdo->prepare("SELECT * FROM salle WHERE true " . $where . $order);
  $listeSalles = $pdo->prepare("SELECT * FROM salle, produit WHERE produit.id_salle = salle.id_salle" . $where . $order . " LIMIT 4");

  if(!empty($where)) {
    $listeSalles->bindParam(':cat', $_GET['cat'], PDO::PARAM_STR);
  }

  $listeSalles->execute();


require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
// var_dump();
?>

	<!-- Page Content -->
    <div class="container">
      <div class="row">		
    	<div class="col-lg-9">
    	<h3 class="card-title mt-4 mb-0"><?php echo $infoProduit['titre']; ?> 
        <span class="text-warning "><?php 
          while($avis = $listeAvis->fetch(PDO::FETCH_ASSOC)) {
                //echo '<pre>'; print_r($avis); echo '</pre>';

                foreach ($avis AS $indice => $valeur) {

                  if($indice == 'note'){

                    $compteurEtoiles = 0;
                    $noteFinale = '';

                    while($compteurEtoiles < $valeur){                         
                            $noteFinale = $noteFinale  . '&#9733';
                            $compteurEtoiles++;
                    }

                    
                  echo $avis['commentaire'] . $noteFinale;

                  } 

                }
              }

          ?>

          <!--&#9733; &#9733; &#9733; &#9733; &#9734;-->
        </span>
      </h3>
    	</div>
    	<div class="col-lg-3 mt-4">
        <!--Si non connecté affichage du bouton "connectez-vous", sinon "réservez"-->
        <?php
          if(!userIsConnect()) {
            echo'<input type="submit" class="btn btn-secondary" value="Connectez-vous">';

        ?>
            
              
        <?php
          } else if ($infoProduit['etat'] == 'reservation') { 
            
            echo '<p class="btn btn-danger">Indisponible</p>';
            echo '<br>';
            echo '<a href="' . URL .'index.php' . '" class="text-info"> Retour vers la page d\'accueil </a>';
            } else {
          
              
              echo '<form method="post" action="panier.php">';
              
              echo '<input type="hidden" name="id_produit" value=" ' . $infoProduit['id_produit'] . '">';
              echo '<input type="hidden" name="titre" value=" ' . $infoProduit['titre'] . '">';
              echo '<input type="hidden" name="prix" value=" ' . $infoProduit['prix'] . '">';
              echo '<input type="hidden" name="prix" value=" ' . 1 . '">';
              echo '<input type="submit" name="ajout_panier" class="btn btn-success"  value="Réservez" >';
              echo '</form>';

            } ?>
        <!--  -->

    	</div>
      </div>
    	<hr>

      <div class="row">

        <div class="col-lg-8">
          <div class="card mt-3">
            <img class="card-img-top img-fluid" src="<?php echo URL . $infoProduit['photo']; ?>" alt="">
            
          </div>
          <!-- /.card -->

        </div>
        <!-- /.col-lg-8 -->

        <div class="col-lg-4">
        	<div>
	            <div class="card-body">
	              <h5>Description</h5>
	              <p class="card-text"><?php echo $infoProduit['description']; ?></p>
	              <h5>Informations complémentaires</h5>
	              <p class="card-text"><i class="fas fa-calendar-alt"></i> Arrivée : <?php echo $infoProduit['date_arrivee']; ?></p>
	              <p class="card-text"><i class="fas fa-calendar-alt"></i> Départ : <?php echo $infoProduit['date_depart']; ?></p>
	              <p class="card-text"><i class="fas fa-user"></i> Capacité : <?php echo $infoProduit['capacite']; ?></p>
	              <p class="card-text"><i class="fas fa-folder-open"></i> Catégorie : <?php echo $infoProduit['categorie']; ?></p>
	              <p class="card-text"><i class="fas fa-map-marker-alt"></i> Adresse : <?php echo $infoProduit['adresse'] . ", ". $infoProduit['cp']  . ", " . $infoProduit['ville']; ?></p>
	              <p class="card-text"><i class="fas fa-euro-sign"></i> Tarif : <?php echo $infoProduit['prix'] . " €"; ?></p>
            	</div>
            </div>
        </div>
        <!-- /.col-lg-4 -->
      </div>
      <!-- /.row -->

      <h3 class="my-3">Autres salles</h3>
      	<hr>
		  <div class="row">
		    <?php 
                    
                    // A corriger //$produit = $pdo->prepare("SELECT * FROM salle, produit WHERE produit.id_produit = :id AND produit.id_salle = salle.id_salle");
                    
                    while ($salle = $listeSalles->fetch(PDO::FETCH_ASSOC)) {
                      //var_dump($salles);
                      echo '
                              <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card h-100">
                                  <a href="' . URL . 'fiche_produit.php?id=' . $salle['id_produit'] .  '"><img class="card-img-top" src="' . URL . $salle['photo'] . '" alt=""></a>
                                  <div class="card-body">
                                    <h4 class="card-title">
                                      <a href="' . URL . 'fiche_produit.php?id=' . $salle['id_produit'] .  '">' . $salle['titre'] . '</a>
                                    </h4>
                                  </div>
                                </div>
                              </div>';         
                  }
          ?>   
		   
		  </div>
		  <!-- /.row -->

		<div class="card card-outline-secondary my-4">
            <div class="card-header">
              <h3>Les avis</h3>
            </div>
            <div class="card-body">
              <?php echo $noteFinale; ?>
              <?php             
                  echo $avis['commentaire'] . $noteFinale;

          ?>
              
              <p><?php echo $infoProduit['commentaire']; ?>
              </p>

            
              <small class="text-muted">Publié par <?php echo $infoProduit['pseudo']; ?> le <?php echo $infoProduit['date_enregistrement']; ?></small>
              <hr>
              <a href="#"></a>
              

            <!--***********************************************************************-->        
            <!--Si non connecté affichage du bouton "connectez-vous", sinon "réservez"-->
              <?php
                if(!userIsConnect()) {
              ?>
                  <input type="submit" class="btn btn-secondary" value="Connectez-vous">

              <?php
                } else if ($infoProduit['etat'] == 'reservation') { 
                  
                  echo '<p class="btn btn-danger">Indisponible</p>';
                  echo '<br>';
                  echo '<a href="' . URL .'index.php' . '" class="text-info"> Retour vers la page d\'accueil </a>';
                  } else {
                
                    echo '<input type="hidden" name="id_produit" value=" ' . $infoProduit['id_salle'] . '">';
                    echo ' <div class="row">
                            <button type="button" class="btn btn-secondary" id="show-form">Laissez un commentaire</button>
                          </div>
 

                          <div class="row" id="formulaire-eval" data-visible="0">
                            <form method="post" action="" class="form">

                                <div class="form-group w-100 mt-2">
                                  <label for="commentaire"></label>
                                  <textarea class="form-control w-100" id="commentaire" name="commentaire" rows="4" placeholder="Ecrivez votre commentaire dans cette zone "></textarea>
                                </div>

                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="note" id="note" value="note" checked>
                                  <label class="form-check-label" for="note">

                                    <i class="fa fa-star" aria-hidden="true"></i>

                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="note" id="note" value="moyen">
                                  <label class="form-check-label" for="note">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>

                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="note" id="note" value="bon">
                                  <label class="form-check-label" for="note">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>

                                  </label>
                                </div>

                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="note" id="note" value="bon">
                                  <label class="form-check-label" for="note">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>

                                  </label>
                                </div> 

                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="note" id="note" value="bon">
                                  <label class="form-check-label" for="note">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>

                                  </label>
                                </div> 

                              <input type="hidden" name="id_article" value="4" id="id_article">
                              <button type="submit" class="btn btn-success" id="send-rating">Ajouter</button>
                            </form>
                          </div> ';

                  } ?>
            <!--  -->



                
                
                

            </div>
        </div>
        <!-- /.card -->  

    </div>
    <!-- /.container -->

<?php
  require_once('inc/footer.inc.php');
  ?>
