<!DOCTYPE html>
<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Funciones/connect.php';

  //Se establece una conexión
  $conn = connect();

  //Se crea un objeto del tipo usuario
  $usuario = new usuario($conn);

  //Si se pula el botón de borrado asociado a un usuario
  if(isset($_POST["delete"])){
    $id = $_POST["id"];

    $usuario->borrarUsuario($id);
  }

  //Se obtiene el número de usuarios presentes en el sistema
  $count = $usuario->contarUsuarios();
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
        //Se muestra la cabecera de la web
        cabecera(True, $conn);
      ?>
    </header>
    <main>
      <?php

        //Se obtienen los datos de todos los usuarios
        $datos = $usuario->getTodosDatos();

        //Itera sobre los datos obtenidos y muestra la información del usuario
        //Cada usuario tiene asociado dos botones: editar y borrar
        for ($i = 0; $i < $count; $i++){
          echo '<div class="listaUsuarios">
                  <div>
                  <form action="perfil.php" method="post">
                        <input type="image" src="data:image/jpeg;base64,'
                        . base64_encode($datos[$i]["image"]) . '" class="imagenPerfil"/>
                        <input type="hidden" name="id" value="' . $datos[$i]["user_id"] . '">
                  </form>
                  </div>
                  <div>
                    <p><em><b>Nombre:</b></em> ' . $datos[$i]["name"] . '</p>
                    <p><em><b>Rol:</b></em> ' . $datos[$i]["type"] . ' </p>
                  </div>
                  <div>
                    <p><em><b>Email:</b></em></p>
                    <p>' . $datos[$i]["email"] . '</p>
                  </div>
                  <div>
                    <form action="registro.php" method="post">
                      <input type="hidden" name="id" value="' . $datos[$i]["user_id"] . '">
                      <input type="submit" name="edit" value="Editar Usuario" class="boton">
                    </form>

                    <form action="" method="post">
                      <input type="hidden" name="id" value="' . $datos[$i]["user_id"] . '">
                      <input type="submit" name="delete" value="Borrar Usuario" class="boton">
                    </form>
                  </div>
                </div>';
        }
      ?>
    </main>
    <footer>
      <?php
        //Se muestra el pie de la web
        pie();
      ?>
    </footer>
  </body>
</html>
