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

// VERIFICAÇÃO DO TIPO DE FORMULÁRIO
if ($type === "register") 
{

  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");
  $confirmPassword = filter_input(INPUT_POST, "confirmPassword");

  //VERIFICAÇÃO DE DADOS MÍNIMOS
  if ($name && $lastname && $email && $password)
  {
    //VERIFICAR SE AS SENHAS SÃO IGUAIS
    if ($password === $confirmPassword) 
    {

      //VERIFICAR SE O EMAIL JA ESTA CADASTRADO NO SISTEMA
      if ($userDao->findByEmail($email) === false) 
      {

        $user = new User();

        //CRIAÇÃO DE TOKEN E SENHA
        $userToken = $user->generateToken();
        $finalPassword = $user->generatePassword($password);
        
        $user->name = $name;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->password = $finalPassword;
        $user->token = $userToken;

        $auth = true;

        $userDao->create($user, $auth);

      }
      else 
      {
        // ENVIA UMA MSG DE ERRO, DE SENHAS QUE NÃO BATEM
        $message->setMessage("E-mail já cadastrado.", "error", "back");
      }
      
    }
    else 
    {
      // ENVIA UMA MSG DE ERRO, DE SENHAS QUE NÃO BATEM
      $message->setMessage("As senhas fornecidas não correspondem. Por favor, tente novamente.", "error", "back");
    }
  }
  else 
  {
    
    // ENVIA UMA MSG DE ERRO, DE DADOS FALTANTES
    $message->setMessage("Por favor, preencha todos os campos!","error","back");
    
  }

}
elseif ($type === "login") 
{

  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");

  // TENTA AUTENTICAR O USUÁRIO
  if ($userDao->authenticateUser($email, $password)) 
  {
    $message->setMessage("Seja bem-vindo!","success",);
  }
  //REDIRECIONA O USÁRIO, CASO NÃO CONSIGA AUTENTICAR
  else 
  {
    $message->setMessage("Dados inválidos!","error","back");
  }
}
else 
{
  $message->setMessage("Informações inválidas!","error");
}

