<?php

//CONNEXION BDD
$pdo = new PDO('mysql:dbname=complex;host=localhost', 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

//REINITIALISE BDD
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE complex');
$pdo->exec('TRUNCATE TABLE cinema');
$pdo->exec('TRUNCATE TABLE halls');
$pdo->exec('TRUNCATE TABLE movies');
$pdo->exec('TRUNCATE TABLE shows');
$pdo->exec('TRUNCATE TABLE booking');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE customer');
$pdo->exec('TRUNCATE TABLE manager');
$pdo->exec('TRUNCATE TABLE opening_hours');
$pdo->exec('TRUNCATE TABLE payment');
$pdo->exec('TRUNCATE TABLE movie_category');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

//CREER DE FAUSSE DONNEES
$faker = Faker\Factory::create();

//TABLE COMPLEX
$pdo->exec("INSERT INTO complex SET name='{$faker->sentence()}', nb_cinema=5");

//TABLE CINEMA
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

//TABLE HALL
$halls = [1, 2, 3, 4, 5, 6, 7, 8, 9];
foreach ($cinemas as $cinema) {
    foreach ($halls as $hall) {
        $pdo->exec("INSERT INTO halls 
        SET hall_number='$hall',
        cinema_id='$cinema',
        capacity='{$faker->numberBetween(150, 300)}'
        ");
    }
}

//TABLE CATEGORY
$categories = [];
for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO category 
                SET title='{$faker->word()}',
                description='{$faker->paragraph()}'");
    $categories[] = $pdo->lastInsertId();
}

//TABLE MOVIES
$movies = [];
$language = ['fr', 'en', 'de', 'es'];
$ageLimit = [12, 16, 18];
for ($i = 0; $i < 10; $i++) {
    $pdo->exec("INSERT INTO movies 
                SET title='{$faker->word()}',
                description='{$faker->paragraph()}',
                language='{$faker->randomElement($language)}',
                time='{$faker->numberBetween(1, 3)}',
                ageLimit='{$faker->randomElement($ageLimit)}',
                director='{$faker->word()}'
                ");
    $movies[] = $pdo->lastInsertId();
}

//TABLE MOVIE_CATEGORY
foreach ($movies as $movie) {
    $randomCategories = $faker->randomElements($categories, rand(0, count($categories)));
    foreach ($randomCategories as $category) {
        $pdo->exec("INSERT INTO movie_category SET movie_id=$movie, category_id=$category");
    }
}

//TABLE CUSTOMER
$role = json_encode('ROLE_USER');
$password = password_hash('admin', PASSWORD_BCRYPT);
$customers = [];
for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO customer
    SET email='{$faker->email()}',
    passw='$password',
    phone='{$faker->e164PhoneNumber()}',
    firstname='{$faker->firstName()}',
    lastname='{$faker->lastName()}',
    age='{$faker->numberBetween(3, 56)}',
    address='{$faker->address()}', 
    postal_code='{$faker->postcode()}', 
    city='{$faker->city()}',
    student_card = 1,
    senior_card=0,
    roles='$role'
    ");
    $customers[] = $pdo->lastInsertId();
}

$pdo->exec("UPDATE customer
            SET student_card =
            CASE 
            WHEN customer.age < 15 THEN '0'
            END
        ");

//TABLE MANAGER

foreach ($cinemas as $cinema) {
    $role = json_encode('ROLE_MANAGER');
    $pdo->exec("INSERT INTO manager
    SET email='{$faker->email()}',
    passw='$password',
    phone='{$faker->e164PhoneNumber()}',
    firstname='{$faker->firstName()}',
    lastname='{$faker->lastName()}',
    age='{$faker->randomDigit()}',
    address='{$faker->address()}', 
    postal_code='{$faker->postcode()}', 
    city='{$faker->city()}',
    id_cinema='$cinema',
    role='$role'
    ");
}

$role = json_encode('ROLE_ADMIN');
$pdo->exec("INSERT INTO manager
            SET email='admin.admin.admin',
            passw='admin',
            phone='0000000000',
            firstname='admin',
            lastname='admin',
            age=11,
            address='address',
            postal_code='00000',
            city='city',
            id_cinema=1,
            role='$role'
            ");

//TABLE SHOW
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

//CONVERT CAPACITY HALL ON SEAT SHOW
$pdo->exec("UPDATE shows 
            JOIN halls ON shows.id_hall = halls.id
            SET seats = capacity
            ");

//TABLE BOOKING
$booking = [];
foreach ($customers as $customer) {
    $pdo->exec("INSERT INTO booking 
                SET created_on = '{$faker->date} {$faker->time}',
                quantity = '{$faker->randomDigit()}',
                price = '9.2',
                id_customer=$customer,
                id_show='{$faker->randomElement($shows)}'
                ");
    $booking[] = $pdo->lastInsertId();
}

//PRICE FOR CUSTOMER
$pdo->exec("UPDATE booking 
            JOIN customer 
            ON booking.id_customer = customer.id
            SET price =
            CASE 
            WHEN customer.age < 15 THEN '5.90'
            WHEN student_card = 1 THEN '7.60'
            ELSE '9.20'
            END
        ");

//MAJ BOOKING -> SEATS
$pdo->exec("INSERT INTO booking 
            SET created_on='{$faker->date} {$faker->time}',
            quantity= 50,
            price= '9.2',
            id_customer= 1,
            id_show= 4
            ");

$pdo->exec("UPDATE shows JOIN booking 
            ON shows.id = booking.id_show
            SET seats = seats - booking.quantity
            ");

//TABLE PAYMENT
foreach ($booking as $book) {
    $pdo->exec("INSERT INTO payment
    SET created_on ='{$faker->date} {$faker->time}',
    book_id = $book,
    status = 1
    ");
}

//TABLE OPENING_HOURS
$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
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
