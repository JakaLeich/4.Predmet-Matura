<?php include "glava.php"; ?>
<?php
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "JakaLeich";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Povezava ni uspela: " . $conn->connect_error);
}

$id_projekcija = $_GET['id'] ?? 0;

// Podatki o projekciji
$sql = "SELECT f.Naslov, p.Datum, p.Ura, d.Ime_dvorane, d.Število_sedežev
        FROM Projekcija p
        JOIN Film f ON p.ID_Film = f.ID_Film
        JOIN Dvorana d ON p.ID_Dvorana = d.ID_Dvorana
        WHERE p.ID_Projekcija = $id_projekcija";
$result = $conn->query($sql);
$projekcija = $result->fetch_assoc();

// Pridobitev že rezerviranih sedežev
$sql_rez = "SELECT Sedez FROM Vstopnica v
            JOIN Rezervirani_sedezi r ON v.ID_Vstopnica = r.ID_Vstopnica
            WHERE v.ID_Projekcija = $id_projekcija";
$rezultat_rez = $conn->query($sql_rez);
$zasedeni_sedezi = [];
while ($row = $rezultat_rez->fetch_assoc()) {
    $zasedeni_sedezi[] = $row['Sedez'];
}
?>

<main>
    <h1>Rezervacija za <?= htmlspecialchars($projekcija['Naslov']) ?></h1>
    <p><strong>Datum:</strong> <?= $projekcija['Datum'] ?> | <strong>Ura:</strong> <?= substr($projekcija['Ura'], 0, 5) ?> | <strong>Dvorana:</strong> <?= $projekcija['Ime_dvorane'] ?></p>

    <form action="obdelava_rezervacijo.php" method="POST">
        <input type="hidden" name="id_projekcija" value="<?= $id_projekcija ?>">
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
<form action="obdelava_rezervacijo.php" method="POST">
    <label for="ime">Ime:</label>
    <input type="text" id="ime" name="ime" required><br><br>

    <label for="priimek">Priimek:</label>
    <input type="text" id="priimek" name="priimek" required><br><br>

    <label for="starost">Starost:</label>
    <input type="number" id="starost" name="starost" required min="0"><br><br>

    <label for="vrsta">Vrsta vstopnice:</label>
    <select id="vrsta" name="vrsta">
        <option value="navadna">Navadna</option>
        <option value="študentska">Študentska</option>
        <option value="senior">Senior</option>
    </select><br><br>

    <button type="submit" class="btn-izbris">Potrdi rezervacijo</button>
</form>
</main>

<?php $conn->close(); ?>
<?php include "noga.php"; ?>
