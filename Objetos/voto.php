<?php
  include './Objetos/votoDB.php';

  //Clase que gestiona la lógica relacionada con los votos de un concurso
  class voto{
    private $DB;

    //Se crea un objeto de la clase votoDB
    function __construct($conn){
      $this->DB = new votoDB($conn);
    }

    function asignarVoto($contest, $voter){
      $this->DB->asignarVotoDB($contest, $voter);
    }

    function comprobarVoto($contest, $voter){
      $res = $this->DB->comprobarVotoDB($contest, $voter);

      if(!empty($res)){
        return true;
      }
      else{
        return false;
      }
    }
  }

 ?>
