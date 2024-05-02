<?php
  class usuarioDB{
    private $conn;

    function __construct(){
      $servername="localhost";
      $username="phpmyadmin";
      $password="ingenieria98";

      $conn = new mysqli($servername, $username, $password);

      if($conn->connect_error){
        exit("Error.");
      }
      else{
        exit("Funciona");
      }
    }
  }

 ?>
