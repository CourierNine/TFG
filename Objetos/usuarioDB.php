<?php
  include './Funciones/connect.php';

  class usuarioDB{
    private $conn;

    function __construct(){
      $this->conn = connect();
    }

    function nuevoUsuarioDB($name, $email, $password, $description, $type, $participations, $wins, $image){
      try{
        $query = 'INSERT INTO Usuario (name, password, email , description, type, participations, wins, image)
         VALUES(:name, :password, :email, :description, :type, :participations, :wins, :image)';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':name', $nameReady);
        $prepared->bindParam(':email', $emailReady);
        $prepared->bindParam(':password', $passwordReady);
        $prepared->bindParam(':description', $descriptionReady);
        $prepared->bindParam(':type', $typeReady);
        $prepared->bindParam(':participations', $participationsReady);
        $prepared->bindParam(':wins', $winsReady);
        $prepared->bindParam(':image', $imageReady);

        $nameReady = $name;
        $emailReady = $email;
        $passwordReady = $password;
        $descriptionReady = $description;
        $typeReady = $type;
        $participationsReady = $participations;
        $winsReady = $wins;
        $imageReady = $image;

        $prepared->execute();

      } catch(PDOException $e){
        echo "Error al crear un nuevo usuario: " . $e->getMessage();
      }
    }

    function contarUsuarios(){
      $query = 'SELECT COUNT(*) FROM Usuario';
      $res = $this->conn->query($query);

      $count = $res->fetchColumn();

      return $count;
    }
  }

 ?>
