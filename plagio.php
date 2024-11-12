<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/connect.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Objetos/concurso.php';

  session_start();

  //Se establece una conexón con la base de datos
  $conn = connect();

  //Se crea un objeto del tipo Concurso
  $usuario = new usuario($conn);


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
    <main class="containerPlagio">
      <h1 class="plagio"><em>¡Se ha detectado un plagio!</em></h1>
      <p class="parrafoPlagio"><b>Nuestro sistema ha detectado que ha intentado subir una foto plagiada por
        <?php echo htmlspecialchars($_GET["plagio"]); ?>
      </p>
         A partir de ahora no podrá subir fotos a ningún concurso. Por favor,
         contacte con un administrador si creo que se ha producido un error.</b></p>
    </main>
    <footer>
      <?php
        //Se muestra el pie de la web
        pie();
      ?>
    </footer>
  </body>
</html>
