<?php
require_once('inc/init.inc.php');


require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>
  <main role="main" class="container">
    <div class="starter-template">
      <h1><i class="fab fa-shopware mes-icones"></i>Location Switch&nbsp;</h1>
      <p class="lead">Contacter l'administrateur&nbsp;</p>
      <hr>

    <form method="post" action="" class="form">
        <div class="form-group">
          <label for="nom">Nom&nbsp; : </label>
          <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom..." value="">
        </div>
        <div class="form-group">
          <label for="prenom">Prénom&nbsp; : </label>
          <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom..." value="">
        </div>
        <div class="form-group">
          <label for="email">Email&nbsp; : </label>
          <input type="text" class="form-control" id="email" name="email" placeholder="Email..." value="">
        </div>
        <div class="form-group">
          <label for="telephone">Téléphone&nbsp; : </label>
          <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Téléphone..." value="">
        </div>
        <div class="form-group">
          <label for="ville">Ville&nbsp; : </label>
          <input type="text" class="form-control" id="ville" name="ville" placeholder="Ville..." value="">
        </div>
        <div class="form-group">Votre message&nbsp; : <textarea cols="155" rows="3" placeholder="Votre message :"></textarea>
      </div>
      <hr>
          <input style="margin-bottom: 30px;" type="submit" class="btn btn-secondary w-100" name="Envoyer" value="Envoyer">
      <hr>
    </form>

    </div>
  </main>

<?php
require_once('inc/footer.inc.php');