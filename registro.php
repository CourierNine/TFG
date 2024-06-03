<?php
  function validarEditarUsuarioPHP($name, $description, $image){
    $error = "";

    if($name==""){
      $error="Debe introducir un nombre de usuario.";
    }
    else if(strlen($name)>30){
      $error="El nombre de usuario es demasiado largo.";
    }
    else if(strlen($description)>80){
      $error = "La descripción es demasiado larga.";
    }


    return $error;
  }

  function validarUsuarioPHP($name, $email, $password, $description, $image){
    $error = "";

    if($name==""){
      $error="Debe introducir un nombre de usuario.";
    }
    else if(strlen($name)>30){
      $error="El nombre de usuario es demasiado largo.";
    }
    else if($email ==""){
      $error="Debe introducir un email";
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $error="El email debe ser válido.";
    }
    else if(strlen($description)>80){
      $error = "La descripción es demasiado larga.";
    }
    else if($password == ""){
      $error = "Debe introducir una contraseña.";
    }
    else if(!preg_match("(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}", $password)){
      $error= "La contraseña debe contener un número, una letra mayuscula, una letra minuscula y al menos 8 caracteres.";
    }


    return $error;
  }
?>

<!DOCTYPE html>
<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Funciones/connect.php';


  $conn = connect();

  session_start();

  $usuario = new usuario($conn);

  $cabecera = false;
  $error="";
  $edit = false;
  $adminSelected = "";
  $normalSelected = "selected";

  if($_SESSION["type"] == "Admin"){
    $disabled = "";
  }
  else{
    $disabled = "disabled";
  }

  if(isset($_POST["edit"])){
    $id = $_POST["id"];
    $edit = true;
    $owner = false;
    $admin = false;
    $cabecera = true;

    if($id == $_SESSION["id"]){
      $owner = true;
    }

    $datos = $usuario->getDatos($id);
    $name = $datos["name"];
    $description = $datos["description"];

    if($datos["type"] == "Admin"){
      $adminSelected = "selected";
      $normalSelected = "";
    }


    $_SESSION["old_image"] = $datos["image"];
  }

  if(isset($_POST["confirmEdit"])){
    $name = $_POST["name"];
    $id = $_POST["id"];
    $description = $_POST["description"];
    $type = $_POST["type"];
    $edit = true;
    $cabecera = true;

    if($type == "Admin"){
      $adminSelected = "selected";
      $normalSelected = "";
    }


    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $image = $_SESSION["old_image"];
    }

    $error = validarEditarUsuarioPHP($name, $description, $image);

    if($error==""){
      $usuario->editarUsuario($id, $name, $description, $type, $image);
    }
  }

  if(isset($_POST["register"])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $description = $_POST["description"];

    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $path = "./Imagenes/Default_User.webp";
      $image = file_get_contents($path);
    }

    $error = validarUsuarioPHP($name, $email, $password, $description, $image);

    $password = hash('sha256', $_POST["password"]);

    if($error==""){
      $usuario->nuevoUsuario($name, $email, $password, $description, $image);
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
      <form class="formulario" action="" onsubmit="return validarUsuario()" name="registroUsuario" enctype="multipart/form-data" method="post">
        <div>
          <label for="name">Nombre:</label>
          <?php
            echo '<input type="text" name="name" id="name" value="' . $name .'">';
          ?>
        </div>

        <?php
          if(!$edit){
            echo '<div>
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email">
                  </div>';

            echo '<div>
                      <label for="password">Contraseña:</label>
                      <input type="password" name="password" id="password">
                  </div>';
          }
          else{
            echo '<div>
                    <label for="type">Tipo de usuario:</label>
                    <select name="type" id="type" ' . $disabled .'>
                        <option value="Admin" ' . $adminSelected . '>Admin</option>
                        <option value="Normal" ' . $normalSelected .'>Normal</option>
                    </select>
                  </div>';
            echo '<div>
                    <input type="hidden" name="id" value=" ' . $id . '">
                  </div>';
          }
         ?>

         <div>
           <label for="description">Descripción:</label>
           <?php
             echo '<textarea name="description" id="description">'. $description .'</textarea>';
           ?>
         </div>

         <div>
           <label for="image">Imagen de perfil:</label>
           <?php
             echo '<input type="file" name="image" id="image" accept=".jpg, .jpeg, .png, .webp">';
           ?>
         </div>

        <div>
          <?php
            if(!$edit){
              echo '<input type="submit" name="register" value="Registrarse" class="submit">';
            }
            else{
              echo '<input type="submit" name="confirmEdit" value="Confirmar edición" class="submit">';
            }

            if($error!=""){
              echo '<p class="error">' . $error .'</p>';
            }
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
