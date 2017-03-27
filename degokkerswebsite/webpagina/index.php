<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    
    
 <?php

include 'connect.php';

$goback = 'echo <pre><a href="index.php">Ga terug naar de hoofdpage</a>';

 ///////// Registeren
   // Sign up functie
 if(isset($_POST['signup'])){


     if (empty($_POST['name'])){die("U moet uw naam invullen".$goback);}
     if ($_POST['date']=="DATE"){die("U moet uw geboorte datum invullen".$goback);}
     if ($_POST['month']=="MONTH"){die("U moet uw geboorte datum invullen".$goback);}
     if ($_POST['year']=="YEAR"){die("U moet uw geboorte datum invullen".$goback);}
     if (empty($_POST['passregister'])){die("U moet uw passwoord invullen".$goback);}
     if (empty($_POST['passcheck'])){die("U moet uw passwoord twee keer invullen".$goback);}
     if ($_POST['passcheck']!= $_POST['passregister']){die("U passwoord komt niet overeen".$goback);}
     if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
         die("U moet uw email invullen");}


     if(!isset($_POST['ToS'])){ die("U moet akkoord gaan".$goback);}

     $email  = $_POST['email'];

     $qry="SELECT `email` FROM `users` ORDER BY `email`";
     $result = mysqli_query($qry) or die(mysqli_error());
     while ($row = mysqli_fetch_array($result))
     {
         if ($email === $row['email']) {
             die("Deze email bestaat al!");
         }
     }

        $name   = htmlspecialchars($_POST['name']);
        $date   = $_POST['date'];
        $month  = $_POST['month'];
        $year   = $_POST['year'];
        $wachtwoord = $_POST['passregister'];
        $code = sha1($wachtwoord);
        $pass = crypt($code,ex);

        $verificatie='name='.$name.'&date='.$date.'&month='.$month.'&year='.$year.'&email='.$email.'&pass='.$pass;


        $subject = 'Verifeer uw email adres';
        $from = 'd223013@edu.rocwb.nl';
        $bericht = '/Project3/verificatie.php?'.$verificatie;

        ini_set('sendmail_from', $from);

        $headers   = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-type: text/plain; charset=iso-8859-1";
        $headers[] = "From: Degokkers Servicedesk <{$from}>";
        $headers[] = "Reply-To: Degokkers Servicedesk <{$from}>";
        //$headers[] = "Subject: {$subject}";
        $headers[] = "/".phpversion();

        mail($email, $subject, $bericht, implode("\r\n", $headers) );

    }


    /////////// Login
  if(isset($_POST['signin'])){
  $wachtwoord = $_POST['pass'];
  	$code = sha1($wachtwoord);
	$pass = crypt($code,ex);


 if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if (isset($_POST['email']) && trim($_POST['email']) != '' &&
            isset($pass) && trim($pass) != '')
        {
            try
            {
                //initialisatie
                $maxAttempts = 3; //pogingen binnen aantal minuten (zie volgende)
                $attemptsTime = 5; //tijd waarin pogingen gedaan mogen worden (in minuten, wil je dat in seconden e.d. met je de query aanpassen)

                //vul hier je eigen databasegegevens in, verbinding maken met database
                $db = new PDO('mysql:host=localhost;dbname=degokker','root','');
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //ophalen gebruikersinformatie, testen of wachtwoord en gebruikersnaam overeenkomen
                $checkUsers =
                    "SELECT 
                        id
                    FROM
                        users
                    WHERE
                        email = :email
                    AND
                        pass = :pass";
                $userStmt = $db->prepare($checkUsers);
                $userStmt->execute(array(
                                    ':email' => $_POST['email'],
                                    ':pass' => $pass
                                    ));
                $user = $userStmt->fetchAll();

                //ophalen inlogpogingen, alleen laatste vijf minuten
                $checkTries =
                    "SELECT
                        email
                    FROM
                        loginfailcount
                    WHERE
                        DateAndTime >= NOW() - INTERVAL :attemptsTime MINUTE
                    AND
                        email = :email    
                    GROUP BY
                        email, IP
                    HAVING
                        (COUNT(email) = :maxAttempts)";
                $triesStmt = $db->prepare($checkTries);
                $triesStmt->execute(array(
                                    ':email' => $_POST['email'],
                                    ':attemptsTime' => $attemptsTime,
                                    ':maxAttempts' => $maxAttempts
                                    ));
                $tries = $triesStmt->fetchAll();

                if (count($user) == 1 && count($tries) == 0)
                {
                    $_SESSION['user'] = array('user_id' => $user[0]['user_id'], 'IP' => $_SERVER['REMOTE_ADDR']);
                    //pagina waar naartoe nadat er succesvol is ingelogd
                    header('Location: profile.php');
                    die;
                }
                else
                {
                    $insertTry =
                        "INSERT INTO
                            loginfailcount
                                (email, 
                                IP,
                                dateAndTime)
                        VALUES
                            (:email,
                            :IP,
                            NOW())";
                    $insertStmt = $db->prepare($insertTry);
                    $insertStmt->execute(array(
                                            ':email' => $_POST['email'],
                                            ':IP' => $_SERVER['REMOTE_ADDR']
                                            ));
                    if(count($tries) > 0)
                    {
                        $message = '<br><br><font color="red">U heeft te vaak een incorrect Gebruikersnaam / Wachtwoord opgegeven. U kunt het over 5 min weer proberen proberen</font>';
                    }
                    else
                    {
                        $message = '<br><br><font color="red">Incorrect Gebruikersnaam / Wachtwoord. probeer opnieuw<br></font>';
                    }
                }



            }
            catch (PDOException $e)
            {
                $message = $e->getMessage();
            }
            $db = NULL;
        }
        else
        {
            $message = '<br><br>Voer uw inlog gegevens in. ';
        }
    }
                if (isset($message))
            {
                echo $message;
            }
  }



