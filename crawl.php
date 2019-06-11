<?php
//Se importa la clase DomDocumentParser
include("config.php");
include("classes/DomDocumentParser.php");

$alreadyCrawled = array();
$crawling = array(); //Enlaces pendientes

function insertLink($url, $title, $description, $keywords){
  echo "Adding";
  global $con; //Se hace referencia a la variable con del archivo config.php

  $query= $con->prepare("INSERT INTO sites (url, title, description, keywords)
                        VALUES (:url, :title, :description, :keywords)");
  //Lo siguiente se hace por motivo de seguridad
  // al poner los dos puntos ("El autor dice placeholder"), se evita la
  // inyección SQL por parte de black hat hackers
  $query->bindParam(":url", $url);
  $query->bindParam(":title", $title);
  $query->bindParam(":description", $description);
  $query->bindParam(":keywords",$keywords);

  return $query->execute();
}

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

function getDetails($url){
  $parser = new DomDocumentParser($url);
  $titleArray = $parser->getTitleTags();

  if(sizeof($titleArray) == 0 || $titleArray->item(0)== NULL){
    return;
  }

  $title = $titleArray->item(0)->nodeValue;
  $title = str_replace("\n", "", $title);

  if($title == ""){
    return;
  }
  $description= "";
  $keywords = "";

  $metasArray = $parser -> getMetaTags();

  foreach ($metasArray as $meta) {
    if($meta->getAttribute("name") == "description"){
      $description = $meta->getAttribute("content");
    }
    if($meta->getAttribute("name") == "keywords"){
      $keywords = $meta->getAttribute("content");
    }
    // code...
  }
  $description = str_replace("\n", "", $description);
  $keywords = str_replace("\n", "", $keywords);

  echo "URL: $url, Title: $title, Description: $description, Keywords: $keywords<br>";
echo "beforeAdding";
  insertLink($url,$title,$description,$keywords);
  echo "added";
}


function followLinks($url) {
    // Se crea un nuevo objeto. Recordar como funcionan las variables
    global $alreadyCrawled;
    global $crawling;
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

      if(!in_array($href, $alreadyCrawled)){
        $alreadyCrawled[] = $href;
        $crawling[]= $href;
         getDetails($href);
         //getDescription($href);
        //insert $href
      }
      else return;

       // echo $href . "<br>";
    //    CREATE TABLE sites(
    //        -> id INT NOT NULL AUTO_INCREMENT,
    //        -> url VARCHAR(512) NOT NULL,
    //        -> title VARCHAR(512) NOT NULL,
    //        -> description VARCHAR(512) NOT NULL,
    //        -> keywords VARCHAR(512) NOT NULL,
    //        -> clicks INT,
    //        -> PRIMARY KEY (id)
    //        -> ) ENGINE=INNODB;
    //
     }

    array_shift($crawling);

    foreach($crawling as $site){
      followLinks($site);
    }
}
$startUrl = "http://bbc.com";
followLinks($startUrl);
 ?>
