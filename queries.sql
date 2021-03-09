USE doingsdone;

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
INSERT INTO tasks (name, status, due_date, user_id, project_id)
VALUES
('Собеседование в IT компании', 0, '2019.12.01', 1, 2),
('Выполнить тестовое задание', 0, '2019.12.25', 1, 2),
('Сделать задание первого раздела', 1, '2019.12.21', 1, 3),
('Встреча с другом', 0, '2019.12.22', 1, 1),
('Купить корм для кота', 0, '2020.11.29', 1, 4),
('Заказать пиццу', 0, NULL, 1, 4);

-- получить список из всех проектов для одного пользователя
SELECT * FROM projects WHERE user_id = 1;

-- получить список из всех задач для одного проекта;
SELECT * FROM tasks WHERE project_id = 1;

-- пометить задачу как выполненную;
UPDATE tasks SET status = 1 WHERE id = 1;

--обновить название задачи по её идентификатору;
UPDATE tasks SET name = 'Повторное собеседование в IT компании' WHERE id = 1;

-- добавить полнотекстовый интекс к имени задачи;
CREATE FULLTEXT INDEX task_ft_search ON tasks(name);
