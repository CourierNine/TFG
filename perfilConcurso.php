<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/concurso.php';
  include './Objetos/usuario.php';
  include './Objetos/foto.php';
  include './Funciones/connect.php';

  $conn = connect();

  session_start();

  $concurso = new concurso($conn);
  $foto = new foto($conn);

  if(isset($_POST["id"])){
    $id = $_POST["id"];
  }
  else{
    $id = $_GET["id"];
  }

  $datos = $concurso->getDatos($id);

  $datosFoto = $foto->getFotosConcurso($id);

  if(isset($_POST["newVote"])){
    $end_date = strtotime($datos["end_date"]);

    setcookie($datos["contest_id"], "alreadyVoted", $end_date);

    $foto->nuevoVoto($_POST["id"]);

    header("Location: listaConcursos.php?status=Abierto");
  }

  if(isset($_POST["endVoting"])){
    $ranking = [];


    for($i=0; $i<sizeof($datosFoto); $i++){
      $ranking[$i] = 1;
      for($j=0; $j<sizeof($datosFoto); $j++){
        if($datosFoto[$i]["votes"]<=$datosFoto[$j]["votes"]){
          $ranking[$i] = $ranking[$i] + 1;
        }
      }

      $datosFoto[$i]["ranking"] = $ranking[$i];
      $foto->setRanking($datosFoto[$i]["photo_id"], $ranking[$i]);

      if($ranking[$i]==1){
        $concurso->setGanador($id, $datosFoto[$i]["photo_id"]);
      }
    }



    $concurso->cambiarEstado($datos["contest_id"], "Cerrado");
    $datos["status"] = "Cerrado";
    unset($_POST["endVoting"]);
  }

  if(isset($_POST["endContest"])){
    $hashtag = $datos["hashtag"];
    $output = shell_exec('python3 ./Funciones/scraper.py ' . $hashtag);


    $posts = explode("\\", $output);

    foreach ($posts as $var){
      if(strlen($var)>1){
        list($path, $author, $caption) = explode("~~~~", $var);
        list($description, $name) = explode("Titulo: ", $caption);
        trim($path);

        $image = file_get_contents($path);

        $foto->nuevaFoto($name, $author, $description, $datos["contest_id"], $image);
      }
    }

    $concurso->cambiarEstado($datos["contest_id"], "Voting");

    $datos["status"] = "Voting";
    unset($_POST["endContest"]);

  }


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
          echo '<img src="data:image/jpeg;base64,' . base64_encode($datos["image"]) . '" class="imagenPerfil"/>';
        ?>

        <div class="resumenUsuario">
          <?php
            echo '<p><em><b>Nombre:</b></em> ' . $datos["name"] . '<p>';

            echo '<p><em><b>Hashtag:</b></em> #' . $datos["hashtag"] . '<p>';

            echo '<p><em><b>Status:</b></em> ' . $datos["status"] . '<p>';
            echo '<p><em><b>Descripción:</b></em> ' . $datos["description"] . '<p>';

            if($datos["status"]=="Cerrado"){
              echo '<p><em><b>Ganador:</b></em> ' . $ganador . '<p>';
            }
            else{
              echo '<p><em><b>Fecha de finalización:</b></em> ' . $datos["end_date"] . '<p>';
            }

            if($_SESSION["type"]=="Admin"){
              echo '<div>
                      <form action="crearConcurso.php" method="post">
                        <input type="hidden" name="id" value="' . $_POST["id"] . '">
                        <input type="submit" name="edit" value="Editar Concurso">
                      </form>
                  </div>';
            }
           ?>
         </div>
      </div>
        <?php
        if($_SESSION["type"]=="Admin"){
            echo '<div class="adminFotos">';
                      if($datos["status"]=="Abierto"){
                        echo '<form action="" method="post">
                                <input type="submit" name="endContest" value="Finalizar Concurso" class="subirFoto"/>
                                <input type="hidden" name="id" value="' . $datos["contest_id"] . '">
                              </form>';
                        echo '<form action="crearFoto.php" method="post">
                                <input type="submit" name="newPhoto" value="Subir Foto" class="subirFoto"/>
                                <input type="hidden" name="id" value="' . $datos["contest_id"] . '">
                              </form>';
                      }
                      else if($datos["status"]=="Voting"){
                        echo '<form action="" method="post">
                                <input type="submit" name="endVoting" value="Finalizar Votación" class="subirFoto"/>
                                <input type="hidden" name="id" value="' . $datos["contest_id"] . '">
                              </form>';
                      }
                echo'</div>';
        }
        if($datos["status"]!="Abierto"){
          echo '<div class="galeria">';
            for($i=0; $i<sizeof($datosFoto); $i++){
              echo '<div>
                      <form action="perfilFoto.php" method="post">
                        <input type="image" src="data:image/jpeg;base64,' . base64_encode($datosFoto[$i]["image"]) . '" class="imagenGaleria"/>
                        <input type="hidden" name="id" value="' . $datosFoto[$i]["photo_id"] . '"/>
                      </form>';
                      if($datos["status"]=="Voting" && !isset($_COOKIE[$datos["contest_id"]])){
                        echo '<form action="" method="post">
                                <input type="submit" name ="newVote" value="+++" class="boton"/>
                                <input type="hidden" name="id" value="' . $datos["photo_id"] . '"/>
                              </form>';
                      }
                      else if($datos["status"]=="Cerrado"){
                        if($datosFoto[$i]["ranking"]==1){
                          $clase = "ganador";
                        }
                        else{
                          $clase = "";
                        }
                        echo '<div class="' . $clase .'">
                                <p><em><b>Puesto:</b></em> ' . $datosFoto[$i]["ranking"] . '<p>
                              </div>';
                      }

                  echo '</div>';
            }

          echo   '</div>';
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
