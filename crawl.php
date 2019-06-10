<?php

//Se importa la clase DomDocumentParser
include("classes/DomDocumentParser.php");

function createLink($src, $url) {
    $scheme= parse_url($url)["scheme"]; //http or HttpQueryString
    $host = parse_url($url)["host"]; // www.reecekenney.com

    if (substr($src,0,2) == "//"){
      $src = $scheme . ":" . $src;
    }
    else if(substr($src,0,1) == "/"){
      $src= $scheme . "://" . $host . $src;
    }
    else if (substr($src,0,2) == "./"){
      $src = $scheme . "://" . $host . dirname(parse_url($url)["path"]) . substr($src,1); //toma todo el texto desde el 2o caracter
    }
    else if (substr($src,0,3) == "../"){
      $src = $scheme . "://" . $host . "/" . $src; //toma todo el texto desde el 2o caracter
    }
    else if (substr($src,0,5) != "https" && substr($src,0,4) != "http"){
      $src = $scheme . "://" . $host . "/" . $src;
    }

    return $src;
}


function followLinks($url) {
    // Se crea un nuevo objeto. Recordar como funcionan las variables
    $parser = new DomDocumentParser($url);
    $linkList = $parser->getlinks();

    foreach($linkList as $link) {
      $href = $link->getAttribute("href");

      if(strpos($href, "#") !== false){
        continue;
        //Continue Corta ejecución y hace que siga el siguiente LINK en el arreglo
      }
      else if(substr($href,0,11) == "javascript:"){
        //Se pueden ejecutar funciones de javascript con el link respectivo
        continue;
      }

      $href= createLink($href,$url);

      echo $href . "<br>";

    }
}
$startUrl = "http://gentesdelcomun.com";
followLinks($startUrl);
 ?>
