<?php
//	include 'filesLogic.php';
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
			$id_klienta = $polaczenie->real_escape_string($_GET['id_klienta']);
			$id_z_uslug = $polaczenie->real_escape_string($_GET['id_z_uslug']);
			$id_pracownik = $polaczenie->real_escape_string($_SESSION['id']);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			$sql = "SELECT * FROM files";
			$result = mysqli_query($polaczenie, $sql);
		
			$ostatnia = "SELECT MAX(id) as max_id FROM files";  
			$ostatnia_result = $polaczenie->query($ostatnia);
			$ostatnie_id = $ostatnia_result->fetch_assoc();
			$ostatnie_id['max_id'] = $ostatnie_id['max_id']+1;

			$dlugosc_id = strlen((string)$ostatnie_id['max_id']);

	// Uploads files
	if (isset($_POST['save'])) { // if save button on the form is clicked
		// name of the uploaded file
		$filename = $ostatnie_id['max_id'];//.$dlugosc_id.$_FILES['myfile']['name'];
		$filename_sql = $_FILES['myfile']['name'];

		// destination of the file on the server
		$destination = 'uploads/' . $filename;

		// get the file extension
		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		// the physical file on a temporary uploads directory on the server
		$file = $_FILES['myfile']['tmp_name'];
		$size = $_FILES['myfile']['size'];

		if ($_FILES['myfile']['size'] > 10000000) { // file shouldn't be larger than 10Megabyte
			echo "File too large!";
		} else {
			// move the uploaded (temporary) file to the specified destination
			if (move_uploaded_file($file, $destination)) {
				$sql = "INSERT INTO files (name, size, opis, id_usluga) VALUES ('$filename_sql', $size, '', '$id_z_uslug')";
				$sql2 = "INSERT INTO status (id, opis, data, id_usluga) VALUES (NULL, 'Dodano plik o nazwie: $filename_sql', CURRENT_TIMESTAMP, '$id_z_uslug')";
				$sql3 = "INSERT INTO zmiany (id, update_date, id_klient, id_usluga, id_pracownik) VALUES (NULL, CURRENT_TIMESTAMP, '$id_klienta', '$id_z_uslug', '$id_pracownik')";
				if (mysqli_query($polaczenie, $sql)) {
				}
				if (mysqli_query($polaczenie, $sql2)) {
				}
				if (mysqli_query($polaczenie, $sql3)) {
				}
			} else {
			}
		}
	}
?>


<!DOCTYPE HTML>
<html lang="pl">

	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style_dodaj_sf1.css" type="text/css" />
		<link rel="icon" type="image/png" href="zdj/favicon.png"/>
		<title>Safety&amp;Finance</title>
	</head>

	<body>

		<div class="container">
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
				
				<section class="slider"></section>
				
				<div class="rejestr">
					<div class="row">
						<form action method="post" enctype="multipart/form-data" >
							<h1>Dodaj plik</h1>
							<input type="file" name="myfile"> <br /><br />
							<button class="button" type="submit" name="save">Prześlij</button>
						</form>
						<br />
						<a href="pliki.php?id_klienta=<?php echo $id_klienta."&id_z_uslug=".$id_z_uslug;?>" style="color: #023c52;"><u>Anuluj</u></a>
					</div>
					<br />
				</div>
				
				<footer>
					<br /><br />
						<p><b>Safety&amp;Finance</b></p>
					<br /><br />
				</footer>
		</div>

		<?php
			$polaczenie->close();
			$adres = $id_klienta."&id_z_uslug=".$id_z_uslug;
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				if (isset($_POST['save'])) {
					header("Location: pliki.php?id_klienta=$adres&id_z_uslug=$id_z_uslug");
					exit;
				} 
			}
		?> 
	</body>
</html>