<?php

////////////////////////////////////////////////////////
/// Insereaza stiri in tabela stiri din baza de date ///
////////////////////////////////////////////////////////

/// Importa o clasa care gestioneaza conexiunea la baza de date (Database.php)
require_once 'exempluDatabase.php';

/// Verifica daca formularul a fost trimis
if (isset($_POST['title']) || isset($_POST['short_description']) || isset($_POST['description'])) {

/// Conectare la baza de date folosind clasa Database
try {
    /// Obtine o instanta PDO (i.e. PHP Data Objects)
    $pdo = Database::getInstance()->getConnection();
    echo "Connection successful!<br>";
} catch (PDOException $e) {
    /// Opreste executia scriptului si afiseaza mesajul de eroare
    die("Connection failed: " . $e->getMessage());
}

// Insereaza o stire in tabela stiri
try {
    $sql = "INSERT INTO stiri (titlu, short_description, description) 
            VALUES (:title, :short_description, :description)";
    
    $stmt = $pdo->prepare($sql);

    /// Creeaza un array cu datele de inserat
    $data = [
        /// Operatorul ?? foloseste valoarea din $_POST daca este setata, altfel foloseste valoarea implicita
        'title' => $_POST['title'] ?? 'Sample Title',
        'short_description' => $_POST['short_description'] ?? 'Sample Short Description',
        'description' => $_POST['description'] ?? 'Sample Description'
    ];

    /// Executa instructiunea pregatita cu datele din array
    $stmt->execute($data);

    /// Redirectioneaza utilizatorul la /read-stiri.php?id=lastInsertId()
    header("Location: read-stiri.php?id=" . $pdo->lastInsertId());

} catch (PDOException $e) {
    echo "Insert failed: " . $e->getMessage();
}
}
?>