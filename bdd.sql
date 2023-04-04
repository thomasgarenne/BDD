CREATE DATABASE complex

CREATE TABLE complex 
(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    nb_cinema INT NOT NULL,
    PRIMARY KEY (id)
)

CREATE TABLE cinema
(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    postal_code VARCHAR(255) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    phone VARCHAR(22) NOT NULL,
    complex_id INT NOT NULL,
    FOREIGN KEY (complex_id)
        REFERENCES complex(id)
        ON DELETE CASCADE,
    PRIMARY KEY (id)
)

CREATE TABLE halls
(
    id INT NOT NULL AUTO_INCREMENT,
    hall_number INT NOT NULL,
    cinema_id INT NOT NULL,
    capacity INT NOT NULL CHECK (capacity >= 0),
    FOREIGN KEY (cinema_id)
        REFERENCES cinema(id)
        ON DELETE CASCADE,
    PRIMARY KEY (id)
)

CREATE TABLE opening_hours
(
    id INT NOT NULL AUTO_INCREMENT,
    day VARCHAR(22) NOT NULL,
    open VARCHAR(22) NOT NULL,
    close VARCHAR(22) NOT NULL,
    cinema_id INT NOT NULL,
    FOREIGN KEY (cinema_id)
        REFERENCES cinema(id)
        ON DELETE CASCADE,
    PRIMARY KEY (id)
)

CREATE TABLE movies
(
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(22) NOT NULL,
    description VARCHAR(255),
    language VARCHAR(22) NOT NULL,
    time FLOAT NOT NULL,
    ageLimit VARCHAR(22),
    director VARCHAR(100),
    PRIMARY KEY (id)
)

CREATE TABLE category
(
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(22) NOT NULL,
    description VARCHAR(255),
    PRIMARY KEY (id)
)

CREATE TABLE movie_category (
    movie_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (MOVIE_ID, category_id),
        FOREIGN KEY (movie_id)
        REFERENCES movies (id)
        ON DELETE CASCADE ON UPDATE RESTRICT,
        FOREIGN KEY (category_id)
        REFERENCES category (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT
)

CREATE TABLE shows
(
    id INT NOT NULL AUTO_INCREMENT,
    dates DATETIME DEFAULT NOW(),
    id_movie INT NOT NULL,
        FOREIGN KEY (id_movie)
        REFERENCES movies(id)
        ON DELETE CASCADE,
    id_hall INT NOT NULL,
        FOREIGN KEY (id_hall)
        REFERENCES halls(id)
        ON DELETE CASCADE,
    id_cinema INT NOT NULL,
        FOREIGN KEY (id_cinema)
        REFERENCES cinema(id)
        ON DELETE CASCADE,
    PRIMARY KEY (id, dates, id_hall)
)

ALTER TABLE shows ADD seats INT NOT NULL
ALTER TABLE shows ADD CONSTRAINT CHECK (seats >=0)

CREATE TABLE users
(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL,
    passw VARCHAR(255) NOT NULL,
    phone VARCHAR(22) NOT NULL,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    age INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    postal_code VARCHAR(255) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    roles JSON,
    PRIMARY KEY (id)
)

CREATE TABLE customer
(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL,
    passw VARCHAR(255) NOT NULL,
    phone VARCHAR(22) NOT NULL,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    age INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    postal_code VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    student_card BOOLEAN CHECK (age < 16 = true),
    senior_card BOOLEAN CHECK (age > 64 = true),
    roles JSON DEFAULT 'ROLE_USER',
    payment_method JSON,
    PRIMARY KEY (id)
)

CREATE TABLE manager
(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL,
    passw VARCHAR(255) NOT NULL,
    phone VARCHAR(22),
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    age INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    postal_code VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    role JSON,
    id_cinema INT NOT NULL,
        FOREIGN KEY (id_cinema)
        REFERENCES cinema(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id)
)

CREATE TABLE booking
(
    id INT NOT NULL AUTO_INCREMENT,
    created_on DATETIME DEFAULT NOW(),
    quantity INT NOT NULL,
    price FLOAT NOT NULL,
    id_customer INT NOT NULL,
        FOREIGN KEY (id_customer)
        REFERENCES customer(id)
        ON DELETE CASCADE,
    id_show INT NOT NULL,
        FOREIGN KEY (id_show)
        REFERENCES shows(id)
        ON DELETE CASCADE,
    id_employe INT,
        FOREIGN KEY (id_employe)
        REFERENCES employe(id)
        ON DELETE CASCADE,
    PRIMARY KEY (id)
)

CREATE TABLE payment
(
    id INT NOT NULL AUTO_INCREMENT,
    created_on DATETIME DEFAULT NOW(),
    book_id INT NOT NULL,
        FOREIGN KEY (book_id)
        REFERENCES booking(id),
    status BOOLEAN NOT NULL,
    PRIMARY KEY (id)
)
