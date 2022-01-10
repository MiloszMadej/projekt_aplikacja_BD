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
		$rezultat = $polaczenie->query("SELECT id, imie, nazwisko, email FROM pracownicy0 ORDER BY nazwisko ASC, imie ASC");
	}

	if (!isset($_SESSION['zalogowany'])) {
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
		<title>Raporty</title>	
	</head>

	<body>
		<div class="container mt-5">
			<main>
				<td style="background-color: <?php echo $status_colors[$f3]; ?>;">
				 <table id="customers" border="5px solid black">
					<tr>
						<th>Nazwisko</th>
						<th>Imię</th>
						<th>E-mail</th>
						<th>Raporty</th>
					</tr>
						
					<?php
						while($row = mysqli_fetch_array($rezultat)) {
							$status_colors = array(0 => '#0000FF', 1 => 'orange');
							
							echo "<tr>";
							$id_pracownika = $row['id'];
							$imie_pracownika = $row['imie'];
							$nazwisko_pracownika = $row['nazwisko'];
							$email_pracownika = $row['email'];
							
							echo "<td>" . $row['nazwisko'] ."</td>";
							echo "<td>" . $row['imie'] ."</td>";
							echo "<td>" . $row['email'] . "</td>";
							
							if ($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka") {
								echo "<td>" . "<a href='raport.php?id_pracownika=$id_pracownika&imie_pracownika=$imie_pracownika&nazwisko_pracownika=$nazwisko_pracownika&email_pracownika=$email_pracownika'><u>Stwórz raport</u></a>"."</td>";
							} else {
								echo "<td>" . "<a href='uslugi.php?id_klienta=$id_klienta'><u>Usługi</u></a>". "</td>";
							}
							echo "</tr>";
						}
						echo "</table>";
					
						$polaczenie->close();
					?>
			</main>
			
			<!--a href="raport.php?id_pracownika=<?php echo $id_pracownika . "&imie_pracownika=" . $imie_pracownika . "&nazwisko_pracownika=" . $nazwisko_pracownika . "&email_pracownika=" . $email_pracownika;?>">Stwórz raport</a-->
				
			<?php echo //"<a href=raport.php?id_pracownika=$id_pracownika&imie_pracownika=$imie_pracownika&nazwisko_pracownika=$nazwisko_pracownika&email_pracownika=$email_pracownika>Stwórz raport</a>";?>
				
			<?php
				$date = date_create();
				echo date_format($date, 'U = Y-m-d H:i:s') . "\n";

				date_timestamp_set($date, 1638313201);
				echo date_format($date, 'U = Y-m-d H:i:s') . "\n";

				date_timestamp_set($date, 1622498401);
				echo date_format($date, 'U = Y-m-d H:i:s') . "\n";
			?>
		</div>
	</body>
</html>