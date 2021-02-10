<?php
require_once ('data.php');
require_once ('connect.php');
require_once ('functions.php');

if (!$link) {
    $error = mysqli_connect_error($link);
    print($error);
   }
   else {
       $query_projects = "SELECT p.id, p.name_of_project, COUNT(t.id) AS count_of_tasks FROM projects p 
                          LEFT JOIN tasks t ON p.id = t.project_id WHERE p.user_id = 1 
                          GROUP BY p.name_of_project, p.id ORDER BY p.id";
       $result_of_projects = mysqli_query ($link, $query_projects);
       if ($result_of_projects) {
           $categories = mysqli_fetch_all ($result_of_projects, MYSQLI_ASSOC);
       }
   }
   
if ($_FILES['file']['error'] === 0) {
    
    $file_name = $_FILES['file']['name'];
    $file_path = __DIR__ . '/img/';
    $file_url = '/img/' . $file_name;
    
    move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
}
else {
    $file_url = NULL;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (empty ($_POST['date'])) {
        $due_date = '';
    }
    else {
        $due_date = "due_date = '" . $_POST['date'] . "',";
    }
  
    if (!(is_date_valid($_POST['date'])) && $_POST['date'] != NULL) {
        $error['date'] = 'Неверный формат даты';
    }
    if ($_POST['date']) {
        $deadline = strtotime ($_POST['date']);
        $today = strtotime (date('Y-m-d'));
        if ($deadline < $today) {
            $error['date'] = 'Срок выполнения не может быть меньше текущей даты';
        }
    }
    
    if (empty ($_POST['name'])) {
        $error['name'] = 'Поле не заполнено';
    }
   
    foreach ($categories as $category) {
        if ($_POST['project'] !== $category['id']) {
            $error['project'] = 'Такого проекта не существует';
        }
        else {
            unset ($error['project']);
            break;
        }
    }

    print_r ($error);
    print ($due_date);

    if (!$error) {
        $add_task = "INSERT INTO tasks SET name = '" . $_POST['name'] . "', 
        project_id = " . $_POST['project'] . ", 
        $due_date
        file = '" . $file_url . "', 
        user_id = 1";
        print ($add_task);
        $resalt_of_add_task = mysqli_query ($link, $add_task);
        if ($resalt_of_add_task) {
        print ('ок');
        header("Location: /?success=true");
        }
}
}

$main = include_template ('form-task.php', ['categories' => $categories,
                                            'button_class' => $button_class,
                                            'error' => $error,
                                            'error_class' => $error_class]);

$layout = include_template ('layout.php', ['main' => $main,
                            'title' => $title,
                            'user' => $user]);

print ($layout);
