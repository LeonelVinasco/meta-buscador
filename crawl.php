<?php

//Se importa la clase DomDocumentParser
include("classes/DomDocumentParser.php");


function followLinks($url) {
    // Se crea un nuevo objeto. Recordar como funcionan las variables
    $parser = new DomDocumentParser($url);
    $linkList = $parser->getlinks();

    foreach($linkList as $link) {
      $href = $link->getAttribute("href");
      echo $href . "<br>";
      // code...
    }
}
$startUrl = "http://xvideos.com";
followLinks($startUrl);
 ?>
