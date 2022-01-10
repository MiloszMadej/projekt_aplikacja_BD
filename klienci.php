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
	if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") {
		$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.status='Pozytywny') AS ile_pozytywnych, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.status='W toku') AS ile_w_toku, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.status='Negatywny') AS ile_negatywnych FROM klienci1  kl ORDER BY nazwisko ASC, imie ASC");
	} else if ($_SESSION['rola']=="kredytowiec") {
		//$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres FROM klienci1  kl LEFT JOIN uslugi u ON u.id_klient=kl.id WHERE u.typ_uslugi='kredyty' GROUP BY kl.id ORDER BY kl.imie ASC, kl.nazwisko ASC");
		$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.typ_uslugi='kredyty' and u.status='Pozytywny') AS ile_pozytywnych, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.typ_uslugi='kredyty' and u.status='W toku') AS ile_w_toku, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.typ_uslugi='kredyty' and u.status='Negatywny') AS ile_negatywnych FROM klienci1  kl LEFT JOIN uslugi u ON u.id_klient=kl.id WHERE u.typ_uslugi='kredyty' GROUP BY kl.id ORDER BY kl.nazwisko ASC, kl.imie ASC");
	} else if ($_SESSION['rola']=="ubezpieczyciel_majatkowy") {
		//$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres FROM klienci1  kl LEFT JOIN uslugi u ON u.id_klient=kl.id WHERE u.typ_uslugi='ubezpieczenie_zdrowotne' OR u.typ_uslugi='ubezpieczenie_majatkowe' GROUP BY kl.id ORDER BY kl.imie ASC, kl.nazwisko ASC");
		$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.status='Pozytywny' and u.typ_uslugi='ubezpieczenie_majatkowe') AS ile_pozytywnych, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.status='W toku' and u.typ_uslugi='ubezpieczenie_majatkowe') AS ile_w_toku, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.status='Negatywny' and u.typ_uslugi='ubezpieczenie_majatkowe') AS ile_negatywnych FROM klienci1  kl LEFT JOIN uslugi u ON u.id_klient=kl.id WHERE u.typ_uslugi='ubezpieczenie_majatkowe' GROUP BY kl.id ORDER BY kl.nazwisko ASC, kl.imie ASC");
	} else if ($_SESSION['rola']=="ubezpieczyciel_zdrowotny") {
		//$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres FROM klienci1  kl LEFT JOIN uslugi u ON u.id_klient=kl.id WHERE u.typ_uslugi='ubezpieczenie_zdrowotne' OR u.typ_uslugi='ubezpieczenie_majatkowe' GROUP BY kl.id ORDER BY kl.imie ASC, kl.nazwisko ASC");
		$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.status='Pozytywny' and u.typ_uslugi='ubezpieczenie_zdrowotne') AS ile_pozytywnych, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.status='W toku' and u.typ_uslugi='ubezpieczenie_zdrowotne') AS ile_w_toku, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.status='Negatywny' and u.typ_uslugi='ubezpieczenie_zdrowotne') AS ile_negatywnych FROM klienci1  kl LEFT JOIN uslugi u ON u.id_klient=kl.id WHERE u.typ_uslugi='ubezpieczenie_zdrowotne' GROUP BY kl.id ORDER BY kl.nazwisko ASC, kl.imie ASC");
	} else if ($_SESSION['rola']=="inwestor") {
		//$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres FROM klienci1  kl LEFT JOIN uslugi u ON u.id_klient=kl.id WHERE u.typ_uslugi='inwestycje' GROUP BY kl.id ORDER BY kl.imie ASC, kl.nazwisko ASC");
		$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.typ_uslugi='inwestycje' and u.status='Pozytywny') AS ile_pozytywnych, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.typ_uslugi='inwestycje' and u.status='W toku') AS ile_w_toku, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.typ_uslugi='inwestycje' and u.status='Negatywny') AS ile_negatywnych FROM klienci1  kl LEFT JOIN uslugi u ON u.id_klient=kl.id WHERE u.typ_uslugi='inwestycje' GROUP BY kl.id ORDER BY kl.nazwisko ASC, kl.imie ASC");
	} else if ($_SESSION['rola']=="doradca") {
		//$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres FROM klienci1  kl LEFT JOIN uslugi u on u.id_klient=kl.id WHERE u.id_pracownik='$id_pracownik' GROUP BY kl.id ORDER BY kl.imie ASC, kl.nazwisko ASC");
		$rezultat = $polaczenie->query("SELECT kl.id, kl.imie, kl.nazwisko, kl.email, kl.telefon, kl.adres, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.id_pracownik='$id_pracownik' and u.status='Pozytywny') AS ile_pozytywnych, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.id_pracownik='$id_pracownik' and u.status='W toku') AS ile_w_toku, (SELECT COUNT(*) FROM uslugi u WHERE u.id_klient=kl.id and u.id_pracownik='$id_pracownik' and u.status='Negatywny') AS ile_negatywnych FROM klienci1  kl LEFT JOIN uslugi u ON u.id_klient=kl.id WHERE u.id_pracownik='$id_pracownik' GROUP BY kl.id ORDER BY kl.nazwisko ASC, kl.imie ASC");
	}
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
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
		<div class="container" style="box-shadow: 0 0 35px rgba(0, 0, 0, 0.5); max-width: 1100px;">
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
				
				<section class="slider" style="text-align: right; text-color: #af7162; text-shadow: 1.5px 1.5px black;">
					<?php if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") echo '<a style="float: left;" href="rejestracja.php"><b>+ Dodaj doradcę</b></a>';?>
					<a href="dodaj_klienta.php"><b>+ Dodaj klienta</b></a>
				</section>
				
				<main>
						<!--td style="background-color: <?php //echo $status_colors[$f3]; ?>;"-->
						 <table id="customers" border="5px solid black">
						<tr>
							<th>Nazwisko</th>
							<th>Imię</th>
							<th style="max-width: 100px">E-mail</th>
							<th>Telefon</th>
							<th>Adres</th>
							<th>Usługi</th>
							<th>Stan usług</th>
						</tr>
					<?php
						while($row = mysqli_fetch_array($rezultat))
						{
							$status_colors = array(0 => '#0000FF', 1 => 'orange');
							$zmienna=$row['ile_w_toku'];
							//"<td style='background-color: <?php echo $status_colors[$zmienna];";
							//if($row['ile_w_toku']>0) echo "<style='background-color: <?php echo $status_colors[$zmienna];";
							
							echo "<tr>";
							$id_klienta = $row['id'];
							
							//if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka" || $_SESSION['rola']=="kredytowiec") {
							if ($_SESSION['rola']!="doradca") {
								if($row['ile_w_toku']>0)
								{
									echo "<td style='background-color: orange;'>". $row['nazwisko'] ."</td>";
									echo "<td style='background-color: orange;'>". $row['imie'] ."</td>";
									echo "<td style='background-color: orange;'>" . $row['email'] . "</td>";
									echo "<td style='background-color: orange;'>" . $row['telefon'] . "</td>";
									echo "<td style='background-color: orange;'>" . $row['adres'] . "</td>";
								} else {
									echo "<td>" . $row['nazwisko'] ."</td>";
									echo "<td>" . $row['imie'] ."</td>";
									echo "<td>" . $row['email'] . "</td>";
									echo "<td>" . $row['telefon'] . "</td>";
									echo "<td>" . $row['adres'] . "</td>";
								}
							} else {
								echo "<td>" . $row['nazwisko'] ."</td>";
								echo "<td>" . $row['imie'] ."</td>";
								echo "<td>" . $row['email'] . "</td>";
								echo "<td>" . $row['telefon'] . "</td>";
								echo "<td>" . $row['adres'] . "</td>";
							}
							
							if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") {
								if($row['ile_w_toku']>0) echo "<td style='background-color: $status_colors[1];'>" . "<a href='uslugi_admin.php?id_klienta=$id_klienta'><u>Usługi</u></a>"."</td>";
								else echo "<td>" . "<a href='uslugi_admin.php?id_klienta=$id_klienta'><u>Usługi</u></a>"."</td>";
							} else if ($_SESSION['rola']=="kredytowiec"){
								if($row['ile_w_toku']>0) echo "<td style='background-color: $status_colors[1];'>" . "<a href='uslugi_kredytowiec.php?id_klienta=$id_klienta'><u>Usługi</u></a>". "</td>";
								else echo "<td>" . "<a href='uslugi_kredytowiec.php?id_klienta=$id_klienta'><u>Usługi</u></a>". "</td>";
							} else if ($_SESSION['rola']=="ubezpieczyciel_majatkowy"){
								if($row['ile_w_toku']>0) echo "<td style='background-color: $status_colors[1];'>" . "<a href='uslugi_ubezpieczyciel_majatkowy.php?id_klienta=$id_klienta'><u>Usługi</u></a>". "</td>";
								else echo "<td>" . "<a href='uslugi_ubezpieczyciel_majatkowy.php?id_klienta=$id_klienta'><u>Usługi</u></a>". "</td>";
							} else if ($_SESSION['rola']=="ubezpieczyciel_zdrowotny"){
								if($row['ile_w_toku']>0) echo "<td style='background-color: $status_colors[1];'>" . "<a href='uslugi_ubezpieczyciel_zdrowotny.php?id_klienta=$id_klienta'><u>Usługi</u></a>". "</td>";
								else echo "<td>" . "<a href='uslugi_ubezpieczyciel_zdrowotny.php?id_klienta=$id_klienta'><u>Usługi</u></a>". "</td>";
							} else {
								if($row['ile_w_toku']>0) echo "<td>" . "<a href='uslugi.php?id_klienta=$id_klienta'><u>Usługi</u></a>". "</td>";
								else echo "<td>" . "<a href='uslugi.php?id_klienta=$id_klienta'><u>Usługi</u></a>". "</td>";
							}
							echo "<td style='min-width: 108px;'>" . $row['ile_pozytywnych'] . "<img src='zdj/green_dot1.png'> &nbsp";
							echo $row['ile_w_toku'] . 			"<img src='zdj/orange_dot1.png'> &nbsp";					 
							echo $row['ile_negatywnych'] . 	"<img src='zdj/red_dot1.png'>" . "</td>";
							
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