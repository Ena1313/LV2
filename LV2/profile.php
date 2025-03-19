<?php

$xmlFile = __DIR__ . "/LV2.xml";
if (!file_exists($xmlFile)) {
    die("XML datoteka LV2.xml ne postoji!");
}

$xml = simplexml_load_file($xmlFile) or die("Greška pri učitavanju XML datoteke.");

// provjera da li je postavljen GET parametar 'id'
if (isset($_GET['id'])) {
    $requestedId = $_GET['id'];
    $found = false;
    
    // prolazak kroz sve osobe i trazenje one s odgovarajućim id-em
    foreach ($xml->osoba as $osoba) {
        if ((string)$osoba->id === (string)$requestedId) {
            $found = true;
            ?>
            <!DOCTYPE html>
            <html lang="hr">
            <head>
                <meta charset="UTF-8">
                <title>Profil osobe: <?php echo htmlspecialchars($osoba->ime); ?></title>
                <style>\n 
                 body { font-family: Arial, sans-serif; margin: 20px; }\n 
                .profil { border: 1px solid #ccc; padding: 20px; max-width: 400px; }\n 
                .profil img { max-width: 100%; height: auto; }\n
                </style>
            </head>
            <body>
                <div class="profil">
                    <h2><?php echo htmlspecialchars($osoba->ime) . " " . htmlspecialchars($osoba->prezime); ?></h2>
                    <img src="<?php echo htmlspecialchars($osoba->slika); ?>" alt="Slika osobe"><br><br>
                    <strong>Email:</strong> <?php echo htmlspecialchars($osoba->email); ?><br><br>
                    <strong>Životopis:</strong><br><?php echo nl2br(htmlspecialchars($osoba->zivotopis)); ?>
                </div>
                <br><a href="profile.php">Nazad na listu</a>
            </body>
            </html>
            <?php
            break;
        }
    }
    
    if (!$found) {
        echo "Osoba s ID " . htmlspecialchars($requestedId) . " nije pronađena.";
    }
} else {
    // GET parametar nije postavljen -> prikaži listu svih osoba
    ?>
    <!DOCTYPE html>
    <html lang="hr">
    <head>
        <meta charset="UTF-8">
        <title>Popis osoba</title>
        <style>\n 
        body { font-family: Arial, sans-serif; margin: 20px; }\n           
         ul { list-style: none; padding: 0; }\n            
         li { margin-bottom: 10px; }\n        
         </style>
    </head>
    <body>
        <h2>Popis osoba</h2>
        <ul>
        <?php
        foreach ($xml->osoba as $osoba) {
            $id = htmlspecialchars($osoba->id);
            $ime = htmlspecialchars($osoba->ime);
            $prezime = htmlspecialchars($osoba->prezime);
            echo "<li><a href='profile.php?id=$id'>$ime $prezime</a></li>";
        }
        ?>
        </ul>
    </body>
    </html>
    <?php
}
?>