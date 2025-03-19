<?php
// URL RandomUser API-ja
$apiUrl = "https://randomuser.me/api/?results=100&nat=us,gb,fr,de";

// dohvacanje JSON podataka
$jsonData = file_get_contents($apiUrl);
$data = json_decode($jsonData, true);

if (!$data || !isset($data["results"])) {
    die("Dohvaćanje podataka nije uspjelo!");
}

$xml = new SimpleXMLElement("<osobe></osobe>");

foreach ($data["results"] as $index => $user) {
    $osoba = $xml->addChild("osoba");
    $osoba->addChild("id", $index + 1);
    $osoba->addChild("ime", htmlspecialchars($user["name"]["first"], ENT_XML1, 'UTF-8'));
    $osoba->addChild("prezime", htmlspecialchars($user["name"]["last"], ENT_XML1, 'UTF-8'));
    $osoba->addChild("email", htmlspecialchars($user["email"], ENT_XML1, 'UTF-8'));
    $osoba->addChild("spol", $user["gender"] == "male" ? "M" : "Z");
    $osoba->addChild("slika", htmlspecialchars($user["picture"]["large"], ENT_XML1, 'UTF-8'));
    $osoba->addChild("zivotopis", "Ova osoba nema dostupni životopis.");
}

$xml->asXML("LV2.xml");

echo "Podaci su uspješno generirani i spremljeni u LV2.xml!";
?>
