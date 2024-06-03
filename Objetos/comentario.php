<?php
  include './Objetos/comentarioDB.php';

  class comentario{
    private $DB;

    function __construct($conn){
      $this->DB = new comentarioDB($conn);
    }

    function nuevoComentario($author, $photo_id, $date, $comment){
      $this->DB->nuevoComentarioDB($author, $photo_id, $date, $comment);
    }

    function getComentariosFoto($id){
      $datos = $this->DB->getComentariosFotoDB($id);

      return $datos;
    }

    function borrarComentario($id){
      $this->DB->borrarComentarioDB($id);
    }
  }

 ?>
