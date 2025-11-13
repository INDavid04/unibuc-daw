<!DOCTYPE html>

<!------------------------------------------------------------------------------>
<!-- Creeaza un sistem simplu de upload fisiere in PHP cu interfata Bootstrap -->
<!------------------------------------------------------------------------------>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <form action="files-handler.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="fileUpload" class="form-label">Upload File</label>
            <input type="file" class="form-control" id="fileUpload" name="fileUpload" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</body>
</html>

<?php
/// Verifica daca un fisier a fost uploadat
if (isset($_FILES['fileUpload'])) {
    /// Directorul unde vor fi salvate fisierele uploadate
    $uploadDir = 'uploads/';
    /// Creeaza directorul daca nu exista
    if (!is_dir($uploadDir)) {
        /// Permisiuni 0755 si creeaza recursiv daca e nevoie
        mkdir($uploadDir, 0755, true);
    }
    /// Afiseaza informatii despre fisierul uploadat
    var_dump($_FILES['fileUpload']);
    /// Calea completa unde va fi salvat fisierul uploadat
    $uploadFile = $uploadDir . basename($_FILES['fileUpload']['name']);

    /// Muta fisierul din locatia temporara in directorul de upload
    if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $uploadFile)) {
        echo "File is valid, and was successfully uploaded.\n";
        echo "File path: " . htmlspecialchars($uploadFile);
    } else {
        echo "Possible file upload attack!\n";
    }
} else {
    echo "No file uploaded or invalid request method.";
}
?>