<!DOCTYPE html>
<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Funciones/connect.php';

  $conn = connect();


  $usuario = new usuario($conn);

  if(isset($_POST["delete"])){
    $id = $_POST["id"];

    $usuario->borrarUsuario($id);
  }


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
        cabecera(True, $conn);
      ?>
    </header>
    <main>
      <?php

        $datos = $usuario->getTodosDatos();

        for ($i = 0; $i < $count; $i++){

          echo '<div class="listaUsuarios">
                  <div>
                  <form action="perfil.php" method="post">
                        <input type="image" src="data:image/jpeg;base64,' . base64_encode($datos[$i]["image"]) . '" class="imagenPerfil"/>
                        <input type="hidden" name="id" value="' . $datos[$i]["user_id"] . '">
                  </form>
                  </div>
                  <div>
                    <p><em><b>Nombre:</b></em> ' . $datos[$i]["name"] . '</p>
                    <p><em><b>Rol:</b></em> ' . $datos[$i]["type"] . ' </p>
                  </div>
                  <div>
                    <p><em><b>Nº Concursos:</b></em> ' . $datos[$i]["participations"] . '</p>
                    <p><em><b>Nº Ganados:</b></em> ' . $datos[$i]["wins"] . ' </p>
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
        pie();
      ?>
    </footer>
  </body>
</html>
