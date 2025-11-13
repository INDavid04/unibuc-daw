<?php
 
require_once 'exempluDatabase.php';
 
if (isset($_GET['id'])) {
    $id = $_GET['id'];

//Database connection
$pdo = Database::getInstance()->getConnection();
    echo "✅ Connection successful!<br>";
 
    $query = "SELECT * FROM stiri WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(
        ['id' => $id]
    );
    $record = $stmt->fetch();
 
    if ($record) {
        echo "Record found:<br>";
        echo "Titlu: " . htmlspecialchars($record['titlu']) . "<br>";
        echo "Descriere: " . htmlspecialchars($record['rezumat']) . "<br>";
        echo "Rezumat: " . htmlspecialchars($record['descriere']) . "<br>";
    } else {
        echo "No record found with ID: " . htmlspecialchars($id);      
 
    }
 
} else {
     // dacă nu e setat niciun id => afișează toate înregistrările din tabela stiri
    $pdo = Database::getInstance()->getConnection();
    echo "✅ Connection successful! Showing all records: <br>";
 
    $query = "SELECT * FROM stiri ORDER BY id ASC";
    $stmt = $pdo->query($query);
    $records = $stmt->fetchAll();
 
    if (isset($records) && count($records) > 0) {
        foreach ($records as $r) {
            echo "<hr>";
            echo "ID: " . htmlspecialchars($r['id']) . "<br>";
            echo "Titlu: " . htmlspecialchars($r['titlu']) . "<br>";
            echo "Descriere: " . htmlspecialchars($r['descriere']) . "<br>";
            echo "Rezumat: " . htmlspecialchars($r['rezumat']) . "<br>";
        }
    } else {
        echo "Nu există înregistrări în tabela stiri.";
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $updateId = $_POST['id'];
        $titlu = $_POST['titlu'] ?? '';
        $descriere = $_POST['descriere'] ?? '';
        $rezumat = $_POST['rezumat'] ?? '';
 
        $updateQuery = "UPDATE stiri SET titlu = :titlu, descriere = :descriere, rezumat = :rezumat WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $success = $updateStmt->execute([
            'titlu' => $titlu,
            'descriere' => $descriere,
            'rezumat' => $rezumat,
            'id' => $updateId
        ]);
 
        if ($success) {
            echo "<br>✅ Record updated successfully!";
        } else {
            echo "<br>❌ Failed to update record.";
        }
    }
 
    // Simple update form
    echo '<hr><form method="post">
        <label>ID de actualizat: <input type="number" name="id" required></label><br>
        <label>Titlu: <input type="text" name="titlu" required></label><br>
        <label>Descriere: <input type="text" name="descriere" required></label><br>
        <label>Rezumat: <input type="text" name="rezumat" required></label><br>
        <button type="submit">Actualizează</button>
    </form>';
}
?>