<?php
  include './Objetos/fotoDB.php';

  class foto{
    private $DB;

    function __construct($conn){
      $this->DB = new fotoDB($conn);
    }

    function nuevaFoto($name, $author, $description, $contest, $image){
      $votes = 0;

      $this->DB->nuevaFotoDB($name, $author, $description, $contest, $image, $votes);
    }

    function getDatos($id){

      $datos = $this->DB->getDatosDB($id);

      return $datos;
    }

    function getTodosDatos(){

      $datos = $this->DB->getTodosDatosDB();

      return $datos;
    }

    function getFotosConcurso($contest){
      $result = $this->DB->getFotosConcursoDB($contest);

      return $result;
    }

    function nuevoVoto($id){
      $this->DB->nuevoVotoDB($id);
    }

    function setRanking($id, $ranking){
      $this->DB->setRankingDB($id, $ranking);
    }

    function borrarFoto($id){
      $this->DB->borrarFotoDB($id);
    }

    function editarFoto($id, $name, $author, $description, $photo){
      $this->DB->editarFotoDB($id, $name, $author, $description, $photo);

      header("Location: perfilFoto.php?id=" . $id);
    }


  }

 ?>
