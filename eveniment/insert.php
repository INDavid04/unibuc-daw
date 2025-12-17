<?php

session_start();
require_once '../login/database.php';

/// Doar organizatorul poate adauga evenimente
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organizator') {
    die("Acces interzis!");
}

/// Organizatorul poate adauga doar evenimente noi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // date_default_timezone_get('Europe/Bucharest'); /// Rezolva aparitia mesajului chiar daca e o data valida
    $data_din_formular = $_POST['data_eveniment'];
    $data_curenta = date('Y-m-d');
    if ($data_din_formular < $data_curenta) {
        die("Ne pare rau insa nu puteti adauga evenimente a caror data este mai veche decat astazi ($data_din_formular a fost inainte de $data_curenta)");
    } else {
        $pdo = Database::getInstance()->getConnection();

        $stmt = $pdo->prepare("INSERT INTO eveniment (nume, locatie, data, idOrganizator) VALUES (?, ?, ?, ?)");

        $stmt->execute([
            $_POST['nume'], 
            $_POST['locatie'], 
            $_POST['data_eveniment'], 
            $_SESSION['idOrganizator']
        ]);

        header("Location: ../");
        exit;
    }
}
