<?php
  include './Objetos/concursoDB.php';

  class concurso{
    private $DB;

    function __construct($conn){
      $this->DB = new concursoDB($conn);
    }

    function nuevoConcurso($name, $hashtag, $description, $end_date, $image){
      $status = "Abierto";

      $this->DB->nuevoConcursoDB($name, $hashtag, $description, $end_date, $status, $image);

      header("Location: index.php");
    }

    function getDestacado(){
      $destacado = $this->DB->getDestacadoDB();

      return $destacado;
    }

    function getDatos($id){
      $result = $this->DB->getDatosDB($id);

      return $result;
    }

    function getTodosDatos(){
      $result = $this->DB->getTodosDatosDB();

      return $result;
    }

    function contarConcursos(){
      $count = $this->DB->contarConcursosDB();

      return $count;
    }

    function setGanador($id, $ganador){
      $this->DB->setGanadorDB($id, $ganador);
    }

    function borrarConcurso($id){
      $this->DB->borrarConcursoDB($id);
    }

    function editarConcurso($id, $name, $end_date, $hashtag, $description, $image){
      $this->DB->editarConcursoDB($id, $name, $end_date, $hashtag, $description, $image);

      header("Location: perfilConcurso.php?id=" . $id);
    }

    function cambiarEstado($id, $nuevoEstado){
      $this->DB->cambiarEstadoDB($id, $nuevoEstado);
    }

  }

 ?>
