<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'lv2_db';

$backupFile = 'backup.txt';
$compressedFile = 'backup.txt.gz';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Konekcija neuspješna: " . $conn->connect_error);
}

$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}

$backupContent = "";

// sql insert upiti
foreach ($tables as $table) {
    $result = $conn->query("SELECT * FROM $table");
    while ($row = $result->fetch_assoc()) {
        // zaštiti vrijednosti od SQL injectiona
        $values = array_map([$conn, 'real_escape_string'], array_values($row));
        $backupContent .= "INSERT INTO $table (" . implode(", ", array_keys($row)) . ") VALUES ('" . implode("', '", $values) . "');\n";
    }
}

// spremanje backupa u .txt datoteku
file_put_contents($backupFile, $backupContent);

// sazimanje .txt datoteke koristeći gzip
$compressedData = gzencode($backupContent, 9);
file_put_contents($compressedFile, $compressedData);

// obrisana originalna .txt datoteka
unlink($backupFile);

echo "Backup baze podataka je uspješno napravljen i sažet u $compressedFile.";

$conn->close();
?>
