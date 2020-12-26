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

print_r ($_FILES);

if (isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];
    $file_path = __DIR__ . '/img/';
    $file_url = '/img/' . $file_name;
    
    move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $file_name);
    
    print("<a href='$file_url'>$file_name</a>");
}

if ($_GET['submit'] == 'true'){
    $add_task = "INSERT INTO tasks SET name = '" . $_POST['name'] . "', 
                                       project_id = " . $_POST['project'] . ", 
                                       due_date = '" . $_POST['date'] . "',
                                       file = '" . $file_url . "', 
                                       user_id = 1";
    $resalt_of_add_task = mysqli_query ($link, $add_task);
    if ($resalt_of_add_task) {
        print ('ок');
    }
}

$main = include_template ('form-task.php', ['categories' => $categories,
                                            'button_class' => $button_class]);

$layout = include_template ('layout.php', ['main' => $main,
                            'title' => $title,
                            'user' => $user]);

print ($layout);
