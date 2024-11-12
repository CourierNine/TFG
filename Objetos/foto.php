<?php
  include './Objetos/fotoDB.php';

  //Clase que gestiona la lógica relacionada con las fotos
  class foto{
    private $DB;

    //Se crea un objeto de la clase fotoBD
    function __construct($conn){
      $this->DB = new fotoDB($conn);
    }

    //Se comuníca que se va a añadir una nueva foto al sistema
    function nuevaFoto($name, $author, $description, $contest, $image){
      $votes = 0;

      $this->DB->nuevaFotoDB($name, $author, $description, $contest, $image, $votes);

    }

    //Se piden los datos de una foto en concreto
    function getDatos($id){

      $datos = $this->DB->getDatosDB($id);

      return $datos;
    }

    //Se piden los datos de todas las fotos
    function getTodosDatos(){

      $datos = $this->DB->getTodosDatosDB();

      return $datos;
    }

    //Se piden los datos de todas las fotos pertenecientes a un concurso
    function getFotosConcurso($contest){
      $result = $this->DB->getFotosConcursoDB($contest);

      return $result;
    }

    //Se suman un voto a una foto en concreto
    function nuevoVoto($id){
      $this->DB->nuevoVotoDB($id);
    }

    //Se establece el ranking de una foto en concreto
    function setRanking($id, $ranking){
      $this->DB->setRankingDB($id, $ranking);
    }

    //Se comunica que se va a borrar una foto en concreto
    function borrarFoto($id){
      $this->DB->borrarFotoDB($id);
    }

    //Se comunica que se van a cambiar los datos de una foto en concreto
    function editarFoto($id, $name, $author, $description, $photo){
      $this->DB->editarFotoDB($id, $name, $author, $description, $photo);

      /*Se redirige una página a la página perfilFoto.php con el identificador
        de la foto como argumento GET*/
      header("Location: perfilFoto.php?id=" . $id);
    }


  }

 ?>
