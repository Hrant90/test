<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$host = $_ENV['DB_HOST'];
$db = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$charset = 'utf8mb4';


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$values = [];
$sql = "INSERT INTO tasks (title, description, status, author, date) VALUES ";

for ($i = 1; $i <= 1000; $i++) {
    $title = "task $i";
    $description = "description $i";
    $status = "status $i";
    $author = "author $i";
    $date = date('Y-m-d H:i:s', strtotime("+$i hours"));

    $values[] = "(:title$i, :description$i, :status$i, :author$i, :date$i)";
}

$sql .= implode(', ', $values);
$stmt = $pdo->prepare($sql);

foreach ($values as $i => $value) {
    $index = $i + 1;
    $stmt->bindValue(":title$index", "task $index");
    $stmt->bindValue(":description$index", "description $index");
    $stmt->bindValue(":status$index", "status $index");
    $stmt->bindValue(":author$index", "author $index");
    $stmt->bindValue(":date$index", date('Y-m-d H:i:s', strtotime("+$index hours")));
}
$stmt->execute();