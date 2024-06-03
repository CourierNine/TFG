<!DOCTYPE html>
<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';

  session_start();

  $usuario = new usuario();

  $cabecera = false;

  if(isset($_POST["edit"])){
    $id = $_POST["id"];
    $owner = false;
    $admin = false;
    $disabled = "disabled";
    $adminSelected = "";
    $normalSelected = "selected";
    $cabecera = true;

    if($id == $_SESSION["id"]){
      $owner = true;
    }

    if($_SESSION["type"] == "Admin"){
      $admin = true;
      $disabled = "";
      $adminSelected = "selected";
      $normalSelected = "";
    }

    $datos = $usuario->getDatos($id);

    $_SESSION["old_image"] = $datos["image"];
  }

  if(isset($_POST["confirmEdit"])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $description = $_POST["description"];

    if($_FILES["image"]["error"]!=4){
      $image = file_get_contents($_FILES["image"]["tmp_name"]);
    }
    else{
      $image = $_SESSION["old_image"];
    }

    $id = $_POST["id"];

    $usuario->editarUsuario($id, $name, $email, $description, $image);
  }

  if(isset($_POST["register"])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = hash('sha256', $_POST["password"]);
    $description = $_POST["description"];
    $image = file_get_contents($_FILES["image"]["tmp_name"]);

    $usuario->nuevoUsuario($name, $email, $password, $description, $image);

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
        cabecera($cabecera, False);
      ?>
    </header>
    <main>
      <form class="formulario" action="" enctype="multipart/form-data" method="post">
        <div>
          <label for="name">Nombre:</label>
          <?php
            echo '<input type="text" name="name" id="name" value="' . $datos["name"] .'">';
          ?>
        </div>

        <div>
          <label for="email">Email:</label>
          <?php
            echo '<input type="text" name="email" id="email" value="' . $datos["email"] .'">';
          ?>
        </div>

        <?php
          if(!isset($_POST["edit"])){
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
             echo '<textarea name="description" id="description">'. $datos["description"] .'</textarea>';
           ?>
         </div>

         <div>
           <label for="image">Imagen de perfil:</label>
           <?php
             echo '<input type="file" name="image" id="image" accept="image/*">';
           ?>
         </div>

        <div>
          <?php
            if(!isset($_POST["edit"])){
              echo '<input type="submit" name="register" value="Registrarse" class="submit">';
            }
            else{
              echo '<input type="submit" name="confirmEdit" value="Confirmar edición" class="submit">';
            }

          ?>

        </div>
      </form>
    </main>
  </body>
</html>
