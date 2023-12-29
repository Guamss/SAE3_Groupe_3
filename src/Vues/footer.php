<footer>
    <div class="copyright">
      <p>Copyright© 2023 Monsters Master's. Tous droits réservés.</p>
    </div>
  </footer>

  </div>

  <script>
    window.onload

    document.addEventListener("DOMContentLoaded", function () {
        // Définissez la valeur maximale pour la longueur du texte
        var maxLength = 50;

        // Sélectionnez toutes les cellules du tableau
        var cells = document.querySelectorAll('.table td, .table th');

        // Parcourez chaque cellule et limitez la longueur du texte
        cells.forEach(function (cell) {
            var originalText = cell.textContent.trim();

            // Vérifiez si la longueur du texte dépasse la valeur maximale
            if (originalText.length > maxLength) {
                // Limitez le texte et ajoutez une indication de troncature
                var truncatedText = originalText.substring(0, maxLength) + '...';
                cell.textContent = truncatedText;
            }
        });


    });

    window.addEventListener('resize', function() {
      var x = document.getElementById("myLinks");
      var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

      if (screenWidth > 1025 && getComputedStyle(x).display === "none") {
          x.style.display = "flex";
      }
      if (screenWidth < 1025 && getComputedStyle(x).display === "flex") {
          x.style.display = "none";
      }
    });

    function mobile_nav() {
      var x = document.getElementById("myLinks");
      var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

      if (x.style.display === "none") {
        x.style.display = "flex";
      }
      else {
        x.style.display = "none";
      }
    }
</script>

</body>
</html>