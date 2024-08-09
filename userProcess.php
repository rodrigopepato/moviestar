<?php

  require_once("globals.php");
  require_once("db.php");
  require_once("models/User.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");

  $message = new Message($BASE_URL);

  $userDao = new UserDAO($conn, $BASE_URL);

    // RESGATA O TIPO DE FORMULÁRIO
    $type = filter_input(INPUT_POST, "type");
  
  // ATUALIZA OS DADOS DO USUÁRIO
  if ($type === "update") 
  {
    // RESGATA DADOS DO USUÁRIO
    $userData = $userDao->verifyToken();

    // RECEBE DADOS DO POST
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    // CRIAR UM NOVO OBJETO DO USUÁRIO
    $user = new User();

    // PREENCHE OS DADOS DO USUÁRIO
    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

    // UPLOAD DA IMAGEM
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) 
    {

      $image = $_FILES["image"];
      $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
      
      // CHECAGEM DE TIPO DE IMAGEM
      if (in_array($image["type"], $imageTypes)) 
      {

          // IMAGEM É JPG OU JPEG
          if ($image["type"]==="image/jpeg" || $image["type"]==="image/jpg") 
          {
            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
          }
          // IMAGEM É PNG
          elseif ($image["type"] === "image/png") 
          {
            $imageFile = imagecreatefrompng($image["tmp_name"]);
          }

          $imageName = $user->imageGenerateName();

          imagejpeg($imageFile, "./img/users/" . $imageName, 100);

          $userData->image = $imageName;

      } 
      else 
      {
        $message->setMessage("Tipo de imagem inválida, insira png ou jpg", "error", "back");
      }

  }

  $userDao->update($userData);

}
// ALTERA A SENHA DO USUARIO
elseif ($type === "changePassword") 
{

   // RECEBE DADOS DO POST
  $password = filter_input(INPUT_POST, "password");
  $confirmPassword = filter_input(INPUT_POST, "confirmPassword");

  // RESGATA DADOS DO USUÁRIO
  $userData = $userDao->verifyToken();

  $id = $userData->id;

  if ($password == $confirmPassword && $password != "") 
  {

    // CRIAR UM NOVO OBJETO DO USUÁRIO
    $user = new User();

    $finalPassoword = $user->generatePassword($password);

    $user->password = $finalPassoword;
    $user->id = $id;

    $userDao->changePassword($user);
    
  }
  elseif ($password == "" || $confirmPassword == "")
  {
    $message->setMessage("Por favor, preencha todos os campos de senha.", "error", "back");
  }
  else 
  {

    $message->setMessage("As senhas fornecidas não correspondem. Por favor, tente novamente.", "error", "back");

  }

} 
else 
{
  $message->setMessage("Informações inválidas!", "error");
}