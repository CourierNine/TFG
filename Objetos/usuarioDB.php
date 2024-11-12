<?php
  //Clase que gestiona las operaciones sobre la tabla Usuario
  class usuarioDB{
    private $conn;

    //Se establece una conexión con la base de datos
    function __construct($conn){
      $this->conn = $conn;
    }

    //Se añade una nueva tupla a la tabla, utilizando los datos provistos
    function nuevoUsuarioDB($name, $email, $password, $description, $type, $participations, $wins, $image, $cheater){
      try{
        $query = 'INSERT INTO Usuario (name, password, email, description, type, participations, wins, image, cheater)
         VALUES(:name, :password, :email, :description, :type, :participations, :wins, :image, :cheater)';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':name', $nameReady);
        $prepared->bindParam(':email', $emailReady);
        $prepared->bindParam(':password', $passwordReady);
        $prepared->bindParam(':description', $descriptionReady);
        $prepared->bindParam(':type', $typeReady);
        $prepared->bindParam(':participations', $participationsReady);
        $prepared->bindParam(':wins', $winsReady);
        $prepared->bindParam(':image', $imageReady);
        $prepared->bindParam(':cheater', $cheaterReady);

        $nameReady = $name;
        $emailReady = $email;
        $passwordReady = $password;
        $descriptionReady = $description;
        $typeReady = $type;
        $participationsReady = $participations;
        $winsReady = $wins;
        $imageReady = $image;
        $cheaterReady = $cheater;

        $prepared->execute();

      } catch(PDOException $e){
        echo "Error al crear un nuevo usuario: " . $e->getMessage();
      }
    }

    //Se obtiene el número de tuplas en la tabla
    function contarUsuariosDB(){
      $query = 'SELECT COUNT(*) FROM Usuario';
      $res = $this->conn->query($query);

      $count = $res->fetchColumn();

      return $count;
    }

    //Se autentifica un usuario identificado por los parametros provistos
    function loginDB($email, $password){
      $query = 'SELECT * FROM Usuario WHERE email=:email AND password=:password';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':email', $emailReady);
      $prepared->bindParam(':password', $passwordReady);

      $emailReady = $email;
      $passwordReady = $password;

      $prepared->execute();

      $result = $prepared->fetch();

      return $result;
    }

    //Se obtienen los datos de la tupla identificada por el parametro provisto
    function getDatosDB($id){
      $query = 'SELECT * FROM Usuario WHERE user_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':id', $idReady);

      $idReady = $id;

      $prepared->execute();

      $result = $prepared->fetch();

      return $result;
    }

    //Se obtienen los datos de todas las tuplas
    function getTodosDatosDB(){
      $query = 'SELECT * FROM Usuario';

      $prepared = $this->conn->prepare($query);

      $prepared->execute();

      $result = $prepared->fetchAll();

      return $result;
    }

    //Se editan los datos de una tupla identificada por los parametros provistos
    function editarUsuarioDB($id, $name, $description, $type, $image){
      $query = 'UPDATE Usuario SET name=:name, description=:description, type=:type, image=:image WHERE user_id=:id';

      $prepared = $this->conn->prepare($query);

      $prepared->bindParam(':name', $nameReady);
      $prepared->bindParam(':id', $idReady);
      $prepared->bindParam(':type', $typeReady);
      $prepared->bindParam(':description', $descriptionReady);
      $prepared->bindParam(':image', $imageReady);

      $nameReady = $name;
      $idReady = $id;
      $typeReady = $type;
      $descriptionReady = $description;
      $imageReady = $image;

      $prepared->execute();
    }

    //Se borra una tupla identificada por el parametro provisto
    function borrarUsuarioDB($id){
      try{
        $query = 'DELETE FROM Usuario WHERE user_id=:id';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':id', $idReady);

        $idReady = $id;

        $prepared->execute();
      }
      catch(PDOException $e){
        echo "Error al crear un nuevo usuario: " . $e->getMessage();
      }
    }

    //Se establece a un usuario como tramposo
    function prohibirSubidaDB($id, $cheater){
      try{
        $query = 'UPDATE Usuario SET cheater=:cheater WHERE user_id=:id ';

        $prepared = $this->conn->prepare($query);

        $prepared->bindParam(':id', $idReady);
        $prepared->bindParam(':cheater', $cheaterReady);

        $idReady = $id;
        $cheaterReady = $cheater;

        $prepared->execute();
      }
      catch(PDOException $e){
        echo "Error al crear un nuevo usuario: " . $e->getMessage();
      }
    }
  }



 ?>
