<?php
    if(isset($_GET["term"])) {
      $term = $_GET["term"];
    }
    else {
      exit("You must enter a search term");
    }

    if(isset($_GET["type"])) {
      $type = $_GET["type"];
    }
    else {
      $type = "sites";
    }
// La manera de abreviar el bloque anterior es esta:
    // $type = isset($_GET["type"]) ? $_GET["type"] : "sites";
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Welcome to Doodle</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
      <div class="wrapper indexPage">
          <div class="header">
              <div class="headerContent">
                  <div class="logoContainer">
                      <a href="index.php">
                          <img src="assets/images/logo.png">
                      </a>
                  </div>

                  <div class="searchContainer">
                      <form action="search.php" method="GET">
                        <div class="searchBarContainer">
                           <input class="searchBox" type="text" name="term">
                           <button class="searchButton">
                             <img src="assets/images/icon.png"
                           </button>
                       </div>
                      </form>

                  </div>
              </div>

              <div class="tabsContainer">

                 <ul class="tabList">
                   <!-- En la siguiente linea se escoge el nombre de la clase
                 active si sites fue el tipo seleccionado o si no un string vacio   -->
                   <li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
                      <a href='<?php echo "search.php?term=$term&type=sites"; ?>'>
                        Sites
                      </a>
                   </li>
                   <li class="<?php echo $type == 'images' ? 'active' : '' ?>">
                      <a href='<?php echo "search.php?term=$term&type=images"; ?>'>
                        Images

                      </a>
                   </li>
                 </ul>
              </div>

          </div>
      </div>
</body>
</html>
