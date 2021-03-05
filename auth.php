<?php
require_once('data.php');
require_once('connect.php');
require_once('functions.php');

if (isset($_SESSION['user'])) {
    header("Location: /index.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $required = ['email', 'password'];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    $valid = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    if (!$valid && !empty($_POST['email'])) {
        $errors['email'] = "E-mail введён некорректно";
    }
    if (!count($errors)) {
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);
    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if ($user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    } 
    }  
    if (isset($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
    }
}

$main = include_template(
    'auth.php',
    [
        'errors' => $errors,
        'error_class' => $error_class
    ]
);

$layout = include_template('layout.php', ['main' => $main]);

print($layout);
