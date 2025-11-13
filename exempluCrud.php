<?php

//////////////////////////////////////////
/// CRUD: Create, Read, Update, Delete ///
//////////////////////////////////////////

/// Clasa care ne ajuta sa lucram cu baza de date fara sa scriem SQL de fiecare data
class OperatiiDB {

    /// READ: Citeste date din baza de date
    public static function read($tabel, $query) {
        require_once 'Database.php';

        /// Fiecare metoda din clasa (read(), create(), update(), delete()) se conecteaza singura la baza de date prin Dababase::getInstance()
        $conn = Database::getInstance()->getConnection();

        $sql = "SELECT * FROM $tabel $query";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function create($tabel, $valori){
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();
        $coloaneNeformatate = implode(",",array_keys($valori));
        $coloane = array_keys($valori);
        for ($i = 0; $i < count($coloane); $i++) {
            $coloane[$i] = ":" . $coloane[$i];
        }
        $coloane = implode(", ", $coloane);

        $sql = "INSERT INTO $tabel ($coloaneNeformatate) VALUES ($coloane)";
        echo $sql;
        echo var_dump($valori);
        $stmt = $conn->prepare($sql);
        $stmt->execute($valori);  

        return $conn->lastInsertId();
    }

    public static function update($tabel, $valori, $conditie){
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();
        $coloane = array_keys($valori);
        for ($i = 0; $i < count($coloane); $i++) {
            $coloane[$i] = $coloane[$i] . "=:" . $coloane[$i];
        }
        $coloane = implode(", ", $coloane);

        $sql = "UPDATE $tabel SET $coloane WHERE $conditie";
        $stmt = $conn->prepare($sql);
        $stmt->execute($valori);  
    }

    public static function delete($tabel, $conditie){
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();

        $sql = "DELETE FROM $tabel WHERE $conditie";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
}