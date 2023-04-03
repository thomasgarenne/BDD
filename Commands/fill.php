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
$pdo->exec('TRUNCATE TABLE movie_category');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$faker = Faker\Factory::create();

$pdo->exec("INSERT INTO complex SET name='{$faker->sentence()}', nb_cinema=5");

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

$halls = [1, 2, 3, 4, 5, 6, 7, 8, 9];

foreach ($cinemas as $cinema) {
    foreach ($halls as $hall) {
        $pdo->exec("INSERT INTO halls 
        SET hall_number='$hall',
        cinema_id='$cinema',
        capacity='{$faker->randomDigit()}'
        ");
    }
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

foreach ($movies as $movie) {
    $randomCategories = $faker->randomElements($categories, rand(0, count($categories)));
    foreach ($randomCategories as $category) {
        $pdo->exec("INSERT INTO movie_category SET movie_id=$movie, category_id=$category");
    }
}

$password = password_hash('admin', PASSWORD_BCRYPT);

$person = [];
for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO users 
    SET email='{$faker->email()}',
    passw='$password',
    phone='{$faker->e164PhoneNumber()}',
    firstname='{$faker->firstName()}',
    lastname='{$faker->lastName()}',
    age='{$faker->randomDigit()}',
    address='{$faker->address()}', 
    postal_code='{$faker->postcode()}', 
    ville='{$faker->city()}'
    ");
    $person[] = $pdo->lastInsertId();
}

$pdo->exec("INSERT INTO customer
    SET user_id = {$faker->randomElement($person)},
    student_card = 0,
    senior_card = 0
    ");

$pdo->exec("INSERT INTO manager
    SET user_id = {$faker->randomElement($person)},
    id_cinema = {$faker->randomElement($cinemas)}
    ");

$pdo->exec("INSERT INTO employe
    SET user_id = {$faker->randomElement($person)},
    id_cinema = {$faker->randomElement($cinemas)}
    ");


$shows = [];
foreach ($cinemas as $cinema) {
    foreach ($halls as $hall) {
        $pdo->exec("INSERT INTO shows 
        SET id_movie='{$faker->randomElement($movies)}',
        id_hall='$hall',
        id_cinema='$cinema}'
        ");
        $shows[] = $pdo->lastInsertId();
    }
}

$booking = [];
for ($i = 0; $i < 10; $i++) {
    $pdo->exec("INSERT INTO booking 
                SET created_on = '{$faker->date} {$faker->time}',
                quantity = '{$faker->randomDigit()}',
                price = 8,
                id_customer=1,
                id_show='{$faker->randomElement($shows)}',
                id_employe = 1
                ");
    $booking[] = $pdo->lastInsertId();
}

foreach ($booking as $book) {
    $pdo->exec("INSERT INTO payment
    SET created_on ='{$faker->date} {$faker->time}',
    book_id = $book,
    status = 1
    ");
}

$days = ['m', 't', 'w', 'th', 'f', 's', 'su'];

foreach ($cinemas as $cinema) {
    foreach ($days as $day) {
        $pdo->exec("INSERT INTO opening_hours
        SET day = '$day',
        open = 14,
        close = 22,
        cinema_id = $cinema
        ");
    }
}
