            <?php include __DIR__ . '/../header.php'; ?>
      <body>
            <div class="container wrapper-main w-5">
                  <h3 class="mt-2 mb-2 text-center">СRUD операции MYSQL на PHP js </h3>
                  <?php if (!empty($error)): ?>
                        <div class="wrapper__error text-center" style="background-color: #c33c3c4f; padding: 5px;margin: 15px"><?= $error ?></div>
                  <?php endif; ?>
                  <form action="/users/add" method="POST">
                        <input class="form-control form-control-lg mb-2" name="name" type="text" placeholder="Name" value="" aria-label=".form-control-lg example">
                        <input class="form-control form-control-lg mb-2" name="email" type="text" placeholder="Email" value="" aria-label=".form-control-lg example">
                        <input class="form-control form-control-lg mb-2" name="phone" type="text" placeholder="Phone" value="" aria-label=".form-control-lg example">
                        <input class="btn btn-primary mt-3" type="submit" value="Добавить">
                  </form>
                  <div class="container  d-flex flex-wrap justify-content-around">
                  <?php if(!empty($users)): ?>
                        <?php foreach($users as $user): ?>
                              <div class="card mt-5  card-user" id="<?=$user->getId()?>" style="width: 14rem;">
                                    <ul class="list-group list-group-flush">
                                          <li class="list-group-item"><?= $user->getName() ?></li>
                                          <li class="list-group-item"><?= $user->getEmail() ?></li>
                                          <li class="list-group-item"><?= $user->getPhone() ?></li>
                                    </ul>
                                    <div class="conteiner mt-2 wrapper-button">
                                          <button type="button"  id="<?=$user->getId()?>"  class="btn btn-primary botton-edit button-<?=$user->getId()?>">Редактировать</button>
                                          <a href="/users/<?= $user->getId() ?>/delete""><button type="button" class="btn btn-primary">Удалить</button></a>
                                    </div>
                              </div>
                        <?php endforeach ?>
                  <?php else: ?>
                        <p>Пока никого не добавили</p>
                  <?php endif ?>
                  </div>
            </div>
      
            <?php include __DIR__ . '/../footer.php'; ?>
      <body>
</html>
  
  
