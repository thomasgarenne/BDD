<?php

//require_once 'vendor/autoload.php';

$pdo = new PDO('mysql:dbname=complex;host=localhost', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE complex');
$pdo->exec('TRUNCATE TABLE cinema');
$pdo->exec('TRUNCATE TABLE halls');
$pdo->exec('TRUNCATE TABLE movies');
$pdo->exec('TRUNCATE TABLE shows');
$pdo->exec('TRUNCATE TABLE booking');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE users');
$pdo->exec('TRUNCATE TABLE customer');
$pdo->exec('TRUNCATE TABLE employe');
$pdo->exec('TRUNCATE TABLE manager');
$pdo->exec('TRUNCATE TABLE opening_hours');
$pdo->exec('TRUNCATE TABLE payment');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$faker = Faker\Factory::create();

$pdo->exec("INSERT INTO complex SET name='{$faker->sentence()}', nb_cinema=5");
/*
$cinemas = [];
for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO cinema 
                SET name='{$faker->sentence()}',
                address='{$faker->address()}', 
                postal_code='{$faker->postcode()}', 
                ville='{$faker->city()}', 
                phone='{$faker->e164PhoneNumber()}', 
                complex_id = 1");
    $cinemas[] = $pdo->lastInsertId();
}

$halls = [];
for ($i = 0; $i < 40; $i++) {
    $pdo->exec("INSERT INTO halls 
                SET hall_number='{$faker->randomDigit()}',
                cinema_id='{$faker->randomElement($cinemas)}',
                capacity='{$faker->randomDigit()}'
                ");
    $halls[] = $pdo->lastInsertId();
}

$categories = [];
for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO category 
                SET title='{$faker->word()}',
                description='{$faker->paragraph()}'");
    $categories[] = $pdo->lastInsertId();
}

$movies = [];
for ($i = 0; $i < 10; $i++) {
    $pdo->exec("INSERT INTO movies 
                SET title='{$faker->word()}',
                description='{$faker->paragraph()}',
                language='{$faker->randomLetter()}',
                time='{$faker->randomDigitNotNull()}',
                ageLimit='{$faker->randomDigitNotNull()}',
                category='{$faker->randomElement($categories)}',
                director='{$faker->word()}'
                ");
    $movies[] = $pdo->lastInsertId();
}


$shows = [];
for ($i = 0; $i < 20; $i++) {
    $pdo->exec("INSERT INTO shows 
                SET id_movie='{$faker->randomElement($movies)}',
                id_hall='{$faker->randomElement($halls)}',
                id_cinema='{$faker->randomElement($cinemas)}'
                ");
    $shows[] = $pdo->lastInsertId();
}
*/

for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO users 
    SET email='{$faker->email()}',
    passw='{$faker->password()}',
    phone='{$faker->e164PhoneNumber()}',
    firstname='{$faker->firstName()}',
    lastname='{$faker->lastName()}',
    age='{$faker->randomNumber()}',
    address='{$faker->address()}', 
    postal_code='{$faker->postcode()}', 
    ville='{$faker->city()}'
    ");
}
