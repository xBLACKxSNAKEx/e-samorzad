<?php
  require_once "read_base.php";
  load();
  session_start();
  echo("<script>");
  if(isset($_SESSION['ok']))
  {
    echo("let sent = true;");
    if($_SESSION['ok'] == true)
      echo("let sending_error = false;");
    else
      echo("let sending_error = true;");
  }
  else
  {
    echo("let sent = false;");
    echo("let sending_error = false;");
  }
  session_unset();
  echo("</script>");
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>E-SAMORZĄD</title>
        <link rel="shortcut icon" href="./img/godlo.ico" type="image/x-icon">
        
        <!-- CSS -->
        <link rel="stylesheet" href="styles/style.css">
        
        <!-- Fonts -->
        
        <script src="https://kit.fontawesome.com/d89bf3921c.js" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
        
    </head>
    <body>
        <div class="baner">
            <div class="godlo"></div>
            <span class='baner-title'>E-SAMORZĄD</span>
        </div>
        <div id="panel">
            
        </div>
        
        <script src="./js/script.js" type="module"></script>
    </body>
    </html>