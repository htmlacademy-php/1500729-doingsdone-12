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

$file_url = NULL;
if ($_FILES && $_FILES['file']['error'] === 0) {

    $file_name = $_FILES['file']['name'];
    $file_path = __DIR__ . '/img/';
    $file_url = '/img/' . $file_name;

    move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
}

$error = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $due_date = NULL;
    if (!empty($_POST['date'])) {
        $due_date =  $_POST['date'];

        if (!(is_date_valid($_POST['date']))) {
            $error['date'] = 'Неверный формат даты';
        } else {
            $deadline = strtotime($_POST['date']);
            $today = strtotime(date('Y-m-d'));
            if ($deadline < $today) {
                $error['date'] = 'Срок выполнения не может быть меньше текущей даты';
            }
        }
    }

    if (empty($_POST['name'])) {
        $error['name'] = 'Поле не заполнено';
    }

    $valid_project = '';
    foreach ($categories as $category) {
        if ($_POST['project'] === $category['id']) {
            $valid_project = TRUE;
            break;
        }
    }
    if (!$valid_project) {
        $error['project'] = 'Такого проекта не существует';
    }

    if (empty($error)) {
        $add_task = "INSERT INTO tasks (NAME, project_id, due_date, FILE, user_id)
                     VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $add_task);
        mysqli_stmt_bind_param($stmt, 'sissi', $_POST['name'], $_POST['project'], $due_date, $file_url, $_SESSION['user']['id']);
        $resalt_of_add_task = mysqli_stmt_execute($stmt);

        if ($resalt_of_add_task) {
            header("Location: /?success=true");
        }
    }
}

$main = include_template('form-task.php', [
    'categories' => $categories,
    'button_class' => $button_class,
    'error' => $error,
    'error_class' => $error_class
]);

$layout = include_template('layout.php', [
    'main' => $main,
    'title' => $title
]);

print($layout);
