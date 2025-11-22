<?php
session_start();
require_once '../login/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'organizator') {
    die("Acces interzis!");
}

$pdo = Database::getInstance()->getConnection();

$stmt = $pdo->prepare("INSERT INTO eveniment (nume, locatie, data, idOrganizator) VALUES (?, ?, ?, ?)");

$stmt->execute([
    $_POST['nume'], 
    $_POST['locatie'], 
    $_POST['data'], 
    $_SESSION['idOrganizator']
]);

header("Location: ../");
exit;
