<?php
  class comentarioDB{
    private $conn;

    function __construct($conn){
      $this->conn = $conn;
    }

    function nuevoComentarioDB($author, $photo, $date, $comment){
      try{
        $query = 'INSERT INTO Comentario (author, photo, date, comment)
         VALUES(:author, :photo, :date, :comment)';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':author', $authorReady);
        $prepared->bindParam(':photo', $photoReady);
        $prepared->bindParam(':date', $dateReady);
        $prepared->bindParam(':comment', $commentReady);

        $authorReady = $author;
        $photoReady = $photo;
        $dateReady = $date;
        $commentReady = $comment;

        $prepared->execute();

      } catch(PDOException $e){
        echo "Error al crear un nuevo concurso: " . $e->getMessage();
      }
    }

    function getComentariosFotoDB($id){
      $query = 'SELECT * FROM Comentario WHERE photo=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();

      $result = $prepared->fetchAll();

      return $result;
    }

    function borrarComentarioDB($id){
      $query = 'DELETE FROM Comentario WHERE comment_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();
    }
  }

 ?>
