<?php
//Función que valida el formulario de creación y edición de concursos
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

  //Se establece una conexión con la base de datos
  $conn = connect();

  //Se inicializa una sesión
  session_start();

  $cabecera = True;
  $edit = false;

  //Se crea un objeto de la clase concurso
  $concurso = new concurso($conn);

  //Si se confirma que se va a crear un nuevo concurso
  if(isset($_POST["confirmNew"])){
    $name = htmlspecialchars($_POST["name"]);
    $hashtag = htmlspecialchars($_POST["hashtag"]);
    $description = htmlspecialchars($_POST["description"]);
    $end_date = htmlspecialchars($_POST["end_date"]);

    //Se valida el formulario
    $error = validarConcursoPHP($name, $hashtag, $description, $end_date);

    //Si el usuario no ha subido una imagen se impide la creación del concurso
    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $error = "Debe subir una imagen.";
    }

    //Si no se detecta ningún error, se crea un nuevo concurso
    if($error==""){
      $concurso->nuevoConcurso($name, $hashtag, $description, $end_date, $image);
    }
  }

  //Si se entra en modo editar
  if(isset($_POST["edit"])){
    $edit = true;
    $id = $_POST["contest_id"];
    //Se obtienen los datos del concurso a editar
    $datos = $concurso->getDatos($id);
    $name = $datos["name"];
    $end_date = $datos["end_date"];
    $hashtag = $datos["hashtag"];
    $description = $datos["description"];

    //Se guarda la imagen original en una variable global
    $_SESSION["old_image"] = $datos["image"];
  }

  //Si se confirma la edición de un concurso
  if(isset($_POST["confirmEdit"])){
    $id = htmlspecialchars($_POST["id"]);
    $name = htmlspecialchars($_POST["name"]);
    $end_date = htmlspecialchars($_POST["end_date"]);
    $hashtag = htmlspecialchars($_POST["hashtag"]);
    $description = htmlspecialchars($_POST["description"]);
    $edit = true;

    //Si el usuario no sube una nueva imagen, se utiliza la antigua
    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $image = $_SESSION["old_image"];
    }

    //Se valida el formulario
    $error = validarConcursoPHP($name, $hashtag, $description, $end_date);

    //Si no se detecta un error, se edita el concurso
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
        //Se muestra la cabecera de la web
        cabecera($cabecera, $conn);
      ?>
    </header>
    <main>
      <form class="formulario" onsubmit="return validarConcurso()" name="registroConcurso" action="" enctype="multipart/form-data" method="post">
        <h3>A continuación, proceda con la creación de un nuevo concurso:</h3>
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

          //Se muestra el boton de crear o editar, dependiendo del modo
          if($edit){
            echo '<input type="submit" name="confirmEdit" value="Confirmar Edición" class="submit">';
          }
          else{
            echo '<input type="submit" name="confirmNew" value="Crear Concurso" class="submit">';
          }
            //Si se detecta un error, se muestra aquí
            echo '<p class="error">' . $error . '</p>';
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
