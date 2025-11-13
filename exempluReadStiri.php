<!DOCTYPE html>

<!-------------------------------------------------------------------->
<!-- Afiseaza detaliile unui articol de stiri dintr-o baza de date  -->
<!-------------------------------------------------------------------->

<html lang="ro">
<head>
    <meta charset="utf-8">
    <title>Detalii stire</title>
</head>
<body>

<?php
/// Incarca clasa Database pentru a gestiona conexiunea la baza de date
require_once 'exempluDatabase.php';

/// Verifica daca parametrul id este setat in URL
if (isset($_GET['id'])) {
    /// Preia valoarea parametrului id
    $id = $_GET['id'];

    /// Conecteaza instanta unica a clasei Database
    try {
        /// PDO = PHP Data Objects
        $pdo = Database::getInstance()->getConnection();

        /// Cauta stirea in tabela stiri dupa id
        $sql = "SELECT * FROM stiri WHERE id = :id";
        
        /// Pregateste statement-ul
        $stmt = $pdo->prepare($sql);
        
        /// Creeaza un array cu datele pentru interogare
        $data = [
            'id' => $id
        ];

        /// Executa interogarea
        $stmt->execute($data);

        /// Preia rezultatul
        $record = $stmt->fetch();

        /// Afiseaza detaliile stirii
        $title = $record['titlu'];
        $shortDescription = $record['short_description'];
        $description = $record['description'];

        echo "<table border='1'>
                <tr>
                    <th>Title</th>
                    <th>Short Description</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>" . htmlspecialchars($title) . "</td>
                    <td>" . htmlspecialchars($shortDescription) . "</td>
                    <td>" . nl2br(htmlspecialchars($description)) . "</td>
                </tr>
            </table>";
    } catch (PDOException $e) {
        /// Opreste executia scriptului
        die("Connection failed: " . $e->getMessage());
    }
}

?>
</body>
</html>
