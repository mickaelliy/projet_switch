<?php
require_once('inc/init.inc.php');


require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>
    <main role="main" class="starter-template" style="background-image:url('assets/img/motion-blur-wallpaper-37068-37911-hd-wallpapers.jpg');">
    
    <div class="row">
      <div class="col-sm-12 mx-auto">
        <div class="starter-template text-center mt-4">
        <h1 class="text-center">Qui sommes-nous ?</h1>
        <img src="<?php echo URL; ?>assets/img/presentation-min.jpg" class="presentation" alt="Mickaël et Michel" style="width:60%" >
        </div>

        <div class="descriptionQsn m-4 text-center mx-auto" style="width: 60%;" >

          <h4>Deux passionnés d'informatique </h4>
      		<p>Nous avons tous les deux des parcours différents mais nous sommes animés par la même passion qui est le développement informatique.
          <br>
          <br> Nous suivons une formation longue de développeur PHP back-end et dans ce cadre nous avons développés deux sites dont un en front-end qui s'appelle mmcommerce.fr et celui-ci qui est orienté back-end. 
          <br>
          <br>Pour notre 1er site, qui est plutôt un site vitrine,   nous avions décidés d'utiliser les langages HTML, CSS, JAVASCRIPT, JQUERY et un peu BOOTSTRAP. 
          <br>
          <br>Dans le cadre de notre 2ème projet, qui est totalement fonctionnel côté back, nous utilisons PHP, BOOSTRAP, AJAX, MYSQL.</p>

        </div>
      </div>
    </div>

    </main><!-- /.container -->

<?php
require_once('inc/footer.inc.php');