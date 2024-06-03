<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/connect.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Objetos/concurso.php';

  session_start();

  $conn = connect();

  $concurso = new concurso($conn);

  $destacado = $concurso->getDestacado();

  $datos = $concurso->getDatos($destacado);

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
      <div class="concursoDestacado">
        <?php
          echo '<form action="perfilConcurso.php" method="post">
                  <input type="image" src="data:image/jpeg;base64,' . base64_encode($datos["image"]) . '" class="imagenPerfil"/>
                  <input type="hidden" name="id" value="' . $destacado . '">
                </form>';
          echo '<div>';
            echo '<h1><em>' . $datos["name"] . '</em></h1>';
            echo '<p>' . $datos["description"] . '</p>';
            echo '<p><b><em>Hashtag: </em></b>#' . $datos["hashtag"] . '</p>';
            echo '<p><b><em>Fecha de finalización: </em></b>' . $datos["end_date"] . '</p>';
          echo '</div>';
        ?>
      </div>
    </main>
    <footer>
      <?php
        pie();
      ?>
    </footer>
  </body>
</html>
