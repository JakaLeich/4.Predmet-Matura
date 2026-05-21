<?php include "glava.php"; ?>
<?php
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "JakaLeich";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Povezava ni uspela: " . $conn->connect_error);

$id_projekcija = $_GET['id'] ?? 0;

$sql = "SELECT f.Naslov, p.Datum, p.Ura, d.Ime_dvorane, d.Število_sedežev
        FROM Projekcija p
        JOIN Film f ON p.ID_Film = f.ID_Film
        JOIN Dvorana d ON p.ID_Dvorana = d.ID_Dvorana
        WHERE p.ID_Projekcija = $id_projekcija";
$projekcija = $conn->query($sql)->fetch_assoc();

$sql_rez = "SELECT Sedez FROM Vstopnica v
            JOIN Rezervirani_sedezi r ON v.ID_Vstopnica = r.ID_Vstopnica
            WHERE v.ID_Projekcija = $id_projekcija";
$rezultat_rez = $conn->query($sql_rez);
$zasedeni_sedezi = [];
while ($row = $rezultat_rez->fetch_assoc()) $zasedeni_sedezi[] = $row['Sedez'];

// tabela slik
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
$slika = '';
foreach ($slike as $kljuc => $datoteka) {
    if (strtolower(trim($kljuc)) === strtolower(trim($projekcija['Naslov']))) {
        $slika = '../Slike/' . $datoteka;
        break;
    }
}
?>

<h1 class="seznam-naslov">Rezervacija za <?= htmlspecialchars($projekcija['Naslov']) ?></h1>
<p class="rezervacija-info">
    <strong>Datum:</strong> <?= $projekcija['Datum'] ?> &nbsp;|&nbsp;
    <strong>Ura:</strong> <?= substr($projekcija['Ura'], 0, 5) ?> &nbsp;|&nbsp;
    <strong>Dvorana:</strong> <?= $projekcija['Ime_dvorane'] ?>
</p>

<!-- POSTER -->
<?php if ($slika): ?>
    <img src="<?= $slika ?>" alt="<?= htmlspecialchars($projekcija['Naslov']) ?>" class="rezerv-poster">
<?php endif; ?>

<!-- LEGENDA -->
<div class="legenda">
    <div class="legenda-item"><span class="legenda-barvica prosto"></span> Prosto</div>
    <div class="legenda-item"><span class="legenda-barvica zasedeno"></span> Zasedeno</div>
    <div class="legenda-item"><span class="legenda-barvica izbrano"></span> Izbrano</div>
</div>

<form action="obdelava_rezervacijo.php" method="POST">
    <input type="hidden" name="id_projekcija" value="<?= $id_projekcija ?>">

    <!-- SEDEŽI -->
    <div class="sedezi">
<?php
$st_sedezev = (int) $projekcija['Število_sedežev'];
$vrstni_seznam = range('A', 'Z');
$vrste = ceil($st_sedezev / 10);
for ($vrsta_index = 0; $vrsta_index < $vrste; $vrsta_index++) {
    $vrsta = $vrstni_seznam[$vrsta_index] ?? '?';
    echo "<div class='vrsta'>";
    for ($j = 1; $j <= 10; $j++) {
        $trenutni = $vrsta_index * 10 + $j;
        if ($trenutni > $st_sedezev) break;
        $oznaka = $vrsta . $j;
        $zaseden = in_array($oznaka, $zasedeni_sedezi);
        echo "<label class='sedez " . ($zaseden ? "zaseden" : "") . "'>";
        if (!$zaseden) {
            echo "<input type='checkbox' name='sedezi[]' value='$oznaka'><span class='oznaka'>$oznaka</span>";
        } else {
            echo "<span class='oznaka'>$oznaka</span>";
        }
        echo "</label>";
    }
    echo "</div>";
}
?>
    </div>

    <!-- OBRAZEC -->
    <div class="rezervacija-forma">
        <h3 style="color:#ff9800; margin:0 0 1rem;">Vaši podatki</h3>
        <label for="ime">Ime:</label>
        <input type="text" id="ime" name="ime" required placeholder="Vnesite ime">
        <label for="priimek">Priimek:</label>
        <input type="text" id="priimek" name="priimek" required placeholder="Vnesite priimek">
        <label for="starost">Starost:</label>
        <input type="number" id="starost" name="starost" required min="0" placeholder="Npr. 25">
        <label for="vrsta">Vrsta vstopnice:</label>
        <select id="vrsta" name="vrsta">
            <option value="navadna">Navadna</option>
            <option value="študentska">Študentska</option>
            <option value="senior">Senior</option>
        </select>
        <button type="submit" class="btn-potrdi">Potrdi rezervacijo</button>
    </div>
</form>

<script>
document.querySelectorAll('.sedez:not(.zaseden) input').forEach(function(cb) {
    cb.addEventListener('change', function() {
        var label = this.closest('.sedez');
        if (this.checked) {
            label.style.backgroundColor = '#ff9800';
            label.style.borderColor = '#ff9800';
            label.style.color = '#121212';
        } else {
            label.style.backgroundColor = '';
            label.style.borderColor = '';
            label.style.color = '';
        }
    });
});
</script>

<?php $conn->close(); ?>
<?php include "noga.php"; ?>
