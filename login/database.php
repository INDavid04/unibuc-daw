<?php

////////////////////////////////////////////////////////////////////////////////////
/// Pattern Singleton pentru gestionarea conexiunii la baza de date folosind PDO ///
////////////////////////////////////////////////////////////////////////////////////

class Database {
    /// Instanta unica a clasei Database
    private static ?Database $instance = null;
    
    /// Instanta PDO (i.e. PHP Data Objects)
    private PDO $connection;

    /// Configuratia bazei de date
    private string $host = "localhost";
    private string $dbName = "dirimias_organizare_evenimente";
    private string $username = "dirimias_organizare_evenimente";
    private string $password = "4ZuW47xKxJDbM6tnxjaq";

    /// Optiuni PDO (ATTR = Attribute i.e atribut, stare)
    private array $options = [
        /// Cum raporteaza erorile
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        /// Cum iei datele
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        /// Securitate mai buna
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /// Constructor privat
    private function __construct() {
        try {
            /// Creeaza conexiunea PDO
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            /// Seteaza conexiunea PDO
            $this->connection = new PDO($dsn, $this->username, $this->password);
            /// Seteaza atributele conexiunii PDO
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            /// Opreste executia scriptului daca conexiunea esueaza
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /// Impiedica clonarea obiectului
    private function __clone() {}

    /// Impiedica unserializarea obiectului
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }

    /// Obtine instanta unica a clasei Database
    public static function getInstance(): Database {
        /// Daca instanta nu exista, o creeaza
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /// Obtine conexiunea PDO
    public function getConnection(): PDO {
        return $this->connection;
    }
}
