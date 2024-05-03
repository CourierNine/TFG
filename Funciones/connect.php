 <?php

 function connect(){

   $servername="localhost";
   $username="phpmyadmin";
   $password="ingenieria98";



   try{
     $conn = new PDO("mysql:host=$servername;dbname=ConcursoFotos", $username, $password);

     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch(PDOException $e){
     echo "Fallo al conectar con la base de datos: " . $e->getMessage();
   }

    return $conn;
 }

  ?>
