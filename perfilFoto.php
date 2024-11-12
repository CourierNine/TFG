<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Objetos/foto.php';
  include './Objetos/concurso.php';
  include './Objetos/voto.php';
  include './Objetos/comentario.php';
  include './Funciones/connect.php';


  $conn = connect();

  //Se establece una sesión
  session_start();

  $concurso = new concurso($conn);
  $foto = new foto($conn);
  $comentario = new comentario($conn);
  $usuario = new Usuario($conn);
  $voto = new Voto($conn);

  //Se obtiene el identificador de la foto
  if(isset($_POST["id"])){
    $id = $_POST["id"];
  }
  else if(isset($_GET["id"])){
    $id = $_GET["id"];
  }


  //Se obtienen los datos de la foto
  $datos = $foto->getDatos($id);



  //Se obtienen los datos del concurso al que pertenece la foto
  $datosConcurso = $concurso->getDatos($datos["contest"]);


  $alreadyVoted = $voto->comprobarVoto($datosConcurso["contest_id"], $_SESSION["id"]);

  //Se establece un nuevo voto
  if(isset($_POST["newVote"])){

    setcookie($datos["contest"], "alreadyVoted", $end_date);

    $foto->nuevoVoto($_POST["id"]);

    header("Location: galeria.php");
  }

  //Se crea un nuevo comentario
  if(isset($_POST["newComment"])){
    $author = htmlspecialchars($_POST["author"]);
    $photo_id = htmlspecialchars($_POST["id"]);
    $comment = htmlspecialchars($_POST["comment"]);

    //Se obtiene la fecha actual
    $date = getDate();
    $dateString = $date["wday"] . "/" . $date["mon"] . "/" . $date["year"];

    //Se sube el nuevo comentario al sistema
    $comentario->nuevoComentario($author, $photo_id, $dateString, $comment);

    //Se recarga la página
    header("Location: perfilFoto.php?id=" . $photo_id);
  }

  //Se borra el comentario seleccionado
  if(isset($_POST["deleteComment"])){
    $comment_id = $_POST["comment_id"];
    $comentario->borrarComentario($comment_id);
  }

  //Se borra la foto indicada
  if(isset($_POST["deletePhoto"])){
    $photo_id = $_POST["id"];

    //Se borran los comentarios de la foto
    $comentario->borrarComentariosFoto($photo_id);
    $foto->borrarFoto($photo_id);
    header("Location: perfilConcurso.php?id=" . $datos["contest"]);
  }

  //Se obtienen los datos de los comentarios de la foto
  $datosComentarios = $comentario->getComentariosFoto($id);
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
    <main>
      <div class="perfilFoto">
        <div>
        <?php
          //Se muestra la imagen
          echo '<img src="data:image/jpeg;base64,' . base64_encode($datos["image"]) . '"
          class="imagenPerfil"/>';

          //Si se esta en proceso de votación y el usuario aún no ha votado, se permite votar
          if($datosConcurso["status"]=="Voting" && !$alreadyVoted && isset($_SESSION["id"])){
            echo '<form action="" method="post">
                    <input type="submit" name ="newVote" value="+++" class="boton"/>
                    <input type="hidden" name="id" value="' . $datos["photo_id"] . '"/>
                  </form>';
          }
        ?>
        </div>

        <div class="resumenUsuario">
          <?php
            //Se muestra información de la imagen
            echo '<p><em><b>Nombre:</b></em> ' . $datos["name"] . '<p>';

            echo '<p><em><b>Autor:</b></em> ' . $datos["author"] . '<p>';

            echo '<p><em><b>Descripción:</b></em> ' . $datos["description"] . '<p>';

            echo '<p><em><b>Votos:</b></em> ' . $datos["votes"] . '<p>';

            echo '<form action="perfilConcurso.php" method="post">
                    <label for="contest" class="labelEnlace"><b><em>Concurso:</em></b></label>
                    <input name="contest" type="submit" value="' . $datosConcurso["name"] .'"
                    class="botonEnlace">
                    <input type="hidden" name="id" value="' . $datosConcurso["contest_id"] . '">
                  </form>';

            /*Si el usuario es administrador y el concurso no esta cerrado ni
            en proceso de votación se puede editar y borrar la foto*/
            if($_SESSION["type"]=="Admin" && ($datosConcurso["status"]!="Cerrado" || $datosConcurso["status"]!="Voting")){
              echo '<div>
                      <form action="crearFoto.php" method="post">
                        <input type="hidden" name="id" value="' . $id . '">
                        <input type="submit" name="edit" value="Editar Foto">
                      </form>

                    <form action="" method="post">
                      <input type="hidden" name="id" value="' . $id . '">
                      <input type="submit" name="deletePhoto" value="Eliminar Foto">
                    </form>
                  </div>';
            }
           ?>
         </div>
      </div>
        <?php
          //Se recorren todos los comentarios pertenecientes a esta foto
          for($i=0; $i<sizeof($datosComentarios); $i++){
            //Se obtienen los datos del usuario que ha realizado el comentario
            $datosUsuario = $usuario->getDatos($datosComentarios[$i]["author"]);


            echo '<div class="listaUsuarios">
                    <div class="imagenComentario">
                      <form action="perfil.php" method="post">
                        <input type="image" src="data:image/jpeg;base64,'
                         . base64_encode($datosUsuario["image"]) . '" class="imagenPerfil"/>
                        <input type="hidden" name="id" value="' . $datosUsuario["user_id"] . '">
                      </form>
                    </div>
                    <div>
                      <p><em><b>Autor:</b></em> ' . $datosUsuario["name"] . '</p>
                      <p><em><b>Fecha:</b></em> ' . $datosComentarios[$i]["date"] . ' </p>
                    </div>
                    <div>
                      <p><em>' . $datosComentarios[$i]["comment"] . '</em></p>
                    </div>';
                    if($_SESSION["type"] == "Admin" || $_SESSION["id"] == $datosUsuario["user_id"]){
                      echo '<div>
                        <form action="" method="post">
                          <input type="hidden" name="comment_id" value="
                          ' . $datosComentarios[$i]["comment_id"] . '">
                          <input type="hidden" name="id" value="' . $datos["photo_id"] . '">
                          <input type="submit" name="deleteComment" value="Borrar Comentario" class="boton">
                        </form>
                      </div>';
                    }
                echo'</div>';
          }
        ?>
      </div>
      <?php
      if(isset($_SESSION["email"])){
        echo '<div class="nuevoComentario">
          <form action="" method="post" class="formulario">
            <div>
              <textarea name="comment" id="description"></textarea>
            </div>
            <div>
                <input type="hidden" name="author" value="' . $_SESSION["id"] .'">
                <input type="hidden" name="id" value="' . $datos["photo_id"] .'">
                <input type="submit" name="newComment" value="Subir Opinión" class="submit">
            </div>
          </form>
        </div>';
      }
      ?>
    </main>
    <footer>
      <?php
        pie();
      ?>
    </footer>
  </body>
</html>
