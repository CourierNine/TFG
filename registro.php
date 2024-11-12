<?php
  //Función que valida el formulario cuando se va a editar un usuario
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

  //Función que valida el formulario cuando se va a registrar un usuario
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


    return $error;
  }
?>

<!DOCTYPE html>
<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Funciones/connect.php';

  //Se establece una conexión con la base de datos
  $conn = connect();

  session_start();  //Se inicializa la sesión

  $usuario = new usuario($conn);  //Se crea un objeto usuario

  $cabecera = false;
  $error="";
  $edit = false;                //Se presume que se entra para registrar un usuario
  $adminSelected = "";          //Se presume que el usuario no es admin
  $normalSelected = "selected";

  //Si el usuario es administrador, se desbloquea la opción para cambiar el tipo de
  //usuario
  if($_SESSION["type"] == "Admin"){
    $disabled = "";
  }
  else{
    $disabled = "disabled";
  }

  /*Si se entra en modo edición se obtienen los datos del usuario que se va a
   editar y se muestran en el formulario*/
  if(isset($_POST["edit"])){
    $id = htmlspecialchars($_POST["id"]);
    $edit = true;
    $admin = false;
    $cabecera = true;

    $datos = $usuario->getDatos($id);
    $name = $datos["name"];
    $description = $datos["description"];

    //Se establece la opción de tipo de usuario por defecto
    if($datos["type"] == "Admin"){
      $adminSelected = "selected";
      $normalSelected = "";
    }

    //Se guardan los datos de la antigua imagen en una variable global
    $_SESSION["old_image"] = $datos["image"];
  }

  //Una vez se confirme la edición del usuario
  if(isset($_POST["confirmEdit"])){
    //Se obtienen los datos del formulario
    $name = htmlspecialchars($_POST["name"]);
    $id = htmlspecialchars($_POST["id"]);
    $description = htmlspecialchars($_POST["description"]);
    $type = htmlspecialchars($_POST["type"]);
    $edit = true;       //Se indica que se muestre el formulario de edición
    $cabecera = true;   //En edición, se muestran los datos del usuario actual

    if($type == "Admin"){
      $adminSelected = "selected";
      $normalSelected = "";
    }

    //Si no se ha introducido una nueva imagen, se utiliza la antigua
    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $image = $_SESSION["old_image"];
    }

    /*Si se obtiene un error a la hora de validar el formulario, este se obtiene
      y se muestra*/
    $error = validarEditarUsuarioPHP($name, $description, $image);

    //Si no hay ningun error, se edita el usuario con los datos indicados
    if($error==""){
      $usuario->editarUsuario($id, $name, $description, $type, $image);
    }
  }

  //Si se confirma el registro de un nuevo usuario
  if(isset($_POST["register"])){
    //Se obtienen los datos del formulario
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $description = htmlspecialchars($_POST["description"]);

    //Si no se ha introducido una nueva imagen, se utiliza la imagen por defecto
    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $path = "./Imagenes/Default_User.webp";
      $image = file_get_contents($path);
    }

    /*Si se obtiene un error a la hora de validar el formulario, este se obtiene
      y se muestra*/
    $error = validarUsuarioPHP($name, $email, $password, $description, $image);

    //Se cifra la contraseña mediante hash
    $password = hash('sha256', $_POST["password"]);

    //Si no se detecta ningún error, se introduce un nuevo usuario en el sistema
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
  </head>
  <body>
    <header>
      <?php
        //Se muestra la cabecera de la web
        cabecera($cabecera, $conn);
      ?>
    </header>
    <main>

      <form class="formulario" action="" onsubmit="return validarUsuario()" name="registroUsuario" enctype="multipart/form-data" method="post">
        <h3>A continuación, proceda con su registro en en la web:</h3>
        <div>
          <label for="name">Nombre:</label>
          <?php
            echo '<input type="text" name="name" id="name" value="' . $name .'">';
          ?>
        </div>

        <?php
          /*Si se va a registrar a un usuario se muestran los siguientes campos
            del formulario*/
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
          //Si se va a editar un usuario se muestran los siguientes campos
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
            //Dependiendo de si se va a editar o no, se muestra un botón u otro
            if(!$edit){
              echo '<input type="submit" name="register" value="Registrarse" class="submit">';
            }
            else{
              echo '<input type="submit" name="confirmEdit" value="Confirmar edición" class="submit">';
            }

            //Si hay un error en la validación, se muestra aquí
            if($error!=""){
              echo '<p class="error">' . $error .'</p>';
            }
          ?>
      </form>
    </div>
    </main>
    <footer>
      <?php
        //Se muestra el pie de la web
        pie();
      ?>
    </footer>
  </body>
</html>
