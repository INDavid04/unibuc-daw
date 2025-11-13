<?php

///////////////////////////////////////////////////////////////////////////////
/// Creeaza si initializeaza tabela produse in baza de date cu date de test ///
///////////////////////////////////////////////////////////////////////////////

/// Incarca clasa care gestioneaza conexiunea la baza de date
require_once 'exempluDatabase.php';

try 
{
    /// Conecteaza instanta unica a clasei Database
    $pdo = Database::getInstance()->getConnection();
    echo "Connection successful!<br>";

    /// Creeaza tabela produse daca nu exista
    $sql = "CREATE TABLE IF NOT EXISTS 
    produse (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titlu VARCHAR(500) NOT NULL,
        descriere VARCHAR(500) NOT NULL,
        categorie VARCHAR(500) NOT NULL,
        pret DECIMAL(10, 2) NOT NULL
    ) ENGINE=INNODB;";

    /// Executa instructiunea SQL
    $pdo->exec($sql);
    echo "Produse create cu succes! <br>";

    /// Sterge toate inregistrarile din tabela produse
    $sql = "TRUNCATE TABLE produse;";
    $pdo->exec($sql);

    /// Insereaza date de test in tabela produse
    $sampleData = [
        [
            'titlu' => 'Laptop Dell XPS 13',
            'descriere' => 'The Dell XPS 13 features a stunning 13.3-inch display, 
                            powerful Intel processors, and long battery life, making it perfect 
                            for both work and entertainment.',
            'categorie' => 'Electronics',
            'pret' => 999.99
        ],
        [
            'titlu' => 'Smartphone Samsung Galaxy S21',
            'descriere' => 'The Samsung Galaxy S21 features a sleek design, powerful performance, and an impressive camera system, making it one of the top smartphones of 2021.',
            'categorie' => 'Tehnologie',
            'pret' => 890.00
        ],
        [
            'titlu' => 'Masa ping pong',
            'descriere' => 'Masa de ping pong este perfecta pentru distractie si sport in aer liber.',
            'categorie' => 'Sport',
            'pret' => 450.00
        ]
    ];

    /// Pregateste instructiunea SQL pentru inserare
    $stmt = $pdo->prepare("INSERT INTO produse (titlu, descriere, categorie, pret) VALUES (:titlu, :descriere, :categorie, :pret)");

    /// Executa inserarile
    foreach ($sampleData as $news) 
    {
        $stmt->execute
        ([
            ':titlu' => $news['titlu'],
            ':descriere' => $news['descriere'],
            ':categorie' => $news['categorie'],
            ':pret' => $news['pret']
        ]);
    }

    echo "Inserted succesfully! <br>";

    /// Afiseaza toate inregistrarile din tabela produse
    $stmt = $pdo->query("SELECT * FROM produse");
    $rows = $stmt->fetchAll();

    /// Log pentru debug
    error_log(print_r($rows, true));

    /// Afiseaza inregistrarile
    echo "<h3>Inserted rows:</h3>";
    foreach ($rows as $row) {
        echo "<b>{$row['titlu']}</b><br>";
        echo "{$row['descriere']}<br>";
        echo "{$row['categorie']}<br>";
        echo "{$row['pret']}<hr>";
    }
} catch (PDOException $e) {
    /// Opreste executia scriptului
    die("Error! Could not connect to db!<br>" . $e->getMessage());
}
