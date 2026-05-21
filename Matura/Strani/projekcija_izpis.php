<?php include "glava.php"; ?>

<?php 
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "JakaLeich";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("Connection failed: " . mysqli_connect_error());

// samo projekcije ki so danes ali v naslednjih 30 dneh (v kinu zdaj)
$sql = "
    SELECT 
        Projekcija.ID_Projekcija,
        Film.Naslov AS Film, 
        Dvorana.Ime_dvorane AS Dvorana, 
        Projekcija.Datum,
        Projekcija.Ura
    FROM Projekcija
    INNER JOIN Film ON Projekcija.ID_Film = Film.ID_Film  
    INNER JOIN Dvorana ON Projekcija.ID_Dvorana = Dvorana.ID_Dvorana  
    WHERE Projekcija.Datum >= CURDATE() AND Projekcija.Datum < DATE_ADD(CURDATE(), INTERVAL 14 DAY)
    ORDER BY Projekcija.Datum, Projekcija.Ura 
";

$result = $conn->query($sql);

$slike = [
    'Matrix'        => 'Matrix.avif',
    'Titanic'       => 'titanic.jpg',
    'Toy Story'     => 'toystory.webp',
    'Inception'     => 'inception.webp',
    'Joker'         => 'joker.jpg',
    'Avengers'      => 'avangers.jpg',
    'Frozen'        => 'frozen.jpg',
    'Gladiator'     => 'gladiator.jpg',
    'Interstellar'  => 'interstellar.jpg',
    'The Lion King' => 'the_lion_king.jpg',
];
?> 

    <h1 class="seznam-naslov">▶ V kinu zdaj</h1>
    <?php if ($result && $result->num_rows > 0): ?>
        <div class="projekcijaizpis">
            <?php while ($vrstica = $result->fetch_assoc()):
                $naslov = $vrstica['Film'];
                $slika = '';
                foreach ($slike as $kljuc => $datoteka) {
                    if (strtolower(trim($kljuc)) === strtolower(trim($naslov))) {
                        $slika = '../Slike/' . $datoteka;
                        break;
                    }
                }
                $datum = date('d. m. Y', strtotime($vrstica['Datum']));
                $ura   = substr($vrstica['Ura'], 0, 5);
            ?>
                <div class="projekcijaposebaj">
                    <?php if ($slika): ?>
                        <img src="<?= $slika ?>" alt="<?= htmlspecialchars($naslov) ?>">
                    <?php endif; ?>
                    <div class="projekcija-info">
                        <h2><?= htmlspecialchars($naslov) ?></h2>
                        <div class="dvorana-text"><?= htmlspecialchars($vrstica['Dvorana']) ?></div>
                        <div class="projekcija-termin">
                            <span class="termin-datum"><?= $datum ?></span>
                            <a href="rezerviraj.php?id=<?= $vrstica['ID_Projekcija'] ?>" class="termin-ura"><?= $ura ?></a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="tabela1">Trenutno ni nobenih projekcij v kinu.</p>
    <?php endif; ?>

<?php 
$conn->close();
include "noga.php";
?>
