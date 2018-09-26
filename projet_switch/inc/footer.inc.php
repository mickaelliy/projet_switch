
    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; M&M Website</p>
      </div>
      <!-- /.container -->
    </footer>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster 
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

    <script src="<?php echo URL; ?>assets/js/popper.min.js"></script>
    <script src="<?php echo URL; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo URL; ?>assets/js/myscript.js"></script>

    <!--Script pour afficher laisser avis sur la page fiche produit -->
    <script>
        function $(el){
          return document.getElementById(el);
        }

        var btnShowForm = $("show-form");

        btnShowForm.addEventListener("click",function(){
          var data = $("formulaire-eval").dataset.visible ;
          if(data == 0){
            $("formulaire-eval").style['display']="block";
            $("formulaire-eval").dataset.visible = 1;
          }else{
            $("formulaire-eval").style['display']="none";
            $("formulaire-eval").dataset.visible = 0;
          }
        });

        $("send-rating").addEventListener('click',function(e){
          e.preventDefault();

          var q = new XMLHttpRequest();

          q.open("POST","data.php");

          q.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

          q.onload = function(){
            var info = q.responseText;
            console.log(info);
          }

          var v = document.querySelector('input[name="exampleRadios"]:checked').value;
          var reponse = "rating="+v+"&id_article="+$("id_article").value;

          console.log(reponse);
          q.send(reponse);

        });

      </script>


  </body>
</html>
