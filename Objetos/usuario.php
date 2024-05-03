<?php
  include './Objetos/usuarioDB.php';

  class usuario{
    private $DB;

    function __construct(){
      $this->DB = new usuarioDB();
    }

    function nuevoUsuario($name, $email, $password, $description, $image){
      $count = $this->DB->contarUsuarios();

      if($count==0){
        $type = "admin";
      }
      else{
        $type = "normal";
      }

      $participations = 0;
      $wins  = 0;

      $this->DB->nuevoUsuarioDB($name, $email, $password, $description, $type, $participations, $wins,$image);
    }
  }

 ?>
