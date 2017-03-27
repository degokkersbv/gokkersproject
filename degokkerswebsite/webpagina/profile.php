<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Download De Gokkers Ooievaar hier</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="wrapper2">

    <?php
    $lifetime=25;
    session_set_cookie_params($lifetime);
    session_start();
    // als er geen inlog gegevens zijn brengt hij je terug naar de homepage
    ?>
    <div>

        <?php
        setcookie(session_name(),session_id(),time()+$lifetime);

        do {
            echo "<h2> Welcome at our download page. Kijk vooral rond!</h2>";
        } while ($lifetime < 0);
        session_destroy();
        ?>
        <?php
        session_start();
        ?>

    </div>
    <div>
        <a href="downloads/download-exe.php">DOWNLOAD</a>
    </div>

    <div>
        <a href="logout.php">LOGOUT</a>
    </div>
</div>
</body>
</html>