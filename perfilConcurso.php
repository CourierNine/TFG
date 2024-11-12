<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/concurso.php';
  include './Objetos/voto.php';
  include './Objetos/usuario.php';
  include './Objetos/comentario.php';
  include './Objetos/foto.php';
  include './Funciones/connect.php';

  $conn = connect();

  //Se establece una sesión
  session_start();

  $concurso = new concurso($conn);
  $foto = new foto($conn);
  $voto = new voto($conn);
  $comentario = new comentario($conn);

  //Se obtiene el identificador del concurso
  if(isset($_POST["id"])){
    $id = $_POST["id"];
  }
  else{
    $id = $_GET["id"];
  }

  //Se borra el concurso
  if(isset($_POST["delete"])){
    $listaFotos = $foto->getFotosConcurso($_POST["contest_id"]);

    /*Para borrar un concurso se deben borrar tambíen sus fotos y
    los comentarios asociados a estas*/
    for($i=0; $i<sizeof($listaFotos); $i++){
      $comentario->borrarComentariosFoto($listaFotos[$i]["photo_id"]);
      $foto->borrarFoto($listaFotos[$i]["photo_id"]);
    }

    $concurso->borrarConcurso($_POST["contest_id"]);
  }

  $datos = $concurso->getDatos($id);

  //Se obtienen los datos de las fotos pertenecientes al concurso
  $datosFoto = $foto->getFotosConcurso($id);

  //Se comprueba si el usuario ya ha votado en este concurso
  $alreadyVoted = $voto->comprobarVoto($datos["contest_id"], $_SESSION["id"]);

  //Si se ha realizado un nuevo voto
  if(isset($_POST["newVote"])){
    $contest = htmlspecialchars($_POST["contest_id"]);
    $voter = htmlspecialchars($_SESSION["id"]);

    //Se establece que el usuario ha votado en este concurso
    $voto->asignarVoto($contest, $voter);

    //Se suma un nuevo voto a la foto
    $foto->nuevoVoto($_POST["id"]);

    //Se redirige a la lista de concursos
    header("Location: listaConcursos.php?status=Abierto");
  }

  //Finalizar el proceso de votación
  if(isset($_POST["endVoting"])){
    $ranking = [];

    //Se recorren los datos de todas las fotos pertenecientes al concurso
    for($i=0; $i<sizeof($datosFoto); $i++){
      $ranking[$i] = 1; //Se le da la primera posición

      //Se vuelven a iterar sobre los datos
      for($j=0; $j<sizeof($datosFoto); $j++){
        /*Si se encuentra una foto con más votos que la foto original
          se aumenta su posición*/
        if($datosFoto[$i]["votes"]<$datosFoto[$j]["votes"]
            && $datosFoto[$i]["photo_id"]!=$datosFoto[$j]["photo_id"]){
          $ranking[$i] = $ranking[$i] + 1;
        }
      }

      $datosFoto[$i]["ranking"] = $ranking[$i];
      $foto->setRanking($datosFoto[$i]["photo_id"], $ranking[$i]);

      //Se establece el ganador
      if($ranking[$i]==1){
        $concurso->setGanador($id, $datosFoto[$i]["author"]);
      }
    }


    //Se cierra el concurso
    $concurso->cambiarEstado($datos["contest_id"], "Cerrado");
    $datos["status"] = "Cerrado";
    unset($_POST["endVoting"]);
  }

  //Comenzar proceso de votación
  if(isset($_POST["endContest"])){
    $hashtag = $datos["hashtag"];
    /*Se ejecuta el script de Python que buscar fotos en instagram
    /con el hashtag indicado*/
    $output = shell_exec('python3 ./Funciones/scraper.py ' . $hashtag);

    $plagio = false;
    $participantes = [];

    if(!empty($output)){
      $posts = explode("\\", $output);

      foreach ($posts as $var){
        if(strlen($var)>1){
          $alreadyParticipated = False;

          //Se gestiona la información obtenida
          list($path, $author, $caption) = explode("~~~~", $var);
          list($description, $name) = explode("Título: ", $caption);
          trim($path);

          $image = file_get_contents($path);

          //Se ejecutan los script python que comprueban si la imagen esta plagiada
          $plagioIA = shell_exec('python3 ./Funciones/IAImage.py ' . $path);
          $plagioReverse = shell_exec('python3 ./Funciones/reverseImage.py ' . $path);




          //Si todo ha ido bien, se sube la foto
          if(false){
            $foto->nuevaFoto($name, $author, $description, $datos["contest_id"], $image);
          }

        }

      }

    }
    $concurso->cambiarEstado($datos["contest_id"], "Voting");

    $datos["status"] = "Voting";
  }

  //Si el concurso esta cerrado, se obtiene el ganador del concurso
  if($datos["status"]=="Cerrado"){
    $ganador = $datosFoto[$datos["winner"]]["author"];

    if(is_null($ganador)){
      $ganador = "Empate";
    }
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
        cabecera(True, $conn);
      ?>
    </header>
    <main>
      <div class="perfil">
        <?php
          //Se muestra la imagen descriptiva del concurso
          echo '<img src="data:image/jpeg;base64,' . base64_encode($datos["image"]) . '" class="imagenPerfil"/>';
        ?>

        <div class="resumenUsuario">
          <?php
            //Se muestran los datos del concurso
            echo '<p><em><b>Nombre:</b></em> ' . $datos["name"] . '<p>';

            echo '<p><em><b>Hashtag:</b></em> #' . $datos["hashtag"] . '<p>';

            echo '<p><em><b>Status:</b></em> ' . $datos["status"] . '<p>';
            echo '<p><em><b>Descripción:</b></em> ' . $datos["description"] . '<p>';

            //Si el concurso esta cerrado se muestran estos datos
            if($datos["status"]=="Cerrado"){
              echo '<p><em><b>Ganador:</b></em> ' . $ganador . '<p>';
            }
            else{
              echo '<p><em><b>Fecha de finalización:</b></em> ' . $datos["end_date"] . '<p>';
            }

            //Los administradores pueden editar y borrar concursos abiertos
            if($_SESSION["type"]=="Admin"){
              echo '<div>
                      <form action="crearConcurso.php" method="post">
                        <input type="hidden" name="contest_id" value="' . $id . '">
                        <input type="submit" name="edit" value="Editar Concurso">
                      </form>
                      <form action="" method="post">
                        <input type="hidden" name="contest_id" value="' . $id . '">
                        <input type="submit" name="delete" value="Borrar Concurso">
                      </form>
                  </div>';
            }
           ?>
         </div>
      </div>
        <?php
        //Los administradores establecen el estado del concurso
        if($_SESSION["type"]=="Admin"){
              //Si el concurso esta abierto se puede pasar a votación
              if($datos["status"]=="Abierto"){
                  echo '<form action="" method="post">
                          <input type="submit" name="endContest" value="Finalizar Concurso" class="subirFoto"/>
                          <input type="hidden" name="id" value="' . $datos["contest_id"] . '">
                        </form>';
              }
              //Si el concurso esta en proceso de votación, se puede finalizar
              else if($datos["status"]=="Voting"){
                  echo '<form action="" method="post">
                          <input type="submit" name="endVoting" value="Finalizar Votación" class="subirFoto"/>
                          <input type="hidden" name="id" value="' . $datos["contest_id"] . '">
                        </form>';
                  }
              echo'</div>';
        }
        //Si el concurso esta en proceso de votación o cerrado se muestra una galería
        if($datos["status"]!="Abierto"){
          echo '<div class="galeria">';
            //Se recorren todas las fotos del concurso y se muestran
            for($i=0; $i<sizeof($datosFoto); $i++){
              echo '<div>
                      <form action="perfilFoto.php" method="post">
                        <input type="image" src="data:image/jpeg;base64,' . base64_encode($datosFoto[$i]["image"]) . '" class="imagenGaleria"/>
                        <input type="hidden" name="id" value="' . $datosFoto[$i]["photo_id"] . '"/>
                      </form>';
                      //Si se esta en proceso de votación, los usuarios registrados y
                      //que aún no han votado en este concurso tienen acceso a un botón para votar la foto
                      if($datos["status"]=="Voting" && !$alreadyVoted && isset($_SESSION["id"])){
                        echo '<form action="" method="post">
                                <input type="submit" name ="newVote" value="+++" class="boton"/>
                                <input type="hidden" name="id" value="' . $datosFoto[$i]["photo_id"] . '"/>
                                <input type="hidden" name="contest_id" value="' . $datos["contest_id"] . '"/>
                              </form>';
                      }
                      //Si el concurso esta cerrado
                      else if($datos["status"]=="Cerrado"){
                        //Si se detecta que la foto es la ganadora, se declara
                        if($datosFoto[$i]["ranking"]==1){
                          $clase = "ganador";
                        }
                        else{
                          $clase = "noGanador";
                        }
                        //Se muestra el ranking de la fotos
                        echo '<div class="' . $clase .'">
                                <p><em><b>Puesto:</b></em> ' . $datosFoto[$i]["ranking"] . '<p>
                                <p><em><b>Votos:</b></em> ' . $datosFoto[$i]["votes"] . '<p>
                              </div>';
                      }

                  echo '</div>';
            }

          echo   '</div>';
        }
        //Solo pueden subir fotos los usuarios que no han sido pillados cometiendo plagio
        else if(true){
          echo '<form action="crearFoto.php" method="post">
                  <input type="submit" name="newPhoto" value="Subir Foto" class="subirFoto"/>
                  <input type="hidden" name="id" value="' . $datos["contest_id"] . '">
                </form>';
        }
        ?>
      </div>
    </main>
    <footer>
      <?php
        pie();
      ?>
    </footer>
  </body>
</html>
