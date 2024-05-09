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

    function contarUsuariosDB(){
      $query = 'SELECT COUNT(*) FROM Usuario';
      $res = $this->conn->query($query);

      $count = $res->fetchColumn();

      return $count;
    }

    function loginDB($email, $password){
      $query = 'SELECT user_id, name, type, image FROM Usuario WHERE email=:email AND password=:password';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':email', $emailReady);
      $prepared->bindParam(':password', $passwordReady);

      $emailReady = $email;
      $passwordReady = $password;

      $prepared->execute();

      $result = $prepared->fetch();

      return $result;
    }

    function getDatosDB($id){
      $query = 'SELECT name, email, description, type, participations, wins, image FROM Usuario WHERE user_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();

      $result = $prepared->fetch();

      return $result;
    }

    function editarUsuarioDB($id, $name, $email, $description, $image){
      $query = 'UPDATE Usuario SET name=:name, email=:email, description=:description, image=:image WHERE user_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);
      $prepared->bindParam(':name', $nameReady);
      $prepared->bindParam(':email', $emailReady);
      $prepared->bindParam(':description', $descriptionReady);
      $prepared->bindParam(':image', $imageReady);

      $idReady = $id;
      $nameReady = $name;
      $emailReady = $email;
      $descriptionReady = $description;
      $imageReady = $image;

      $prepared->execute();
    }
  }



 ?>
