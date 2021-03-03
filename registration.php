<?php
require_once('data.php');
require_once('connect.php');
require_once('functions.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $fields = ['email', 'password', 'name'];

    foreach ($fields as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Не заполнено поле " . $field;
        }
    }

    $valid = filter_var($form['email'], FILTER_VALIDATE_EMAIL);
    if (!$valid && !empty($form['email'])) {
        $errors['email'] = "E-mail введён некорректно";
    } else {
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);
        if (mysqli_num_rows($res) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        }
    }

    if (count($errors) == 0) {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (email, name, password) VALUES (?, ?, ?)';
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $form['email'], $form['name'], $password);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            header("Location: /index.php");
            exit();
        }
    }
}

$layout = include_template(
    'register.php',
    [
        'user' => $user,
        'errors' => $errors,
        'error_class' => $error_class
    ]
);

print($layout);
