<?php
function validarConcursoPHP($name, $hashtag, $description, $end_date){
  $error = "";

  if($name==""){
    $error="Debe introducir un nombre para el concurso.";
  }
  else if(strlen($name)>30){
    $error="El nombre del concurso es demasiado largo.";
  }
  else if($hashtag==""){
    $error="Debe introducir un hashtag para el concurso.";
  }
  else if(preg_match('(^#[a-zA-Z-а-яА-ЯÀ-ÖØ-öø-ʸ0-9(_)]{1,}$)', $hashtag)){
    $error="El hashtag no debe tene almohadilla.";
  }
  else if(strlen($hashtag)>30){
    $error="El hashtag es demasiado largo.";
  }
  else if($description==""){
    $error = "Debe introducir una descripicón.";
  }
  else if(strlen($description)>80){
    $error = "La descripción es demasiado larga.";
  }
  else if($end_date==""){
    $error = "Debe introducir una fecha de finalización.";
  }

  return $error;
}
?>

<!DOCTYPE html>
<?php
  include './Objetos/usuario.php';
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/concurso.php';
  include './Funciones/connect.php';

  $conn = connect();

  session_start();

  $cabecera = True;
  $edit = false;

  $concurso = new concurso($conn);

  if(isset($_POST["confirmNew"])){
    $name = $_POST["name"];
    $hashtag = $_POST["hashtag"];
    $description = $_POST["description"];
    $end_date = $_POST["end_date"];

    $error = validarConcursoPHP($name, $hashtag, $description, $end_date);

    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $error = "Debe subir una imagen.";
    }

    if($error==""){
      $concurso->nuevoConcurso($name, $hashtag, $description, $end_date, $image);
    }
  }

  if(isset($_POST["edit"])){
    $id = $_POST["id"];
    $edit = true;

    $datos = $concurso->getDatos($id);
    $name = $datos["name"];
    $end_date = $datos["end_date"];
    $hashtag = $datos["hashtag"];
    $description = $datos["description"];


    $_SESSION["old_image"] = $datos["image"];
  }

  if(isset($_POST["confirmEdit"])){
    $id = $_POST["id"];
    $name = $_POST["name"];
    $end_date = $_POST["end_date"];
    $hashtag = $_POST["hashtag"];
    $description = $_POST["description"];
    $edit = true;

    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $image = $_SESSION["old_image"];
    }

    $error = validarConcursoPHP($name, $hashtag, $description, $end_date);

    if($error == ""){
      $concurso->editarConcurso($id, $name, $end_date, $hashtag, $description, $image);
    }

  }

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Fotografías Excepcionales</title>
    <link rel="stylesheet" href="Style/style.css">
  </head>
  <body>
    <header>
      <?php
        cabecera($cabecera, $conn);
      ?>
    </header>
    <main>
      <form class="formulario" onsubmit="return validarConcurso()" name="registroConcurso" action="" enctype="multipart/form-data" method="post">
        <div>
          <label for="name">Nombre:</label>
          <?php
            echo '<input type="text" name="name" id="name" value="' . $name .'">';
          ?>
        </div>

        <div>
          <label for="hashtag">Hashtag:</label>
          <?php
            echo '<input type="text" name="hashtag" id="hashtag" value="' . $hashtag .'">';
          ?>
        </div>

         <div>
           <label for="description">Descripción:</label>
           <?php
             echo '<textarea name="description" id="description">'. $description .'</textarea>';
           ?>
         </div>

         <div>
           <label for="end_date">Fecha de cierre:</label>
           <?php
             echo '<input type="date" name="end_date" id="end_date" value="' . $end_date .'">';
           ?>
         </div>

         <div>
           <label for="image">Imagen del concurso:</label>
           <?php
             echo '<input type="file" name="image" id="image" accept="image/*">';
           ?>
         </div>

        <div>
          <?php
          echo '<input type="hidden" name="id" value="'. $datos["contest_id"] .'">';

          if($edit){
            echo '<input type="submit" name="confirmEdit" value="Confirmar Edición" class="submit">';
          }
          else{
            echo '<input type="submit" name="confirmNew" value="Crear Concurso" class="submit">';
          }

            echo '<p class="error">' . $error . '</p>';
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
