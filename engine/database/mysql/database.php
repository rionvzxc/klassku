<?php
class MysqlDatabase {
    private PDO $pdo;
    private bool $autoRollback = true;

    public function __construct(array $options) {
        ["host" => $host, "port" => $port, "database" => $database, "username" => $username, "password" => $password] = $options;
        $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";

        if ($database !== "") {
            $dsn .= ";dbname=$database";
        }
        $this->pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_FOUND_ROWS => true,
        ]);
    }
    private function handleError(): void {
        if ($this->pdo->inTransaction() && $this->autoRollback) {
            $this->pdo->rollBack();
        }
    }
    public function Quote(string $value): string {
        return $this->pdo->quote($value);
    }
    public function Query(string $query): ?PDOStatement {
        $stmt = $this->pdo->query($query);

        if ($stmt === false) {
            $this->handleError();
            return null;
        }
        return $stmt;
    }
    public function Prepare(string $query, array $values = []): ?PDOStatement {
        $stmt = $this->pdo->prepare($query);

        if ($stmt === false || !$stmt->execute($values)) {
            $this->handleError();
            return null;
        }
        return $stmt;
    }
    public function BeginTransaction(bool $autoRollback = true): bool {
        if ($this->pdo->inTransaction()) {
            return false;
        }
        $this->autoRollback = $autoRollback;
        return $this->pdo->beginTransaction();
    }
    public function Commit(): bool {
        if (!$this->pdo->commit()) {
            $this->handleError();
            return false;
        }
        return true;
    }
    public function RollBack(): bool {
        return $this->pdo->rollBack();
    }
    public function InTransaction(): bool {
        return $this->pdo->inTransaction();
    }
    public function PDO(): PDO {
        return $this->pdo;
    }
}
?>
