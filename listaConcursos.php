<!DOCTYPE html>
<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Objetos/foto.php';
  include './Objetos/comentario.php';
  include './Objetos/concurso.php';
  include './Funciones/connect.php';

  $conn = connect();


  $concurso = new concurso($conn);
  $foto = new foto($conn);
  $comentario = new comentario($conn);

  //Se borra el concurso
  if(isset($_POST["delete"])){
    $listaFotos = $foto->getFotosConcurso($_POST["contest_id"]);

    //Se deben borrar todas las fotos del concurso, así como todos los comentarios asociados
    for($i=0; $i<sizeof($listaFotos); $i++){
      $comentario->borrarComentariosFoto($listaFotos[$i]["photo_id"]);
      $foto->borrarFoto($listaFotos[$i]["photo_id"]);
    }

    $concurso->borrarConcurso($_POST["contest_id"]);
  }

  $count = $concurso->contarConcursos();

  //Se comprueba si se deben mostrar los concursos abiertos o cerrados
  if(isset($_POST["status"])){
    $status = htmlspecialchars($_POST["status"]);
  }
  else{
    $status = htmlspecialchars($_GET["status"]);
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
        //Se muestra la cabecera de la página
        cabecera(True, $conn);
      ?>
    </header>
    <main>
      <?php
        //Se obtienen los datos de los concursos
        $datos = $concurso->getTodosDatos();

        //Se itera sobre todos los concursos
        for ($i = 0; $i < $count; $i++){
          //Se muestran los datos de los concursos que corresponden al estado indicado
          if($status==$datos[$i]["status"] || ($status=="Abierto" && $datos[$i]["status"]=="Voting")){
            echo '<div class="listaUsuarios">
                    <div>
                    <form action="perfilConcurso.php" method="post">
                          <input type="image" src="data:image/jpeg;base64,' .
                          base64_encode($datos[$i]["image"]) . '" class="imagenPerfil"/>
                          <input type="hidden" name="id" value="' . $datos[$i]["contest_id"] . '">
                    </form>
                    </div>
                    <div>
                      <p><em><b>Nombre:</b></em> ' . $datos[$i]["name"] . '</p>
                      <p><em><b>Hashtag:</b></em> #' . $datos[$i]["hashtag"] . ' </p>
                    </div>
                    <div>
                      <p><em><b>Estado:</b></em> ' . $datos[$i]["status"] . '</p>';

                      //Si el concurso esta abierto se muestran estos datos
                      if($status=="Abierto" && $datos[$i]["status"]!="Voting"){
                        echo '<p><em><b>Fecha de finalización:</b></em> ' . $datos[$i]["end_date"]
                        . ' </p>';
                      }
                      else if($status=="Cerrado"){
                        echo '<p><em><b>Ganador:</b></em> ' . $datos[$i]["winner"] . ' </p>';
                      }
                    echo '</div>';
                    echo '<div>';
                          //Lo administradores pueden editar y borrar los concursos abiertos
                          if($_SESSION["type"] == "Admin" && $datos[$i]["status"]=="Abierto"){
                            echo '<form action="crearConcurso.php" method="post">
                                    <input type="hidden" name="contest_id" value="' . $datos[$i]["contest_id"] . '">
                                    <input type="submit" name="edit" value="Editar Concurso" class="boton">
                                  </form>';
                            echo '<form action="" method="post">
                                    <input type="hidden" name="contest_id" value="' . $datos[$i]["contest_id"] . '">
                                    <input type="submit" name="delete" value="Borrar Concurso" class="boton">
                                  </form>';

                          }
                    echo '</div>';
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
