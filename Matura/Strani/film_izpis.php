<?php include "glava.php"; ?>

<?php 
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "JakaLeich";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("Connection failed: " . mysqli_connect_error());

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

function najdiSliko($naslov, $slike) {
    // najprej preveri statično tabelo
    foreach ($slike as $kljuc => $datoteka) {
        if (strtolower(trim($kljuc)) === strtolower(trim($naslov))) {
            return '../Slike/' . $datoteka;
        }
    }
    // potem poišči v mapi Slike po imenu filma (za novo naložene slike)
    $extensions = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
    foreach ($extensions as $ext) {
        $pot = '../Slike/' . $naslov . '.' . $ext;
        if (file_exists($pot)) return $pot;
    }
    return '';
}

function izpisiFilme($filmi, $slike) {
    foreach ($filmi as $film):
        $slika = najdiSliko($film['Naslov'], $slike);
    ?>
        <div class="vsakfilem">
            <?php if ($slika): ?>
                <a href="film_detail.php?id=<?= $film['ID_Film'] ?>">
                    <img src="<?= $slika ?>" alt="<?= htmlspecialchars($film['Naslov']) ?>">
                </a>
            <?php endif; ?>
            <div class="film-info">
                <h2><a href="film_detail.php?id=<?= $film['ID_Film'] ?>" class="film-naslov-link"><?= htmlspecialchars($film['Naslov']) ?></a></h2>
                <p><strong>Žanr:</strong> <?= htmlspecialchars($film['Žanr']) ?></p>
                <p><strong>Trajanje:</strong> <?= htmlspecialchars($film['Trajanje']) ?> min</p>
                <p><strong>Starostna omejitev:</strong> <?= htmlspecialchars($film['Starostna_omejitev']) ?>+</p>

            </div>
        </div>
    <?php endforeach;
}

// filmi ki se predvajajo zdaj (projekcija v naslednjih 14 dneh)
$sql_zdaj = "
    SELECT DISTINCT f.ID_Film, f.Naslov, f.Žanr, f.Trajanje, f.Starostna_omejitev
    FROM Film f
    INNER JOIN Projekcija p ON f.ID_Film = p.ID_Film
    WHERE p.Datum >= CURDATE() AND p.Datum < DATE_ADD(CURDATE(), INTERVAL 14 DAY)
";

// filmi ki pridejo kmalu (projekcija čez 30 ali več dni)
$sql_kmalu = "
    SELECT DISTINCT f.ID_Film, f.Naslov, f.Žanr, f.Trajanje, f.Starostna_omejitev
    FROM Film f
    INNER JOIN Projekcija p ON f.ID_Film = p.ID_Film
    WHERE p.Datum >= DATE_ADD(CURDATE(), INTERVAL 14 DAY)
";

$res_zdaj  = $conn->query($sql_zdaj);
$res_kmalu = $conn->query($sql_kmalu);

$filmi_zdaj  = $res_zdaj  ? $res_zdaj->fetch_all(MYSQLI_ASSOC)  : [];
$filmi_kmalu = $res_kmalu ? $res_kmalu->fetch_all(MYSQLI_ASSOC) : [];
?>

<h1 class="seznam-naslov">Seznam filmov</h1>

<?php if (!empty($filmi_zdaj)): ?>
    <h2 class="sekcija-naslov" style="text-align:center;">▶ V kinu zdaj</h2>
    <div class="filmi">
        <?php izpisiFilme($filmi_zdaj, $slike); ?>
    </div>
<?php endif; ?>

<?php if (!empty($filmi_kmalu)): ?>
    <h2 class="sekcija-naslov" style="text-align:center;">🕐 Kmalu v kinu</h2>
    <div class="filmi filmi-kmalu">
        <?php izpisiFilme($filmi_kmalu, $slike); ?>
    </div>
<?php endif; ?>

<?php if (empty($filmi_zdaj) && empty($filmi_kmalu)): ?>
    <p>Trenutno ni nobenih filmov v bazi.</p>
<?php endif; ?>

<?php 
$conn->close();
include "noga.php"; 
?>
