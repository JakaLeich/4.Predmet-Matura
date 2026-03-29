<?php include "glava.php" 
?>
 <form method="post" action="obdelava_dodaj_film.php" class="form-film">
        <label for="naslov">Naslov filma:</label>
        <input type="text" id="naslov" name="naslov" required maxlength="100" placeholder="Vnesite naslov filma">
        
        <label for="zanr">Žanr:</label>
        <input type="text" id="zanr" name="zanr" required maxlength="50" placeholder="Npr. Drama, Akcija, Animirani">

        <label for="trajanje">Trajanje (v minutah):</label>
        <input type="number" id="trajanje" name="trajanje" required min="1" max="500" placeholder="Npr. 120">

        <label for="starostna_omejitev">Starostna omejitev:</label>
        <input type="number" id="starostna_omejitev" name="starostna_omejitev" required min="0" max="18" placeholder="Npr. 12">

        <button type="submit" class="btn-primarni">Dodaj film</button>
    </form>
    <?php include "noga.php" 
?>