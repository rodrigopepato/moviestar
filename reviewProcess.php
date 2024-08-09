<?php

  require_once("globals.php");
  require_once("db.php");
  require_once("models/Movie.php");
  require_once("models/Message.php");
  require_once("models/Review.php");
  require_once("dao/UserDAO.php");
  require_once("dao/MovieDAO.php");
  require_once("dao/ReviewDAO.php");

  $message = new Message($BASE_URL);
  $userDao = new UserDAO($conn, $BASE_URL);
  $movieDao = new MovieDAO($conn, $BASE_URL);
  $reviewDao = new ReviewDAO($conn, $BASE_URL);

  // RECEBE O TIPO DO FORMULÁRIO
  $type = filter_input(INPUT_POST, "type");

  // RESGATA DADOS DO USUÁRIO
  $userData = $userDao->verifyToken();

  if ($type === "create") 
  {
    // RECEBENDO DADOS DO POST
    $rating = filter_input(INPUT_POST, "rating");
    $review = filter_input(INPUT_POST, "review");
    $movies_id = filter_input(INPUT_POST, "movies_id");
    $users_id = $userData->id;

    $reviewObject = new Review();

    $movieData = $movieDao->findById($movies_id);

    // VALIDANDO SE FILME EXISTE
    if ($movieData) 
    {
      // VERIFICAR DADOS MÍNIMOS
      if (!empty($rating) && !empty($review) && !empty($movies_id)) 
      {

        $reviewObject->rating = $rating;
        $reviewObject->review = $review;
        $reviewObject->movies_id = $movies_id;
        $reviewObject->users_id = $users_id;

        $reviewDao->create($reviewObject);
        
      }
      else 
      {
        $message->setMessage("Você precisa insira nota e comentário", "error", "back");
      }
      
    }
    else 
    {
      $message->setMessage("Informações inválidas!", "error", "index.php");
    }
    
  }
  else 
  {
    $message->setMessage("Informações inválidas!", "error", "index.php");
  }
