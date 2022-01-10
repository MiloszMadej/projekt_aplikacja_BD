<?php
	
	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
	
	
	if (!isset($_POST['zaznacz']))
		{
			$wszystko_OK=false;
		}
		
		if (isset($_POST['zaznacz']))	$_SESSION['fr_zaznacz'] = true;
		
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			$polaczenie->set_charset("utf8mb4");
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			$sql = "SELECT * FROM pracownicy";
			$result = mysqli_query($polaczenie, $sql);
?>


<!DOCTYPE HTML>
<html lang="pl">
<? header('Content-type: text/html; charset=utf-8'); ?>
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
                    <li><a href="dane.php"><b>Twoje dane</b></a></li>
					&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
					<li><a href="logout.php"><b>Wyloguj się</b></a></li>
                </ul>
            </nav>
        </header>
        <section class="slider">
        </section>
        
		
<?php
	
	$imie = $_SESSION['imie'];
	$nazwisko = $_SESSION['nazwisko'];
	$telefon = $_SESSION['telefon'];

?>
<br />
<table id="customers" border="5px solid black" style="width: 80%; margin-left: 100px; box-shadow: 0 0 15px black;">
				<thead>
					<th>Imię</th>
					<th>Nazwisko</th>
					<th>E-mail</th>
					<th>Telefon</th>
					<th>Zmień hasło</th>
				</thead>
				
				<tbody>
					<br />
						<tr>
						<td><?php echo $_SESSION['imie']; ?></td>
						<td><?php echo $_SESSION['nazwisko']; ?></td>
						<td><?php echo $_SESSION['email']; ?></td>
						<td><?php echo $_SESSION['telefon']; ?></td>
						<?php if ($_SESSION['rola']!="administrator" && $_SESSION['rola']!="sekretarka") echo '<td><a href="zmiana_hasla.php"><u>Zmień</u></td>';?>
				<!--		<td><a href="aaa.php">Kliknij</td>	-->
						</tr>
					
			  	</tbody>
			</table>
	<br />
	<br />

        <section class="slider"></section>
        <footer>
			<br />
				<p><b>Safety&amp;Finance</b></p>
			<br />
        </footer>

	</div>
</body>
</html>


