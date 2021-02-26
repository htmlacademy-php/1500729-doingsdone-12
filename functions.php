<?php
/**
 * подсчитывает количество задач в категориях. 
 * $name_of_category - имя категории
 * $tasks_of_category - список задач 
 */
function count_of_tasks ($name_of_category, $tasks_of_category) {
    $count_of_task = 0;
    foreach ($tasks_of_category as $task_of_category) {
        if ($task_of_category['name_of_project'] === $name_of_category) {
            $count_of_task ++;
        }
    }
    if ($count_of_task > 0) {
        return $count_of_task;
    }
    else {
        return 0;
    }
}
/**
 * контроль выполнения задач
 * если до даты выполнения осталось меньше или равно суток,
 * то добавляется иконка срочности и дата выделяется цветом.
 */
function due_control ($due_time, $complete) {
    $task_time = strtotime ($due_time);
    $current_time = time();
    $diff_time = $task_time - $current_time;
    if ($due_time !== null && $diff_time <= 86400 && !$complete) {
        $important = 'task--important';
    }
    else {
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
function include_template($name, array $data = []) {
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

function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

function getPostVal($name) {
    return $_POST[$name] ?? "";
}
