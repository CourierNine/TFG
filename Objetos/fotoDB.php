<?php
  //Clase que gestiona las operaciones sobre la tabla Foto de la base de datos
  class fotoDB{
    private $conn;

    //Se establece una conexión con la base de datos
    function __construct($conn){
      $this->conn = $conn;
    }

    //Se añade una tupla con los parametros provistos a la tabla
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

    //Se obtienen los datos de la tupla correspondiente al identificador
    function getDatosDB($id){
      $query = 'SELECT * FROM Foto WHERE photo_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();

      $result = $prepared->fetch();

      return $result;
    }

    //Se obtienen los datos de todas las tuplas de la tabla
    function getTodosDatosDB(){
      $query = 'SELECT * FROM Foto';

      $prepared = $this->conn->prepare($query);

      $prepared->execute();

      $result = $prepared->fetchAll();

      return $result;
    }

    /*Se obtienen los datos de todas las tuplas cuyo atributo contest corresponde con
      el parametro indicado*/
    function getFotosConcursoDB($contest){
      $query = 'SELECT * FROM Foto WHERE contest=:contest';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':contest', $contestReady);

      $contestReady = $contest;

      $prepared->execute();

      $result = $prepared->fetchAll();

      return $result;
    }

    //Se suma 1 al atributo votes de la tupla correspondiente
    function nuevoVotoDB($id){

      $query = 'UPDATE Foto SET votes = votes + 1 WHERE photo_id=:id';


      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();

    }

    //Se establece un valor para el atributo ranking de la tupla correspondiente
    function setRankingDB($id, $ranking){
      $query = 'UPDATE Foto SET ranking=:ranking WHERE photo_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);
      $prepared->bindParam(':ranking', $rankingReady);

      $idReady = $id;
      $rankingReady = $ranking;

      $prepared->execute();
    }

    //Se borra la tupla indicada por el identificador provisto
    function borrarFotoDB($id){
      $query = 'DELETE FROM Foto WHERE photo_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();
    }

    //Se editan los datos provistos en la tupla indicada
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
