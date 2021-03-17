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

if (!$link) {
    $error = mysqli_connect_error($link);
    print($error);
} else {
    
    $categories = get_categories($user['id'], $link);

    $filter = '';
    $type = '';
    $due_date = '';
    $seach_query = '';
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

    if (isset($_GET['seach'])) {
        $search = filter_input(INPUT_GET, 'seach', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($search) {
             $seach_query = " AND MATCH(name) AGAINST ('" . $search . "' IN BOOLEAN MODE)";
        }
    }

    $query_task = "SELECT t.id, name, file, DATE_FORMAT(due_date,'%d.%m.%Y') due_date, status, p.name_of_project FROM tasks t
        JOIN projects p ON t.project_id = p.id WHERE t.user_id =" . $user['id'] . $filter . $due_date . $seach_query;

    $result_task = mysqli_query($link, $query_task);
    if ($result_task) {
        $tasks = mysqli_fetch_all($result_task, MYSQLI_ASSOC);
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
    'seach_error' => $seach_error
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
