<?php
class Sqlite3Database {
    private PDO $pdo;

    public function __construct(array $options) {
        $database = $options["database"] ?? ":memory:";
        $dsn = "sqlite:$database";
        $this->pdo = new PDO($dsn, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    public function Query(string $query): PDOStatement|false {
        return $this->pdo->query($query);
    }
    public function Prepare(string $query, array $values = []): PDOStatement {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt;
    }
    public function GetPDO(): PDO {
        return $this->pdo;
    }
}
?>
