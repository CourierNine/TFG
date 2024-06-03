<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Objetos/foto.php';
  include './Objetos/concurso.php';
  include './Objetos/comentario.php';
  include './Funciones/connect.php';


  $conn = connect();

  session_start();

  $concurso = new concurso($conn);
  $foto = new foto($conn);
  $comentario = new comentario($conn);
  $usuario = new Usuario($conn);

  if(isset($_POST["id"])){
    $id = $_POST["id"];
  }
  else if(isset($_GET["id"])){
    $id = $_GET["id"];
  }

  $datos = $foto->getDatos($id);

  $datosConcurso = $concurso->getDatos($datos["contest"]);

  $end_date = strtotime($datosConcurso["end_date"]);

  if(isset($_POST["newVote"])){

    setcookie($datos["contest"], "alreadyVoted", $end_date);

    $foto->nuevoVoto($_POST["id"]);

    header("Location: galeria.php");
  }

  if(isset($_POST["newComment"])){
    $author = $_POST["author"];
    $photo_id = $_POST["id"];
    $comment = $_POST["comment"];

    $date = getDate();
    $dateString = $date["wday"] . "/" . $date["mon"] . "/" . $date["year"];

    $comentario->nuevoComentario($author, $photo_id, $dateString, $comment);

    header("Location: perfilFoto.php?id=" . $photo_id);
  }

  if(isset($_POST["deleteComment"])){
    $comment_id = $_POST["comment_id"];
    $comentario->borrarComentario($comment_id);
  }

  if(isset($_POST["deletePhoto"])){
    $photo_id = $_POST["id"];
    $foto->borrarFoto($photo_id);
    header("Location: perfilConcurso.php?id=" . $datos["contest"]);
  }

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
        cabecera(True, $conn);
      ?>
    </header>
    <main>
      <div class="perfilFoto">
        <div>
        <?php
          echo '<img src="data:image/jpeg;base64,' . base64_encode($datos["image"]) . '" class="imagenPerfil"/>';

          if((time() > $datosConcurso["end_date"]) && (time() < ($datosConcurso["end_date"] + 604800)) && !isset($_COOKIE[$datos["contest"]])){
            echo '<form action="" method="post">
                    <input type="submit" name ="newVote" value="+++" class="boton"/>
                    <input type="hidden" name="id" value="' . $datos["photo_id"] . '"/>
                  </form>';
          }
        ?>
        </div>

        <div class="resumenUsuario">
          <?php

            echo '<p><em><b>Nombre:</b></em> ' . $datos["name"] . '<p>';

            echo '<p><em><b>Autor:</b></em> ' . $datos["author"] . '<p>';

            echo '<p><em><b>Descripción:</b></em> ' . $datos["description"] . '<p>';

            echo '<p><em><b>Votos:</b></em> ' . $datos["votes"] . '<p>';

            echo '<form action="perfilConcurso.php" method="post">
                    <label for="contest" class="labelEnlace"><b><em>Concurso:</em></b></label>
                    <input name="contest" type="submit" value="' . $datosConcurso["name"] .'" class="botonEnlace">
                    <input type="hidden" name="id" value="' . $datosConcurso["contest_id"] . '">
                  </form>';

            if($_SESSION["type"]=="Admin" && $datosConcurso["status"]!="Cerrado"){
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
          for($i=0; $i<sizeof($datosComentarios); $i++){
            $datosUsuario = $usuario->getDatos($datosComentarios[$i]["author"]);
            echo '<div class="listaUsuarios">
                    <div>
                      <form action="perfil.php" method="post">
                        <input type="image" src="data:image/jpeg;base64,' . base64_encode($datosUsuario["image"]) . '" class="imagenPerfil"/>
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
                          <input type="hidden" name="comment_id" value="' . $datosComentarios[$i]["comment_id"] . '">
                          <input type="hidden" name="id" value="' . $datos["photo_id"] . '">
                          <input type="submit" name="deleteComment" value="Borrar Comentario" class="boton">
                        </form>
                      </div>';
                    }
                echo'</div>';
          }
        ?>
      </div>
      <div class="nuevoComentario">
        <form action="" method="post" class="formulario">
          <div>
            <textarea name="comment" id="description"></textarea>
          </div>
          <div>
            <?php
              echo '<input type="hidden" name="author" value="' . $_SESSION["id"] .'">';
              echo '<input type="hidden" name="id" value="' . $datos["photo_id"] .'">';
            ?>
              <input type="submit" name="newComment" value="Subir Opinión" class="submit">
          </div>
        </form>
      </div>
    </main>
    <footer>
      <?php
        pie();
      ?>
    </footer>
  </body>
</html>
