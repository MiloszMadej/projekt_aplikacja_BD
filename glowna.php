<?php
	
	session_start();	
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	} 
?>


<!DOCTYPE HTML>
<html lang="pl">

<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style_sf.css" type="text/css" />
	<link rel="icon" type="image/png" href="zdj/favicon.png"/>
	<title>Safety&amp;Finance</title>
</head>

<body>
	<div class="container" style="box-shadow: 0 0 35px rgba(0, 0, 0, 0.5);">
        <header>
            <section class="brand">
                <a href="glowna.php"><img src="zdj/sf.jpg" alt=""></a>
            </section>
			
            <nav>
                <ul>
                    <li><a href="klienci.php"><b>Klienci</b></a></li>
                    <?php if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") echo '<li><a href="powiadomienia.php"><b>Powiadomienia</b></a></li>';?>
					<?php if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") echo '<li><a href="pracownicy.php"><b>Pracownicy</b></a></li>';?>
					<?php if ($_SESSION['rola']!="administrator" && $_SESSION['rola']!="sekretarka") echo '<li><a href="dane.php"><b>Twoje dane</b></a></li>'?>
					&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
					<li><a href="logout.php"><b>Wyloguj się</b></a></li>
                </ul>
            </nav>
        </header>
		
        <section class="slider">
            <img src="zdj/glowna.jpg" alt="">
        </section>
        
		<footer>
			<br />
				<p><b>Safety&amp;Finance</b></p>
			<br />
        </footer>
    </div>

</body>
</html>


