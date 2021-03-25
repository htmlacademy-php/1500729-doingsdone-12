<?php
require_once('data.php');
require_once('connect.php');
require_once('helpers.php');

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
    exit();
}

if (!$link) {
    $error = mysqli_connect_error($link);
    print($error);
} else {

    $categories = get_categories($_SESSION['user']['id'], $link);

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
