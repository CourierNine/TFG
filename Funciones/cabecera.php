
<?php

  function cabecera($formulario){
    echo '<h1>Fotografías Excepcionales</h1>

    <menu>
      <ul>
        <li><a href="index.php" class="menuBoton">Página Principal</a></li>
        <li><a href="concursos.html" class="menuBoton">Concursos Abiertos</a></li>
        <li><a href="concursos.html" class="menuBoton">Concursos Cerrados</a></li>
        <li><a href="galeria.html" class="menuBoton">Galería Completa</a></li>
        <li><a href="administracion.html" class="menuBoton">Administración y Actividad</a></li>
      </ul>';

      if($formulario){
        echo '<form class="formulario" action="" method="post">
          <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
          </div>

          <div>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password">
          </div>

          <div>
            <input type="submit" name="submit" value="Login" class="submit">
          </div>

          <div>
            <p>¿No estas registrado?</p>
            <a href="registro.php">¡Clickea aqui!</a>
          </div>
        </form>
      <menu>';
      }
  }

 ?>
