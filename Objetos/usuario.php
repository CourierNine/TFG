<?php
  class usuario{
    private $DB;

    function __construct(){
      $this->DB = new usuarioDB();
    }
  }

 ?>
