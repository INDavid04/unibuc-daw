<?php
session_start();
require_once '../login/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organizator') {
    die("Acces interzis!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data_din_formular = $_POST['data_eveniment'];
    $data_curenta = date('Y-m-d');
    if ($data_din_formular < $data_curenta) {
        die("Ne pare rau insa nu puteti adauga evenimente a caror data este mai veche decat astazi");
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
