-- Добавление пользователей
INSERT INTO users (email, name, password)
VALUES
('test@test.ru', 'Алексей', '123456'),
('test2@test.ru', 'Константин', '123456');

-- Добавление проектов
INSERT INTO projects (name_of_project, user_id)
VALUES
('Входящие', 1),
('Работа', 1),
('Учеба', 1),
('Домашние дела', 1),
('Авто', 1);

-- Добавление задач
INSERT INTO tasks (name, due_date, user_id, project_id)
VALUES
('Собеседование в IT компании', '2020.12.01', 1, 2);