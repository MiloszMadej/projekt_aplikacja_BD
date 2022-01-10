<?php

	session_start();
	
	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: glowna.php');
		exit();
	}
	
?>


<!DOCTYPE HTML>
<html lang="pl">

<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="stylesheet" href="style3_sf.css" type="text/css" />
	<link rel="icon" type="image/png" href="zdj/favicon.png"/>

	<title>Safety&amp;Finance</title>
</head>

<body style="text-align: center;">
	<div class="container">
		
		<section class="slider2"><img src="zdj/sf.jpg" alt=""></section>
		
		<div class="rejestracj">
			<form action="zaloguj.php" method="post" />
			<br />

				E-mail: <br /> <input type="text" name="email" /> <br />
				Hasło: <br /> <input type="password" name="haslo" /> <br />
				<?php
					if (isset($_SESSION['blad']))	echo $_SESSION['blad'];
				?>
				<br />
				<button style="
								  background-color: #af7162;
								  border: none;
								  color: black;
								  padding: 15px 16px;
								  text-align: center;
								  text-decoration: none;
								  display: inline-block;
								  font-size: 12px;
								  margin: 4px 2px;
								  cursor: pointer;" 
								  type="submit">
								<b>Zaloguj się</b></button>
				<br /><br /><br />
			</form>
		</div>

		<footer>
			<br /><br />
				<p><b>Safety&amp;Finance</b></p>
			<br /><br />
		</footer>
	</div>
</body>
</html>


