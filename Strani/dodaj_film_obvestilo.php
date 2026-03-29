<?php include "glava.php"; ?>
<?php
    
        if(isset($_GET["tabela1"])){   // pogleda če je v naslovu (URL) nekaj poslano z imenom "tabela1"
            $tabela1=$_GET["tabela1"];   // to kar je v URLju shrani v spremenljivko
             echo "<p class='obvestilo'>Uspešno shranjen film v tabelo.</p>";
        }else{
           echo "<p class='obvestilo'>Film ni bil shranjen v tabelo.</p>"; 
        }
         echo "<a href='film_izpis.php' class='gumbek'>Pogled vseh filmov</a>";  // gumb za nazaj na seznam filmov
        ?>
        <?php include "noga.php"; ?>