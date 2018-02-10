CREATE DATABASE IF NOT EXISTS formassess;

USE formassess;

CREATE TABLE IF NOT EXISTS admins (
    ID int NOT NULL AUTO_INCREMENT,
    name varchar(50),
    email varchar(50) NOT NULL,
    password char(128) NOT NULL,

    PRIMARY KEY(ID)
);

CREATE TABLE IF NOT EXISTS classes (
    ID int NOT NULL AUTO_INCREMENT,
    gradeLevel int NOT NULL,

    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS schools (
    ID int NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,

    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS teachers (
    ID int NOT NULL AUTO_INCREMENT,
    name varchar(50),
    email varchar(50) NOT NULL,
    password char(128) NOT NULL,
    schoolID int NOT NULL,
    classID int NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (schoolID) REFERENCES schools(ID),
    FOREIGN KEY (classID) REFERENCES classes(ID) 
);

CREATE TABLE IF NOT EXISTS categories (
    ID int NOT NULL AUTO_INCREMENT,
    name varchar(50) NOT NULL,
    level varchar(50) NOT NULL,

    PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS terms (
    ID int NOT NULL AUTO_INCREMENT,
    name varchar(50) NOT NULL,
    definition varchar(500) NOT NULL,
    catID int,

    PRIMARY KEY (ID),
    FOREIGN KEY (catID) REFERENCES categories(ID)
);

CREATE TABLE IF NOT EXISTS students (
    ID int NOT NULL AUTO_INCREMENT,
    schoolID int NOT NULL,
    classID int NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (schoolID) REFERENCES schools(ID),
    FOREIGN KEY (classID) REFERENCES classes(ID)
);

CREATE TABLE IF NOT EXISTS assessments (
    ID int NOT NULL AUTO_INCREMENT,
    start_date datetime NOT NULL,
    end_date datetime,
    catID int NOT NULL,
    classID int NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (catID) REFERENCES categories(ID),
    FOREIGN KEY (classID) REFERENCES classes(ID)
);

CREATE TABLE IF NOT EXISTS results (
    ID int NOT NULL AUTO_INCREMENT,
    studentID int NOT NULL,
    assessmentID int NOT NULL,
    termID int NOT NULL,
    correct boolean NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (studentID) REFERENCES students(ID),
    FOREIGN KEY (assessmentID) REFERENCES assessments(ID),
    FOREIGN KEY (termID) REFERENCES terms(ID)
);

CREATE TABLE IF NOT EXISTS assessmentQuestions (
    ID int NOT NULL AUTO_INCREMENT,
    termID int NOT NULL,
    assessmentID int NOT NULL,
    isMatch boolean NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (termID) REFERENCES terms(ID),
    FOREIGN KEY (assessmentID) REFERENCES assessments(ID)
);

CREATE TABLE IF NOT EXISTS password_change_requests (
    ID char(64) NOT NULL,
    time datetime NOT NULL,
    userID varchar(50),

    PRIMARY KEY (ID) 
);