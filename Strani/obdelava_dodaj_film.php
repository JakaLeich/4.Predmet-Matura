<?php include "glava.php"; ?>

<?php 
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "JakaLeich";

// Vzpostavi povezavo z bazo
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Ce povezava ni uspela izpise napako
if (!$conn) {
  die("Povezava ni uspela " . mysqli_connect_error());
}

// Pridobivanje podatkov iz obrazca
$naslov = $_POST['naslov'];
$zanr = $_POST['zanr'];
$trajanje = $_POST['trajanje'];
$starostna_omejitev = $_POST['starostna_omejitev'];

// SQL koda za vstavljanje podatkov v tabelo
$sql = "INSERT INTO Film (Naslov, Žanr, Trajanje, Starostna_omejitev) 
        VALUES ('$naslov', '$zanr', $trajanje, $starostna_omejitev)";

// Izvede poizvedbo in preveri, ce je bila uspesna
  if ( mysqli_query($conn, $sql) )
  {
    header('location:dodaj_film_obvestilo.php?tabela1=film');
  } 
  else // Ce pride do napake, izpise kaj je bila napaka
  {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  };
        ?>
<?php        
// Prekinjanje povezave z bazo
mysqli_close($conn);

include "noga.php"; 
?>