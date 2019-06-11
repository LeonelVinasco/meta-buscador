<?php


class DomDocumentParser {
    private $doc;
  // Se crea el constructor de la get_declared_classes
  //Importantísimo, los dos underscore.
  public function __construct($url){

    $options = array(
      'http'=>array('method'=>"GET", 'header'=>"User-Agent: doodleBot/0.1\n")
    );
    $context = stream_context_create($options);
    $this->doc = new DomDocument();
    $this->doc->loadHTML(file_get_contents($url, false, $context));
  }

  public function getlinks(){
    return $this->doc->getElementsByTagName("a");
  }
  public function getTitleTags(){
    return $this->doc->getElementsByTagName("title");
  }
<<<<<<< HEAD
  public function getMetaTags(){
    return $this->doc->getElementsByTagName("meta");
=======
  public function getDescriptionTags(){
    return $this->doc->getElementsByTagName("description");
  }
  public function getKeyWordsTags(){
    return $this->doc->getElementsByTagName("keywords");
>>>>>>> d5fa8cccd2daeebbd1b0ae5fb7a9fbfe5c6e8fc2
  }

}

 ?>
