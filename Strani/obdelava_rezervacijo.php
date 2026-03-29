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
  die("Povezava ni uspela " . mysqli_connect_error());
}

// pobere podatke iz obrazca 
$ime = $_POST['ime'];
$priimek = $_POST['priimek'];
$starost = $_POST['starost'];
$vrsta = $_POST['vrsta'];
$id_projekcija = $_POST['id_projekcija'];
$sedezi = $_POST['sedezi'] ?? [];

// 1. najprej vstavi obiskovalca v tabelo Obiskovalec
$sql_obiskovalec = "INSERT INTO Obiskovalec (Ime, Priimek, Starost) 
                    VALUES ('$ime', '$priimek', $starost)";
if (mysqli_query($conn, $sql_obiskovalec)) {
    // vzami ID, ki ga je baza dodelila novemu obiskovalcu
    $id_obiskovalec = mysqli_insert_id($conn);

    // 2. potem vstavi vstopnico (poveže projekcijo in obiskovalca)
    $sql_vstopnica = "INSERT INTO Vstopnica (ID_Projekcija, ID_Obiskovalec, Vrsta) 
                      VALUES ($id_projekcija, $id_obiskovalec, '$vrsta')";
    
    if (mysqli_query($conn, $sql_vstopnica)) {
        // vzami ID nove vstopnice
        $id_vstopnica = mysqli_insert_id($conn);

        // 3. za vsak izbran sedež naredi svoj vnos v Rezervirani_sedezi
        foreach ($sedezi as $sedez) {
            // zaščita pred napadi (ubije posebne znake)
            $sedez_clean = mysqli_real_escape_string($conn, $sedez);
            $sql_sedez = "INSERT INTO Rezervirani_sedezi (ID_Vstopnica, Sedez) 
                          VALUES ($id_vstopnica, '$sedez_clean')";
            mysqli_query($conn, $sql_sedez);
        }

        // vse ok - preusmeri na stran z obvestilom
        header("Location: obdelaj_rezervacijo_obvestilo.php?tabela2==rezervacija");
    } else {
        // če ni šlo vstopnica, izpiši napako
        echo "Napaka pri vnosu vstopnice: " . mysqli_error($conn);
    }
} else {
    // če ni šlo obiskovalec, izpiši napako
    echo "Napaka pri vnosu obiskovalca: " . mysqli_error($conn);
}

// zapri povezavo z bazo
mysqli_close($conn);
include "noga.php"; // prilepi nogo (footer)
?>