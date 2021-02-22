<?php
require_once ('data.php');
require_once ('connect.php');
require_once ('functions.php');

//*здесь сразу собираю и проекты и количество задач в проектах
if (!$link) {
    $error = mysqli_connect_error($link);
    print($error);
} else {
        $query_projects = "SELECT p.id, p.name_of_project, COUNT(t.id) AS count_of_tasks FROM projects p 
                           LEFT JOIN tasks t ON p.id = t.project_id WHERE p.user_id = 1 
                           GROUP BY p.name_of_project, p.id ORDER BY p.id";
        $result_of_projects = mysqli_query ($link, $query_projects);
        if ($result_of_projects) {
            $categories = mysqli_fetch_all ($result_of_projects, MYSQLI_ASSOC);
        }

        $filter = '';
        $type = '';
        if (isset ($_GET['project_id'])) {
            $type = $_GET['project_id'];
            $filter = ' AND p.id = ' . $type;
        }

        $query_task = "SELECT name, file, DATE_FORMAT(due_date,'%d.%m.%Y') due_date, status, p.name_of_project FROM tasks t
        JOIN projects p ON t.project_id = p.id WHERE t.user_id = 1" . $filter;
        $result_task = mysqli_query ($link, $query_task);
        if ($result_task) {
            $tasks = mysqli_fetch_all ($result_task, MYSQLI_ASSOC);
        }
}
    
$main = include_template ('main.php', ['show_complete_tasks' => $show_complete_tasks,
                                       'categories' => $categories,
                                       'tasks' => $tasks,
                                       'type' => $type,
                                       'button_class' => $button_class]);

$layout = include_template ('layout.php', ['main' => $main,
                                           'title' => $title,
                                           'user' => $user]);

if (empty($tasks)) {
    http_response_code (404);
    } else {
    print ($layout);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header("Location: /add.php?success=true");
}