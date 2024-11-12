<?php

include './Funciones/cabecera.php';
include './Funciones/connect.php';
include './Funciones/pie.php';
include './Objetos/usuario.php';
include './Objetos/concurso.php';

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Fotografías Excepcionales</title>
    <link rel="stylesheet" href="Style/style.css" type="text/css">
  </head>
  <body>
    <header>
      <?php
        //Se muestra la cabecera de la web
        cabecera(true, $conn);
      ?>
    </header>
    <main>
      <form class="formulario" name="login" action="" method="post">
      <fieldset>
        <legend>Formulario de contacto</legend>
        <div>
          <label for="email">Email:</label>
          <input type="text" name="email" id="email">
        </div>

        <div>
          <label for="description">Incidencia:</label>
          <textarea name="description" id="description"></textarea>
        </div>

        <div>
          <input type="submit" name="login" value="Login" class="submit">
        </div>
      </fieldset>
    </main>
    <footer>
      <?php
        //Se muestra el pie de la página
        pie();
      ?>
    </footer>
  </body>
</html>
