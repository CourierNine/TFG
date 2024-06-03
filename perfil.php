<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Funciones/connect.php';

  $conn = connect();

  session_start();

  $usuario = new usuario($conn);

  $id= $_POST["id"];

  $datos = $usuario->getDatos($id);

  if(isset($_POST["delete"])){
    $id = $_POST["id"];

    $usuario->borrarUsuario($id);
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
        cabecera(True, $conn);
      ?>
    </header>
    <main>
      <div class="perfil">
        <?php
          echo '<img src="data:image/jpeg;base64,' . base64_encode($datos["image"]) . '" class="imagenPerfil"/>';
        ?>

        <div class="resumenUsuario">
          <?php
            echo '<p><em><b>Nombre:</b></em> ' . $datos["name"] . '<p>';

            echo '<p><em><b>Email:</b></em> ' . $datos["email"] . '<p>';

            echo '<p><em><b>Rol:</b></em> ' . $datos["type"] . '<p>';

            echo '<p><em><b>Descripción:</b></em> ' . $datos["description"] . '<p>';

            echo '<p><em><b>Nº Concursos:</b></em> ' . $datos["participations"] . '<p>';

            echo '<p><em><b>Nº Concursos Ganados:</b></em> ' . $datos["participations"] . '<p>';

            if($_SESSION["type"]=="Admin" || $_SESSION["id"] == $datos["user_id"]){
              echo '<div>
                      <form action="registro.php" method="post">
                        <input type="hidden" name="id" value="' . $datos["user_id"] . '">
                        <input type="submit" name="edit" value="Editar Usuario">
                      </form>

                    <form action="" method="post">
                      <input type="hidden" name="id" value="' . $datos["user_id"] . '">
                      <input type="submit" name="delete" value="Borrar Usuario">
                    </form>
                  </div>';
            }
           ?>
         </div>
      </div>
    </main>
    <footer>
      <?php
        pie();
      ?>
    </footer>
  </body>
</html>
