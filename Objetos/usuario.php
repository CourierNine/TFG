<?php
  include './Objetos/usuarioDB.php';

  //Clase que gestiona la lógica relacionada con un usuario
  class usuario{
    private $DB;

    //Se crea un objeto de la clase usuarioDB
    function __construct($conn){
      $this->DB = new usuarioDB($conn);
    }

    //Se pide el número de usuarios presentes en el sistema
    function contarUsuarios(){
      $count = $this->DB->contarUsuariosDB();

      return $count;
    }

    //Se comunica que se va a añadir nuevo usuario al sistema
    function nuevoUsuario($name, $email, $password, $description, $image){
      $count = $this->contarUsuarios();

      //Si el número de usuarios es igual a 0, el primer usuario será administrador
      if($count==0){
        $type = "Admin";
      }
      else{
        $type = "Normal";
      }

      $participations = 0;
      $wins  = 0;
      $cheater = 0;

      $this->DB->nuevoUsuarioDB($name, $email, $password, $description, $type, $participations, $wins,$image, $cheater);

      $this->login($email, $password);

      //Se refirige a la página index.php
      header("Location: ../index.php");
    }

    //Se intenta autentificar un usuario
    function login($email, $password){
      $result = $this->DB->loginDB($email, $password);

      /*Si el usuario existe en el sistema, se devuelven sus datos y
        se establece una sesión con ellos*/
      if(!empty($result)){
        $name = $result["name"];
        $type = $result["type"];
        $image = $result["image"];
        $id = $result["user_id"];
        $cheater = $result["cheater"];
        $email = trim($result["email"]);


        session_start();
        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email;
        $_SESSION["id"] = $id;
        $_SESSION["type"] = $type;
        $_SESSION["image"] = $image;
        $_SESSION["cheater"] = $cheater;
      }

      //Se redirige a la página index.php
      header("Location: ../index.php");
    }

    //Se piden los datos de un usuario en concreto
    function getDatos($id){

      $result = $this->DB->getDatosDB($id);

      return $result;
    }

    //Se piden los datos de todos los usuarios
    function getTodosDatos(){
      $result = $this->DB->getTodosDatosDB();

      return $result;
    }

    //Se comunica que se van a editar los datos de un usuario
    function editarUsuario($id, $name, $description, $type, $image){
      $this->DB->editarUsuarioDB($id, $name, $description, $type, $image);

      /*Si el usuario editado corresponde con la sesión actual, se editan
        los datos de la sesión*/
      if($_SESSION["id"]==$id){
        session_start();
        $_SESSION["name"] = $name;
        $_SESSION["image"] = $image;
      }

      //Se redirige a la página index.php
      header("Location: ../index.php");
    }

    //Se comunica que se va a borrar un usuario concreto
    function borrarUsuario($id){

      $this->DB->borrarUsuarioDB($id);

      //Se redirige a la página listaUsuarios.php
      header("Location: ../listaUsuarios.php");
    }

    function prohibirSubida($id){
      $cheater = 1;
      echo "hola";

      $this->DB->prohibirSubidaDB($id, $cheater);
    }

    //Se cierra una sesión
    function cerrar(){
      session_destroy();
    }
  }

 ?>
