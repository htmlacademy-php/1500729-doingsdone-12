<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <p>Уважаемый, <?= $user_name ?>. У вас запланированы задачи:</p>
    <ul>
        <?php foreach ($contents as $content) : ?>
            <li><?= $content['task'] ?> на <?= $content['due_date'] ?></li>
        <?php endforeach; ?>
    </ul>
</body>

</html>
