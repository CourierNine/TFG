<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Funciones/connect.php';

  //Establece conexión con la BD
  $conn = connect();

  //Se declara una sesión
  session_start();

  $usuario = new usuario($conn);  //Se crea una instancia del objeto Usuario

  $id= $_POST["id"];  //Identificador de usuario

  //Se obtienen los datos del usuario correspondiente al identificador
  $datos = $usuario->getDatos($id);

  //Si se pulsa el botón de borrar
  if(isset($_POST["delete"])){

    //Se elimina el usuario correspondiente al identificador
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
        //Cabecera de la web
        cabecera(True, $conn);
      ?>
    </header>
    <main>
      <div class="perfil">
        <?php
          //Se codifica y muestra la imagen
          echo '<img src="data:image/jpeg;base64,'
            . base64_encode($datos["image"]) . '" class="imagenPerfil"/>';
        ?>

        <div class="resumenUsuario">
          <?php
            //Se muestran el resto de datos
            echo '<p><em><b>Nombre:</b></em> ' . $datos["name"] . '<p>';

            echo '<p><em><b>Email:</b></em> ' . $datos["email"] . '<p>';

            echo '<p><em><b>Rol:</b></em> ' . $datos["type"] . '<p>';

            echo '<p><em><b>Descripción:</b></em> ' . $datos["description"] . '<p>';

            echo '<p><em><b>Nº Concursos:</b></em> ' . $datos["participations"] . '<p>';

            echo '<p><em><b>Nº Concursos Ganados:</b></em> ' . $datos["wins"] . '<p>';

            /*Si se detecta que el usuario es el propietario del perfil o administrador
              se desbloquean botones para borrar o editar el usuario*/
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
      //Pie de la web
        pie();
      ?>
    </footer>
  </body>
</html>
