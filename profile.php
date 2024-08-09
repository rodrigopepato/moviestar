<?php
  include_once("templates/header.php");

  // VERIFICA SE USUÁRIO ESTÁ AUTENTICADO
  require_once("models/User.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");

  $user = new User();
  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);

  // RECEBER O ID DO USUÁRIO
  $id = filter_input(INPUT_GET, "id");

  if (empty($id)) 
  {
    if (!empty($userData)) 
    {
      $id = $userData->id;
    }
    else 
    {
      $message->setMessage("O usuário não encontrado!", "error", "index.php");
    }
    
  }
  else 
  {

    $userData = $userDao->findById($id);

    // SE NÃO ENCONTRAR USUÁRIO
    if (!$userData) 
    {
      $message->setMessage("O usuário não encontrado!", "error", "index.php");
    }
    
  }

  $fullName = $user->getFullName($userData);

  if ($userData->image == "") 
  {
    $userData->image = "user.png";
  }
  // FILMES QUE O USUÁRIO ADCIONOU
  $userMovies = $movieDao->getMoviesByUserId($id);

?>

<div id="main-container" class="container-fluid">
  <div class="col-md-8 offset-md-2">
    <div class="row profile-container">
      <div class="col-md-12 about-container">
        <h1 class="page-title"><?= $fullName ?></h1>
        <div id="profile-image-container" class="profile-image" 
            style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userData->image ?>')">
        </div>
        <h3 class="about-title">Sobre:</h3>
        <?php if(!empty($userData->bio)): ?>
          <p class="profile-description"><?= $userData->bio ?></p>
        <?php else: ?>
          <p class="profile-description">O usuário não tem biografia...</p>
        <?php endif; ?>
      </div>
      <div class="col-md-12 added-movies-container">
        <h3>Filmes que enviou:</h3>
        <div class="movies-container">
        <?php if (is_array($userMovies) && count($userMovies) > 0): ?>
          <?php foreach($userMovies as $movie): ?>
            <?php require("templates/movieCard.php"); ?>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="empty-list">O usuário ainda não enviou filmes.</p>
        <?php endif; ?>
      </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  include_once("templates/footer.php")
?>