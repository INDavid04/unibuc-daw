<?php

//////////////////////////////////////////
/// CRUD: Create, Read, Update, Delete ///
//////////////////////////////////////////

/// Clasa care ne ajuta sa lucram cu baza de date fara sa scriem SQL de fiecare data
class OperatiiDB {

    /// READ: Citeste date din baza de date
    public static function read($tabel, $query) {
        /// Incarca clasa Database pentru a gestiona conexiunea la baza de date
        require_once 'exempluDatabase.php';

        /// Fiecare metoda din clasa (read(), create(), update(), delete()) se conecteaza singura la baza de date prin Dababase::getInstance()
        $conn = Database::getInstance()->getConnection();

        /// Pregateste si executa interogarea SQL
        $sql = "SELECT * FROM $tabel $query";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        /// Returneaza toate inregistrarile gasite   
        return $stmt->fetchAll();
    }

    /// CREATE: Insereaza date in baza de date
    public static function create($tabel, $valori){
        /// Incarca clasa Database pentru a gestiona conexiunea la baza de date
        require_once 'exempluDatabase.php';

        /// Fiecare metoda din clasa (read(), create(), update(), delete()) se conecteaza singura la baza de date prin Dababase::getInstance()
        $conn = Database::getInstance()->getConnection();
        
        /// Pregateste si executa interogarea SQL
        $coloaneNeformatate = implode(",",array_keys($valori));
        
        /// Formateaza coloanele pentru PDO
        $coloane = array_keys($valori);
        
        /// Adauga ":" in fata fiecarui nume de coloana pentru PDO
        for ($i = 0; $i < count($coloane); $i++) {
            $coloane[$i] = ":" . $coloane[$i];
        }

        /// Implementeaza array-ul de coloane intr-un string
        $coloane = implode(", ", $coloane);

        /// Creeaza instructiunea SQL de inserare   
        $sql = "INSERT INTO $tabel ($coloaneNeformatate) VALUES ($coloane)";
        echo $sql;
        echo var_dump($valori);
        
        /// Pregateste si executa interogarea SQL
        $stmt = $conn->prepare($sql);
        $stmt->execute($valori);  

        return $conn->lastInsertId();
    }

    /// UPDATE: Actualizeaza date in baza de date
    public static function update($tabel, $valori, $conditie){
        /// Incarca clasa Database pentru a gestiona conexiunea la baza de date
        require_once 'Database.php';

        /// Fiecare metoda din clasa (read(), create(), update(), delete()) se conecteaza singura la baza de date prin Dababase::getInstance()
        $conn = Database::getInstance()->getConnection();
        
        /// Pregateste si executa interogarea SQL
        $coloane = array_keys($valori);
        
        /// Formateaza coloanele pentru PDO
        for ($i = 0; $i < count($coloane); $i++) {
            $coloane[$i] = $coloane[$i] . "=:" . $coloane[$i];
        }

        /// Implementeaza array-ul de coloane intr-un string
        $coloane = implode(", ", $coloane);

        /// Creeaza instructiunea SQL de actualizare
        $sql = "UPDATE $tabel SET $coloane WHERE $conditie";
        
        /// Pregateste si executa interogarea SQL
        $stmt = $conn->prepare($sql);
        
        /// Executa instructiunea pregatita cu datele din array
        $stmt->execute($valori);  
    }

    /// DELETE: Sterge date din baza de date
    public static function delete($tabel, $conditie){
        /// Incarca clasa Database pentru a gestiona conexiunea la baza de date
        require_once 'Database.php';

        /// Fiecare metoda din clasa (read(), create(), update(), delete()) se conecteaza singura la baza de date prin Dababase::getInstance()
        $conn = Database::getInstance()->getConnection();

        /// Pregateste si executa interogarea SQL
        $sql = "DELETE FROM $tabel WHERE $conditie";
        
        /// Pregateste si executa interogarea SQL
        $stmt = $conn->prepare($sql);
        
        /// Executa instructiunea pregatita
        $stmt->execute();
    }
}