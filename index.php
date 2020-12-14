<?php
require_once ('data.php');
require_once ('connect.php');
require_once ('functions.php');

if (!$link) {
    $error = mysqli_connect_error($link);
    print($error);
   }
   else {
       $query_projects = "SELECT name_of_project FROM projects WHERE user_id = 1";
       $result_of_projects = mysqli_query ($link, $query_projects);
       if ($result_of_projects) {
           $categories = mysqli_fetch_all ($result_of_projects, MYSQLI_ASSOC);
       }
       $query_task = "SELECT name, file, DATE_FORMAT(due_date,'%d.%m.%Y') due_date, status, p.name_of_project FROM tasks t
                      JOIN projects p ON t.project_id = p.id WHERE t.user_id = 1";
       $result_task = mysqli_query ($link, $query_task);
       if ($result_task) {
           $tasks = mysqli_fetch_all ($result_task, MYSQLI_ASSOC);
       }
   }

if (isset($_GET['name_of_projects'])) {
    $type = $_GET['name_of_projects'];
    print ($type);
}

$main = include_template ('main.php', ['show_complete_tasks' => $show_complete_tasks,
                                       'categories' => $categories,
                                       'tasks' => $tasks]);

$layout = include_template ('layout.php', ['main' => $main,
                                           'title' => $title,
                                           'user' => $user]);

print ($layout);
