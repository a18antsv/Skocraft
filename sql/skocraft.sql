DROP DATABASE skocraft;
CREATE DATABASE skocraft;
USE skocraft;

CREATE TABLE user(
	email VARCHAR(50) NOT NULL,
	mojang VARCHAR(50) NOT NULL,
    activation_code VARCHAR(100) NOT NULL,
    active_status BOOLEAN NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(email)
)ENGINE=InnoDB;

CREATE VIEW active AS
SELECT * FROM user
WHERE active_status = 1;

CREATE VIEW inactive AS
SELECT * FROM user
WHERE active_status = 0;