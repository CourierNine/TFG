<?php
  include './Objetos/usuarioDB.php';

  class usuario{
    private $DB;

    function __construct($conn){
      $this->DB = new usuarioDB($conn);
    }

    function contarUsuarios(){
      $count = $this->DB->contarUsuariosDB();

      return $count;
    }

    function nuevoUsuario($name, $email, $password, $description, $image){
      $count = $this->contarUsuarios();



      if($count==0){
        $type = "Admin";
      }
      else{
        $type = "Normal";
      }

      $participations = 0;
      $wins  = 0;

      $this->DB->nuevoUsuarioDB($name, $email, $password, $description, $type, $participations, $wins,$image);

      $this->login($email, $password);

      header("Location: ../index.php");
    }

    function login($email, $password){
      $result = $this->DB->loginDB($email, $password);

      if(!empty($result)){
        $name = $result["name"];
        $type = $result["type"];
        $image = $result["image"];
        $id = $result["user_id"];
        $email = trim($result["email"]);


        session_start();
        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email;
        $_SESSION["id"] = $id;
        $_SESSION["type"] = $type;
        $_SESSION["image"] = $image;
      }

      header("Location: ../index.php");
    }

    function getDatos($id){

      $result = $this->DB->getDatosDB($id);

      return $result;
    }

    function getTodosDatos(){
      $result = $this->DB->getTodosDatosDB();

      return $result;
    }

    function editarUsuario($id, $name, $description, $type, $image){
      $this->DB->editarUsuarioDB($id, $name, $description, $type, $image);

      if($_SESSION["id"]==$id){
        session_start();
        $_SESSION["name"] = $name;
        $_SESSION["image"] = $image;
      }

      header("Location: ../index.php");
    }

    function borrarUsuario($id){
      $this->DB->borrarUsuarioDB($id);

      header("Location: ../listaUsuarios.php");
    }

    function cerrar(){
      session_destroy();
    }
  }

 ?>
