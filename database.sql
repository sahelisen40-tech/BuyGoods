CREATE DATABASE IF NOT EXISTS buygoods1;

USE buygoods1;

-- Users table
CREATE TABLE register (
    id INT(3) NOT NULL AUTO_INCREMENT,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
);

-- Products table
CREATE TABLE products (
    id INT(10) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    quantity INT(10) NOT NULL DEFAULT 0,
    state VARCHAR(50) DEFAULT NULL,
    PRIMARY KEY (id)
);

-- Billing / orders table
CREATE TABLE billing (
    id INT(10) NOT NULL AUTO_INCREMENT,
    client_id INT(10) NOT NULL,
    fullname VARCHAR(60) NOT NULL,
    email VARCHAR(30) NOT NULL,
    address VARCHAR(80) NOT NULL,
    city TEXT NOT NULL,
    state TEXT NOT NULL,
    payment VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    item TEXT NOT NULL,
    quantity INT(10) NOT NULL,
    PRIMARY KEY (id)
);