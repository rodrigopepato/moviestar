<?php

  require_once("models/Movie.php"); 
  require_once("models/Message.php");

  // REVIEW DAO
  require_once("dao/ReviewDAO.php");

  class MovieDAO implements MovieDAOInterface
  {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url)
    {
      $this->conn = $conn;
      $this->url = $url;
      $this->message = new Message($url);      
    }

    public function buildMovie($data)
    {
      $movie = new Movie();

      $movie->id = $data["id"];
      $movie->title = $data["title"];
      $movie->description = $data["description"];
      $movie->image = $data["image"];
      $movie->trailer = $data["trailer"];
      $movie->category = $data["category"];
      $movie->length = $data["length"];
      $movie->users_id = $data["users_id"];

      // RECEBE AS RATINGS DO FILME
      $reviewDao = new ReviewDAO($this->conn, $this->url);

      $rating = $reviewDao->getRatings($movie->id);

      $movie->rating = $rating;

      return $movie;
      
    }

    public function findAll()
    {
      
    }

    public function getLatestMovies()
    {

      $movies = [];

      $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");

      $stmt->execute();

      if ($stmt->rowCount() > 0) 
      {
        
        $moviesArray = $stmt->fetchAll();

        foreach ($moviesArray as $movie) 
        {
          $movies[] = $this->buildMovie($movie);
        }

      }

      return $movies;
      
    }

    public function getMoviesByCategoty($category)
    {

      $movies = [];

      $stmt = $this->conn->prepare("SELECT * FROM movies
                                    WHERE category = :category 
                                    ORDER BY id DESC");
                                  
      $stmt->bindParam(":category", $category);

      $stmt->execute();

      if ($stmt->rowCount() > 0) 
      {
        
        $moviesArray = $stmt->fetchAll();

        foreach ($moviesArray as $movie) 
        {
          $movies[] = $this->buildMovie($movie);
        }

      }

      return $movies; 
      
    }

    public function getMoviesByUserId($id)
    {

      $movies = [];

      $stmt = $this->conn->prepare("SELECT * FROM movies
                                    WHERE users_id = :users_id 
                                    ORDER BY id ASC");
                                  
      $stmt->bindParam(":users_id", $id);

      $stmt->execute();

      if ($stmt->rowCount() > 0) 
      {
        
        $moviesArray = $stmt->fetchAll();

        foreach ($moviesArray as $movie) 
        {
          $movies[] = $this->buildMovie($movie);
        }

        return $movies;       

      }
      else 
      {
        return false;
      }

      
    }

    public function findById($id)
    {

      $movie = [];

      $stmt = $this->conn->prepare("SELECT * FROM movies
                                    WHERE id = :id 
                                    ORDER BY id ASC");
                                  
      $stmt->bindParam(":id", $id);

      $stmt->execute();

      if ($stmt->rowCount() > 0) 
      {
        
        $movieData = $stmt->fetch();

        $movie = $this->buildMovie($movieData);

        return $movie;

      }
      else 
      {
        return false;
      }   
      
    }

    public function findByTitle($title)
    {

      $movies = [];

      $stmt = $this->conn->prepare("SELECT * FROM movies
                                    WHERE title LIKE :title
                                    ORDER BY id ASC");
                                  
      $stmt->bindValue(":title", '%'.$title.'%');

      $stmt->execute();

      if ($stmt->rowCount() > 0) 
      {
        
        $moviesArray = $stmt->fetchAll();

        foreach ($moviesArray as $movie) 
        {
          $movies[] = $this->buildMovie($movie);
        }

        return $movies;       

      }
      else 
      {
        return false;
      }
      
    }

    public function create(Movie $movie)
    {

      $stmt = $this->conn->prepare
        (
          "INSERT INTO movies 
            (title, description, image, trailer, category, length, users_id) 
          VALUES 
            (:title, :description, :image, :trailer, :category, :length, 
            :users_id)"
        );

      $stmt->bindParam(":title", $movie->title);
      $stmt->bindParam(":description", $movie->description);
      $stmt->bindParam(":image", $movie->image);
      $stmt->bindParam(":trailer", $movie->trailer);
      $stmt->bindParam(":category", $movie->category);
      $stmt->bindParam(":length", $movie->length);
      $stmt->bindParam(":users_id", $movie->users_id);

      $stmt->execute();

      // MENSAGEM DE SUCESSO POR ADICIONAR FILME
      $this->message->setMessage("Filme adcionado com sucesso!", "success");
      
    }

    public function update(Movie $movie)
    {

      $stmt = $this->conn->prepare(" UPDATE movies SET
        title = :title,
        description = :description,
        image = :image,
        category = :category,
        trailer = :trailer,
        length = :length
        WHERE id = :id"
        );

        $stmt->bindParam(":title", $movie->title);
        $stmt->bindParam(":description", $movie->description);
        $stmt->bindParam(":image", $movie->image);
        $stmt->bindParam(":category", $movie->category);
        $stmt->bindParam(":trailer", $movie->trailer);
        $stmt->bindParam(":length", $movie->length);
        $stmt->bindParam(":id", $movie->id);

        $stmt->execute();

        // MENSAGEM DE SUCESSO POR EDITAR FILME
        $this->message->setMessage("Filme editado com sucesso!", "success");
      
    }
    
    public function destroy($id)
    {
        try {
            // Iniciar transação
            $this->conn->beginTransaction();
    
            // Excluir as reviews associadas ao filme
            $stmt = $this->conn->prepare("DELETE FROM reviews WHERE movies_id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Excluir o filme
            $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Confirmar transação
            $this->conn->commit();
    
            // MENSAGEM DE SUCESSO POR REMOVER FILME
            $this->message->setMessage("Filme removido com sucesso!", "success", "dashboard.php");
        } catch (PDOException $e) {
            // Reverter transação em caso de erro
            $this->conn->rollBack();
            throw $e;
        }
    }
    


  }