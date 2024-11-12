<?php

include './Funciones/cabecera.php';
include './Funciones/connect.php';
include './Funciones/pie.php';
include './Objetos/usuario.php';
include './Objetos/concurso.php';

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Fotografías Excepcionales</title>
    <link rel="stylesheet" href="Style/style.css" type="text/css">
  </head>
  <body>
    <header>
      <?php
        //Se muestra la cabecera de la web
        cabecera(true, $conn);
      ?>
    </header>
    <main>
      <div class="politica">
        <h3>Política de privacidad</h3>
        <p>A través de este sitio web no se recaban datos de carácter personal de las personas usuarias sin su conocimiento, ni se ceden a terceros.</p>

        <p>Con la finalidad de ofrecerle el mejor servicio y con el objeto de facilitar el uso, se analizan el número de páginas visitadas, el número de visitas, así como la actividad de las persona visitantes y su frecuencia de utilización. A estos efectos, la Agencia Española de Protección de Datos (AEPD) utiliza la información estadística elaborada por el Proveedor de Servicios de Internet.</p>

        <p>La AEPD no utiliza cookies para recoger información de las personas usuarias, ni registra las direcciones IP de acceso. Únicamente se utilizan cookies propias, de sesión, con finalidad técnica (aquellas que permiten la navegación a través del sitio web y la utilización de las diferentes opciones y servicios que en ella existen).</p>

        <p>El portal del que es titular la AEPD contiene enlaces a sitios web de terceros, cuyas políticas de privacidad son ajenas a la de la AEPD. Al acceder a tales sitios web usted puede decidir si acepta sus políticas de privacidad y de cookies. Con carácter general, si navega por internet usted puede aceptar o rechazar las cookies de terceros desde las opciones de configuración de su navegador.<p>
      </div>


      <div class="politica">
        <h3>Información básica sobre protección de datos</h3>
        <p>A continuación le informamos sobre la política de protección de datos de la Agencia Española de Protección de Datos.</p>
      </div>


      <div class="politica">
        <h3>Responsable del tratamiento</h3>
        <p>Los datos de carácter personal que se pudieran recabar directamente de la persona interesada serán tratados de forma confidencial y quedarán incorporados a la correspondiente actividad de tratamiento titularidad de la Agencia Española de Protección de Datos (AEPD).
        <p>La relación actualizada de las actividades de tratamiento que la AEPD lleva a cabo se encuentra disponible en el siguiente registro de actividades de la AEPD.</p>
      </div>


      <div class="politica">
        <h3>Finalidad</h3>
        <p>La finalidad del tratamiento de los datos corresponde a cada una de las actividades de tratamiento que realiza la AEPD y que están accesibles en el registro de actividades de tratamiento.</p>
      </div>


      <div class="politica">
        <h3>Legitimación</h3>
        <p>El tratamiento de sus datos se realiza para el cumplimiento de obligaciones legales por parte de la AEPD, para el cumplimiento de misiones realizada en interés público o en el ejercicio de poderes públicos conferidos a la AEPD, así como cuando la finalidad del tratamiento requiera su consentimiento, que habrá de ser prestado mediante una clara acción afirmativa.</p>
        <p>Puede consultar la base legal para cada una de las actividades de tratamiento que lleva a cabo la AEPD en el siguiente Acceder al registro de actividades de la AEPD.</p>
      </div>


      <div class="politica">
        <h3>Conservación de datos</h3>
        <p>Los datos personales proporcionados se conservarán durante el tiempo necesario para cumplir con la finalidad para la que se recaban y para determinar las posibles responsabilidades que se pudieran derivar de la finalidad, además de los períodos establecidos en la normativa de archivos y documentación.</p>
      </div>


      <div class="politica">
        <h3>Comunicación de datos</h3>
        <p>Con carácter general no se comunicarán los datos personales a terceros, salvo obligación legal, entre las que pueden estar las comunicaciones al Defensor del Pueblo, Jueces y Tribunales, personas interesadas en los procedimientos relacionados con la reclamaciones presentadas.</p>
        <p>Puede consultar los destinatarios para cada una de las actividades de tratamiento que lleva a cabo la AEPD en el siguiente Acceder al registro de actividades de la AEPD.</p>
      </div>


      <div class="politica">
        <h3>Derechos de las personas interesadas</h3>
        <p>Para solicitar el acceso, la rectificación, supresión o limitación del tratamiento de los datos personales o a oponerse al tratamiento, en el caso de se den los requisitos establecidos en el Reglamento General de Protección de Datos, así como en la Ley Orgánica 3/2018, de 5 de diciembre, de Protección de Datos Personal y garantía de los derechos digitales, puede dirigir un escrito al responsable del tratamiento, en este caso, la AEPD, dirigiendo el mismo a la Agencia Española de Protección de Datos, C/Jorge Juan, 6, 28001- Madrid o en el registro electrónico de la AEPD</p>

        <p>Datos de contacto del DPD: dpd@aepd.es.</p>
      </div>



      <div class="politica">
        <h3>Aviso legal</h3>
        <p>Este portal, cuyo titular es la Agencia Española de Protección de Datos (AEPD), con NIF Q2813014D, domicilio en la calle Jorge Juan, nº6, 28001 Madrid, y teléfono 901 100 099, está constituido por los sitios web asociados a los dominios aepd.es:</p>

        <p>Sede electronica de la AEPD</p>
        <p>Red iberoamericana de protección de datos</p>
      </div>


      <div class="politica">
        <h3>Propiedad intelectual e industrial</h3>
          <p>El diseño del portal y sus códigos fuente, así como los logos, marcas y demás signos distintivos que aparecen en el mismo pertenecen a la AEPD y están protegidos por los correspondientes derechos de propiedad intelectual e industrial.</p>

          <p>Las infografías y guías de la Agencia se publican con una licencia de uso CC BY-NC-SA 4.0 que permite su reutilización para uso no comercial, atribuyendo la autoría y manteniendo las mismas condiciones.</p>
      </div>
    </main>
    <footer>
      <?php
        //Se muestra el pie de la página
        pie();
      ?>
    </footer>
  </body>
</html>
