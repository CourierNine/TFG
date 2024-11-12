<?php
//Función que valida el formulario en back-end
function validarFotoPHP($name, $author, $description){
  $error = "";

  if($name==""){
    $error="Debe introducir un nombre para la foto.";
  }
  else if(strlen($name)>30){
    $error="El nombre de la foto es demasiado largo.";
  }
  else if($author==""){
    $error="Debe introducir un autor para la foto.";
  }
  else if(strlen($author)>30){
    $error="El nombre del autor es demasiado largo.";
  }
  else if($description==""){
    $error = "Debe introducir una descripicón.";
  }
  else if(strlen($description)>80){
    $error = "La descripción es demasiado larga.";
  }

  return $error;
}
?>

<!DOCTYPE html>
<?php
  include './Objetos/usuario.php';
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/foto.php';
  include './Funciones/connect.php';

  $conn = connect();

  //Se establece una sesión
  session_start();


  $cabecera = True;

  $foto = new foto($conn);
  $usuario = new usuario($conn);

  //Si se confirma la subida de una nueva foto al sistema
  if(isset($_POST["confirmNew"])){
    $name = htmlspecialchars($_POST["name"]);
    $author = htmlspecialchars($_POST["author"]);
    $description = htmlspecialchars($_POST["description"]);
    $contest = htmlspecialchars($_POST["contest"]);
    $id_user = htmlspecialchars($_SESSION["id"]);

    //Se valida la foto subida
    $error = validarFotoPHP($name, $author, $description);

    //Si no se sube ninguna foto en el dormulario se genera un error
    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);

      //Se llaman a scripts de python que comprueban si se ha producido plagio
      $plagioIA = shell_exec('python3 ./Funciones/IAImage.py ' . $_FILES["image"]["tmp_name"]);
      $plagioReverse = shell_exec('python3 ./Funciones/reverseImage.py ' . $_FILES["image"]["tmp_name"]);
    }
    else{
      $error = "Debe subir una imagen.";
    }

    //Si no se detecta plagio ni error se sube la foto al sistema
    if($error=="" && $plagioIA==False && $plagioReverse==False){
      $foto->nuevaFoto($name, $author, $description, $contest, $image);
      header("Location: perfilConcurso.php?id=" . $contest);
    }

    /*Si se ha detectado un plagio, se inicia un proceso que impide al
    plagiador volver a subir una foto*/
    if($plagioIA || $plagioReverse){
      $usuario->prohibirSubida($id_user);
      $_SESSION["cheater"] = 1;
      if($plagioIA){
        $plagio = "IA";
      }
      else{
        $plagio = "Google Reverse";
      }

      header("Location: plagio.php?plagio=" . $plagio); //Avisa al usuario de que se le ha pillado
    }
  }

  //Si se va a editar una foto
  if(isset($_POST["edit"])){
    $id = $_POST["id"];

    $datos = $foto->getDatos($id); //Se obtienen los datos de la foto a editar

    //Se guarda la imagen antigua en una variable global
    $_SESSION["old_image"] = $datos["image"];
  }

  //Si se confirma la edición
  if(isset($_POST["confirmEdit"])){
    $id = $_POST["id"];
    $name = htmlspecialchars($_POST["name"]);
    $author = htmlspecialchars($_POST["author"]);
    $description = htmlspecialchars($_POST["description"]);

    //Se validan los nuevos datos de la foto
    $error = validarFotoPHP($name, $author, $description);
    //Si no se ha subido una nueva foto, se utiliza la antigua
    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);

      //Se llaman a scripts de python que comprueban si se ha producido plagio
      $plagioIA = shell_exec('python3 ./Funciones/IAImage.py ' . $_FILES["image"]["tmp_name"]);
      $plagioReverse = shell_exec('python3 ./Funciones/reverseImage.py ' . $_FILES["image"]["tmp_name"]);
    }
    else{
      $error = "Debe subir una imagen.";
    }

    //Si no se ha detectado ni error ni plagio se edita la imagen
    if($error=="" && $plagioIA==False && $plagioReverse==False){
      $foto->editarFoto($id, $name, $author, $description, $image);
    }
  }

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Fotografías Excepcionales</title>
    <link rel="stylesheet" href="Style/style.css">
    <script src="./Funciones/validation.js"></script>
  </head>
  <body>
    <header>
      <?php
        //Se muestra la cabecera de la página
        cabecera($cabecera, $conn);
      ?>
    </header>
    <main>
      <form class="formulario" action="" name="registroFoto" onsubmit="return validarFoto()" enctype="multipart/form-data" method="post">
        <h3>A continuación, publique una nueva foto:</h3>
        <div>
          <label for="name">Nombre:</label>
          <?php
            echo '<input type="text" name="name" id="name" value="' . $datos["name"] .'">';
          ?>
        </div>

        <div>
          <label for="author">Autor:</label>
          <?php
            echo '<input type="text" name="author" id="author" value="' . $datos["author"] .'">';
          ?>

        </div>

         <div>
           <label for="description">Descripción:</label>
           <?php
             echo '<textarea name="description" id="description">'. $datos["description"] .'</textarea>';
           ?>
         </div>

         <div>
           <label for="image">Foto:</label>
           <?php
             echo '<input type="file" name="image" id="image" accept="image/*">';
           ?>
         </div>

         <div>
           <?php
             echo '<input type="hidden" name="contest" id="contest" value="' . htmlspecialchars($_POST["id"]) .'">';
           ?>
         </div>

        <div>
          <?php
            //Si se esta en modo edición se muestra el siguiente botón
            if(isset($_POST["edit"])){
              echo '<input type="hidden" name="id" value="' . htmlspecialchars($_POST["id"]) .'">
                    <input type="submit" name="confirmEdit" value="Editar Foto" class="submit">';
            }
            else{
              echo '<input type="hidden" name="id" value="' . htmlspecialchars($_POST["id"]) .'">
              <input type="submit" name="confirmNew" value="Subir Foto" class="submit">';
            }

            //Si se ha detectado un error en la validación, se muestra el error
            echo '<p class="error">' . $error .'</p>';
          ?>
        </div>
      </form>
    </main>
    <footer>
      <?php
        //Se muestra el pie de la página
        pie();
      ?>
    </footer>
  </body>
</html>
