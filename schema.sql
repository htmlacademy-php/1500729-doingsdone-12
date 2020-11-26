CREATE DATABASE doingsdone
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE UTF8_GENERAL_CI;

USE doingsdone;

-- таблица пользователей
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(64) NOT NULL UNIQUE,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name VARCHAR(64) NOT NULL,
    password VARCHAR(64) NOT NULL
);

-- Таблица проектов
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_of_project VARCHAR(64) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id)
);

-- таблица задач
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dt_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status TINYINT DEFAULT 0,
    name VARCHAR(128) NOT NULL,
    file VARCHAR(64),
    due_date TIMESTAMP,
    user_id INT NOT NULL,
    project_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (project_id) REFERENCES projects (id)
);

-- дополнительные индексы
CREATE INDEX t_name ON tasks (name);
