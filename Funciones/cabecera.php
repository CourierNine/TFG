<?php

  //Función para validar el formulario de autentificación en back-end

  function validarLoginPHP($email, $password){
    $error = "";

    //Se detecta un error si no se ha introducido email
    if($email==""){
      $error = "Debe introducir un email.";
    }
    //Se detecta un error si no se introduce una contraseña
    else if($password == ""){
      $error = "Debe introducir una contraseña.";
    }

    return $error;
  }

  /*Función que gestiona la apariencia y la lógica de la cabecera
    común a toda la web*/

  function cabecera($formulario, $conn){
    //Se declara una sesión
    session_start();

    $login = false;
    $usuario = new usuario($conn);

    //Si se ha pulsado el botón correspondiente se mata la sesión
    if(isset($_POST["cerrar"])){
      $usuario->cerrar();

      header("Location: ../index.php");
    }

    /*Si se quiere autentificar un usuario, se obtienen los datos y se llama
      al metodo pertienente*/
    if(isset($_POST["login"]) && ($error=="")){

      $email = htmlspecialchars($_POST["email"]);
      $password = htmlspecialchars($_POST["password"]);

      /*Se valida el formulario en back-end
        Devuelve el error encontrado o una cadena vacia*/
      $error = validarLoginPHP($email, $password);

      $password = hash('sha256', $password);

      //Si no hay error, se llama a la base de datos
      if($error==""){
        $usuario->login($email, $password);
      }
    }

    //Se comprueba si hay una sesión establecida
    if(isset($_SESSION["name"])){
      $login = true;
    }


    //Titulo de la página
    echo '<h1><em>Fotografías Excepcionales</em></h1>';

    //Menu con enlaces a todos los apartados importantes de la web
    echo '<menu>
      <ul>
        <li><a href="index.php" class="menuBoton">Página Principal</a></li>
        <li><a href="listaConcursos.php?status=Abierto" class="menuBoton">Concursos Abiertos
        </a></li>
        <li><a href="listaConcursos.php?status=Cerrado" class="menuBoton">Concursos Cerrados
        </a></li>
        <li><a href="galeria.php" class="menuBoton">Galería Completa</a></li>';

        //Si el usuario es administrador, se muestran dos enlaces adicionales
        if($_SESSION["type"] == "Admin"){
          echo '<li><a href="crearConcurso.php" class="menuBoton">Crear Nuevo Concurso</a></li>
                <li><a href="listaUsuarios.php" class="menuBoton">Lista de usuarios</a></li>';
        }

      echo '</ul>';

      //Si se quiere mostrar el formulario
      if($formulario){
        /*Si no hay usuario identificado, se muestra el formulario de
          autentificación y el enlace al formulario para registrarse*/
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

            //Si se detecta un error en la validación, se muestra
            if($error!=""){
              echo '<p class="error">' . $error .'</p>';
            }

            //Enlace al formulario para registrarse
    echo   '<div>
              <p>¿No estas registrado?</p>
              <a href="registro.php">¡Clickea aqui!</a>
            </div>
          </form>
        <menu>';
        }
        //Se muestra información del usuario al que pertenece la sesión
        else{

          echo '<div class="miniUsuario">
                  <form action="perfil.php" method="post">
                        <input type="image" src="data:image/jpeg;base64,'
                         . base64_encode($_SESSION["image"]) . '" class="imagenEnlace"/>
                        <input type="hidden" name="id" value="' . $_SESSION["id"] . '">
                  </form>
                  <p><em>Bienvenido ' . $_SESSION["name"];
          echo    '.</em></p>';
                //Botones para editar el usuario y cerrar la sesión
          echo     '<div>
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
