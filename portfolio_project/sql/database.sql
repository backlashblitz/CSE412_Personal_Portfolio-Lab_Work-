CREATE DATABASE portfolio_db;
USE portfolio_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE portfolios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(255),
    contact TEXT,
    photo VARCHAR(255),
    bio TEXT,
    soft_skills TEXT,
    technical_skills TEXT,
    bsc_cgpa VARCHAR(10),
    bsc_institute VARCHAR(255),
    bsc_degree VARCHAR(255),
    bsc_year VARCHAR(10),
    msc_cgpa VARCHAR(10),
    msc_institute VARCHAR(255),
    msc_degree VARCHAR(255),
    msc_year VARCHAR(10),
    experience TEXT,
    projects TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);