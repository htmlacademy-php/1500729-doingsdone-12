<?php
require_once('data.php');
require_once('connect.php');
require_once('functions.php');

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
    exit();
}

if (!$link) {
    $error = mysqli_connect_error($link);
    print($error);
} else {
    $query_projects = "SELECT p.id, p.name_of_project, COUNT(t.id) AS count_of_tasks FROM projects p 
                       LEFT JOIN tasks t ON p.id = t.project_id  AND t.status = 0 WHERE p.user_id = " . $_SESSION['user']['id'] .
                       " GROUP BY p.name_of_project, p.id ORDER BY p.id";
    $result_of_projects = mysqli_query($link, $query_projects);
    if ($result_of_projects) {
        $categories = mysqli_fetch_all($result_of_projects, MYSQLI_ASSOC);
    }
}

$errors = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['project_name'])) {
        $errors = "Не заполнено поле";
    } else {
        foreach ($categories as $category) {
            if ($_POST['project_name'] == $category['name_of_project']) {
                $errors = "Проект с таким названием уже существует";
                break;
            }
        }
    }

    if (empty($errors)) {
        $sql = "INSERT INTO projects (name_of_project, user_id) VALUES (?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $_POST['project_name'], $_SESSION['user']['id']);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            header("Location: /index.php");
            exit();
        }
    }
}

$main = include_template(
    'form-project.php',
    [
        'errors' => $errors,
        'error_class' => $error_class,
        'categories' => $categories
    ]
);

$layout = include_template('layout.php', ['main' => $main]);

print($layout);
