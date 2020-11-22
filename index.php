<?php
require_once ('data.php');
require_once ('helpers.php');

$main = include_template ('main.php', ['show_complete_tasks' => $show_complete_tasks,
                                       'categories' => $categories,
                                       'tasks' => $tasks]);

$layout = include_template ('layout.php', ['main' => $main,
                                           'title' => $title,
                                           'user' => $user]);

print ($layout);
