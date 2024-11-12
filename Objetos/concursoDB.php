<?php
  //Clase que gestiona las operaciones sobre la tabla Concurso de la base de datos
  class concursoDB{
    private $conn;

    //Se establece una conexión con la base de datos
    function __construct($conn){
      $this->conn = $conn;
    }

    //Se crea una nueva tupla en la tabla con los parametros provistos
    function nuevoConcursoDB($name, $hashtag, $description, $end_date, $status, $image){
      try{
        $query = 'INSERT INTO Concurso (name, hashtag, description, status , end_date, image)
         VALUES(:name, :hashtag, :description, :status, :end_date, :image)';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':name', $nameReady);
        $prepared->bindParam(':hashtag', $hashtagReady);
        $prepared->bindParam(':description', $descriptionReady);
        $prepared->bindParam(':end_date', $end_dateReady);
        $prepared->bindParam(':status', $statusReady);
        $prepared->bindParam(':image', $imageReady);

        $nameReady = $name;
        $hashtagReady = $hashtag;
        $statusReady = $status;
        $descriptionReady = $description;
        $end_dateReady = $end_date;
        $imageReady = $image;

        $prepared->execute();

      } catch(PDOException $e){
        echo "Error al crear un nuevo concurso: " . $e->getMessage();
      }
    }

    //Se obtienen los datos de un concurso con el identificador indicado
    function getDatosDB($id){
      $query = 'SELECT * FROM Concurso WHERE contest_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();

      $result = $prepared->fetch();

      return $result;
    }

    //Se obtienen los datos de todos las tuplas de la tabla
    function getTodosDatosDB(){
      $query = 'SELECT * FROM Concurso';

      $prepared = $this->conn->prepare($query);

      $prepared->execute();

      $result = $prepared->fetchAll();

      return $result;
    }

    /*Se obtienen los datos de la tupla con el mayor identificador
      (correpondería a la más reciente)*/
    function getDestacadoDB(){
      $query = 'SELECT MAX(contest_id)FROM Concurso';

      $prepared = $this->conn->prepare($query);

      $prepared->execute();

      $result = $prepared->fetch();

      return $result[0];
    }

    //Se obtiene el número de tuplas presentes en la tabla
    function contarConcursosDB(){
      $query = 'SELECT COUNT(*) FROM Concurso';
      $res = $this->conn->query($query);

      $count = $res->fetchColumn();

      return $count;
    }

    //Se da un valor al atributo winner de la tupla
    function setGanadorDB($id, $winner){
      try{
        $query = 'UPDATE Concurso SET winner=:winner WHERE contest_id=:id';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':id', $idReady);
        $prepared->bindParam(':winner', $winnerReady);

        $idReady = $id;
        $winnerReady = $winner;
        $prepared->execute();
      }
      catch(PDOException $e){
        echo "La votación no se ha podido cerrar: " . $e->getMessage();
      }

    }

    //Se borra una tupla correspondiente al parametro identificador
    function borrarConcursoDB($id){
      try{
        $query = 'DELETE FROM Concurso WHERE contest_id=:id';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':id', $idReady);

        $idReady = $id;

        $prepared->execute();
      }
      catch(PDOException $e){
        echo "Error al borrar concurso: " . $e->getMessage();
      }
    }

    //Se editan los datos de una tupla correspondiente al identificador
    function editarConcursoDB($id, $name, $end_date, $hashtag, $description, $image){
      try{
        $query = 'UPDATE Concurso SET name=:name, description=:description, end_date=:end_date, hashtag=:hashtag, image=:image WHERE contest_id=:id';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':name', $nameReady);
        $prepared->bindParam(':id', $idReady);
        $prepared->bindParam(':hashtag', $hashtagReady);
        $prepared->bindParam(':end_date', $end_dateReady);
        $prepared->bindParam(':description', $descriptionReady);
        $prepared->bindParam(':image', $imageReady);

        $nameReady = $name;
        $idReady = $id;
        $hashtagReady = $hashtag;
        $end_dateReady = $end_date;
        $descriptionReady = $description;
        $imageReady = $image;

        $prepared->execute();

      }
      catch(PDOException $e){
        echo "Error al editar el concurso: " . $e->getMessage();
      }
    }

    //Se edita el parametro status de la tupla correspondiente al identificador
    function cambiarEstadoDB($id, $nuevoEstado){
      try{
        $query = 'UPDATE Concurso SET status=:status WHERE contest_id=:id';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':status', $statusReady);
        $prepared->bindParam(':id', $idReady);

        $statusReady = $nuevoEstado;
        $idReady = $id;

        $prepared->execute();
      }
      catch(PDOException $e){
        echo "Error al actualizar el concurso: " . $e->getMessage();
      }
    }
  }

 ?>
