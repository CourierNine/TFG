<!DOCTYPE html>
<?php

  include './Funciones/cabecera.php';
  include './Objetos/usuario.php';
  include './Objetos/usuarioDB.php';

  if(isset($_POST["submit"])){
    $usuario = new usuario();
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
        cabecera(False);
      ?>
    </header>
    <main>
      <form class="formulario" action="" method="post">
        <div>
          <label for="name">Nombre:</label>
          <input type="text" name="name" id="name">
        </div>

        <div>
          <label for="email">Email:</label>
          <input type="text" name="email" id="email">
        </div>

        <div>
          <label for="password">Contraseña:</label>
          <input type="password" name="password" id="password">
        </div>

        <div>
          <label for="description">Descripción:</label>
          <textarea name="description" id="description"></textarea>
        </div>

        <div>
          <label for="image">Imagen de perfil:</label>
          <input type="file" name="image" id="image" accept="image/*">
        </div>

        <img src="Imagenes/Default_User.webp">

        <div>
          <input type="submit" name="submit" value="Registrarse" class="submit">
        </div>
      </form>
    </main>
  </body>
</html>
