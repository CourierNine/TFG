<?php
  //Objeto que gestiona operaciones sobre la tabla Voto de la base de datos
  class votoDB{
    private $conn;

    //Se establece una conexión con la base de datos
    function __construct($conn){
      $this->conn = $conn;
    }

    function asignarVotoDB($contest, $voter){
      try{
        $query = 'INSERT INTO Voto (contest, voter) VALUES (:contest, :voter)';
        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':contest', $contestReady);
        $prepared->bindParam(':voter', $voterReady);

        $contestReady = $contest;
        $voterReady = $voter;

        $prepared->execute();
      }catch(PDOException $e){
          echo "Error al crear guardar voto: " . $e->getMessage();
      }
    }

    function comprobarVotoDB($contest, $voter){
      try{
        $query = 'SELECT * FROM Voto WHERE contest=:contest AND voter=:voter';
        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':contest', $contestReady);
        $prepared->bindParam(':voter', $voterReady);

        $contestReady = $contest;
        $voterReady = $voter;
        $prepared->execute();

        $result = $prepared->fetch();

        return $result;
      }catch(PDOException $e){
          echo "Error al crear guardar voto: " . $e->getMessage();
      }
    }
  }

 ?>
