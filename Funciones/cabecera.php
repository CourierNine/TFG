
<?php



  function cabecera($formulario){
    session_start();

    $login = false;

    $usuario = new usuario();

    if(isset($_POST["cerrar"])){
      $usuario->cerrar();

      header("Location: ../index.php");
    }

    if(isset($_POST["login"])){

      $email = $_POST["email"];
      $password = hash('sha256', $_POST["password"]);

      $usuario->login($email, $password);
    }

    if(isset($_SESSION["name"])){
      $login = true;
    }

    echo '<h1>Fotografías Excepcionales</h1>

    <menu>
      <ul>
        <li><a href="index.php" class="menuBoton">Página Principal</a></li>
        <li><a href="concursos.html" class="menuBoton">Concursos Abiertos</a></li>
        <li><a href="concursos.html" class="menuBoton">Concursos Cerrados</a></li>
        <li><a href="galeria.html" class="menuBoton">Galería Completa</a></li>
        <li><a href="admin.php" class="menuBoton">Administración y Actividad</a></li>
      </ul>';

      if($formulario){
        if($login==false){
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
              <input type="submit" name="login" value="Login" class="submit">
            </div>

            <div>
              <p>¿No estas registrado?</p>
              <a href="registro.php">¡Clickea aqui!</a>
            </div>
          </form>
        <menu>';
        }
        else{

          echo '<div class="miniUsuario">
                  <form action="perfil.php" method="post">
                        <input type="image" src="data:image/jpeg;base64,' . base64_encode($_SESSION["image"]) . '" class="imagenEnlace"/>
                        <input type="hidden" name="id" value=" ' . $_SESSION["id"] . '">
                  </form>
                  <p><em>Bienvenido ' . $_SESSION["name"];
          echo    '.</em></p>
                  <div>
                    <form action="registro.php" method="post">
                      <input type="hidden" name="id" value=" ' . $_SESSION["id"] . '">
                      <input type="submit" name="edit" value="Editar" class="boton">
                    </form>

                    <form action="" method="post">
                      <input type="submit" name="cerrar" value="Cerrar Sesión" class="boton">
                    </form>
                  </div>
                </div>';
        }

      }
  }

 ?>
