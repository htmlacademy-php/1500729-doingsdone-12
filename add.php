<?php
require_once ('data.php');
require_once ('connect.php');
require_once ('functions.php');

if (!$link) {
    $error = mysqli_connect_error($link);
    print($error);
   }
   else {
       $query_projects = "SELECT id, name_of_project FROM projects WHERE user_id = 1";
       $result_of_projects = mysqli_query ($link, $query_projects);
       if ($result_of_projects) {
           $categories = mysqli_fetch_all ($result_of_projects, MYSQLI_ASSOC);
       }
   }
$main = include_template ('form-task.php', ['categories' => $categories,
                                            'button_class' => $button_class]);

$layout = include_template ('layout.php', ['main' => $main,
                            'title' => $title,
                            'user' => $user]);

print ($layout);
