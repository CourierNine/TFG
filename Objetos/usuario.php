<?php
  include './Objetos/usuarioDB.php';

  class usuario{
    private $DB;

    function __construct(){
      $this->DB = new usuarioDB();
    }

    function nuevoUsuario($name, $email, $password, $description, $image){
      $count = $this->DB->contarUsuariosDB();

      if($count==0){
        $type = "Admin";
      }
      else{
        $type = "Normal";
      }

      $participations = 0;
      $wins  = 0;

      $this->DB->nuevoUsuarioDB($name, $email, $password, $description, $type, $participations, $wins,$image);
    }

    function login($email, $password){
      $result = $this->DB->loginDB($email, $password);

      if(!empty($result)){
        $name = $result["name"];
        $type = $result["type"];
        $image = $result["image"];
        $id = $result["user_id"];

        session_start();
        $_SESSION["name"] = $name;
        $_SESSION["id"] = $id;
        $_SESSION["type"] = $type;
        $_SESSION["image"] = $image;
      }
    }

    function getDatos($id){

      $result = $this->DB->getDatosDB($id);

      return $result;
    }

    function editarUsuario($id, $name, $email, $description, $image){
      $this->DB->editarUsuarioDB($id, $name, $email, $description, $image);

      if($_SESSION["id"]==$id){
        session_start();
        $_SESSION["name"] = $name;
        $_SESSION["image"] = $image;
      }

      header("Location: perfil.php");
    }

    function cerrar(){
      session_destroy();
    }
  }

 ?>
