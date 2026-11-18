<?php
function CheckEnv(array $keys, array $allowEmpty = []): bool {
    foreach ($keys as $key) {
        if (!isset($_ENV[$key]) || ($_ENV[$key] === "" && !in_array($key, $allowEmpty, true))) {
            return false;
        }
    }
    return true;
}
function LoadEnv() {
    $file = __DIR__ . "/../../.env";
    LoadEnvFile($file);
    $appEnv = $_ENV["APP_ENV"] ?? "";

    if (!in_array($appEnv, ["development", "production"], true)) {
        return;
    }
    $file = $file.".".$appEnv;

    if (is_file($file)) {
        LoadEnvFile($file);
    }
}
function LoadEnvFile($file) {
    if (!is_file($file)) {
        return;
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lines === false) {
        return;
    }
    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === "" || $line[0] === "#") {
            continue;
        }
        $parts = explode("=", $line, 2);

        if (count($parts) !== 2) {
            continue;
        }
        $key = trim($parts[0]);
        $value = trim($parts[1]);

        if ($key === "") {
            continue;
        }
        if (strlen($value) >= 2) {
            $first = $value[0];
            $last = substr($value, -1);

            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }
        $_ENV[$key] = $value;
        putenv($key . "=" . $value);
    }
}
function UpdateEnv(string $path, array $values): bool {
    if (!is_file($path) || !is_readable($path) || !is_writable($path)) {
        return false;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES);

    if ($lines === false) {
        return false;
    }
    foreach ($lines as &$line) {
        $trimmed = trim($line);

        if ($trimmed === "" || strpos($trimmed, "#") === 0) {
            continue;
        }
        if (preg_match("/^([A-Za-z_][A-Za-z0-9_]*)\s*=/", $trimmed, $matches)) {
            $key = $matches[1];

            if (array_key_exists($key, $values)) {
                $line = $key."=".$values[$key];
                unset($values[$key]);
            }
        }
    }
    unset($line);

    foreach ($values as $key => $value) {
        $lines[] = $key."=".$value;
    }
    return file_put_contents($path, implode(PHP_EOL, $lines).PHP_EOL, LOCK_EX) !== false;
}
?>