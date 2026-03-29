<?php include "glava.php"; // prilepi meni in glavo strani ?>
<?php
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "JakaLeich";

// Povezava z bazo
$conn = mysqli_connect($servername, $username, $password, $dbname);

// če povezava ni uspela javi napako
if (!$conn) {
    die("Povezava z bazo ni uspela: " . mysqli_connect_error());
}

// preveri, ali je v URLju poslan ID
if (isset($_GET["id"])) {
    $id = $_GET["id"];  // vzame ID iz URLja

    // preveri, ali film s tem ID sploh obstaja v bazi
    $sql_preveri = "SELECT * FROM Film WHERE ID_Film = '$id'";
    $result_preveri = mysqli_query($conn, $sql_preveri);

    // če obstaja vsaj ena vrstica (film obstaja)
    if (mysqli_num_rows($result_preveri) > 0) {
        
        // najprej zbriši vse projekcije za ta film (da ni napak s tujimi ključi)
        $sql_izbrisi_projekcije = "DELETE FROM projekcija WHERE ID_Film = '$id'";
        mysqli_query($conn, $sql_izbrisi_projekcije);
        
        // šele zdaj zbriše sam film
        $sql_izbrisi = "DELETE FROM Film WHERE ID_Film = '$id'";
        $rezultat = mysqli_query($conn, $sql_izbrisi);

    
        if ($rezultat) {
            echo "<p class='tabela1'>Film z ID $id je bil uspešno izbrisan.</p>";
        } else {
            echo "<p class='tabela1'>Napaka pri brisanju filma: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p class='tabela1'>Film z ID $id ne obstaja.</p>";
    }
} else {
    echo "<p class='tabela1'>ID filma ni podan.</p>";
}

// gumb za nazaj na seznam filmov
echo "<a href='film_izpis.php' class='gumbek'>Nazaj na seznam</a>";

// zapri povezavo z bazo
mysqli_close($conn);
?>
<?php include "noga.php"; // prilepi nogo (footer) ?>