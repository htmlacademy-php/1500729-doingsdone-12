<?php
require_once('data.php');
require_once('connect.php');
require_once('functions.php');

if (!isset($_SESSION['user'])) {
    $main = include_template('guest.php', []);
    $layout = include_template(
        'layout.php',
        ['main' => $main]
    );
    print($layout);
    exit();
}

$user = $_SESSION['user'];
$categories = [];
$tasks = [];
//*здесь сразу собираю и проекты и количество задач в проектах
if (!$link) {
    $error = mysqli_connect_error($link);
    print($error);
} else {
    $query_projects = "SELECT p.id, p.name_of_project FROM projects p  WHERE p.user_id = " . $user['id'] . " ORDER BY p.id;";
    $result_of_projects = mysqli_query($link, $query_projects);
    if ($result_of_projects) {
        $categories = mysqli_fetch_all($result_of_projects, MYSQLI_ASSOC);
    }

    $filter = '';
    $type = '';
    $due_date = '';
    if (isset($_GET['project_id'])) {
        $type = $_GET['project_id'];
        $filter = ' AND p.id = ' . $type;
    }

    if (isset($_GET['date'])) {
        switch ($_GET['date']) {
            case 'today':
                $date = date('Y-m-d');
                $due_date = " AND due_date = '" . $date . "'";
                break;
    

            case 'tomorrow':
                $date = date('Y-m-d', time() + 86400);
                $due_date = " AND due_date = '" . $date . "'";
                break;

            case 'overdue':
                $date = date('Y-m-d');
                $due_date = " AND due_date < '" . $date . "'";
                break;
        }
    }

    $query_task = "SELECT t.id, name, file, DATE_FORMAT(due_date,'%d.%m.%Y') due_date, status, p.name_of_project FROM tasks t
        JOIN projects p ON t.project_id = p.id WHERE t.user_id =" . $user['id'] . $filter . $due_date;
        print($query_task);

    $result_task = mysqli_query($link, $query_task);
    if ($result_task) {
        $tasks = mysqli_fetch_all($result_task, MYSQLI_ASSOC);
    }

    if (isset($_GET['seach'])) {
        $search = $_GET['seach'] ?? '';
        $search = trim($search);
        if ($search) {
            $query_seach = "SELECT name, file, DATE_FORMAT(due_date,'%d.%m.%Y') due_date, status FROM tasks 
                            WHERE MATCH(name) AGAINST ('" . $search . "') AND user_id=" . $user['id'];
            $result_seach = mysqli_query($link, $query_seach);
            if ($result_seach) {
                $tasks = mysqli_fetch_all($result_seach, MYSQLI_ASSOC);
            }
        }
    }

    if (isset($_GET['task_id']) && isset($_GET['check'])) {
        mysqli_begin_transaction($link);

        $query_task = "SELECT * FROM tasks WHERE id = " . $_GET['task_id'];
        $result_task = mysqli_query($link, $query_task);
        $query_update_status = "UPDATE tasks SET status = abs(status-1) WHERE id = " . $_GET['task_id'];
        $result_update_status = mysqli_query($link, $query_update_status);

        if ($result_task && $result_update_status) {
            mysqli_commit($link);
            header("Location: /index.php");
            exit();
        } else {
            mysqli_rollback($link);
        }
    }
}
if (isset($_GET['show_completed'])) {
    $show_complete_tasks = $_GET['show_completed'];
}

$main = include_template('main.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'categories' => $categories,
    'tasks' => $tasks,
    'type' => $type,
    'button_class' => $button_class,
    'seach_error' => $seach_error,
]);

$layout = include_template('layout.php', [
    'main' => $main,
    'title' => $title,
    'user' => $user
]);

if (empty($tasks) && !empty($type)) {
    http_response_code(404);
} else {
    print($layout);
}
