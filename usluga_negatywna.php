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

	// Create connection
	$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
	$polaczenie->set_charset("utf8mb4");
	$id_klienta = $polaczenie->real_escape_string($_GET['id_klienta']);
	$id_z_uslug = $polaczenie->real_escape_string($_GET['id_z_uslug']);

		if ($polaczenie->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		$sql = "UPDATE uslugi SET status = 'Negatywny' WHERE id='$id_z_uslug'";
		$result = mysqli_query($polaczenie, $sql);

	// Check connection
	if ($polaczenie->connect_error) {
	  die("Connection failed: " . $polaczenie->connect_error);
	}

	if($_SESSION['rola']=="administrator" || $_SESSION['rola']=="sekretarka"){
		header("Location: uslugi_admin.php?id_klienta=$id_klienta");
	}
	if($_SESSION['rola']=="kredytowiec"){
		header("Location: uslugi_kredytowiec.php?id_klienta=$id_klienta");
	}
	if($_SESSION['rola']=="ubezpieczyciel_majatkowy"){
		header("Location: uslugi_ubezpieczyciel_majatkowy.php?id_klienta=$id_klienta");
	}
	if($_SESSION['rola']=="ubezpieczyciel_zdrowotny"){
		header("Location: uslugi_ubezpieczyciel_zdrowotny.php?id_klienta=$id_klienta");
	}
	if($_SESSION['rola']=="inwestor"){
		header("Location: uslugi_inwestor.php?id_klienta=$id_klienta");
	}
	exit;

	$polaczenie->close();
?> 