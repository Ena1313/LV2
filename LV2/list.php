<?php
$uploadsDir = __DIR__ . "/uploads/";
$files = array_diff(scandir($uploadsDir), array('..', '.'));

echo "<h2>Lista enkriptiranih datoteka</h2>";
if (empty($files)) {
    echo "Nema uploadanih datoteka.";
} else {
    echo "<ul>";
    foreach ($files as $file) {
        //file se šalje kao GET parametar
        echo "<li><a href=\"download.php?file=" . urlencode($file) . "\">$file</a></li>";
    }
    echo "</ul>";
}
?>