?>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        

        <div class="maincontainer">
            <header>
                <img src="img/racelogo.png" alt="">
            </header>

            <div class="opacity">
                <div class="hero">

                    <div class="texttop">
                        <h1>Get Rich Fast!</h1>
                    </div>

                    <div class="vidbox">
                        <iframe width="640" height="480" src="https://www.youtube.com/embed/rsyk_wsCFwM" frameborder="0" allowfullscreen></iframe>
                    </div>

                </div>
            </div>

            <div class="slide container opacity">
                <div class="picture">
                    <h2>Bet on your stork</h2>
                    <img src="img/picturegame2.jpg" alt="">
                </div>
                <div class="picture">
                    <h2>See them race</h2>
                    <img src="img/picturegame3.jpg" alt="">
                </div>
                <div class="picture">
                    <h2>Gather your winnings</h2>
                    <img src="img/picturegame4.jpg" alt="">
                </div>
            </div>

            <div class="about container">
                <div class="textabout">
                    <h3>About the game</h3>
                    <p>Coming out of after a year in Beta 525,000 players downloaded the game. Stork Game has been shaped by a very active community of fans. (and a good amount of debt)
                    In this time we have processed over 42,000 pieces of feedback, and already awarded more than $34,100 in cash prizes to players who are becoming Stork game legends. And now you can become the top racing legend! Download now Stork game and win that jack pot!
                    Inspired by the adventure and story of where baby’s came from. that we love from classic children story’s, Stork game presents you with a sky to race, filled with chances to win money! and constant danger and action.  In Stork game, every player is the Star of Their own Stork race  each orbited by other players filled with money, and you can get all of it! 
                    Fly smoothly from start to the finch line with no loading screens but watch out that you don’t catch the baby! Because if you do you will get a lot slower.  generated a lot of money by playing Stork game! and perhaps get rich beyond believe.
                    </p>
                    <h4>
                    Embark on an epic Race
                    </h4> 
                    <p>
                    At the start of the race you can decide how much money you want to play with.  You can play with the wonderful amounts between $5-$15. facing hostile players and fierce Admins, you'll know that debt come at a cost, and survival will be down to the choices you make over how much money your willing to play with.
                    </p>
                    <h4>
                    Find your own destiny 
                    </h4>
                    <p>
                    Your voyage  through Stork game is up to you. Will you be a player preying on the weak and taking their riches, or taking heavy hits and collecting a lot of debts? Power is yours if you upgrade your Stork to Stork whit a baby for a leak of speed Invest in more play sessions and you'll reap huge rewards. Or perhaps not? Strengthen your savings for survival in toxic environments that would kill the unwary. 
                    </p>
                    <h4>
                    Share your journey 
                    </h4>
                    <p>
                    The sky is a living, breathing place. the police are ever watching. Every other player lives in the same sky, and you can choose to share your winnings with them on social media (Note: there is no online game mode in Stroke game) Perhaps you will see the results of their actions as well as your own... 
                    </p>
                    <h4>
                    Play now the best game ever made! 
                    </h4>
                    <p>
                    SYSTEM REQUIREMENTS MINIMUM: 
                    </p>
                    <p>
                    OS: Windows : xp sp 2/7/8.1/10
                    </p>
                    <p> 
                    Processor: Intel Core i3 (or less)
                    </p>
                    <p>
                    Memory: 32 Bit Ram
                    </p>
                    <p>
                    Graphics: - 
                    </p>
                    <p>
                    Storage: 2,3 MB available space
                    </p>

                </div>
            </div>
            <div class="downloadregister container">
                <div class="textabout">
                    <div class="flex">
                <form action="" method="post" class="column">
                    <h2>Create a account</h2>
                    <br>
                    <input type="text" name="name" placeholder="Username" id="name" required/>
                    <br>
                    <input type="email" name="email" placeholder="exmaple@example.com" required/>
                    <br>
                    <input type="password" name="passregister" placeholder="Password" required/>
                    <input type="password" name="passcheck" placeholder="Password check" required/>
                    <br>
                    <div class="row">
                        <!-- alle datums in een optie veld -->
                        <select name="date">
                            <option value="DATE">DATE</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
        </select>
                        <!-- alle maanden in een optie veld -->
                        <select name="month" class="padding-form">
                            <option value="Month">MONTH</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>


        </select>
                        <!-- alle maanden in een optie veld -->
                        <select name="year" class="padding-form">
                            <option value="YEAR">YEAR</option>
                            <option value="1975">1975</option>
                            <option value="1976">1976</option>
                            <option value="1977">1977</option>
                            <option value="1978">1978</option>
                            <option value="1979">1979</option>
                            <option value="1980">1980</option>
                            <option value="1981">1981</option>
                            <option value="1982">1982</option>
                            <option value="1983">1983</option>
                            <option value="1984">1984</option>
                            <option value="1985">1985</option>
                            <option value="1986">1986</option>
                            <option value="1987">1987</option>
                            <option value="1988">1988</option>
                            <option value="1989">1989</option>
                            <option value="1990">1990</option>
                            <option value="1991">1991</option>
                            <option value="1992">1992</option>
                            <option value="1993">1993</option>
                            <option value="1994">1994</option>
                            <option value="1995">1995</option>
                            <option value="1996">1996</option>
                            <option value="1997">1997</option>
                            <option value="1998">1998</option>
                            <option value="1999">1999</option>
                            <option value="2000">2000</option>
                            <option value="2001">2001</option>
                            <option value="2002">2002</option>
                            <option value="2003">2003</option>
                            <option value="2004">2004</option>
                            <option value="2005">2005</option>
                            <option value="2006">2006</option>
                            <option value="2007">2007</option>
                            <option value="2008">2008</option>
                            <option value="2009">2009</option>
                            <option value="2010">2010</option>
                            <option value="2011">2011</option>
