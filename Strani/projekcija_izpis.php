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

// poizvedba
$sql = "
    SELECT 
        Projekcija.ID_Projekcija,
        Film.Naslov AS Film, 
        Dvorana.Ime_dvorane AS Dvorana, 
        Projekcija.Datum,
        Projekcija.Ura
    FROM 
        Projekcija
    INNER JOIN Film ON Projekcija.ID_Film = Film.ID_Film  
    INNER JOIN Dvorana ON Projekcija.ID_Dvorana = Dvorana.ID_Dvorana  
    ORDER BY Projekcija.Datum, Projekcija.Ura 
";

$result = $conn->query($sql);  // izvede poizvedbo
?> 

<main>
    <h1>Seznam projekcij</h1>
    <?php 
    // če je poizvedba uspela in je kaj vrstic
    if ($result && $result->num_rows > 0): 
    ?>
        <div class="projekcijaizpis">
            <?php 
            // skozi vse projekcije
            while ($vrstica = $result->fetch_assoc()): 
            ?>
                <div class="projekcijaposebaj">
                    <div class="projekcija-info">
                        <?php
                        ?>
                        <h2><?= htmlspecialchars($vrstica['Film']) ?></h2>
                        <p><strong>Dvorana:</strong> <?= htmlspecialchars($vrstica['Dvorana']) ?></p>
                        <p><strong>Datum:</strong> <?= htmlspecialchars($vrstica['Datum']) ?></p>
                        <p><strong>Ura:</strong> <?= htmlspecialchars($vrstica['Ura']) ?></p>
                        <?php 
                        // pošlje ID projekcije v URL za rezervacijo
                        ?>
                        <a href="rezerviraj.php?id=<?= $vrstica['ID_Projekcija'] ?>" class="btn-rezerviraj">Rezerviraj</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="tabela1">Trenutno ni nobenih projekcij v bazi.</p>
    <?php endif; ?>
</main>

<?php 
$conn->close();  // zapri povezavo z bazo
include "noga.php"; // prilepi nogo (footer)
?>