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

// Poizvedba za izpis podatkov iz tabel Vstopnica, Obiskovalec, Projekcija, Dvorana
$sql = "
    SELECT 
        v.ID_Vstopnica, 
        v.Cena, 
        v.Vrsta, 
        o.Ime, 
        o.Priimek, 
        o.Starost, 
        d.Ime_dvorane, 
        d.Število_sedežev
    FROM Vstopnica v
    JOIN Obiskovalec o ON v.ID_Obiskovalec = o.ID_Obiskovalec 
    JOIN Projekcija p ON v.ID_Projekcija = p.ID_Projekcija     
    JOIN Dvorana d ON p.ID_Dvorana = d.ID_Dvorana          
    ORDER BY v.ID_Vstopnica;                                      
";

$result = mysqli_query($conn, $sql);  // izvede poizvedbo

// če je poizvedba uspela in je kaj vrstic
if ($result && mysqli_num_rows($result) > 0) {
    echo "<h2 class='naslovcek'>Izpis vstopnic z obiskovalci in dvoranami</h2>";
    echo "<table class='tabela1'>";
    echo "<thead>
            <tr>
                <th>ID Vstopnice</th>
                <th>Cena (€)</th>
                <th>Vrsta</th>
                <th>Ime obiskovalca</th>
                <th>Priimek obiskovalca</th>
                <th>Starost</th>
                <th>Ime dvorane</th>
                <th>Število sedežev</th>
            </tr>
          </thead><tbody>";

    // skozi vse vrstice, ki jih je vrnila poizvedba
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['ID_Vstopnica']) . "</td>";
        echo "<td>" . number_format($row['Cena'], 2) . "</td>";  // ceno oblikue na 2 decimalki
        echo "<td>" . htmlspecialchars($row['Vrsta']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Ime']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Priimek']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Starost']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Ime_dvorane']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Število_sedežev']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p class='tabela1'>Ni podatkov za prikaz.</p>";
}

mysqli_close($conn);  // zapri povezavo z bazo

// gumb za nazaj na začetno stran
echo "<a href='index.php' class='gumbek'>Nazaj na začetek</a>";
?>

<?php include "noga.php"; // prilepi nogo (footer) ?>