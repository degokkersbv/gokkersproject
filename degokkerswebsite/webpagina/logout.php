<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Download De Gokkers Ooievaar hier</title>
    <link rel="stylesheet" href="css/main.css">
</head>
    <body>
       <div class="wrapper">
          <?php
          // Dit start eeb sessie
           session_start();
           // Dit beindigd een sessie
           session_destroy();
           
           header("location: index.php")
           ?>
          
        </div>
    </body>
</html>