<?php include "glava.php"; ?>
<?php
$servername = "localhost:3308";
$username = "root";
$password = "";
$dbname = "JakaLeich";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("Povezava ni uspela: " . mysqli_connect_error());

$id = $_GET['id'] ?? 0;

// poberi podatke o filmu
$sql = "SELECT * FROM Film WHERE ID_Film = $id";
$result = $conn->query($sql);
$film = $result->fetch_assoc();

if (!$film) {
    echo "<p class='seznam-naslov'>Film ne obstaja.</p>";
    include "noga.php";
    exit;
}

// poberi projekcije za ta film (samo prihajajoče)
$sql_proj = "
    SELECT p.ID_Projekcija, p.Datum, p.Ura, d.Ime_dvorane
    FROM Projekcija p
    JOIN Dvorana d ON p.ID_Dvorana = d.ID_Dvorana
    WHERE p.ID_Film = $id AND p.Datum >= CURDATE()
    ORDER BY p.Datum, p.Ura
";
$projekcije = $conn->query($sql_proj);

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
    if (strtolower(trim($kljuc)) === strtolower(trim($film['Naslov']))) {
        $slika = '../Slike/' . $datoteka;
        break;
    }
}

// opisi in ocene filmov
$opisi = [
    'Matrix'        => ['opis' => 'Hacker Neo odkrije, da resničnost, v kateri živimo, ni nič drugega kot računalniška simulacija, ki jo nadzorujejo stroji. Pridruži se uporu proti sistemu in spozna, da je morda izbranec.', 'ocena' => 8.7],
    'Titanic'       => ['opis' => 'Na krovu luksuznega ladja Titanic se zaljubita bogata deklica Rose in revni umetnik Jack. Ko ladja trči v ledeno goro, morata preživeti katastrofo in boriti za njuno ljubezen.', 'ocena' => 7.9],
    'Toy Story'     => ['opis' => 'Cowboy Woody je najljubša igrača Andyja, dokler se ne pojavi vesoljec Buzz Lightyear. Ko se izgubita, morata skupaj najti pot domov in pri tem odkrijeta pristno prijateljstvo.', 'ocena' => 8.3],
    'Inception'     => ['opis' => 'Dom Cobb je mojster kraje idej iz sanj. Tokrat dobi nalogo, da v um poslovneža vsadi idejo – namesto da jo ukrade. Misija ga popelje skozi plasti sanj znotraj sanj.', 'ocena' => 8.8],
    'Joker'         => ['opis' => 'Arthur Fleck je neuspešen komik z duševnimi težavami, ki ga Gotham City zavrača. Postopoma se sprevrže v kaotičnega kriminalca Jokerja in postane simbol upora proti sistemu.', 'ocena' => 8.4],
    'Avengers'      => ['opis' => 'Nick Fury zbere skupino izjemnih junakov – Iron Mana, Capta Americe, Thorja, Hulka in drugih – da skupaj preprečijo Lokiju, da bi zavladal Zemlji z močjo kozmičnega kuba.', 'ocena' => 8.0],
    'Frozen'        => ['opis' => 'Ko snežna kraljica Elsa po nesreči zamrzne kraljevino Arendelle, se njena sestra Anna odpravi na misijo, da jo reši. Na poti spozna gorskega fanta Kristoffa in sneženega moža Olafa.', 'ocena' => 7.4],
    'Gladiator'     => ['opis' => 'Rimski general Maximus je po predaji cesarstva zlobnemu Commodusu izgubil vse – družino in svobodo. Kot suženj postane gladiator in se bori za maščevanje ter čast Rima.', 'ocena' => 8.5],
    'Interstellar'  => ['opis' => 'Ko Zemlja postane neprimerna za življenje, se astronavt Cooper odpravi skozi črno luknjo v iskanje novega doma za človeštvo. Potovanje ga loči od hčerke in ga sooči z relativnostjo časa.', 'ocena' => 8.7],
    'The Lion King' => ['opis' => 'Mladi levji princ Simba po tragični smrti očeta pobegne v izgnanstvo. Odrastek se mora soočiti s svojo preteklostjo in prevzeti prestol, ki mu po pravici pripada.', 'ocena' => 8.5],
];

$opis = $opisi[$film['Naslov']]['opis'] ?? 'Opis filma ni na voljo.';
$ocena = $opisi[$film['Naslov']]['ocena'] ?? 0;
?>

<div class="detail-wrapper">

    <!-- LEVO: poster -->
    <div class="detail-levo">
        <?php if ($slika): ?>
            <img src="<?= $slika ?>" alt="<?= htmlspecialchars($film['Naslov']) ?>" class="detail-poster">
        <?php endif; ?>
    </div>

    <!-- DESNO: info -->
    <div class="detail-desno">
        <h1 class="detail-naslov"><?= htmlspecialchars($film['Naslov']) ?></h1>

        <div class="detail-ocena">
            ⭐ <span><?= number_format($ocena, 1) ?> / 10</span>
        </div>

        <div class="detail-meta">
            <div class="detail-meta-vrstica">
                <span class="detail-label">Žanr</span>
                <span><?= htmlspecialchars($film['Žanr']) ?></span>
            </div>
            <div class="detail-meta-vrstica">
                <span class="detail-label">Trajanje</span>
                <span><?= htmlspecialchars($film['Trajanje']) ?> min</span>
            </div>
            <div class="detail-meta-vrstica">
                <span class="detail-label">Starostna omejitev</span>
                <span class="detail-starost"><?= $film['Starostna_omejitev'] == 0 ? 'VSI' : $film['Starostna_omejitev'] . '+' ?></span>
            </div>
        </div>

        <p class="detail-opis"><?= htmlspecialchars($opis) ?></p>

        <!-- projekcije -->
        <?php if ($projekcije && $projekcije->num_rows > 0): ?>
            <h3 class="detail-termin-naslov">Termini predvajanja</h3>
            <div class="detail-termini">
                <?php while ($p = $projekcije->fetch_assoc()): ?>
                    <a href="rezerviraj.php?id=<?= $p['ID_Projekcija'] ?>" class="detail-termin-gumb">
                        <span class="detail-termin-datum"><?= date('d. m. Y', strtotime($p['Datum'])) ?></span>
                        <span class="detail-termin-ura"><?= substr($p['Ura'], 0, 5) ?></span>
                        <span class="detail-termin-dvorana"><?= htmlspecialchars($p['Ime_dvorane']) ?></span>
                    </a>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php
$conn->close();
include "noga.php";
?>
