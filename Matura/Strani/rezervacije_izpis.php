<?php include "glava.php"; // prilepi meni in glavo strani ?>

<?php
// podatki za povezavo z bazo
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "JakaLeich";

// Povezava z bazo
$conn = mysqli_connect($servername, $username, $password, $dbname);
// če povezava ni uspela, javi napako
if (!$conn) {
    die("<p class='tabela1'>Povezava z bazo ni uspela: " . mysqli_connect_error() . "</p>");
}

// Poizvedba za vse rezervirane sedeže (urejeno po ID vstopnice)
$sql = "SELECT ID_Vstopnica, Sedez FROM Rezervirani_sedezi ORDER BY ID_Vstopnica";
$result = mysqli_query($conn, $sql);  // izvedi poizvedbo

// če je poizvedba uspela in je kaj vrstic
if ($result && mysqli_num_rows($result) > 0) {
    echo "<h2 class='naslovcek'>Seznam rezerviranih sedežev</h2>";
    echo "<table class='tabela1'>";
    echo "<thead><tr><th>ID Vstopnice</th><th>Rezervirani sedež</th></tr></thead><tbody>";

    //skozi vse vrstice
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ID_Vstopnica']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Sedez']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p class='tabela1'>Trenutno ni rezerviranih sedežev.</p>";
}

mysqli_close($conn);  // zapri povezavo z bazo


echo "<a href='index.php' class='gumbek'>Nazaj na začetek</a>";
?>

<?php include "noga.php"; // prilepi nogo (footer) ?>