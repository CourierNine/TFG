<?php
  include './Objetos/comentarioDB.php';

  //Clase que gestiona la lógica relacionada con los comentarios de una imagen
  class comentario{
    private $DB;

    //Se crea un objeto de la clase comentarioDB
    function __construct($conn){
      $this->DB = new comentarioDB($conn);
    }

    //Se comunica que se va a añadir un nuevo comentario
    function nuevoComentario($author, $photo_id, $date, $comment){
      $this->DB->nuevoComentarioDB($author, $photo_id, $date, $comment);
    }

    //Se piden los datos de todos los comentarios pertenecientes a una foto
    function getComentariosFoto($id){
      $datos = $this->DB->getComentariosFotoDB($id);

      return $datos;
    }

    //Se comunica que se va a borrar un comentario
    function borrarComentario($id){
      $this->DB->borrarComentarioDB($id);
    }

    function borrarComentariosFoto($photo_id){
      $this->DB->borrarComentariosFotoDB($photo_id);
    }
  }

 ?>
