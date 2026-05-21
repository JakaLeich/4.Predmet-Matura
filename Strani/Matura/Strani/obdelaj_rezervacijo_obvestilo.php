<?php include "glava.php"; // prilepi meni in glavo strani ?>
<?php
    // preveri, ali je v URLju poslan parameter "tabela2"
    if(isset($_GET["tabela2"])){
        $tabela1=$_GET["tabela2"];  // vzame vrednost iz URLja
        echo "<p class='obvestilo'>Uspešno shranjena rezervacija v tabelo.</p>";
    } else {
        echo "<p class='obvestilo'>Rezervacija ni bila shranjena v tabelo.</p>"; 
    }
    // gumb za nazaj na seznam rezervacij
    echo "<a href='rezervacije_izpis.php' class='gumbek'>Pogled vseh rezervacij</a>";
?>
<?php include "noga.php"; // prilepi nogo (footer) ?>