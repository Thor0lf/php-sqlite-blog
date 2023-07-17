<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$faker = Faker\Factory::create('fr_FR');

$file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'test.db';
$pdo = new PDO("sqlite:$file", null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$pdo->exec("DROP TABLE IF EXISTS articles");
$pdo->exec("DROP TABLE IF EXISTS categories");
$pdo->exec("DROP TABLE IF EXISTS users");

$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    fullname TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'user',
    isValid INTEGER NOT NULL DEFAULT 0,
    token TEXT,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    name TEXT NOT NULL,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS articles (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    title TEXT NOT NULL,
    formFile TEXT NOT NULL DEFAULT 'default.webp',
    content TEXT NOT NULL,
    user_id INTEGER NOT NULL,
    metadata TEXT NOT NULL,
    category_id TEXT NOT NULL,
    created_at INTEGER NOT NULL,
    updated_at INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
)");

$categories = [];
$articles = [];

$passwordAdmin = password_hash('admin', PASSWORD_DEFAULT);
$query = $pdo->prepare('INSERT INTO users (fullname, email, password, role, isValid, token , created_at, updated_at) VALUES (:fullname, :email, :password, :role, :isValid, :token, :created_at, :updated_at)');
$query->execute([
    'fullname'   => 'Admin',
    'email'      => 'admin@admin.com',
    'password'   => $passwordAdmin,
    'token'      => null,
    'isValid'    => 1,
    'role'       => 'admin',
    'created_at' => $faker->date() . ' ' . date($format = 'H:i:s'),
    'updated_at' => $faker->date() . ' ' . date($format = 'H:i:s')
]);
$passwordUser = password_hash('user', PASSWORD_DEFAULT);
$query->execute([
    'fullname'   => 'User',
    'email'      => 'user@user.com',
    'password'   => $passwordUser,
    'token'      => null,
    'isValid'    => 1,
    'role'       => 'user',
    'created_at' => $faker->date() . ' ' . date($format = 'H:i:s'),
    'updated_at' => $faker->date() . ' ' . date($format = 'H:i:s')
]);  

for ($i = 0; $i < 5; $i++) {
    $query = $pdo->prepare("INSERT INTO categories (name, created_at, updated_at) VALUES (:name, :created_at, :updated_at)");
    $query->execute([
        'name'       => $faker->word, 
        'created_at' => $faker->date() . ' ' . date($format = 'H:i:s'),
    'updated_at' => $faker->date() . ' ' . date($format = 'H:i:s')
    ]);
    $categories[] = $pdo->lastInsertId();
}

for ($i = 0; $i < 50; $i++) {
    $query = $pdo->prepare("INSERT INTO articles (title, content, formFile, metadata, user_id, category_id, created_at, updated_at) VALUES (:title, :content, :formFile, :metadata, :user_id, :category_id, :created_at, :updated_at)");
    $query->execute([
        'title'       => $faker->words($nb = 5, $asText = true), 
        'content'     => $faker->words($nb = 500, $asText = true), 
        'formFile'    => 'default.webp', 
        'metadata'    => $faker->words($nb = 5, $asText = true), 
        'user_id'     => 1,
        'category_id' => $faker->numberBetween($min = 1, $max = 5),
        'created_at' => $faker->date() . ' ' . date($format = 'H:i:s'),
        'updated_at' => $faker->date() . ' ' . date($format = 'H:i:s')
    ]);
    $articles[] = $pdo->lastInsertId();
}