</select>
                    </div>
                    <br>
                    <input type="submit" name="signup" value="SIGN UP" id="signup">
                    <br>
                    <label><input type="checkbox" name="ToS" value="Term of agreement"> <a href="downloads/download-text.php"> Terms Of Use</a></label>

                </form>
            </div>
             <div class="flex">
                <form action="" method="post" class="column">
                    <h2>Sign in</h2>
                    <br>
                    <input type="email" name="email" placeholder="exmaple@example.com" required/>
                    <br>
                    <input type="password" name="pass" placeholder="password" required/>
                    <br>
                    <input type="submit" name="signin" value="SIGN IN" id="signin">

                </form>
            </div>
        </div>
                </div>
            </div>



            <div class="team container">
                <div class="textabout teamlisting">
                    <span><h3>Team</h3></span>

                <div class="teammembers">
                   <div class="teammember">
                        <img src="img/ProfilePlaceholderSuit.png" alt="">
                        <h3>Dion</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum modi vitae vel quasi vero expedita provident, optio rem nulla aut deserunt nisi velit, hic beatae repellendus perferendis doloribus autem dolore.</p>
                    </div>
                    <div class="teammember">
                        <img src="img/ProfilePlaceholderSuit.png" alt="">
                        <h3>Noel</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum modi vitae vel quasi vero expedita provident, optio rem nulla aut deserunt nisi velit, hic beatae repellendus perferendis doloribus autem dolore.</p>
                    </div>
                    <div class="teammember">
                        <img src="img/ProfilePlaceholderSuit.png" alt="">
                        <h3>Roel</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum modi vitae vel quasi vero expedita provident, optio rem nulla aut deserunt nisi velit, hic beatae repellendus perferendis doloribus autem dolore.</p>
                    </div>           
                </div>    
                </div>
            </div>

            <footer class="container">
             <h6>©By Gokkers BV and © By incasso burou de vrolijke munt</h6>
            </footer>
        </div>












        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')</script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='https://www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
    </body>
</html>
