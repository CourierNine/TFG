<?php
  //Objeto que gestiona operaciones sobre la tabla Comentario de la base de datos
  class comentarioDB{
    private $conn;

    //Se establece una conexión con la base de datos
    function __construct($conn){
      $this->conn = $conn;
    }

    //Utilizando los datos provistos, se inserta una nueva tupla en la tabla
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

    /*Se obtienen los datos de las tuplas cuyo identificador de foto corresponda con
      el provisto*/
    function getComentariosFotoDB($id){
      $query = 'SELECT * FROM Comentario WHERE photo=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();

      $result = $prepared->fetchAll();

      return $result;
    }

    //Se elimina una tupla concreta de la tabla
    function borrarComentarioDB($id){
      $query = 'DELETE FROM Comentario WHERE comment_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();
    }

    //Se elimina una tupla concreta de la tabla
    function borrarComentariosFotoDB($photo_id){
      $query = 'DELETE FROM Comentario WHERE photo=:photo_id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':photo_id', $idReady);

      $idReady = $photo_id;

      $prepared->execute();
    }
  }

 ?>
