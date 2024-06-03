<?php
  class fotoDB{
    private $conn;

    function __construct($conn){
      $this->conn = $conn;
    }

    function nuevaFotoDB($name, $author, $description, $contest, $image, $votes){
      try{
        $query = 'INSERT INTO Foto (name, author, description, contest , votes, image)
         VALUES(:name, :author, :description, :contest, :votes, :image)';

        $prepared = $this->conn->prepare($query);


        $prepared->bindParam(':name', $nameReady);
        $prepared->bindParam(':author', $authorReady);
        $prepared->bindParam(':description', $descriptionReady);
        $prepared->bindParam(':contest', $contestReady);
        $prepared->bindParam(':votes', $votesReady);
        $prepared->bindParam(':image', $imageReady);

        $nameReady = $name;
        $authorReady = $author;
        $votesReady = $votes;
        $descriptionReady = $description;
        $contestReady = $contest;
        $imageReady = $image;

        $prepared->execute();

      } catch(PDOException $e){
        echo "Error al crear una nueva Foto: " . $e->getMessage();
      }
    }

    function getDatosDB($id){
      $query = 'SELECT * FROM Foto WHERE photo_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();

      $result = $prepared->fetch();

      return $result;
    }

    function getTodosDatosDB(){
      $query = 'SELECT * FROM Foto';

      $prepared = $this->conn->prepare($query);

      $prepared->execute();

      $result = $prepared->fetchAll();

      return $result;
    }

    function getFotosConcursoDB($contest){
      $query = 'SELECT * FROM Foto WHERE contest=:contest';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':contest', $contestReady);

      $contestReady = $contest;

      $prepared->execute();

      $result = $prepared->fetchAll();

      return $result;
    }

    function nuevoVotoDB($id){
      $query = 'UPDATE Foto SET votes = votes + 1 WHERE photo_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();
    }

    function setRankingDB($id, $ranking){
      $query = 'UPDATE Foto SET ranking=:ranking WHERE photo_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);
      $prepared->bindParam(':ranking', $rankingReady);

      $idReady = $id;
      $rankingReady = $ranking;

      $prepared->execute();
    }

    function borrarFotoDB($id){
      $query = 'DELETE FROM Foto WHERE photo_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();
    }

    function editarFotoDB($id, $name, $author, $description, $image){
      try{
        $query = 'UPDATE Foto SET name=:name, description=:description, author=:author, image=:image WHERE photo_id=:id';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':name', $nameReady);
        $prepared->bindParam(':author', $authorReady);
        $prepared->bindParam(':description', $descriptionReady);
        $prepared->bindParam(':image', $imageReady);
        $prepared->bindParam(':id', $idReady);

        $nameReady = $name;
        $idReady = $id;
        $authorReady = $author;
        $descriptionReady = $description;
        $imageReady = $image;

        $prepared->execute();
      }
      catch(PDOException $e){
        echo "Error al crear un nuevo concurso: " . $e->getMessage();
      }
    }
  }

 ?>
