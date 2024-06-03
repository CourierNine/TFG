<!DOCTYPE html>
<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Objetos/concurso.php';
  include './Funciones/connect.php';

  $conn = connect();


  $concurso = new concurso($conn);

  $count = $concurso->contarConcursos();

  if(isset($_POST["status"])){
    $status = $_POST["status"];
  }
  else{
    $status = $_GET["status"];
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
      <?php
        $datos = $concurso->getTodosDatos();


        for ($i = 0; $i < $count; $i++){
          if($status==$datos[$i]["status"]){
            echo '<div class="listaUsuarios">
                    <div>
                    <form action="perfilConcurso.php" method="post">
                          <input type="image" src="data:image/jpeg;base64,' . base64_encode($datos[$i]["image"]) . '" class="imagenPerfil"/>
                          <input type="hidden" name="id" value="' . $datos[$i]["contest_id"] . '">
                    </form>
                    </div>
                    <div>
                      <p><em><b>Nombre:</b></em> ' . $datos[$i]["name"] . '</p>
                      <p><em><b>Hashtag:</b></em> #' . $datos[$i]["hashtag"] . ' </p>
                    </div>
                    <div>
                      <p><em><b>Estado:</b></em> ' . $datos[$i]["status"] . '</p>';

                      if($status=="Abierto"){
                        echo '<p><em><b>Fecha de finalización:</b></em> ' . $datos[$i]["end_date"] . ' </p>';
                      }
                      else{
                        echo '<p><em><b>Ganador:</b></em> ' . $datos[$i]["winner"] . ' </p>';
                      }
              echo '</div>';
              echo '<div>';
                    if($_SESSION["type"] == "Admin" && $datos[$i]["status"]=="Abierto"){

                  echo '<form action="crearConcurso.php" method="post">
                          <input type="hidden" name="id" value="' . $datos[$i]["contest_id"] . '">
                          <input type="submit" name="edit" value="Editar Concurso" class="boton">
                        </form>';

                    }
              echo '</div>';

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
