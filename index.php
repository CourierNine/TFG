<!DOCTYPE html>

<?php
  include './Funciones/cabecera.php';
  include './Funciones/pie.php';
  include './Objetos/usuario.php';
  include './Objetos/foto.php';
  include './Objetos/comentario.php';
  include './Objetos/concurso.php';
  include './Funciones/connect.php';

  session_start();

  //Se establece una conexón con la base de datos
  $conn = connect();

  $concurso = new concurso($conn);
  $foto = new foto($conn);
  $comentario = new comentario($conn);

  $datos = $concurso->getTodosDatos();
  $count = $concurso->contarConcursos();

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
        <div class="resumenPage">
          <h3>¡Bienvenido a Fotografías Excepcionales!</h3>
          <p>A continuación encontrará una selección de todos nuestros concursos.
              Sientase libre de explorar toda la galería y disfrutar de su pasión
              por la fotografía.</p>
        <?php
          for ($i = 0; $i < $count; ++$i){
            echo '<div class="concursoDestacado';
              if($i==0){
                echo ' active';
              }
            echo '">';

            echo '<button type="button" class="botonDestacado" onclick="otroConcurso('.$i.', -1)"><--</button>';

            //Se muestran los datos de los concursos
            echo '<form action="perfilConcurso.php" method="post">
                  <input type="image" src="data:image/jpeg;base64,'
                   . base64_encode($datos[$i]["image"]) . '" class="imagenPerfil"/>
                  <input type="hidden" name="id" value="' . $datos[$i]["contest_id"] . '">
                </form>';
            echo '<div class="resumenDestacado">';
              echo '<h1><em>' . $datos[$i]["name"] . '</em></h1>';
              echo '<p><b><em>Hashtag: </em></b>#' . $datos[$i]["hashtag"] . '</p>';
              echo '<p><b><em>Fecha de finalización: </em></b>' . $datos[$i]["end_date"] . '</p>';
            echo '</div>';

            echo '<button type="button" class="botonDestacado" onclick="otroConcurso('.$i.', 1)">--></button>';
            echo '</div>';
          }
        ?>
      </div>
    </main>
    <footer>
      <?php
        //Se muestra el pie de la web
        pie();
      ?>
    </footer>
  </body>
  <script>
    function otroConcurso(index, number){
      const images = document.querySelectorAll('.concursoDestacado');
      const totalImages = images.length;
      images[index].classList.remove('active');
      nextIndex = (index + number) % totalImages;

      if(nextIndex<0){
        nextIndex = totalImages-1;
      }

      images[nextIndex].classList.add('active');
    }
  </script>
</html>
