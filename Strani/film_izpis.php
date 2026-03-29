<?php include "glava.php"; // prilepi meni in glavo strani ?>

<?php 
// podatki za povezavo z bazo
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "JakaLeich";

// poveže se z bazo
$conn = mysqli_connect($servername, $username, $password, $dbname);
// če ne gre, javi napako
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// pobere vse filme iz baze
$sql = "SELECT ID_Film, Naslov, Žanr, Trajanje, Starostna_omejitev FROM Film";
// izvede poizvedbo
$result = $conn->query($sql);
?> 

<main>
    <h1>Seznam filmov</h1>
    <?php 
    // če je kaj rezultatov in če obstajajo vrstice
    if ($result && $result->num_rows > 0): 
    ?>
        <div class="filmi">
            <?php 
            // vrti v zanki dokler še je filmov
            while ($film = $result->fetch_assoc()): 
            ?>
                <div class="vsakfilem">
                    <div class="film-info">
                        <?php 
                        // htmlspecialchars preprečuje napade (XSS)
                        ?>
                        <h2><?=htmlspecialchars($film['Naslov'])?></h2>
                        <p><strong>Žanr:</strong> <?=htmlspecialchars($film['Žanr'])?></p>
                        <p><strong>Trajanje:</strong> <?=htmlspecialchars($film['Trajanje'])?> min</p>
                        <p><strong>Starostna omejitev:</strong> <?=htmlspecialchars($film['Starostna_omejitev'])?>+</p>
                        <?php 
                        // pošlje ID filma v URL za brisanje
                        ?>
                        <a href="izbrisi_film.php?id=<?= $film['ID_Film'] ?>" class="btn-izbris">Izbriši</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>Trenutno ni nobenih filmov v bazi.</p>
    <?php endif; ?>

</main>
<?php 
// zapri povezavo z bazo
$conn->close();
// prilepi nogo (footer)
include "noga.php"; 
?>