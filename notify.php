<?php
require_once('connect.php');
require_once('helpers.php');
require_once('vendor/autoload.php');

$transport = new Swift_SmtpTransport('phpdemo.ru', 25);
$transport->setUsername('keks@phpdemo.ru');
$transport->setPassword('htmlacademy');

$sql = "SELECT t.name AS task, DATE_FORMAT(due_date,'%d.%m.%Y') AS due_date, u.email, u.name, u.id FROM tasks t LEFT JOIN users u ON  t.user_id = u.id
        WHERE due_date = CURDATE() AND status = 0";

$query = mysqli_query($link, $sql);

if ($query) {
    $due_tasks = mysqli_fetch_all($query, MYSQLI_ASSOC);
    if ($due_tasks) {
        foreach ($due_tasks as $user_task) {
            $all_users[$user_task['id']][] =
                [
                    'email' => $user_task['email'],
                    'name' => $user_task['name'],
                    'task' => $user_task['task'],
                    'due_date' => $user_task['due_date']
                ];
        }

        foreach ($all_users as $user_id => $tasks) {
            $content = '';
            $contents = [];
            foreach ($tasks as $task) {
                $email = $task['email'];
                $user_name = $task['name'];
                $contents[] = ['task' => $task['task'], 'due_date' => $task['due_date']];
            }

            $message_body = include_template('notify.php', ['user_name' => $user_name, 'contents' => $contents, 'email' => $task['email']]);

            $message = new Swift_Message($transport);
            $message->setTo($task['email']);
            $message->setBody($message_body, 'text/html');
            $message->setFrom('keks@phpdemo.ru', 'Дела в порядке');
            $message->setSubject('Задачи на сегодня');

            $mailer = new Swift_Mailer($transport);
            $mailer->send($message);
        }
    }
}
