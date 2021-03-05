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

        <a class="button button--transparent button--plus content__side-button" href="form-project.html">Добавить проект</a>
      </section>

      <main class="content__main">
        <h2 class="content__main-heading">Добавление проекта</h2>

        <form class="form"  action="" method="post" autocomplete="off">
          <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>

            <input class="form__input" type="text" name="project_name" id="project_name" value="" placeholder="Введите название проекта">
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
      </main>
    </div>
  </div>
</div>