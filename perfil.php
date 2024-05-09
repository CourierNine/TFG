<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';

  session_start();

  $usuario = new usuario();

  $id = $_POST["id"];

  $datos = $usuario->getDatos($id);
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
        cabecera(True);
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
