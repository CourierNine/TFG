<?php

  function validarLoginPHP($email, $password){
    $error = "";

    if($email==""){
      $error = "Debe introducir un email.";
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $error = "El email debe ser válido.";
    }
    else if($password == ""){
      $error = "Debe introducir una contraseña.";
    }

    return $error;
  }

  function cabecera($formulario, $conn){
    session_start();

    $login = false;
    $usuario = new usuario($conn);

    if(isset($_POST["cerrar"])){
      $usuario->cerrar();

      header("Location: ../index.php");
    }

    if(isset($_POST["login"]) && ($error=="")){

      $email = $_POST["email"];
      $password = $_POST["password"];

      $error = validarLoginPHP($email, $password);

      $password = hash('sha256', $password);

      if($error==""){
        $usuario->login($email, $password);
      }
    }

    if(isset($_SESSION["name"])){
      $login = true;
    }

    echo '<h1>Fotografías Excepcionales</h1>

    <menu>
      <ul>
        <li><a href="index.php" class="menuBoton">Página Principal</a></li>
        <li><a href="listaConcursos.php?status=Abierto" class="menuBoton">Concursos Abiertos</a></li>
        <li><a href="listaConcursos.php?status=Cerrado" class="menuBoton">Concursos Cerrados</a></li>
        <li><a href="galeria.php" class="menuBoton">Galería Completa</a></li>';

        if($_SESSION["type"] == "Admin"){
          echo '<li><a href="crearConcurso.php" class="menuBoton">Crear Nuevo Concurso</a></li>
                <li><a href="listaUsuarios.php" class="menuBoton">Lista de usuarios</a></li>';
        }

      echo '</ul>';

      if($formulario){
        if($login==false){
          echo '<form class="formulario" name="login" action="" onsubmit="return validarRegistro()" method="post">
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
            </div>';

            if($error!=""){
              echo '<p class="error">' . $error .'</p>';
            }

    echo   '<div>
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
                        <input type="hidden" name="id" value="' . $_SESSION["id"] . '">
                  </form>
                  <p><em>Bienvenido ' . $_SESSION["name"];
          echo    '.</em></p>
                  <div>
                    <form action="registro.php" method="post">
                      <input type="hidden" name="id" value="' . $_SESSION["id"] . '">
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
