<?php

/**
 * МОЯ ФУНКЦИЯ!
 * подсчитывает количество задач в категориях. 
 * @param string $name_of_category имя категории
 * @param array $tasks_of_category массив со всеми задачами
 * @return int возвращает количество задач в выбранной категории, если их нет - ноль
 * @deprecated функция не используется, т.к. количество задач в категориях берется из запроса к БД
 */
function count_of_tasks($name_of_category, $tasks_of_category)
{
    $count_of_task = 0;
    foreach ($tasks_of_category as $task_of_category) {
        if ($task_of_category['project_id'] === $name_of_category) {
            $count_of_task++;
        }
    }
    if ($count_of_task > 0) {
        return $count_of_task;
    } else {
        return 0;
    }
}
/**
 * МОЯ ФУНКЦИЯ
 * контроль выполнения задач
 * @param string @due_time дата выполнения задачи
 * @param int @complete Cтатус задачи, 0 - не выполнена, 1 - выполнена
 * @return возвращает класс (иконка срочности и дата выделяется цветом) если до даты выполнения осталось меньше или равно суток
 */
function due_control($due_time, $complete)
{
    $task_time = strtotime($due_time);
    $current_time = time();
    $diff_time = $task_time - $current_time;
    if ($due_time !== null && $diff_time <= 86400 && !$complete) {
        $important = 'task--important';
    } else {
        $important = '';
    }
    return $important;
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Возвращает отправленное значение из поля методом POST
 * @param string $name имя поля
 * 
 * @return string Возвращает отправленное значение
 */
function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

/**
 * МОЯ ФУНКЦИЯ
 * Возвращает массив с проектами пользователя и количеством невыполненных задач в проектах
 * @param int $user_id ID пользователя
 * @param object $link Объект mysqli_connect, представляющий подключение к серверу MySQL
 * @return array $categories возвращает массив с проектами и количеством невыполненных задач в них, иначе FALSE
 */
function get_categories($user_id, $link)
{
    $query_projects = "SELECT p.id, p.name_of_project, COUNT(t.id) AS count_of_tasks FROM projects p 
        LEFT JOIN tasks t ON p.id = t.project_id  AND t.status = 0 WHERE p.user_id = " . $user_id .
        " GROUP BY p.name_of_project, p.id ORDER BY p.id";
    $result_of_projects = mysqli_query($link, $query_projects);

    if ($result_of_projects) {
        $categories = mysqli_fetch_all($result_of_projects, MYSQLI_ASSOC);
    } else {
        $categories = '';
    }

    return $categories;
}
