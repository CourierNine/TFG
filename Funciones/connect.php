 <?php

 function connect(){
   $conn;

   //Parametros de conexión
   $servername="localhost";
   $username="phpmyadmin";
   $password="ingenieria98";

   try{
     //Se crea un objeto PDO que contiene la conexión a la base de datos
     $conn= new PDO("mysql:host=$servername;dbname=ConcursoFotos", $username, $password);

     //Facilita la depuración mediante el uso de excepciones
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch(PDOException $e){
     //Si no se puede conectar, salta este error
     echo "Fallo al conectar con la base de datos: " . $e->getMessage();
   }



   return $conn;

 }

  ?>
