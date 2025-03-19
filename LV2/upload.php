<?php
// konfiguracija enkripcije
$cipher = "AES-256-CBC";
$key = "a1b2c3d4e5f67890a1b2c3d4e5f67890a1b2c3d4e5f67890a1b2c3d4e5f67890";
$iv_length = openssl_cipher_iv_length($cipher);

$allowedExtensions = ['pdf', 'jpeg', 'jpg', 'png'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['document']['tmp_name'];
        $fileName = $_FILES['document']['name'];
        $fileSize = $_FILES['document']['size'];
        $fileType = $_FILES['document']['type'];
        
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "Dozvoljeni su samo PDF, JPEG i PNG formati.";
            exit;
        }
        
        $data = file_get_contents($fileTmpPath);
        
        $iv = openssl_random_pseudo_bytes($iv_length);
        
        // enkriptiranje sadržaja (uz OPENSSL_RAW_DATA za raw output)
        $encryptedData = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        
        if ($encryptedData === false) {
            echo "Greška pri enkripciji!";
            exit;
        }
        
        $finalEncrypted = base64_encode($iv . $encryptedData);
        
        // definiranje imena za enkriptirani file
        $encryptedFileName = pathinfo($fileName, PATHINFO_FILENAME) . "." . $fileExtension . ".enc";
        $destination = __DIR__ . "/uploads/" . $encryptedFileName;
        
        if (file_put_contents($destination, $finalEncrypted)) {
            echo "Datoteka je uspješno uploadana i enkriptirana.";
        } else {
            echo "Došlo je do greške pri spremanju datoteke.";
        }
    } else {
        echo "Molim odaberi datoteku za upload.";
    }
} else {
    ?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Upload i enkripcija datoteka</title>
</head>
<body>
    <h2>Uploadaj dokument ili sliku (PDF, JPEG, PNG)</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="document" required><br><br>
        <input type="submit" value="Uploadaj">
    </form>
</body>
</html>
    <?php
}
?>
