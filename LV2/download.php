<?php

$cipher = "AES-256-CBC";
$key = "a1b2c3d4e5f67890a1b2c3d4e5f67890a1b2c3d4e5f67890a1b2c3d4e5f67890";
$iv_length = openssl_cipher_iv_length($cipher);

if (!isset($_GET['file'])) {
    die("Nije specificirana datoteka.");
}

$encryptedFileName = basename($_GET['file']);
$filePath = __DIR__ . "/uploads/" . $encryptedFileName;

if (!file_exists($filePath)) {
    die("Datoteka ne postoji.");
}

$finalEncrypted = file_get_contents($filePath);
$encryptedData = base64_decode($finalEncrypted);

$iv = substr($encryptedData, 0, $iv_length);
$ciphertext = substr($encryptedData, $iv_length);

$decryptedData = openssl_decrypt($ciphertext, $cipher, $key, OPENSSL_RAW_DATA, $iv);

if ($decryptedData === false) {
    die("Dekriptiranje nije uspjelo!");
}

// odredivanje originalnog naziva i ekstenzije datoteke
$originalName = preg_replace('/\\.enc$/', '', $encryptedFileName);

// zaglavlja za preuzimanje filea
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $originalName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($decryptedData));

echo $decryptedData;
exit;
?>
