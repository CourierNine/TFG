<?php
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

  session_start();

  $cabecera = True;

  $foto = new foto($conn);

  if(isset($_POST["confirmNew"])){
    $name = $_POST["name"];
    $author = $_POST["author"];
    $description = $_POST["description"];
    $contest = $_POST["contest"];

    $error = validarFotoPHP($name, $author, $description);

    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $error = "Debe subir una imagen.";
    }

    if($error==""){
      $foto->nuevaFoto($name, $author, $description, $contest, $image);
    }
  }

  if(isset($_POST["edit"])){
    $id = $_POST["id"];

    $datos = $foto->getDatos($id);

    $_SESSION["old_image"] = $datos["image"];
  }

  if(isset($_POST["confirmEdit"])){
    $id = $_POST["contest"];
    $name = $_POST["name"];
    $author = $_POST["author"];
    $description = $_POST["description"];

    $error = validarFotoPHP($name, $author, $description);

    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $error = "Debe subir una imagen.";
    }

    if($error==""){
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
        cabecera($cabecera, $conn);
      ?>
    </header>
    <main>
      <form class="formulario" action="" name="registroFoto" onsubmit="return validarFoto()" enctype="multipart/form-data" method="post">
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
             echo '<input type="hidden" name="contest" id="contest" value="' . $_POST["id"] .'">';
           ?>
         </div>

        <div>
          <?php
            if(isset($_POST["edit"])){
              echo '<input type="submit" name="confirmEdit" value="Editar Foto" class="submit">';
            }
            else{
              echo '<input type="submit" name="confirmNew" value="Subir Foto" class="submit">';
            }

            echo '<p class="error">' . $error .'</p>';
          ?>
        </div>
      </form>
    </main>
    <footer>
      <?php
        pie();
      ?>
    </footer>
  </body>
</html>
