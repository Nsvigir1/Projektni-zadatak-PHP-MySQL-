<?php

  if(isset($_GET['menu'])) { $menu   = (int)$_GET['menu']; }

  #Set menu 1 if none selected
  if (!isset($menu)) { $menu = 1; }

  if(!isset($_POST['action']))  { $_POST['action'] = FALSE; }

  # DataBase connection 
  include("db_connection.php");
print'
  <!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0; maximum-scale=1.0"
      />
      <meta http-equiv="content-type" content="text/html" />
      <meta name="author" content="Nikola Švigir " />
      <title>NTPWS projekt</title>
      <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png" />
      <link rel="stylesheet" href="style.css?v=5" />
    </head>
  <body>
    <header>
      <img class="header-img" src="img/code.png" />
      <nav>';
      include("menu.php");
      print '</nav>
    </header>
  <main>';
      if (isset($_SESSION['message'])) {
          print $_SESSION['message'];
          unset($_SESSION['message']);
      }

  # Start page
  if (!isset($_GET['menu']) || $_GET['menu'] == 1) {include ("home.php");}

  # Programing language
  else if($_GET['menu'] == 2) {include ("programing_language.php");}

  # Contact
  else if($_GET['menu'] == 3) {include ("contact.php");}
  
  # Gallery
  else if($_GET['menu'] == 4) {include ("gallery.php");}
  
  # About
  else if($_GET['menu'] == 5) {include ("about.php");}

  #registrarion
  else if($_GET['menu'] == 6) {include ("registration.php");}

  #Sign in
  else if($_GET['menu'] == 7) {include ("signin.php");}

  #Administrator
  else if($_GET['menu'] == 8) {include ("administrator.php");}

  #Administrator
  else if($_GET['menu'] == 9) {include ("weather.php");}
  
  print'
  </main>
    <script type="text/javascript"></script>
  </body>
</html>';
?>