<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Objetos/foto.php';
  include './Objetos/concurso.php';
  include './Funciones/connect.php';

  $conn = connect();

  session_start();

  $foto = new foto($conn);
  $concurso = new concurso($conn);

  $filtro = false;
  $filtroName = false;
  $filtroAutor = false;
  $filtroVotos = false;
  $filtroConcursos = false;

  $datos = $foto->getTodosDatos();

  $datosConcursos = $concurso->getTodosDatos();

  if(isset($_POST["filter"])){
    $contestFilter = $_POST["contestFilter"];

    if($_POST["contestFilter"]!="empty"){
      $filtroConcursos = true;
    }
  }

  if(isset($_POST["reset"])){
    header("Location: galeria.php");
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
      <div class="formulario">
        <form action="" method="post">
          <fieldset>
            <div>
              <label for="nameFilter">Nombre: </label>
              <?php
                echo '<input type="text" name="nameFilter" value="' . $nameFilter .'">';
              ?>
            </div>
            <div>
              <label for="authorFilter">Autor: </label>
              <?php
                echo '<input type="text" name="authorFilter" value="'. $authorFilter .'">';
              ?>
            </div>
            <div>
              <label for="votesFilter">Número de votos: </label>
              <?php
                echo '<input type="number" min="0" name="votesFilter" value="' . $votesFilter .'">';
              ?>

            </div>
            <div>
              <label for="contestFilter">Concurso: </label>
              <select name="contestFilter">
                <option value="empty" <?php if($contestFilter=="empty") echo 'selected'; ?>>No filtrar</option>
                <?php
                  for($i=0; $i<sizeof($datosConcursos); $i++){
                    echo '<option value="' . $datosConcursos[$i]["name"] .'"';
                    if($contestFilter==$datosConcursos[$i]["name"]) echo 'selected';
                    echo '>' . $datosConcursos[$i]["name"] .'</option>';
                  }
                ?>
              </select>
              <?php

              ?>
            </div>
            <div>
              <input type="submit" name="filter" value="Filtrar Galeria">
              <input type="submit" name="reset" value="Eliminar Filtro">
            </div>

          </fieldset>
        </form>
      </div>
        <?php
          echo '<div class="galeria">';
            for($i=0; $i<sizeof($datos); $i++){
              if($filtroConcursos){
                $unConcurso = $concurso->getDatos($datos[$i]["contest"]);
                $nombreConcurso = $unConcurso["name"];
              }

              if((($_POST["nameFilter"] =="") || ($_POST["nameFilter"]==$datos[$i]["name"]))
                    && (($_POST["authorFilter"] =="") || ($_POST["authorFilter"]==$datos[$i]["author"]))
                    && (($_POST["votesFilter"] =="") || ($_POST["votesFilter"]==$datos[$i]["votes"]))
                    && (($_POST["contestFilter"] =="empty") || $_POST["contestFilter"]==$nombreConcurso)){
                echo '<div>
                        <form action="perfilFoto.php" method="post">
                          <input type="image" src="data:image/jpeg;base64,' . base64_encode($datos[$i]["image"]) . '" class="imagenGaleria"/>
                          <input type="hidden" name="id" value="' . $datos[$i]["photo_id"] . '"/>
                        </form>

                        <div>
                          <p><b><em>' . $datos[$i]["name"] .' </b></em>
                        </div>
                      </div>';
              }
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
