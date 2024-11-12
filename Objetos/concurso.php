<?php
  include './Objetos/concursoDB.php';

  //Clase que gestiona la lógica relacionada con los concursos
  class concurso{
    private $DB;

    //Se crea un objeto de la clase concursoBD
    function __construct($conn){
      $this->DB = new concursoDB($conn);
    }

    //Se comunica que se va a abir un nuevo concurso
    function nuevoConcurso($name, $hashtag, $description, $end_date, $image){
      $status = "Abierto";

      $this->DB->nuevoConcursoDB($name, $hashtag, $description, $end_date, $status, $image);

      header("Location: index.php");
    }

    //Se piden los datos del concurso más recientemente añadido
    function getDestacado(){
      $destacado = $this->DB->getDestacadoDB();

      return $destacado;
    }

    //Se piden los datos de un concurso en concreto
    function getDatos($id){
      $result = $this->DB->getDatosDB($id);

      return $result;
    }

    //Se piden los datos de todos los concursos
    function getTodosDatos(){
      $result = $this->DB->getTodosDatosDB();

      return $result;
    }

    //Se pide el número de concursos actualmente presentes en el sistema
    function contarConcursos(){
      $count = $this->DB->contarConcursosDB();

      return $count;
    }

    //Se comunican los parametros para establecer un ganador de concurso
    function setGanador($id, $ganador){
      $this->DB->setGanadorDB($id, $ganador);
    }

    //Se comunica que se va a borrar un concurso
    function borrarConcurso($id){
      $this->DB->borrarConcursoDB($id);
    }

    //Se comunican los paramatros necesarios para realizar cambios en un concurso
    function editarConcurso($id, $name, $end_date, $hashtag, $description, $image){
      $this->DB->editarConcursoDB($id, $name, $end_date, $hashtag, $description, $image);

      /*Se redirige una página a la página perfilConcurso.php con el identificador
        del concurso como argumento GET*/
      header("Location: perfilConcurso.php?id=" . $id);
    }

    //Se comunincan los parametros necesarios para cambiar el estado del concurso
    function cambiarEstado($id, $nuevoEstado){
      $this->DB->cambiarEstadoDB($id, $nuevoEstado);
    }

  }

 ?>
