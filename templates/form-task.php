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
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form"  action="add.php?submit=true" method="post" enctype="multipart/form-data" autocomplete="off">
          <div class="form__row">
            <?php if (isset($error['name'])): ?>
            <p class="form__message"><?= $error['name']; ?> </p>
            <?php endif; ?>
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?=  isset($error['name']) ? $error_class : '' ?> " type="text" name="name" id="name" value="<?=  $error ? getPostVal('name') : '' ?>" placeholder="Введите название">
     
          </div>

          <div class="form__row">
          <?php if (isset($error['project'])): ?>
            <p class="form__message"><?= $error['project']; ?> </p>
            <?php endif; ?>
            <label class="form__label" for="project">Проект <sup>*</sup></label>
            
            <select class="form__input form__input--select <?=  isset($error['project']) ? $error_class : '' ?>" name="project" id="project">
            <?php foreach ($categories as $category): ?>
              <option value="<?= $category['id'] ?>"><?= $category['name_of_project']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form__row">
          <?php if (isset($error['date'])): ?>
            <p class="form__message"><?= $error['date']; ?> </p>
            <?php endif; ?>
            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date <?=  isset($error['date']) ? $error_class : '' ?>" type="text" name="date" id="date" value="<?=  $error ? getPostVal('date') : '' ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            
          </div>

          <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
              <input class="visually-hidden" type="file" name="file" id="file" value="<?= $_FILE['name'] ?>">

              <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
              </label>
            </div>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="send" value="Добавить">
          </div>
        </form>
      </main>
    </div>
  </div>
</div>
