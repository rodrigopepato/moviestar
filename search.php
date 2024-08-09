<?php

  require_once("templates/header.php");

  require_once("dao/MovieDAO.php");

  // DAO dos filmes
  $movieDao = new MovieDAO($conn, $BASE_URL);

  // RESGATA BUSCA DO USUÁRIO
  $q = filter_input(INPUT_GET, "q");

  $movies = $movieDao->findByTitle($q);

?>

<div id="main-container" class="container-fluid">
  <h2 class="section-title" id="search-title">Você está buscando por: <span id="search-result"><?= $q ?></span></h2>
  <p class="section-description">
    Resultados de busca:  
  </p>  
  <div class="movies-container">
    <?php if (is_array($movies) && count($movies) > 0): ?>
      <?php foreach ($movies as $movie): ?>
        <?php require("templates/movieCard.php"); ?>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="empty-list">Nenhum filme encontrado para a busca: <?= $q ?>. <a href="<?= $BASE_URL ?>newMovie.php" class="back-link">Cadastrar.</a></p>
    <?php endif; ?>
  </div>
</div>


<?php
  include_once("templates/footer.php")
?>
