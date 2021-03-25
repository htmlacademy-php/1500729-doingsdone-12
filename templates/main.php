<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

            <nav class="main-navigation">
            <?php foreach ($categories as $category): ?>
                <ul class="main-navigation__list">
                    <li class="main-navigation__list-item <?= ($type === $category['id']) ? $button_class : '' ?>">
                        <a class="main-navigation__list-item-link" href='?project_id=<?=$category['id']?>'><?= strip_tags($category['name_of_project']) ?></a>
                        <span class="main-navigation__list-item-count"><?= $category['count_of_tasks'] ?></span>
                    </li>
                </ul>
            <?php endforeach; ?>
            </nav>

        <a class="button button--transparent button--plus content__side-button"
        href="form-project.php" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="" method="get" autocomplete="off">
            <input class="search-form__input" type="text" name="seach" id="seach" value="<?= isset($_GET['seach']) ? trim($_GET['seach']) : '' ?>" placeholder="Поиск по задачам">
            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/" class="tasks-switch__item <?= !isset($_GET['date']) ? 'tasks-switch__item--active' : '' ?>">Все задачи</a>
                <a href="?date=today" class="tasks-switch__item <?= isset($_GET['date']) && $_GET['date'] == 'today' ? 'tasks-switch__item--active' : '' ?>">Повестка дня</a>
                <a href="?date=tomorrow" class="tasks-switch__item <?= isset($_GET['date']) && $_GET['date'] == 'tomorrow' ? 'tasks-switch__item--active' : '' ?>">Завтра</a>
                <a href="?date=overdue" class="tasks-switch__item <?= isset($_GET['date']) && $_GET['date'] == 'overdue' ? 'tasks-switch__item--active' : '' ?>">Просроченные</a>
            </nav>

            <label class="checkbox">
                <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= $show_complete_tasks ? 'checked' : '' ?>>
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <?php if (isset($_GET['seach']) && empty($tasks)): ?>
        <p><?= $seach_error ?> </p>
        <?php else: ?>

        <table class="tasks">
        <?php foreach ($tasks as $task):
            if (!$show_complete_tasks && $task['status']) {
                 continue;
            }
        ?>

            <tr class="tasks__item task <?= $task['status'] ? 'task--completed' : '';
                                            echo(due_control($task['due_date'], $task['status'])); ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" name="checkbox" value="<?= $task['id'] ?>"
                        >
                        <span class="checkbox__text"><?= strip_tags($task['name']); ?></span>
                    </label>
                </td>
                <?php if ($task['file']): ?>
                <td class="task__file">
                    <a class="download-link" href="<?= $task['file'] ?>"><?= strip_tags(substr($task['file'], 5)); ?></a>
                </td>
                <?php else: ?>
                <td></td>
                <?php endif; ?>
                <td class="task__date"><?= strip_tags($task['due_date']); ?></td>
            </tr>
        <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </main>
</div>
