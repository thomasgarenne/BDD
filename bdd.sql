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

ALTER TABLE cinema MODIFY name VARCHAR(255) NOT NULL UNIQUE

CREATE TABLE halls
(
    id INT NOT NULL AUTO_INCREMENT,
    hall_number INT NOT NULL,
    cinema_id INT NOT NULL,
    FOREIGN KEY (cinema_id)
        REFERENCES cinema(id)
        ON DELETE CASCADE,
    PRIMARY KEY (id)
)

ALTER TABLE halls ADD capacity INT NOT NULL
ALTER TABLE halls ADD CHECK (capacity>=0);

CREATE TABLE opening_hours
(
    id INT NOT NULL AUTO_INCREMENT,
    day VARCHAR(22) NOT NULL,
    open VARCHAR(22) NOT NULL,
    close VARCHAR(22) NOT NULL,
)

DROP TABLE opening_hours

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
    PRIMARY KEY (id)
)

CREATE TABLE category
(
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(22) NOT NULL,
    description VARCHAR(255),
    PRIMARY KEY (id)
)

ALTER TABLE movies ADD category JSON

ALTER TABLE movies ADD director VARCHAR(100)

CREATE TABLE shows
(
    id INT NOT NULL AUTO_INCREMENT,
    dates DATETIME DEFAULT NOW(),
    id_movie INT NOT NULL,
        FOREIGN KEY (id_movie)
        REFERENCES movies(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    id_hall INT NOT NULL,
        FOREIGN KEY (id_hall)
        REFERENCES halls(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    id_cinema INT NOT NULL,
        FOREIGN KEY (id_cinema)
        REFERENCES cinema(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (id, dates, id_hall)
)

CREATE TABLE users
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
    ville VARCHAR(255) NOT NULL,
    roles JSON,
    PRIMARY KEY (id)
)

CREATE TABLE customer
(
    user_id INT NOT NULL,
        FOREIGN KEY (user_id)
        REFERENCES users(id),
    student_card BOOLEAN,
    senior_card BOOLEAN,
    payment_method JSON,
    PRIMARY KEY (user_id)
)

CREATE TABLE manager
(
    user_id INT NOT NULL,
        FOREIGN KEY (user_id)
        REFERENCES users(id),
    id_cinema INT NOT NULL,
        FOREIGN KEY (id_cinema)
        REFERENCES cinema(id)
        ON UPDATE CASCADE,
    PRIMARY KEY (user_id)
)

CREATE TABLE employe
(
    user_id INT NOT NULL,
        FOREIGN KEY (user_id)
        REFERENCES users(id),
    id_cinema INT NOT NULL,
        FOREIGN KEY (id_cinema)
        REFERENCES cinema(id)
        ON UPDATE CASCADE,
    PRIMARY KEY (user_id)
)

CREATE TABLE booking
(
    id INT NOT NULL AUTO_INCREMENT,
    created_on DATETIME DEFAULT NOW(),
    quantity INT NOT NULL,
    price FLOAT NOT NULL,
    id_customer INT NOT NULL,
        FOREIGN KEY (id_customer)
        REFERENCES customer(user_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    id_show INT NOT NULL,
        FOREIGN KEY (id_show)
        REFERENCES shows(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
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

ALTER TABLE booking ADD id_employe INT

ALTER TABLE booking ADD FOREIGN KEY (id_employe) REFERENCES employe(user_id)