<?php
	
	session_start();
	
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
						
	// Create connection
	$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
	$polaczenie->set_charset("utf8mb4");
	// Check connection
	if ($polaczenie->connect_error) {
		die("Connection failed: " . $polaczenie->connect_error);
	}
						
	$id_pracownik =$polaczenie->real_escape_string($_SESSION['id']);
	if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka")
	{
	//$rezultat = $polaczenie->query("SELECT zm.id, zm.update_date, zm.id_klient, zm.id_usluga FROM zmiany zm LEFT JOIN pracownicy tst ON tst.id=zm.id_pracownik WHERE tst.id_pracownik='$id_pracownik'");
	$rezultat = $polaczenie->query("SELECT zm.id, zm.update_date, zm.id_klient, zm.id_usluga, zm.id_pracownik, usl.nazwa_uslugi, pra.imie, pra.nazwisko FROM zmiany0 zm INNER JOIN uslugi0 usl INNER JOIN pracownicy0 pra ON zm.id_pracownik=pra.id AND zm.id_usluga=usl.id ORDER BY zm.id DESC");
	$rezultat1 = $polaczenie->query("SELECT zm.id, zm.id_klient, kl.id, kl.imie, kl.nazwisko FROM zmiany0 zm LEFT JOIN klienci0  kl ON zm.id_klient=kl.id ORDER BY zm.id DESC");
	}
		
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
				
			<section class="slider" style="text-align: right; text-shadow: 1.5px 1.5px black;"></section>
				
			<main>
				<table id="customers" border="5px solid black">
					<tr>
						<th>Data i czas zmiany</th>
						<th>Klient</th>
						<th>Nazwa usługi</th>
						<th>Pracownik</th>
						<th>Odnośnik</th>
						<!--th>Raporty</th-->
					</tr>
						
					<?php
						while($row = mysqli_fetch_array($rezultat))
						{
						$row1 = mysqli_fetch_array($rezultat1);
						
						echo "<tr>";
						echo "<td>" . $row['update_date'] . "</td>";
						$id_klient = $row['id_klient'];
						$id_z_uslug = $row['id_usluga'];
						echo "<td>" . $row1['nazwisko'] . " " . $row1['imie'] . "</td>";
						echo "<td>" . $row['nazwa_uslugi'] . "</td>";
						echo "<td>" . $row['imie'] ." ". $row['nazwisko'] . "</td>";
						echo "<td>" . "<a href='status.php?id_klienta=$id_klient&id_z_uslug=$id_z_uslug'><u>Przejdź</u></a>"."</td>";
						//echo "<td>" . "<a href='raport_html.php?id_pracownik=$id_pracownik'><u>Przejdź</u></a>"."</td>";
						echo "</tr>";
						}
						echo "</table>";
					
						$polaczenie->close();
					?>
			</main>
				
			<footer>
				<br />
					<p><b>Safety&amp;Finance</b></p>
				<br />
			</footer>
		</div>
	</body>
</html>